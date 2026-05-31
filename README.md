# ISP Management

Aplikasi manajemen ISP (Internet Service Provider) berbasis web yang dibangun menggunakan **Laravel 13**, **PHP 8.4**, **MySQL 8.0**, dan **Tailwind CSS**.

---

## Persyaratan Sistem

Pastikan sistem kamu telah memenuhi persyaratan berikut sebelum melakukan instalasi:

| Kebutuhan | Versi |
|-----------|-------|
| PHP | 8.4.21 |
| MySQL | 8.0.30 |
| Laravel | 13.x |
| Tailwind CSS | 4.x |
| Composer | >= 2.x |
| Node.js & NPM | >= 18.x |

---

## 1. Cara Instalasi Aplikasi

Clone repository ini ke direktori lokal kamu:

```bash
git clone https://github.com/msuryaa/isp-management.git
cd isp-management
```

Install semua dependensi PHP menggunakan Composer:

```bash
composer install
```

Install dependensi Node.js:

```bash
npm install
```

---

## 2. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Buka file `.env` dan sesuaikan konfigurasi database:

```env
APP_NAME="ISP Management"
APP_ENV=local
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=isp_management
DB_USERNAME=root
DB_PASSWORD=
```

Build asset frontend (Tailwind CSS):

```bash
npm run build
```

---

## 3. Cara Menjalankan Migration

Buat database `isp_management` terlebih dahulu di MySQL, kemudian jalankan migration:

```bash
php artisan migrate
```

Jika ingin mereset database dan menjalankan ulang semua migration:

```bash
php artisan migrate:fresh
```

---

## 4. Cara Menjalankan Seeder

Jalankan seeder untuk mengisi data awal ke dalam database:

```bash
php artisan db:seed
```

Atau jalankan migration sekaligus seeder dalam satu perintah:

```bash
php artisan migrate:fresh --seed
```

---

## 5. Cara Menjalankan Aplikasi

Jalankan development server Laravel:

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`.

Untuk mode development dengan hot-reload Tailwind CSS, jalankan di terminal terpisah:

```bash
npm run dev
```

Atau jalankan semua service sekaligus menggunakan:

```bash
composer run dev
```

---

## Akun Default

Setelah menjalankan seeder, gunakan akun berikut untuk login ke aplikasi:

| Role | Email | Password |
|------|-------|----------|
| Administrator | admin@gmail.com | password123 |
| Staff | staff@gmail.com | password123 |

> **Catatan:** Segera ganti password setelah login pertama kali di lingkungan production.

---

## Lisensi

Aplikasi ini menggunakan lisensi [MIT](https://opensource.org/licenses/MIT).
