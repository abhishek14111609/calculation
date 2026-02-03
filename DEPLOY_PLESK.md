# Plesk Production Deployment Guide

This document outlines the steps to deploy the **Calculation System** to a Plesk production environment.

## 1. Prerequisites
- **PHP 8.2+** (Recommended)
- **MySQL 5.7+** or **MariaDB 10.3+**
- **Composer** installed on server
- **SSH Access** (Optional but highly recommended)

## 2. Prepare Assets (Locally)
Before uploading, ensure you have built the production assets:
```bash
npm install
npm run build
```
Ensure the `public/build` folder exists and is included in your upload.

## 3. Uploading Files
- Upload all files to the domain's root (usually `/httpdocs`).
- **IMPORTANT**: If your domain root is `/httpdocs`, you **MUST** change the Document Root in Plesk to `/httpdocs/public`.

## 4. Plesk Configuration
1. **Hosting Settings**:
   - Change "Document Root" to `/httpdocs/public`.
   - Ensure "PHP Support" is enabled and set to **8.2**.
2. **Database**:
   - Create a database, user, and password in Plesk.
   - Note these for the `.env` file.
3. **Environment Setup**:
   - Create/Edit the `.env` file in the root directory.
   - Set `APP_ENV=production` and `APP_DEBUG=false`.
   - Update `DB_*` settings with your Plesk database credentials.
   - Run `php artisan key:generate` if not already set.

## 5. Post-Deployment Commands
Run these via SSH or the Plesk "PHP Composer" and "PHP Artisan" tools:
```bash
# Install production dependencies
composer install --optimize-autoloader --no-dev

# Generate storage link
php artisan storage:link

# Run migrations (CAUTION: backup first)
php artisan migrate --force

# Optimize performance
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 6. Permissions
Ensure the following directories are writable by the web server (usually handled automatically by Plesk, but worth checking):
- `storage`
- `bootstrap/cache`

## 7. Cron Job (Optional but Recommended)
For background tasks and cleanups, add this Cron Job in Plesk:
- **Command**: `/opt/plesk/php/8.2/bin/php /var/www/vhosts/YOUR_DOMAIN/httpdocs/artisan schedule:run`
- **Schedule**: Every Minute (`* * * * *`)

## 8. Common Issues
- **500 Error**: Check `storage/logs/laravel.log` for details.
- **Mix/Vite Manifest missing**: Ensure `public/build/manifest.json` was uploaded.
- **Database Connection**: Verify DB credentials in `.env`.
