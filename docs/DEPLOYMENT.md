# Deploying Nexo Links to shared hosting (Hostinger)

This guide targets Hostinger shared/Premium hosting, but applies to any host
with **PHP 8.3+, MySQL and SSH access**. No Node.js is needed on the server —
assets are built locally or by CI.

## Running it locally

Before deploying anywhere, this is how to get Nexo Links up on your own machine. The README
points here on purpose: keeping the steps in one place is why they stopped drifting.

### Option A — everything in Docker (recommended if you just want it running)

`compose.yaml` in this repo runs the **app only**: the author's machine keeps a single
MySQL/Mailpit shared by every Nexo tool, so shipping another database per repo would be
waste. `compose.selfhost.yaml` is the overlay that fills the gap for everyone else.

```sh
cp .env.example .env
# in .env: DB_HOST=mysql  DB_PORT=3306  MAIL_HOST=mailpit  MAIL_PORT=1025
docker compose -f compose.yaml -f compose.selfhost.yaml up -d
docker compose exec laravel.test composer install
docker compose exec laravel.test php artisan key:generate
docker compose exec laravel.test php artisan migrate
npm install && npm run build
```

The app answers on **http://localhost:8090** and outgoing mail lands in Mailpit at
http://localhost:8025.

### Option B — your own MySQL

Keep `compose.yaml` alone (or no Docker at all) and point `.env` at your database:
`DB_HOST` / `DB_PORT` / `DB_DATABASE` (`nexo`) / `DB_USERNAME` / `DB_PASSWORD`. Everything
else is a stock Laravel app: `composer install`, `php artisan key:generate`,
`php artisan migrate`, `npm run build`, `php artisan serve`.

> The values committed in `.env.example` target the author's shared local stack
> (`host.docker.internal:3307`). Override them — they are a default, not a requirement.

Run the suite with `vendor/bin/pest` (SQLite in memory — it never touches your database).

---

## 1. One-time server setup

### Subdomain and document root

1. hPanel → **Domains → Subdomains** → create `nexolinks` (→ `nexolinks.alvarocdev.com`).
2. The app must serve from its `public/` folder. Point the subdomain's
   document root at `<app>/public`, e.g. app in
   `~/domains/alvarocdev.com/nexo-links` → document root
   `~/domains/alvarocdev.com/nexo-links/public`.
   If hPanel doesn't let you pick a folder outside the subdomain root, create
   the subdomain first and replace its folder with a symlink:
   ```bash
   rm -rf ~/domains/alvarocdev.com/public_html/nexolinks
   ln -s ~/domains/alvarocdev.com/nexo-links/public ~/domains/alvarocdev.com/public_html/nexolinks
   ```
3. hPanel → **Security → SSL** → issue the free certificate for the subdomain.
4. hPanel → **Advanced → PHP Configuration** → select **PHP 8.3+**.

### Database

hPanel → **Databases → MySQL** → create a database and user, note the
credentials (Hostinger prefixes them, e.g. `u123456_nexo`).

### Code and dependencies

SSH in (hPanel → Advanced → SSH Access):

```bash
cd ~/domains/alvarocdev.com
git clone https://github.com/nexo-tools/nexo-links.git nexo
cd nexo
# --no-scripts + manual package:discover because shared hosts usually
# disable proc_open, which Composer needs to run post-install scripts
composer install --no-dev --optimize-autoloader --no-scripts
php artisan package:discover --ansi
cp .env.example .env
nano .env    # see the template below
php artisan key:generate
php artisan migrate --force
# storage:link needs exec(), usually disabled on shared hosts — link manually:
ln -s "$(pwd)/storage/app/public" "$(pwd)/public/storage"
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

> Private repo? Add a read-only deploy key: `ssh-keygen -t ed25519`, then add
> `~/.ssh/id_ed25519.pub` under the repo's **Settings → Deploy keys**.

### Production `.env` essentials

```dotenv
APP_NAME="Nexo Links"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://nexolinks.alvarocdev.com
APP_TIMEZONE=America/Argentina/Buenos_Aires

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=u123456_nexo
DB_USERNAME=u123456_nexo
DB_PASSWORD=********

SESSION_SECURE_COOKIE=true
CACHE_STORE=file
QUEUE_CONNECTION=sync

# Hostinger email account for verification/reset emails
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_SCHEME=smtps
MAIL_USERNAME=nexo@alvarocdev.com
MAIL_PASSWORD=********
MAIL_FROM_ADDRESS=nexo@alvarocdev.com

NEXO_ATTRIBUTION_LABEL="powered by alvarocdev.com"
NEXO_ATTRIBUTION_URL="https://alvarocdev.com"
NEXO_EXAMPLE_USERNAME=alvarocdev
```

### Frontend assets (no Node on the server)

Build locally and upload once:

```bash
npm ci && npm run build
rsync -avz public/build/ user@host:~/domains/alvarocdev.com/nexo-links/public/build/
```

…or let the GitHub Actions workflow below handle it on every deploy.

### Cron (optional, for future scheduled tasks)

hPanel → **Advanced → Cron Jobs**:

```
* * * * * php ~/domains/alvarocdev.com/nexo-links/artisan schedule:run >> /dev/null 2>&1
```

## 2. Deploying updates

### Option A — manual over SSH

```bash
cd ~/domains/alvarocdev.com/nexo-links && bash scripts/deploy.sh
```

Then upload `public/build/` if frontend files changed.

### Option B — automated with GitHub Actions

`.github/workflows/deploy.yml` builds the assets and deploys over SSH.
Trigger it manually from the Actions tab. Configure these repository
secrets first (**Settings → Secrets and variables → Actions**):

| Secret | Example |
| --- | --- |
| `DEPLOY_HOST` | `123.45.67.89` (hPanel → SSH Access) |
| `DEPLOY_PORT` | `65002` |
| `DEPLOY_USER` | `u123456` |
| `DEPLOY_KEY` | private SSH key with access to the server |
| `DEPLOY_PATH` | `/home/u123456/domains/alvarocdev.com/nexo-links` |

## 3. Post-deploy checklist

- `https://nexolinks.alvarocdev.com/up` returns 200
- Landing, `/help` and the demo page render with styles
- Register a test account and verify the email arrives
- Set `NEXO_EXAMPLE_USERNAME` to your real username once your page exists
- `APP_DEBUG` is `false` and `php artisan config:cache` was re-run after
  any `.env` change
- The full CSP survives host overrides (some hosts' Force HTTPS replaces
  PHP-sent CSP headers; `public/.htaccess` re-asserts the app's policy):
  ```bash
  curl -sSI https://nexolinks.alvarocdev.com | grep -i content-security-policy
  # must show the full policy, not just "upgrade-insecure-requests"
  ```
