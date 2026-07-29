# Nexo Links

> Entry point for any AI/agent working on this project. It follows Alvaro's
> standards system (repo `alvaro`, alvarocdev.com). Keep this file updated:
> persist here any important context that comes up during work sessions.
> Written in English because this is a public open-source repo (deliberate
> exception to the standards system's Spanish-docs rule).

## What is this project

Open-source, self-hosted link-in-bio platform (Linktree alternative): each
user gets a public page at `/{username}` with links, cookieless analytics,
scheduling, themes, social icons, QR sharing and a report system. Multi-user,
multilingual (en/es/pt). **In production** at https://nexolinks.alvarocdev.com
(Alvaro's reference instance, Hostinger shared hosting).

## Stack

- Laravel 13 (framework `^13.8`) · PHP 8.4 in Sail, 8.5 in production
- MySQL 8 · SQLite in-memory for tests
- Blade + Alpine.js + Tailwind CSS v3 + Vite (server-rendered, minimal JS)
- Quality: Pest, Pint, Larastan level 6, GitHub Actions CI
- Hosting: Hostinger shared (LiteSpeed) · mail via smtp.hostinger.com

## How to run it

There is no local PHP/Composer — everything runs through Docker/Sail
(see README for first-time setup). Since 2026-07-26 the stateful services
(MySQL, Mailpit, phpMyAdmin) come from the shared dev environment
(`~/dev-environment`, compose project `nexo`): MySQL on host port **3307**
(db `nexo`, user/pass `dev`/`dev`), Mailpit SMTP 1025 / UI 8025, phpMyAdmin
8306. This repo's `compose.yaml` ships only the app runtime and reaches them
via `host.docker.internal`; `APP_PORT=8090` / `VITE_PORT=5174` /
`WWWUSER`/`WWWGROUP` are pinned in `.env`. Verified daily commands:

```bash
cd ~/dev-environment && docker compose up -d mysql mailpit  # shared services first
./vendor/bin/sail up -d                 # app at http://localhost:8090
./vendor/bin/sail artisan migrate --seed  # demo login: demo@nexo.test / password
./vendor/bin/sail npm run build         # or `npm run build` on the host
./vendor/bin/sail artisan test          # Pest suite (SQLite, no MySQL needed)
./vendor/bin/sail composer lint:check   # Pint (lint = auto-fix)
./vendor/bin/sail composer analyse      # Larastan
```

## Project conventions

- **One Conventional Commit per phase/feature, in English**; tests + Pint +
  Larastan must be green and the change verified end-to-end before committing.
- **Every user-facing string goes through `__()`**. New strings must be added
  (en → es + pt, canonical code `pt` sourced from laravel-lang `pt_BR`) to
  `scripts/generate-translations.mjs`, then run it.
  Custom rule messages need `$fail(...)->translate()`.
  `node scripts/generate-translations.mjs --check` verifies without writing
  (missing translations + lang files that no longer match the generator);
  `TranslationsSyncTest` and the CI i18n step both run it, so forgetting to
  re-generate is a red build, not a silently English UI.
- **Generated assets are scripted, never hand-edited**: brand/favicons/OG via
  `scripts/generate-brand-assets.mjs` (source: `resources/brand/mark.svg`),
  platform icons via `scripts/generate-social-icons.mjs`.
- **CSP lives in two synced places**: `SecurityHeaders` middleware and
  `public/.htaccess` (re-asserted there because the host overrides PHP-sent
  CSP). A test compares both — change them together.
- **Branding is env-driven, never hardcoded**: `NEXO_ATTRIBUTION_*` controls
  the public footer per instance. Do not hardcode alvarocdev.com in views —
  self-hosted instances must stay neutral (this is why the standards-system
  branding footer component is intentionally NOT installed here).
- New top-level routes need their path added to `reserved_usernames` in
  `config/nexo.php` (public pages are a root-level catch-all).
- Public pages are cached per locale; models that affect them must bust the
  cache (`$touches = ['page']` + `Page::flushCache()` via model events;
  query-builder writes need a manual `$page->touch()`).
- No external requests from any page (no CDNs, no font services) — privacy
  pitch and CSP both depend on it.

## Important decisions

- **2026-07-13** — Laravel + MySQL chosen because the deploy target is
  Hostinger shared hosting (PHP-native, no Node/Docker in production).
- **2026-07-14** — Rebranded "Nexo" → "Nexo Links" to differentiate from the
  Nexo crypto company; "nexolinks" only when a single token is required.
- **2026-07-14** — Attribution footer made configurable per instance by
  design (open source neutrality); Alvaro's instance sets
  "powered by alvarocdev.com" via env.
- **2026-07-19** — Standards-system validation: AGENTS.md created (in
  English, deliberate exception), branding-footer skill satisfied through the
  existing env attribution + UTM params instead of the shared component.

## Accumulated context

- **2026-07-28** — **Nexo standard: static pages, guardians, i18n drift check.**
  (a) **Error pages**: `resources/views/components/error-layout.blade.php` +
  `errors/{403,404,419,429,500,503}.blade.php`, copied from the standards repo
  (`~/alvaro/templates/nexo-ui/pages/`) and adapted — this repo has no
  `partials/head`, so the layout inlines its own head (brand-head + `<x-nexo-seo>`
  with `noindex` and no hreflang). Classes verified against **Tailwind 3** (the
  sibling tools are on TW4). (b) **Legal pages**: `/privacidad` + `/terminos`
  (route names `legal.privacy` / `legal.terms`), `LegalController` + `legal/show`,
  content in `lang/{es,en,pt}/legal.php` — Spanish is the source, en/pt are
  translations. Content describes what the code really does (VisitorHash's
  daily-rotating SHA-256, the sessions table storing IP+UA only while signed in,
  public avatar/banner files, the URL-scheme whitelist, reserved usernames); both
  segments added to `reserved_usernames` and linked from `nexo-footer`, the
  sitemap and the public page footer (privacy only there). (c) **Guardians**:
  `BrandAssetsPresentTest`, `DarkModeCoverageTest`, `StaticPagesTest` in
  `tests/Feature/Nexo/`. DarkMode scans the whole view tree with **one carve-out,
  `pages/show.blade.php`** — the storefront is themed by its owner and picks its
  ink from the owner's background brightness, not from `data-theme`; a companion
  test asserts that logic still exists so the exception cannot rot into a bug.
  Fixing it rather than weakening it: the leftover Breeze surfaces
  (`secondary-button`, `modal`, `responsive-nav-link`, the SSO button in
  `auth/login`, `profile/edit`) moved onto the token layer. (d) **i18n**:
  `--check` added to `scripts/generate-translations.mjs` (extracts `__()` /
  `trans_choice()` / `$fail()` literals from `app/` + `resources/views`, skipping
  lang-file and interpolated keys; fails on untranslated strings AND on lang files
  that differ from the generator's output), plus `TranslationsSyncTest` and a CI
  step. It immediately caught a pre-existing gap: `Avatar`, `Banner`, `Color`,
  `URL`, `Are you sure you want to delete your account?` and the account-deletion
  paragraph were never translated. (e) **SEO**: `/help` migrated from a hand-rolled
  head to `<x-nexo-seo>` (it was trilingual with no hreflang); the storefront keeps
  its own head **on purpose** — per-page title/description, the owner's avatar as
  `og:image`, `og:type=profile`, and no hreflang because there is no translated
  twin. 250 tests green (1 skipped: the sync test needs node, which the composer
  runner image lacks — CI runs the generator step directly).
- **2026-07-23** — **Nexo ID SSO client integrated** (ecosystem FASE 1; copied from the
  standards-repo template `~/alvaro/templates/nexo-sso-client`). Optional and **off by
  default** (`NEXO_SSO_ENABLED=false`) — standalone local auth is untouched and still the
  default (AC-CFG-1 asserts the SSO routes 404 when disabled). Files: `config/nexo-sso.php`,
  `routes/nexo-sso.php` (required in `web.php` **before** the `/{username}` catch-all),
  `app/Services/NexoSso/*`, `app/Http/Controllers/Auth/NexoSsoController.php`, migration
  `..._add_nexo_id_sub_to_users_table`. **Key adaptation (the blocker):**
  `NexoSsoUserResolver::newUser()` provisions the mandatory 1:1 `Page` (with a generated
  unique, reserved-safe, `Username`-rule-valid handle) inside the user-create transaction —
  a bare template insert would 404 every dashboard route (`pageOf()` → abort 404). Callback
  lands on `route('dashboard')`. Login view shows "Continue with Nexo ID" only when enabled.
  `phpunit.xml` sets `NEXO_SSO_ENABLED=true` (routes evaluate at boot). Register the prod
  client with redirect `{APP_URL}/auth/nexo/callback` (owner-gated). Also this pass:
  `reserved_usernames` now covers every top-level GET route segment (`analytics`, `design`,
  `auth`, `forgot-password`, `reset-password`, `verify-email`, `confirm-password`) with a
  **route-enumerating guardian** test (`tests/Feature/ReservedUsernamesTest.php`) replacing
  the old 2-URL check; `guzzlehttp/guzzle` bumped ≥7.15.1 (4 medium advisories, `composer
  audit` now clean) and `firebase/php-jwt` added; `DesignTest` made GD-independent
  (`createWithContent` + real PNG bytes) so it runs on the GD-less container runner;
  `deploy.sh` now `cache:clear`s (page cache bakes in @vite hashes); CI gained `composer
  audit`; the `alvarocdev` example handle genericised to `yourname`. 198 tests, pint +
  Larastan + audit + i18n all green. **Deferred to FASE 5** (bucket B/F): i18n `--check`
  drift guardian + CI node step, stricter public-page CSP, unique-visitor multi-day label,
  countdown reload guard. Source: `~/alvaro/inbox/ecosystem-audit`.
- **2026-07-19** — Retroactive open-source audit passed: full git history
  (37 commits) and HEAD checked — no secrets, no `.env` ever committed, no
  real credentials/IPs/server data; only intentional public info (docs use
  Alvaro's domain as the self-hosting example). Informational: commit author
  email is `alvaro@mc4pc.com`; sender mailbox appears in DEPLOYMENT.md's
  env template.
- **2026-07-19** — Standards validation session. Pending on the server:
  update `NEXO_ATTRIBUTION_URL` to
  `https://alvarocdev.com/?utm_source=nexo-links&utm_medium=powered-by`
  (+ `php artisan config:cache`) to match the standard component's UTM
  behavior.
- **2026-07-14** — Hostinger gotchas (all documented in
  `docs/DEPLOYMENT.md`): `proc_open`/`exec` disabled → composer runs with
  `--no-scripts` + manual `php artisan package:discover`; `storage:link`
  replaced by a manual `ln -s`; Force HTTPS replaces the PHP CSP header →
  `.htaccess` re-asserts it. Deploys: `bash scripts/deploy.sh` over SSH +
  upload `public/build` (built locally or by the manual-trigger deploy
  workflow) — no Node on the server.
- **2026-07-14** — Laravel 13 gotchas hit during development: session
  validation errors are plain arrays (use `assertInvalid`), JSON error
  rendering defaults to `api/*` only (overridden in `bootstrap/app.php`),
  unordered `pluck` flaked under SQLite (always order before asserting).
- **2026-07-13** — The seeder is dev-only (demo account). Production's
  landing example comes from `NEXO_EXAMPLE_USERNAME` (set to `alvarocdev`);
  the CTA hides itself if that page doesn't exist.
