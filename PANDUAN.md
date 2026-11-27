# Panduan: Database + Laravel – LED Speed #9thGarage

Folder ini adalah **tambahan (overlay)** untuk project Laravel baru. Isinya migration, model,
controller, route, view, seeder, CSS/JS, dan logo. Strukturnya sama dengan project Laravel,
jadi tinggal disalin ke dalamnya.

Kebutuhan: PHP 8.2+, Composer, MySQL/MariaDB (paling mudah pakai Laragon atau XAMPP), Laravel 11 atau lebih baru.

## 1. Buat project Laravel

    composer create-project laravel/laravel bengkel-ledspeed
    cd bengkel-ledspeed

## 2. Buat database

Lewat phpMyAdmin: buat database `bengkel_ledspeed` dengan collation `utf8mb4_unicode_ci`.
Atau lewat terminal:

    mysql -u root -p -e "CREATE DATABASE bengkel_ledspeed CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

## 3. Salin file tambahan

Salin seluruh isi folder `laravel-bengkel` ke root project, lalu pilih **Replace / timpa** bila diminta.
File bawaan yang ikut tertimpa: `app/Models/User.php`, `routes/web.php`, `database/seeders/DatabaseSeeder.php`.

## 4. Sambungkan ke database (.env)

Buka file `.env` di root project, ubah/tambahkan baris berikut (Laravel baru memakai SQLite secara default):

    APP_NAME="LED Speed 9thGarage"
    APP_TIMEZONE=Asia/Jakarta
    APP_LOCALE=id

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=bengkel_ledspeed
    DB_USERNAME=root
    DB_PASSWORD=

`DB_PASSWORD` kosong untuk Laragon/XAMPP bawaan. `APP_TIMEZONE` penting supaya jam yang sudah lewat terdeteksi dengan benar.

## 5. Buat tabel dan isi data contoh

    php artisan migrate --seed
    php artisan serve

Buka http://127.0.0.1:8000

Akun contoh:

| Peran | Email | Password |
|---|---|---|
| Admin | admin@ledspeed.test | admin12345 |
| Pelanggan | budi@example.test | password123 |

Ganti password admin sebelum aplikasi dipasang online.

## Opsi B: import SQL lewat phpMyAdmin

Kalau tidak ingin memakai migration, import `database/bengkel_ledspeed.sql` di phpMyAdmin.
File itu sudah membuat database, tabel, dan data contoh. Di `.env` tambahkan
`SESSION_DRIVER=file` dan `CACHE_STORE=file`. **Pilih salah satu**: migration atau SQL, jangan keduanya.

## Bagaimana kode tersambung ke database

    Browser -> routes/web.php -> Controller -> Model (Eloquent) -> tabel MySQL
                                     |
                                     +-> view Blade (resources/views) -> HTML

| Bagian | File | Tabel |
|---|---|---|
| Daftar / masuk | `AuthController` | `users` |
| Form reservasi, slot jam, riwayat | `ReservationController` | `reservations`, `vehicles`, `services` |
| Kelola status reservasi | `Admin/ReservationController` | `reservations` |
| Kelola layanan | `Admin/ServiceController` | `services` |

Relasi: `User` punya banyak `Vehicle` dan `Reservation`; `Reservation` milik satu `User`, `Vehicle`, dan `Service`.
Kuota 2 kendaraan per jam dicek di `ReservationController@store` dalam transaksi database.
Harga layanan disalin ke `harga_saat_booking` sehingga perubahan harga tidak mengubah reservasi lama.

## Mencoba kode di tinker

    php artisan tinker
    >>> App\Models\Reservation::with('service','user')->latest()->first()
    >>> App\Models\Service::where('is_active', true)->count()

## Masalah yang sering muncul

- `Unknown database 'bengkel_ledspeed'`: database belum dibuat (langkah 2) atau nama di `.env` salah.
- `Access denied for user`: `DB_USERNAME` / `DB_PASSWORD` tidak cocok dengan MySQL Anda.
- `Connection refused`: MySQL belum menyala. Nyalakan dari Laragon/XAMPP.
- `419 Page Expired`: jalankan `php artisan migrate` (tabel `sessions` dibutuhkan) lalu bersihkan cookie browser.
- Tampilan tanpa gaya: pastikan `public/css/style.css` ikut tersalin dan buka lewat `php artisan serve`.
- Setelah mengubah `.env`: jalankan `php artisan config:clear`.
