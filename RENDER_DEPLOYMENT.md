# Deployment Guide - Render.com

## Prasyarat

-   Repository di GitHub (dengan semua code sudah di-push)
-   Akun Render (daftar gratis di https://render.com)
-   GitHub account untuk authorize Render

## Langkah-Langkah Deployment

### 1. Persiapan Repository

Pastikan semua ini sudah ada di repository:

-   `.env.example` (jangan include `.env`)
-   `composer.json` & `composer.lock` ✓ (sudah updated untuk PHP 8.2)
-   `package.json` & `package-lock.json` ✓
-   `build.sh` script untuk build custom
-   Semua file aplikasi Laravel

### 2. Buat Build Script (`build.sh`)

Render butuh script custom untuk install dependencies PHP dan Node. Buat file `build.sh` di root project:

```bash
#!/usr/bin/env bash
set -o errexit

echo "Installing PHP dependencies..."
composer install --no-dev --no-interaction --prefer-dist

echo "Installing Node dependencies..."
npm install

echo "Building assets..."
npm run build

echo "Caching configuration..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Caching views..."
php artisan view:cache

echo "Build completed!"
```

Jangan lupa commit dan push file ini ke GitHub:

```bash
git add build.sh
git commit -m "Add build script for Render deployment"
git push
```

### 3. Setup di Render Dashboard

1. **Login ke Render** (https://dashboard.render.com)
2. **Klik "New +"** → Pilih **"Web Service"**
3. **Connect GitHub Repository:**

    - Klik "Connect account"
    - Authorize Render ke GitHub
    - Pilih repository `pos_bangBoel`

4. **Konfigurasi Web Service:**

    - **Name:** `pos_bangboel` (atau nama lain)
    - **Environment:** `PHP`
    - **Region:** Asia (Singapore) untuk latency rendah
    - **Branch:** `main`
    - **Build Command:** `./build.sh`
    - **Start Command:** `php artisan serve --host=0.0.0.0 --port=10000`

5. **Plan:** Pilih **"Free"**

### 4. Environment Variables

Di Render Dashboard > Environment:

Tambahkan ini:

```
APP_NAME=POS_BangBoel
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:wh5+sTPN4quraSzefm3cGSfVjPZXQIqdHZZX2XJ4k9Q=
APP_URL=https://your-app.onrender.com

DB_CONNECTION=mysql
DB_HOST=your-mysql-host.c.db.onrender.com
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 5. Setup Database MySQL (PostgreSQL juga bisa)

#### Opsi A: Gunakan Render MySQL Service (Recommended)

1. Di Render Dashboard, klik **"New +"** → **"MySQL"**
2. Konfigurasi database
3. Copy connection string ke environment variables Web Service
4. Render auto-generate DB name, user, password

#### Opsi B: Gunakan Database Eksternal

-   AWS RDS (MySQL)
-   ClearDB (MySQL gratis terbatas)
-   Supabase (PostgreSQL gratis)

### 6. Deploy!

1. Setelah environment variables set, Render otomatis mulai build
2. Cek **"Logs"** tab untuk melihat progress
3. Tunggu hingga status jadi **"Live"** (hijau)
4. Akses aplikasi di URL: `https://your-app.onrender.com`

### 7. Jalankan Database Migrations

Setelah deploy sukses, Render akan provide shell access:

1. Di Dashboard > Web Service > **"Shell"**
2. Jalankan:
    ```bash
    php artisan migrate --force
    ```
3. Atau cek logs untuk lihat error

## Troubleshooting

### Error: "Build failed"

Cek **Logs** tab untuk detail error. Common issues:

**Error: Permission denied untuk build.sh**

```bash
# Di lokal, buat executable:
chmod +x build.sh
git add build.sh
git commit -m "Make build.sh executable"
git push
```

**Error: Composer dependencies**

-   Sudah di-fix dengan update composer.lock sebelumnya ✓

**Error: PHP extensions missing**

-   Render auto-install extensions dasar: PDO MySQL, cURL, dll

### Error: "Database connection failed"

-   Pastikan DB_HOST, DB_USERNAME, DB_PASSWORD correct
-   Cek MySQL service sudah running di Render
-   Jalankan migrations via Shell

### Error: "404 - Not Found"

-   Pastikan routes di-cache dengan benar
-   Render serve app di `/` (root)

### App Sleeping (Free Tier)

⚠️ **Free tier apps will spin down after 15 minutes of inactivity**

-   Cold start (~30 detik) saat pertama akses
-   Ini limitation dari Render free tier
-   Upgrade ke paid untuk "always on" ($7/bulan)

## Tips & Tricks

### 1. View Logs

```
Render Dashboard > Logs tab → Scroll untuk see error
```

### 2. SSH ke Server

```
Render Dashboard > Web Service > "Shell" tab
cd /var/task  # aplikasi berada di sini
```

### 3. Database Backup

```
Di Render Dashboard > MySQL service > Backups
```

### 4. Custom Domain

```
Dashboard > Web Service > Settings > Custom Domain
Tambahkan domain Anda
```

### 5. Enable HTTPS

Render auto-generate SSL certificate ✓

## Storage Files

Untuk upload files, gunakan:

1. **Local storage** (temporary, hilang saat redeploy)

    ```php
    Storage::disk('public')->put('file.txt', 'content');
    ```

2. **External Storage (Recommended):**
    - AWS S3
    - Cloudinary
    - UploadThing
    - Etc.

## Performance Tips

1. **Cache Configuration**

    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```

    Sudah di-include di `build.sh` ✓

2. **Database Optimization**

    - Add indexes di migrations
    - Use eager loading (with())

3. **Asset Optimization**
    - Vite auto-minify ✓
    - Gzip enabled by default ✓

## Cost Breakdown (Free Tier)

-   Web Service: Gratis
-   MySQL Database: Gratis (dengan limitasi)
-   Bandwidth: Gratis
-   Build Time: Limited

**Upgrade Path (jika needed):**

-   Web Service Pro: $7/bulan (no sleeping)
-   MySQL Pro: $15/bulan (no limitasi)

## Resources

-   Render Docs: https://render.com/docs
-   Render Laravel Guide: https://render.com/docs/deploy-laravel
-   Render Dashboard: https://dashboard.render.com

## Checklist Sebelum Deploy

-   [ ] `.env.example` ada
-   [ ] `composer.lock` sudah updated ✓
-   [ ] `build.sh` sudah di-push
-   [ ] `build.sh` permission 755
-   [ ] APP_KEY sudah di-set di environment
-   [ ] GitHub repository connected
-   [ ] Database credentials correct
-   [ ] All code sudah di-push ke main branch

---

**Siap deploy!** Buka https://dashboard.render.com dan mulai 🚀

Hubungi jika ada error!
