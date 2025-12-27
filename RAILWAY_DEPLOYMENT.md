# Deployment Guide - Railway.app

## Prasyarat

-   Repository di GitHub (dengan semua code sudah di-push)
-   Akun Railway (daftar di https://railway.app)

## Langkah-Langkah Deployment

### 1. Persiapan Repository

Pastikan semua ini sudah ada di repository Anda:

-   `.env.example` (jangan include `.env`)
-   `composer.json` & `composer.lock`
-   `package.json` & `package-lock.json`
-   Semua file aplikasi Laravel

**Command untuk generate APP_KEY jika belum ada:**

```bash
php artisan key:generate
```

### 2. Setup di Railway Dashboard

1. **Login ke Railway** (https://railway.app)
2. **Buat Project Baru:**

    - Klik "New Project"
    - Pilih "Deploy from GitHub repo"
    - Authorize GitHub dan pilih repository `pos_bangBoel`

3. **Railway akan auto-detect Laravel** dan setup:
    - ✅ Nginx web server
    - ✅ PHP runtime
    - ✅ MySQL database

### 3. Environment Variables

Railway akan meminta konfigurasi environment variables. Set yang penting:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app
APP_KEY=base64:wh5+sTPN4quraSzefm3cGSfVjPZXQIqdHZZX2XJ4k9Q=

DB_CONNECTION=mysql
DB_HOST=${{ Mysql.MYSQL_HOST }}
DB_PORT=${{ Mysql.MYSQL_PORT }}
DB_DATABASE=${{ Mysql.MYSQL_DATABASE }}
DB_USERNAME=${{ Mysql.MYSQL_USER }}
DB_PASSWORD=${{ Mysql.MYSQL_PASSWORD }}
```

### 4. Database Setup

1. **Railway akan auto-create MySQL database**
2. **Jalankan migrations saat deployment:**
    - Buka Railway Dashboard > Deployments tab
    - Di "Run command on deploy", masukkan:
    ```
    php artisan migrate --force
    ```

### 5. Storage & Public Files

Untuk melayani file yang di-upload:

```bash
# Di Railway, set ini di start command:
php artisan storage:link
```

### 6. Deploy

1. Setelah setup selesai, Railway akan **auto-deploy** setiap kali Anda push ke GitHub
2. Cek status deployment di Railway Dashboard
3. Akses aplikasi di URL yang disediakan Railway

## Troubleshooting

### Error: "ext-intl missing" atau "ext-zip missing"

Railway PHP 8.2 tidak install semua extensions. Railway akan auto-detect `railway.sh` dan gunakan custom build script.

File `railway.sh` sudah ada dengan:

-   `--no-dev` (skip testing dependencies yang butuh PHP 8.3+)
-   `--ignore-platform-req=php` (ignore PHP version check)
-   `--ignore-platform-req=ext-intl` (ignore missing intl extension)

Jika masih error, update composer.lock secara lokal:

```bash
# Lokal di Windows/Mac dengan PHP 8.2
php artisan optimize
composer dump-autoload --no-dev
git add composer.lock
git commit -m "Update composer.lock for PHP 8.2"
git push
```

### Error: "Database connection failed"

-   Cek environment variables sudah benar (terutama `DB_*`)
-   Pastikan migrations sudah berjalan (Railway auto-run di `start-container.sh`)
-   Cek Railway Dashboard > Logs untuk error detail

### Error: "File upload tidak work"

-   Railway auto-run `php artisan storage:link`
-   Untuk production storage, gunakan external seperti AWS S3

## Tips & Tricks

1. **SSH ke Server:**

    ```
    Railway CLI > railway shell
    ```

2. **Check Logs:**

    ```
    Railway Dashboard > Logs tab
    ```

3. **Backup Database:**

    - Railway menyediakan automated backups
    - Cek di Dashboard > MySQL service > Backups

4. **Custom Domain:**
    - Railway Dashboard > Settings > Domains
    - Tambahkan custom domain Anda

## Resources

-   Dokumentasi Railway: https://docs.railway.app
-   Laravel on Railway: https://railway.app/docs/guides/laravel
-   Railway CLI: https://docs.railway.app/cli/commands

---

**Deploy sekarang!** 🚀
