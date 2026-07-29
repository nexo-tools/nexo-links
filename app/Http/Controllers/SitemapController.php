<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $xml = cache()->remember('sitemap', now()->addHour(), function (): string {
            $urls = collect([
                ['loc' => url('/'), 'lastmod' => null],
                ['loc' => route('help'), 'lastmod' => null],
                ['loc' => route('legal.privacy'), 'lastmod' => null],
                ['loc' => route('legal.terms'), 'lastmod' => null],
            ])->concat(
                Page::query()
                    ->latest('updated_at')
                    ->limit(5000)
                    ->get()
                    ->map(fn (Page $page): array => [
                        'loc' => route('page.show', $page->username),
                        'lastmod' => $page->updated_at?->toAtomString(),
                    ]),
            );

            $entries = $urls->map(function (array $url): string {
                $lastmod = $url['lastmod'] !== null ? "<lastmod>{$url['lastmod']}</lastmod>" : '';

                return '<url><loc>'.e($url['loc']).'</loc>'.$lastmod.'</url>';
            })->implode('');

            return '<?xml version="1.0" encoding="UTF-8"?>'
                .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'
                .$entries
                .'</urlset>';
        });

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
