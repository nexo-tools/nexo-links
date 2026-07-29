<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ClickRedirectController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\OwnerReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SocialLinkController;
use App\Models\Page;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing', [
        'exampleUsername' => Page::query()
            ->where('username', config('nexo.example_username'))
            ->value('username'),
    ]);
})->name('home');

// Help center. Registered before the /{username} catch-all so it is never
// shadowed. The FAQ content is translatable (lang/*/help.php).
Route::get('/help', HelpController::class)->name('help');

// Legal pages. Spanish paths because the ecosystem is Spanish-first (the route
// NAMES stay legal.privacy / legal.terms), registered before the /{username}
// catch-all; both segments are reserved usernames so nobody can shadow them.
Route::get('/privacidad', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/terminos', [LegalController::class, 'terms'])->name('legal.terms');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Disallow: /l/',
        'Disallow: /report/',
        'Disallow: /dashboard',
        'Disallow: /analytics',
        'Disallow: /design',
        'Disallow: /reports',
        'Disallow: /qr',
        'Disallow: /profile',
        'Disallow: /login',
        'Disallow: /register',
        '',
        'Sitemap: '.route('sitemap'),
    ];

    return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain']);
})->name('robots');

Route::get('/report/{page:username}', [ReportController::class, 'create'])->name('report.create');
Route::post('/report/{page:username}', [ReportController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('report.store');

Route::get('/l/{link}', ClickRedirectController::class)->name('link.visit');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [LinkController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/design', [DesignController::class, 'edit'])->name('design.edit');
    Route::get('/qr', QrCodeController::class)->name('qr.show');
    Route::get('/reports', [OwnerReportController::class, 'index'])->name('reports.index');
    Route::patch('/reports/{report}', [OwnerReportController::class, 'update'])->name('reports.update');
    Route::patch('/design', [DesignController::class, 'update'])->name('design.update');
    Route::post('/links', [LinkController::class, 'store'])->name('links.store');
    Route::patch('/links/reorder', [LinkController::class, 'reorder'])->name('links.reorder');
    Route::patch('/links/{link}', [LinkController::class, 'update'])->name('links.update');
    Route::delete('/links/{link}', [LinkController::class, 'destroy'])->name('links.destroy');
    Route::post('/socials', [SocialLinkController::class, 'store'])->name('socials.store');
    Route::delete('/socials/{socialLink}', [SocialLinkController::class, 'destroy'])->name('socials.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Nexo ID SSO client. No-op in standalone mode (NEXO_SSO_ENABLED unset); must be
// required before the catch-all so /auth/nexo/* can never be shadowed.
require __DIR__.'/nexo-sso.php';

// Catch-all public page route: must stay last so it never shadows app routes.
Route::get('/{username}', [PublicPageController::class, 'show'])
    ->where('username', '[a-z0-9]+(?:[-_][a-z0-9]+)*')
    ->name('page.show');
