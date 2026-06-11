<?php
$secret = "xenoraa-deploy-2026";
if (!isset($_GET["key"]) || $_GET["key"] !== $secret) {
    http_response_code(403);
    die("Forbidden");
}
$output = [];
$output[] = shell_exec("cd /var/www/gopi.blog/gopi-portfolio && git pull origin main 2>&1");
$output[] = shell_exec("cd /var/www/gopi.blog/gopi-portfolio && php artisan config:cache 2>&1");
$output[] = shell_exec("cd /var/www/gopi.blog/gopi-portfolio && php artisan route:cache 2>&1");
$output[] = shell_exec("cd /var/www/gopi.blog/gopi-portfolio && php artisan view:clear 2>&1");
echo "<pre>" . implode("\n---\n", $output) . "</pre>";
unlink(__FILE__);
echo "Done. Script deleted.";
