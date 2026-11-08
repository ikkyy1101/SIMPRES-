# SIMPRESMA - Sistem Informasi Manajemen Prestasi Mahasiswa

Fakultas Sains dan Teknologi, UIN Imam Bonjol Padang (Laravel 13, MySQL, Bootstrap 5).
Rancangan mengacu pada *Laporan Pengembangan Proyek Sistem Informasi* Kelompok 4.

## Menjalankan (XAMPP / lokal)

```bash
composer install
cp .env.example .env          # lalu atur DB_* (database: simpres)
php artisan key:generate
php artisan migrate --seed    # membuat tabel + akun contoh
php artisan serve
```

Tampilan memakai Bootstrap dari CDN, jadi **tidak perlu `npm run build`**
(butuh koneksi internet saat membuka halaman).

Akun contoh (dari `UserSeeder`) - ganti passwordnya lewat menu Profil:

| Role      | Username      | Password    |
|-----------|---------------|-------------|
| Admin     | `admin01`     | `Admin12345`|
| Mahasiswa | `mahasiswa01` | `Mhs12345`  |

Akun mahasiswa lain dibuat Admin lewat menu **Mahasiswa > Tambah Mahasiswa**.

## Pengujian

```bash
php artisan test
```

## Catatan penyimpanan berkas

Sertifikat dan dokumentasi disimpan di disk privat (`storage/app/private`) dan hanya
dapat dibuka lewat route `berkas.*` oleh pemilik prestasi atau Admin. Tidak perlu
`php artisan storage:link`.

# SIMPRES-