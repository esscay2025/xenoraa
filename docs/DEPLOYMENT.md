# Deployment Guide — Gopi K Portfolio

## Hostinger Deployment (Recommended)

### Prerequisites
- Hostinger Business Hosting or VPS plan
- Domain connected to Hostinger
- MySQL database created in hPanel
- PHP 8.1+ enabled

---

## Step 1 — Create MySQL Database in hPanel

1. Go to **hPanel → Databases → MySQL Databases**
2. Create a new database: `gopi_portfolio`
3. Create a database user and assign it to the database
4. Note down: Database Name, Username, Password, Host (usually `127.0.0.1`)

---

## Step 2 — Upload Files via File Manager or FTP

### Option A — File Manager (Easiest)
1. Download the project ZIP from GitHub: `https://github.com/esscay2025/gopi-portfolio/archive/refs/heads/main.zip`
2. Go to **hPanel → File Manager**
3. Navigate to `public_html`
4. Upload and extract the ZIP
5. Move all files from `gopi-portfolio-main/` up one level into `public_html/`

### Option B — SSH (Recommended for VPS)
```bash
# SSH into your server
ssh username@your-server-ip

# Navigate to web root
cd /home/username/public_html

# Clone the repository
git clone https://github.com/esscay2025/gopi-portfolio.git .

# Or pull latest changes
git pull origin main
```

---

## Step 3 — Configure Environment

1. Copy `.env.example` to `.env`:
```bash
cp .env.example .env
```

2. Update `.env` with your Hostinger database details:
```env
APP_NAME="Gopi K Portfolio"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

---

## Step 4 — Install Dependencies

Via SSH Terminal (**hPanel → Advanced → SSH Terminal**):

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies and build assets
npm install && npm run build

# Generate application key
php artisan key:generate

# Create storage symlink
php artisan storage:link
```

---

## Step 5 — Run Migrations & Seed Data

```bash
# Run migrations
php artisan migrate --force

# Seed initial data (admin user, roles, blog posts, jobs)
php artisan db:seed --force

# Cache configuration for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Step 6 — Configure Document Root

If your Hostinger plan uses `public_html` as the web root, you need to point it to the Laravel `public` folder.

**Option A — Symlink (Recommended)**
```bash
# Move public_html contents to project root, then symlink
# This assumes project is at /home/username/gopi-portfolio
ln -s /home/username/gopi-portfolio/public /home/username/public_html
```

**Option B — .htaccess in public_html**

Create `/home/username/public_html/.htaccess`:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ /gopi-portfolio/public/$1 [L]
</IfModule>
```

**Option C — Update index.php**

Edit `public_html/index.php` to point to the project:
```php
<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/../gopi-portfolio/vendor/autoload.php';
$app = require_once __DIR__.'/../gopi-portfolio/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::capture());
$response->send();
$kernel->terminate($request, $response);
```

---

## Step 7 — Set Permissions

```bash
chmod -R 755 storage bootstrap/cache
chmod -R 644 .env
```

---

## Step 8 — Admin Login

After deployment, log in at `https://yourdomain.com/login`:
- **Email:** `gopi@outlook.in`
- **Password:** `@biSou20717`

> **Important:** Change the admin password immediately after first login via Admin → Users.

---

## Google & Facebook OAuth Setup

### Google OAuth
1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Create a new project → Enable Google+ API
3. Create OAuth 2.0 credentials
4. Add Authorized redirect URI: `https://yourdomain.com/auth/google/callback`
5. Add to `.env`:
```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
```

### Facebook OAuth
1. Go to [Meta for Developers](https://developers.facebook.com)
2. Create a new App → Add Facebook Login product
3. Add Valid OAuth Redirect URI: `https://yourdomain.com/auth/facebook/callback`
4. Add to `.env`:
```env
FACEBOOK_CLIENT_ID=your-app-id
FACEBOOK_CLIENT_SECRET=your-app-secret
```

---

## Git Branching Strategy

| Branch | Purpose | Deployment |
|--------|---------|------------|
| `main` | Production-ready code | Auto-deploys to production |
| `staging` | Pre-production testing | Auto-deploys to staging |
| `develop` | Active development | Manual deployment |

### Workflow
```
develop → staging → main
```

1. All new features are developed on `develop` (or feature branches)
2. Merge to `staging` for testing
3. Merge to `main` for production release
4. Tag releases: `git tag -a v1.x.x -m "Release notes"`

---

## CI/CD Pipeline

The CI/CD workflow is documented in `docs/ci-cd-workflow.yml`.

To activate it:
1. Copy `docs/ci-cd-workflow.yml` to `.github/workflows/ci.yml`
2. Add GitHub Secrets in repository settings:
   - `PROD_HOST` — Production server IP
   - `PROD_USER` — SSH username
   - `PROD_SSH_KEY` — SSH private key
   - `STAGING_HOST` — Staging server IP
   - `STAGING_USER` — SSH username
   - `STAGING_SSH_KEY` — SSH private key

---

## Troubleshooting

| Issue | Solution |
|-------|---------|
| 500 Error | Check `storage/logs/laravel.log` |
| Permission denied | `chmod -R 755 storage bootstrap/cache` |
| DB connection failed | Verify `.env` DB credentials |
| Assets not loading | Run `npm run build` and `php artisan storage:link` |
| Login not working | Clear cache: `php artisan cache:clear` |
