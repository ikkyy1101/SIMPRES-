<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Notifikasi;
use App\Models\Pimpinan;
use App\Models\Prestasi;
use App\Models\Sertifikat;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin (Aura Maylia - Lap. hal. 116-118)
        $admin = User::updateOrCreate(
            ['username' => 'admin01'],
            [
                'nama' => 'Aura Maylia',
                'nim' => null,
                'email' => 'aura12@gmail.com',
                'password' => 'Admin12345',
                'role' => 'admin',
                'no_hp' => '0812678898506',
                'fakultas' => 'Fakultas Sains dan Teknologi',
                'gender' => 'Perempuan',
            ]
        );

        $adminData = Admin::updateOrCreate(
            ['id_user' => $admin->id],
            [
                'nama' => $admin->nama,
                'username' => $admin->username,
                'email' => $admin->email,
                'no_hp' => $admin->no_hp,
            ]
        );

        // 2. Akun Pimpinan (Selvia Purnama Sari - Lap. hal. 128-132)
        $pimpinan = User::updateOrCreate(
            ['username' => 'pimpinan01'],
            [
                'nama' => 'Selvia Purnama Sari',
                'nim' => null,
                'email' => 'selvia@uinib.ac.id',
                'password' => 'Pimpinan12345',
                'role' => 'pimpinan',
                'no_hp' => '081234567890',
                'fakultas' => 'Fakultas Sains dan Teknologi',
                'gender' => 'Perempuan',
            ]
        );

        Pimpinan::updateOrCreate(
            ['id_user' => $pimpinan->id],
            [
                'nama' => $pimpinan->nama,
                'username' => $pimpinan->username,
                'email' => $pimpinan->email,
                'no_hp' => $pimpinan->no_hp,
                'jabatan' => 'Dekan Fakultas Sains dan Teknologi',
                'gender' => 'Perempuan',
            ]
        );

        // 3. Akun Mahasiswa Utama (Alya putri - Lap. hal. 104-106)
        $mhsAlya = User::updateOrCreate(
            ['username' => 'mahasiswa01'],
            [
                'nama' => 'Alya putri',
                'nim' => '2417020001',
                'email' => 'Alya01@gmail.com',
                'password' => 'Mhs12345',
                'role' => 'mahasiswa',
                'prodi' => 'Sistem Informasi',
                'fakultas' => 'Sains Dan Teknologi',
                'angkatan' => 2024,
                'gender' => 'Perempuan',
                'no_hp' => '082170001122',
            ]
        );

        // 4. Mahasiswa lainnya (dari tim & prodi saintek)
        $daftarMhs = [
            ['nama' => 'Dzikri Pratama Putra Ameri', 'nim' => '2317020149', 'username' => 'dzikri', 'prodi' => 'Sistem Informasi', 'angkatan' => 2023, 'gender' => 'Laki-laki', 'email' => 'dzikri@simpres.test'],
            ['nama' => 'Najwa Khairany Depi', 'nim' => '2417020020', 'username' => 'najwa', 'prodi' => 'Sistem Informasi', 'angkatan' => 2024, 'gender' => 'Perempuan', 'email' => 'najwa@simpres.test'],
            ['nama' => 'Muhamad Rizky', 'nim' => '2417020002', 'username' => 'rizky', 'prodi' => 'Sistem Informasi', 'angkatan' => 2024, 'gender' => 'Laki-laki', 'email' => 'rizky@simpres.test'],
            ['nama' => 'Ranny Fadila', 'nim' => '2417020019', 'username' => 'ranny', 'prodi' => 'Sistem Informasi', 'angkatan' => 2024, 'gender' => 'Perempuan', 'email' => 'ranny@simpres.test'],
            ['nama' => 'Nur Janna', 'nim' => '2417020015', 'username' => 'nurjanna', 'prodi' => 'Sistem Informasi', 'angkatan' => 2024, 'gender' => 'Perempuan', 'email' => 'nurjanna@simpres.test'],
            ['nama' => 'Budi Santoso', 'nim' => '2317020088', 'username' => 'budi', 'prodi' => 'Matematika', 'angkatan' => 2023, 'gender' => 'Laki-laki', 'email' => 'budi@simpres.test'],
            ['nama' => 'Siti Nurhaliza', 'nim' => '2417020099', 'username' => 'siti', 'prodi' => 'Ilmu Aktuaria', 'angkatan' => 2024, 'gender' => 'Perempuan', 'email' => 'siti@simpres.test'],
            ['nama' => 'Ahmad Fauzi', 'nim' => '2317020077', 'username' => 'fauzi', 'prodi' => 'Sistem Informasi', 'angkatan' => 2023, 'gender' => 'Laki-laki', 'email' => 'fauzi@simpres.test'],
        ];

        $mhsObjects = [$mhsAlya];
        foreach ($daftarMhs as $m) {
            $userMhs = User::updateOrCreate(
                ['username' => $m['username']],
                [
                    'nama' => $m['nama'],
                    'nim' => $m['nim'],
                    'email' => $m['email'],
                    'password' => 'Mhs12345',
                    'role' => 'mahasiswa',
                    'prodi' => $m['prodi'],
                    'fakultas' => 'Fakultas Sains dan Teknologi',
                    'angkatan' => $m['angkatan'],
                    'gender' => $m['gender'],
                ]
            );
            $mhsObjects[] = $userMhs;
        }

        // 5. Data Prestasi Contoh (Persis seperti di UI Laporan)
        // Prestasi Alya Putri:
        $p1 = Prestasi::updateOrCreate(
            ['nama_prestasi' => 'Lomba Desain UI/UX Mahasiswa Nasional', 'id_mahasiswa' => $mhsAlya->id],
            [
                'jenis_prestasi' => 'akademik',
                'tingkat_prestasi' => 'nasional',
                'perolehan' => 'juara_1',
                'tahun_perolehan' => 2024,
                'tahun_penyelenggaraan' => 2024,
                'penyelenggara' => 'Universitas Islam Negeri Imam Bonjol Padang',
                'tempat_pelaksanaan' => 'Padang',
                'deskripsi' => 'Kompetisi perancangan UI/UX tingkat mahasiswa nasional se-Indonesia.',
                'status_verifikasi' => 'terverifikasi',
                'skor' => 95.00,
                'id_admin' => $adminData->id,
                'diverifikasi_pada' => now()->subDays(5),
            ]
        );

        Sertifikat::updateOrCreate(
            ['id_prestasi' => $p1->id],
            [
                'file_sertifikat' => 'sertifikat_dummy.pdf',
                'nama_file' => 'Sertifikat_Juara1_UIUX_Alya.pdf',
                'tgl_upload' => '2024-10-10',
            ]
        );

        $p2 = Prestasi::updateOrCreate(
            ['nama_prestasi' => 'Lomba Karya Tulis Ilmiah Sains Terapan', 'id_mahasiswa' => $mhsAlya->id],
            [
                'jenis_prestasi' => 'akademik',
                'tingkat_prestasi' => 'nasional',
                'perolehan' => 'juara_2',
                'tahun_perolehan' => 2024,
                'tahun_penyelenggaraan' => 2024,
                'penyelenggara' => 'Universitas Andalas',
                'tempat_pelaksanaan' => 'Padang',
                'deskripsi' => 'Karya tulis penerapan Internet of Things di bidang pertanian.',
                'status_verifikasi' => 'terverifikasi',
                'skor' => 88.00,
                'id_admin' => $adminData->id,
                'diverifikasi_pada' => now()->subDays(10),
            ]
        );

        $p3 = Prestasi::updateOrCreate(
            ['nama_prestasi' => 'Hackathon Inovasi Sistem Cerdas', 'id_mahasiswa' => $mhsAlya->id],
            [
                'jenis_prestasi' => 'teknologi_inovasi',
                'tingkat_prestasi' => 'nasional',
                'perolehan' => 'juara_3',
                'tahun_perolehan' => 2024,
                'tahun_penyelenggaraan' => 2024,
                'penyelenggara' => 'Kementerian Komunikasi dan Informatika',
                'tempat_pelaksanaan' => 'Jakarta',
                'deskripsi' => 'Membangun purwarupa aplikasi pemantau bencana berbasis AI.',
                'status_verifikasi' => 'menunggu',
                'skor' => null,
            ]
        );

        $p4 = Prestasi::updateOrCreate(
            ['nama_prestasi' => 'Festival Tari Kreasi Tradisional Minang', 'id_mahasiswa' => $mhsAlya->id],
            [
                'jenis_prestasi' => 'seni_budaya',
                'tingkat_prestasi' => 'daerah',
                'perolehan' => 'harapan',
                'tahun_perolehan' => 2023,
                'tahun_penyelenggaraan' => 2023,
                'penyelenggara' => 'Dinas Kebudayaan Sumatera Barat',
                'tempat_pelaksanaan' => 'Padang',
                'deskripsi' => 'Penampilan grup tari tradisional kontemporer.',
                'status_verifikasi' => 'perlu_perbaikan',
                'catatan_revisi' => 'Mohon unggah file sertifikat yang lebih jelas/tidak buram dan cantumkan surat tugas pengiriman delegasi.',
                'id_admin' => $adminData->id,
            ]
        );

        // Notifikasi untuk Alya
        Notifikasi::updateOrCreate(
            ['id_user' => $mhsAlya->id, 'id_prestasi' => $p1->id],
            [
                'judul' => 'Prestasi Berhasil Diverifikasi',
                'pesan' => 'Pengajuan prestasi "Lomba Desain UI/UX Mahasiswa Nasional" telah diverifikasi dengan skor 95.00.',
                'dibaca_pada' => now(),
            ]
        );

        Notifikasi::updateOrCreate(
            ['id_user' => $mhsAlya->id, 'id_prestasi' => $p4->id],
            [
                'judul' => 'Perbaikan Dokumen Diperlukan',
                'pesan' => 'Catatan Admin: Mohon unggah file sertifikat yang lebih jelas/tidak buram dan cantumkan surat tugas delegasi.',
                'dibaca_pada' => null,
            ]
        );

        Notifikasi::updateOrCreate(
            ['id_user' => $mhsAlya->id, 'id_prestasi' => $p3->id],
            [
                'judul' => 'Pengajuan Berhasil Dikirim',
                'pesan' => 'Pengajuan "Hackathon Inovasi Sistem Cerdas" berhasil disimpan dan sedang menunggu verifikasi.',
                'dibaca_pada' => null,
            ]
        );

        // Prestasi tambahan untuk statistik grafik (tahun 2020 s.d. 2025 dan lintas kategori)
        $dataTambahan = [
            ['nama' => 'Turnamen Futsal Pomda Sumbar', 'jenis' => 'olahraga', 'tingkat' => 'daerah', 'perolehan' => 'juara_1', 'tahun' => 2024, 'skor' => 85, 'status' => 'terverifikasi', 'peny' => 'BAPOMI'],
            ['nama' => 'Olimpiade Sains Matematika Nasional', 'jenis' => 'akademik', 'tingkat' => 'nasional', 'perolehan' => 'juara_2', 'tahun' => 2023, 'skor' => 90, 'status' => 'terverifikasi', 'peny' => 'Puspresnas'],
            ['nama' => 'Lomba Fotografi Alam Terbuka', 'jenis' => 'seni_budaya', 'tingkat' => 'lokal', 'perolehan' => 'juara_3', 'tahun' => 2023, 'skor' => 78, 'status' => 'terverifikasi', 'peny' => 'UKM Seni'],
            ['nama' => 'International Student IoT Expo', 'jenis' => 'teknologi_inovasi', 'tingkat' => 'internasional', 'perolehan' => 'finalis', 'tahun' => 2024, 'skor' => 92, 'status' => 'terverifikasi', 'peny' => 'IEEE Student Branch'],
            ['nama' => 'Kejuaraan Pencak Silat Daerah', 'jenis' => 'olahraga', 'tingkat' => 'daerah', 'perolehan' => 'juara_1', 'tahun' => 2022, 'skor' => 84, 'status' => 'terverifikasi', 'peny' => 'IPSI Sumbar'],
            ['nama' => 'Kompetisi Debat Bahasa Arab Nasional', 'jenis' => 'non-akademik', 'tingkat' => 'nasional', 'perolehan' => 'juara_1', 'tahun' => 2023, 'skor' => 89, 'status' => 'terverifikasi', 'peny' => 'UIN Maulana Malik Ibrahim'],
            ['nama' => 'Lomba Startup Mahasiswa Digital', 'jenis' => 'teknologi_inovasi', 'tingkat' => 'nasional', 'perolehan' => 'juara_2', 'tahun' => 2022, 'skor' => 87, 'status' => 'terverifikasi', 'peny' => 'Kemenkop UKM'],
            ['nama' => 'National English Speech Contest', 'jenis' => 'non-akademik', 'tingkat' => 'nasional', 'perolehan' => 'partisipasi', 'tahun' => 2021, 'skor' => 70, 'status' => 'terverifikasi', 'peny' => 'Universitas Indonesia'],
            ['nama' => 'Pekan Ilmiah Fisika Terapan', 'jenis' => 'akademik', 'tingkat' => 'nasional', 'perolehan' => 'juara_3', 'tahun' => 2021, 'skor' => 82, 'status' => 'terverifikasi', 'peny' => 'UNP'],
            ['nama' => 'Lomba Paduan Suara Mahasiswa', 'jenis' => 'seni_budaya', 'tingkat' => 'lokal', 'perolehan' => 'harapan', 'tahun' => 2020, 'skor' => 75, 'status' => 'terverifikasi', 'peny' => 'UIN Imam Bonjol'],
            ['nama' => 'Turnamen Badminton FST Cup', 'jenis' => 'olahraga', 'tingkat' => 'lokal', 'perolehan' => 'juara_2', 'tahun' => 2020, 'skor' => 76, 'status' => 'terverifikasi', 'peny' => 'DEMA FST'],
            ['nama' => 'Paper Presentation Bio-Conservation', 'jenis' => 'akademik', 'tingkat' => 'internasional', 'perolehan' => 'partisipasi', 'tahun' => 2025, 'skor' => 85, 'status' => 'terverifikasi', 'peny' => 'ASEAN Bio Society'],
            ['nama' => 'Lomba Esai Lingkungan Hidup', 'jenis' => 'lainnya', 'tingkat' => 'daerah', 'perolehan' => 'juara_3', 'tahun' => 2025, 'skor' => 80, 'status' => 'terverifikasi', 'peny' => 'DLH Sumbar'],
        ];

        foreach ($dataTambahan as $idx => $dt) {
            $pemilik = $mhsObjects[$idx % count($mhsObjects)];
            Prestasi::updateOrCreate(
                ['nama_prestasi' => $dt['nama']],
                [
                    'id_mahasiswa' => $pemilik->id,
                    'jenis_prestasi' => $dt['jenis'],
                    'tingkat_prestasi' => $dt['tingkat'],
                    'perolehan' => $dt['perolehan'],
                    'tahun_perolehan' => $dt['tahun'],
                    'tahun_penyelenggaraan' => $dt['tahun'],
                    'penyelenggara' => $dt['peny'],
                    'tempat_pelaksanaan' => 'Kampus / Daring',
                    'deskripsi' => 'Partisipasi dan perolehan penghargaan pada event.',
                    'status_verifikasi' => $dt['status'],
                    'skor' => $dt['skor'],
                    'id_admin' => $adminData->id,
                    'diverifikasi_pada' => now()->subMonths(rand(1, 24)),
                ]
            );
        }
    }
}
