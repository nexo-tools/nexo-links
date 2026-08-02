<?php

namespace Database\Seeders;

use App\Models\Click;
use App\Models\Link;
use App\Models\Page;
use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Demo data for the landing screenshots (design.md, "Family": real captures from
 * a LOCAL instance, never production). The point is an honest page — six links a
 * person would plausibly publish, in the mixed states this product actually
 * supports, and a fortnight of traffic behind three of them — so the figures on
 * the landing show the product working instead of a seeded emptiness.
 *
 * Deliberately NOT registered in DatabaseSeeder: the one there is the developer
 * fixture (random counts, real destinations). This one is run explicitly by
 * whoever is re-capturing, and is deterministic on purpose — same links, same
 * shape of traffic on every run — because a screenshot that changes when nothing
 * changed is a diff nobody can review.
 */
class DemoSeeder extends Seeder
{
    use WithoutModelEvents;

    /** The traffic window the analytics figure shows. */
    private const DAYS = 14;

    /**
     * Every destination is an example.* domain. The six cover the states the
     * dashboard has to be able to show at once: two plain, one highlighted, one
     * with a countdown still counting, one scheduled to open later, one hidden.
     *
     * @var list<array{title:string, url:string, highlighted?:bool, countdown?:bool, startsInDays?:int, hidden?:bool}>
     */
    private const LINKS = [
        ['title' => 'Curso en vivo — plazas abiertas', 'url' => 'https://cursos.example.com/en-vivo/2026/inscripcion', 'highlighted' => true],
        ['title' => 'Mi portfolio', 'url' => 'https://portfolio.example.org/trabajos'],
        ['title' => 'Newsletter quincenal', 'url' => 'https://boletin.example.net/suscribirse'],
        ['title' => 'Lanzamiento del libro', 'url' => 'https://libro.example.com/preventa', 'countdown' => true, 'startsInDays' => 7],
        ['title' => 'Entradas del festival', 'url' => 'https://entradas.example.com/festival-2026', 'startsInDays' => 21],
        ['title' => 'Blog viejo (oculto)', 'url' => 'https://blog.example.org/archivo', 'hidden' => true],
    ];

    /**
     * Clicks per day for the three measured links, oldest day first. Hand-written
     * rather than random: a link peaks the day it goes out and decays, and a
     * chart that says that reads as a product, not as noise.
     *
     * @var array<string, list<int>>
     */
    private const TRAFFIC = [
        'Curso en vivo — plazas abiertas' => [6, 11, 9, 14, 38, 29, 18, 13, 10, 16, 22, 12, 8, 11],
        'Mi portfolio' => [3, 4, 6, 5, 9, 7, 6, 8, 11, 7, 5, 6, 9, 4],
        'Newsletter quincenal' => [0, 1, 2, 4, 3, 7, 19, 41, 28, 15, 10, 7, 5, 4],
    ];

    /**
     * Weighted by repetition, not by a flat cycle: a real breakdown has a head
     * and a long tail, and a table where every row shows the same count is the
     * one thing that gives a seeded screenshot away. null = direct traffic.
     *
     * @var list<string|null>
     */
    private const REFERRERS = [
        'instagram.com', 'instagram.com', 'instagram.com', 'instagram.com', 'instagram.com',
        'x.com', 'x.com', 'x.com',
        'youtube.com', 'youtube.com',
        'google.com',
        null, null, null,
    ];

    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'demo@example.com'],
            ['name' => 'Demo', 'password' => Hash::make('password')],
        );

        // User implements MustVerifyEmail and /dashboard and /analytics sit
        // behind the `verified` middleware: without this the capture run signs
        // in fine and lands on /verify-email. The date is fixed, not now(), so
        // re-seeding does not move it.
        $user->forceFill(['email_verified_at' => now()->subDays(self::DAYS + 30)->startOfDay()])->save();

        // `demo` is what config('nexo.example_username') points at, so the
        // landing's "See a live example" link keeps resolving to /demo.
        $page = Page::firstOrNew(['username' => 'demo']);
        $page->forceFill([
            'user_id' => $user->id,
            'bio' => 'Diseño, escribo y a veces enseño. Todo lo que hago, en un solo lugar.',
            'theme' => 'default',
            'background_type' => 'default',
        ])->save();

        $created = [];
        foreach (self::LINKS as $position => $link) {
            $at = now()->subDays(self::DAYS)->addHours($position * 5);

            // forceFill, not fill: created_at is the whole point of the seeder (a
            // fortnight-old page with a fortnight of clicks) and is not — rightly
            // — in the model's fillable list.
            $model = Link::firstOrNew(['page_id' => $page->id, 'title' => $link['title']]);
            $model->forceFill([
                'page_id' => $page->id,
                'url' => $link['url'],
                'position' => $position,
                'is_visible' => ! ($link['hidden'] ?? false),
                'is_highlighted' => $link['highlighted'] ?? false,
                'show_countdown' => $link['countdown'] ?? false,
                'starts_at' => isset($link['startsInDays'])
                    ? now()->addDays($link['startsInDays'])->startOfHour()
                    : null,
                'ends_at' => null,
                'created_at' => $at,
                'updated_at' => $at,
            ])->save();

            $created[$link['title']] = $model;
        }

        foreach (self::TRAFFIC as $title => $perDay) {
            $this->seedClicks($created[$title], $perDay);
        }

        // Four channels, so the public page shows a row of icons rather than a
        // lone one. Handles and the address are the demo's own, on example.com.
        foreach ([
            ['platform' => 'instagram', 'value' => 'demo'],
            ['platform' => 'github', 'value' => 'nexo-tools'],
            ['platform' => 'youtube', 'value' => 'demo'],
            ['platform' => 'email', 'value' => 'demo@example.com'],
        ] as $position => $social) {
            SocialLink::firstOrNew(['page_id' => $page->id, 'platform' => $social['platform']])
                ->forceFill([
                    'page_id' => $page->id,
                    'value' => $social['value'],
                    'position' => $position,
                ])->save();
        }
    }

    /**
     * @param  list<int>  $perDay  clicks for each of the last DAYS days, oldest first
     */
    private function seedClicks(Link $link, array $perDay): void
    {
        $link->clicks()->delete();

        $rows = [];
        $n = 0;

        foreach ($perDay as $offset => $count) {
            $day = now()->subDays(self::DAYS - 1 - $offset)->startOfDay();
            // Some people open the same link twice in a day, so uniques land
            // below total the way they do in production. The pool is per day
            // because the stored hash is per day: it rotates at midnight, so
            // yesterday's visitor is arithmetically a new one today.
            $pool = max(1, (int) ceil($count * 0.72));

            for ($i = 0; $i < $count; $i++, $n++) {
                $visitor = 'demo-'.$link->id.'-'.($i % $pool).'-'.$day->toDateString();

                $rows[] = [
                    'link_id' => $link->id,
                    'visitor_hash' => hash('sha256', $visitor),
                    'referrer_host' => self::REFERRERS[($n * 3) % count(self::REFERRERS)],
                    // Spread over working hours so the day looks lived in.
                    'created_at' => $day->copy()->addMinutes(8 * 60 + ($n * 37) % (11 * 60)),
                ];
            }
        }

        Click::insert($rows);
    }
}
