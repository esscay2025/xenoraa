<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Artisan command to provision nginx config + Let's Encrypt SSL
 * for a tenant's custom domain.
 *
 * Usage:
 *   php artisan xenoraa:provision-domain joseindustries.in
 *
 * This command is also called automatically from SiteController::saveDomain()
 * when a tenant maps a custom domain.
 */
class ProvisionDomainSSL extends Command
{
    protected $signature   = 'xenoraa:provision-domain {domain : The custom domain to provision SSL for}';
    protected $description = 'Create nginx config and obtain Let\'s Encrypt SSL for a tenant custom domain';

    const APP_ROOT  = '/var/www/gopi.blog/gopi-portfolio/public';
    const PHP_SOCK  = 'unix:/run/php/php8.3-fpm.sock';
    const LETSENCRYPT_BASE = '/etc/letsencrypt/live';

    public function handle(): int
    {
        $domain    = trim($this->argument('domain'));
        $domain    = preg_replace('#^https?://#', '', $domain);
        $domain    = rtrim($domain, '/');
        $wwwDomain = 'www.' . $domain;

        $this->info("Provisioning SSL for: {$domain}");

        // ── 1. Verify domain points to this server ─────────────────────────
        $serverIp = trim(shell_exec('curl -s ifconfig.me 2>/dev/null') ?: '');
        $domainIp = trim(gethostbyname($domain));
        if ($serverIp && $domainIp && $domainIp !== $serverIp) {
            $this->warn("Domain {$domain} resolves to {$domainIp}, but server IP is {$serverIp}.");
            $this->warn('DNS may not have propagated yet. Proceeding anyway...');
        }

        // ── 2. Write temporary HTTP-only nginx config for certbot challenge ─
        $tmpConfig  = $this->buildTmpNginxConfig($domain, $wwwDomain);
        $tmpPath    = "/etc/nginx/sites-available/{$domain}.tmp";
        $tmpEnabled = "/etc/nginx/sites-enabled/{$domain}.tmp";
        $fullPath   = "/etc/nginx/sites-available/{$domain}";
        $fullEnabled = "/etc/nginx/sites-enabled/{$domain}";

        file_put_contents($tmpPath, $tmpConfig);
        if (file_exists($fullEnabled)) {
            unlink($fullEnabled);
        }
        if (!file_exists($tmpEnabled)) {
            symlink($tmpPath, $tmpEnabled);
        }
        shell_exec('nginx -t 2>&1 && nginx -s reload 2>&1');
        $this->info('Nginx reloaded with HTTP-only config for certbot challenge');

        // ── 3. Obtain SSL certificate via Certbot ──────────────────────────
        $email      = config('mail.from.address', 'admin@xenoraa.com');
        $certCmd    = "certbot certonly --nginx -d {$domain} -d {$wwwDomain} --non-interactive --agree-tos --email {$email} 2>&1";
        $certOutput = shell_exec($certCmd);
        $this->line($certOutput ?? '');

        $certPath = self::LETSENCRYPT_BASE . "/{$domain}/fullchain.pem";

        if (!file_exists($certPath)) {
            // Try without www (some domains may not have www DNS)
            $certCmd2    = "certbot certonly --nginx -d {$domain} --non-interactive --agree-tos --email {$email} 2>&1";
            $certOutput2 = shell_exec($certCmd2);
            $this->line($certOutput2 ?? '');

            if (!file_exists($certPath)) {
                $this->error('Failed to obtain SSL certificate. Check DNS and try again.');
                if (file_exists($tmpEnabled)) unlink($tmpEnabled);
                if (file_exists($tmpPath))    unlink($tmpPath);
                shell_exec('nginx -s reload 2>&1');
                return self::FAILURE;
            }
        }

        $this->info("SSL certificate obtained for {$domain}");

        // ── 4. Write full HTTPS nginx config ──────────────────────────────
        $fullConfig = $this->buildNginxConfig($domain, $wwwDomain);
        file_put_contents($fullPath, $fullConfig);

        // ── 5. Remove temp config, enable full HTTPS config ────────────────
        if (file_exists($tmpEnabled)) unlink($tmpEnabled);
        if (file_exists($tmpPath))    unlink($tmpPath);
        if (!file_exists($fullEnabled)) {
            symlink($fullPath, $fullEnabled);
        }
        shell_exec('nginx -t 2>&1 && nginx -s reload 2>&1');
        $this->info("HTTPS nginx config enabled for {$domain}");

        Log::info("SSL provisioned for custom domain: {$domain}");

        $this->info('');
        $this->info("Domain {$domain} is now live with HTTPS!");
        return self::SUCCESS;
    }

    private function buildNginxConfig(string $domain, string $wwwDomain): string
    {
        $root = self::APP_ROOT;
        $sock = self::PHP_SOCK;
        $cert = self::LETSENCRYPT_BASE . "/{$domain}";
        return "# {$domain} — Xenoraa Tenant Custom Domain
server {
    listen 80;
    listen [::]:80;
    server_name {$domain} {$wwwDomain};
    return 301 https://\$host\$request_uri;
}
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name {$domain} {$wwwDomain};
    root {$root};
    index index.php index.html;
    ssl_certificate {$cert}/fullchain.pem;
    ssl_certificate_key {$cert}/privkey.pem;
    include /etc/letsencrypt/options-ssl-nginx.conf;
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem;
    add_header X-Frame-Options SAMEORIGIN always;
    add_header X-Content-Type-Options nosniff always;
    add_header Strict-Transport-Security max-age=31536000 always;
    client_max_body_size 20M;
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/json;
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    error_page 404 /index.php;
    location ~ \\.php\$ {
        fastcgi_pass {$sock};
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
    }
    location ~* \\.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot|webp)\$ {
        expires 30d;
        add_header Cache-Control public;
        access_log off;
    }
    access_log /var/log/nginx/{$domain}.access.log;
    error_log /var/log/nginx/{$domain}.error.log;
}
";
    }

    private function buildTmpNginxConfig(string $domain, string $wwwDomain): string
    {
        $root = self::APP_ROOT;
        $sock = self::PHP_SOCK;
        return "server {
    listen 80;
    listen [::]:80;
    server_name {$domain} {$wwwDomain};
    root {$root};
    index index.php index.html;
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }
    location ~ \\.php\$ {
        fastcgi_pass {$sock};
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }
}
";
    }
}
