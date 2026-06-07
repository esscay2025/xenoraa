#!/bin/bash
# ============================================================
# POS Module Production Deployment Script
# Run this on the production server as ubuntu user
# ============================================================

set -e

APP_DIR="/var/www/xenoraa"  # Adjust if different
cd "$APP_DIR"

echo "=== Pulling latest code from GitHub ==="
git pull origin main

echo "=== Running POS migration ==="
php artisan migrate --path=database/migrations/2026_06_07_500001_create_pos_tables.php --force

echo "=== Clearing caches ==="
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "=== Optimizing ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Done! POS module deployed successfully ==="
echo "Access POS at: https://xenoraa.com/admin/pos"
