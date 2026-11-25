# SIMPRES — Sistem Informasi Manajemen Prestasi Mahasiswa

**SIMPRES** adalah aplikasi web berbasis Laravel untuk pengelolaan, pengajuan, verifikasi, dan pelaporan prestasi mahasiswa di lingkungan **Fakultas Sains dan Teknologi (FST) Universitas Islam Negeri (UIN) Imam Bonjol Padang**.

---

## Teknologi yang Digunakan

- **Backend:** PHP `^8.3`, Laravel Framework `^13.17`
- **Frontend:** Blade Template, Bootstrap 5.3, Bootstrap Icons, Chart.js, Vite
- **Database:** MySQL / SQLite (konfigurasi via `.env`)
- **Testing & Quality:** PHPUnit `^12.5`, Laravel Pint `^1.27`

---

## Program Studi Terdaftar (FST)

Sistem menggunakan daftar Program Studi resmi FST yang terstandardisasi di `User::PROGRAM_STUDI`:
1. **Sistem Informasi**
2. **Ilmu Aktuaria**
3. **Matematika**

---

## Fitur Sistem Berdasarkan Peran (Role)

### 1. Halaman Publik (Landing Page)
- Menampilkan informasi profil SIMPRES, statistik prestasi terverifikasi secara langsung (*live stats*), daftar prestasi terbaru yang telah diverifikasi, panduan peran pengguna, dan informasi kontak fakultas.
- Tautan navigasi langsung menuju halaman **Login**.

### 2. Mahasiswa
- **Dashboard Mahasiswa:** Ringkasan jumlah prestasi (Total, Terverifikasi, Menunggu Verifikasi, Perlu Perbaikan) dan daftar riwayat prestasi terbaru.
- **Pengajuan Prestasi:**
  - Formulir pengajuan prestasi (nama prestasi, jenis/kategori, tingkat, perolehan, penyelenggara, tempat, tahun, deskripsi).
  - Unggah berkas **Sertifikat** (PDF/JPG/PNG).
  - Unggah berkas **Dokumentasi** pendukung kegiatan.
- **Riwayat Prestasi:** Daftar seluruh prestasi yang pernah diajukan dengan filter berdasarkan status verifikasi, tampilan detail berkas, serta edit pengajuan apabila berstatus *Menunggu* atau *Perlu Perbaikan*.
- **Notifikasi:** Informasi pembaruan status verifikasi dan catatan revisi dari admin.
- **Profil:** Pembaruan biodata mahasiswa (NIM, program studi terstandar, angkatan, kontak) dan penggantian kata sandi.

### 3. Admin Fakultas
- **Dashboard Admin:** Ringkasan statistik keseluruhan, grafik distribusi kategori & status verifikasi, serta daftar pengajuan terbaru yang menunggu verifikasi.
- **Data Prestasi & Verifikasi:**
  - Daftar pengajuan prestasi lengkap dengan filter (Program Studi, Status, Kategori, Tingkat, Perolehan, Tahun, Skor Minimum).
  - Pemeriksaan berkas sertifikat dan dokumentasi secara privat.
  - Proses verifikasi (*Terverifikasi* dengan pemberian skor, *Perlu Perbaikan* dengan catatan revisi, atau *Ditolak*) yang otomatis mengirim notifikasi kepada mahasiswa.
- **Riwayat Data:** Halaman riwayat pengajuan prestasi yang tersinkronisasi dengan Data Prestasi (dilengkapi filter Program Studi, Kategori, Tingkat, Tahun, Status, serta fitur **Unduh Excel / CSV**).
- **Data Mahasiswa:** Daftar seluruh mahasiswa beserta jumlah prestasi & status terverifikasi, filter Program Studi, dan tampilan detail riwayat prestasi masing-masing mahasiswa.
- **Rekapitulasi:** Visualisasi grafik kategori & tren tahunan, beserta ekspor laporan CSV.
- **Profil:** Pengelolaan data akun admin dan kata sandi.

### 4. Pemimpin Fakultas (Pimpinan)
- **Dashboard Pimpinan:** Ringkasan analitik capaian prestasi mahasiswa FST.
- **Rekapitulasi Prestasi:** Grafik persentase kategori prestasi, grafik tren tahunan, tabel rekapitulasi terfilter (Kategori, Tingkat, Tahun, Program Studi), dan ekspor laporan CSV.
- **Data Prestasi:** Tampilan daftar dan detail prestasi mahasiswa *(read-only)*.
- **Profil:** Pengelolaan data akun pimpinan dan kata sandi.

---

## Struktur Model & Database

| Model | Tabel | Keterangan |
| :--- | :--- | :--- |
| `User` | `users` | Akun pengguna (`mahasiswa`, `admin`, `pimpinan`) dan biodata |
| `Admin` | `admins` | Profil tambahan akun admin |
| `Pimpinan` | `pimpinan` | Profil dan jabatan pemimpin fakultas |
| `Prestasi` | `prestasi` | Data prestasi mahasiswa, status verifikasi, skor, dan catatan revisi |
| `Sertifikat` | `sertifikats` | Metadata dan path berkas sertifikat |
| `Dokumentasi` | `dokumentasis` | Metadata dan path berkas dokumentasi pendukung |
| `Notifikasi` | `notifikasi` | Riwayat notifikasi verifikasi untuk pengguna |

---

## Instalasi & Menjalankan Proyek

1. **Clone & masuk ke direktori proyek:**
   ```bash
   git clone <url-repository>
   cd simpres
   ```

2. **Instal dependensi PHP & JavaScript:**
   ```bash
   composer install
   npm install
   ```

3. **Salin file konfigurasi `.env` dan generate application key:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi database pada `.env`**, lalu jalankan migrasi & seeder:
   ```bash
   php artisan migrate --seed
   ```

5. **Build aset frontend & jalankan server lokal:**
   ```bash
   npm run build
   php artisan serve
   ```
   Akses aplikasi melalui `http://localhost:8000`.

---

## Akun Bawaan (Database Seeder)

Berikut adalah akun demo yang dibuat oleh `Database\Seeders\UserSeeder`:

| Peran | Nama | Username | Email / NIM | Kata Sandi |
| :--- | :--- | :--- | :--- | :--- |
| **Mahasiswa** | Alya putri | `mahasiswa01` | `Alya01@gmail.com` / `2417020001` | `Mhs12345` |
| **Admin** | Aura Maylia | `admin01` | `aura12@gmail.com` | `Admin12345` |
| **Pemimpin** | Selvia Purnama Sari | `pimpinan01` | `selvia@uinib.ac.id` | `Pimpinan12345` |

> *Login mendukung input **Username**, **Email**, atau **NIM** beserta pemilihan peran yang sesuai pada halaman `/login`.*

---

## Pengujian & Pemformatan Kode

- **Menjalankan seluruh test suite (PHPUnit):**
  ```bash
  php artisan test
  ```

- **Memeriksa/merapikan gaya penulisan kode (Laravel Pint):**
  ```bash
  vendor/bin/pint
  ```
