<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Prestasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Menguji alur utama sesuai Laporan Pengembangan Proyek (F-01 s.d. F-42)
 * sekaligus menjadi smoke test untuk seluruh halaman Blade.
 */
class SimpresmaTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $u = User::factory()->create([
            'role' => 'admin',
            'nim' => null,
            'prodi' => null,
            'angkatan' => null,
        ]);

        Admin::create([
            'id_user' => $u->id,
            'nama' => $u->nama,
            'username' => $u->username,
            'email' => $u->email,
        ]);

        return $u;
    }

    private function mahasiswa(array $o = []): User
    {
        return User::factory()->create($o);
    }

    private function prestasi(User $m, array $o = []): Prestasi
    {
        return Prestasi::create(array_merge([
            'id_mahasiswa' => $m->id,
            'nama_prestasi' => 'Lomba Web Nasional',
            'jenis_prestasi' => 'non-akademik',
            'tingkat_prestasi' => 'nasional',
            'perolehan' => 'juara_1',
            'tahun_perolehan' => 2026,
            'penyelenggara' => 'Kemdikbud',
            'status_verifikasi' => 'menunggu',
        ], $o));
    }

    private function dataForm(array $o = []): array
    {
        return array_merge([
            'nama_prestasi' => 'Hackathon Nasional',
            'jenis_prestasi' => 'non-akademik',
            'tingkat_prestasi' => 'nasional',
            'perolehan' => 'juara_2',
            'tahun_perolehan' => 2026,
            'penyelenggara' => 'Kampus Merdeka',
            'tempat_pelaksanaan' => 'Jakarta',
            'deskripsi' => 'Diikuti 200 tim',
            'sertifikat' => UploadedFile::fake()->create('sertifikat.pdf', 100, 'application/pdf'),
        ], $o);
    }

    /* ---------- Autentikasi & hak akses ---------- */

    public function test_login_redirects_by_role_and_rejects_wrong_role(): void
    {
        $m = $this->mahasiswa();

        $this->post('/login', [
            'login' => $m->username, 'password' => 'password', 'role' => 'admin',
        ])->assertSessionHasErrors('login');

        $this->post('/login', [
            'login' => $m->nim, 'password' => 'password', 'role' => 'mahasiswa',
        ])->assertRedirect(route('mahasiswa.dashboard'));
    }

    public function test_dashboard_route_redirects_by_authenticated_role(): void
    {
        $m = $this->mahasiswa();
        $this->actingAs($m)->get(route('dashboard'))
            ->assertRedirect(route('mahasiswa.dashboard'));

        $admin = $this->admin();
        $this->actingAs($admin)->get(route('dashboard'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_quick_login_authenticates_and_redirects_by_role(): void
    {
        $mhs = $this->mahasiswa();
        $this->post(route('login.quick', ['role' => 'mahasiswa']))
            ->assertRedirect(route('mahasiswa.dashboard'));
        $this->assertAuthenticatedAs($mhs);

        $this->post('/logout');

        $admin = $this->admin();
        $this->post(route('login.quick', ['role' => 'admin']))
            ->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_mahasiswa_cannot_open_admin_area_and_vice_versa(): void
    {
        $this->actingAs($this->mahasiswa())
            ->get(route('admin.dashboard'))->assertForbidden();

        $this->actingAs($this->admin())
            ->get(route('mahasiswa.prestasi.index'))->assertForbidden();
    }

    /* ---------- Mahasiswa: input prestasi ---------- */

    public function test_mahasiswa_submits_prestasi_with_certificate_and_optional_docs(): void
    {
        Storage::fake('local');
        $m = $this->mahasiswa();

        // tanpa dokumentasi (opsional)
        $this->actingAs($m)
            ->post(route('mahasiswa.prestasi.store'), $this->dataForm())
            ->assertSessionHasNoErrors();

        $p = Prestasi::firstOrFail();
        $this->assertSame('menunggu', $p->status_verifikasi);
        $this->assertSame('juara_2', $p->perolehan);
        $this->assertCount(1, $p->sertifikat);
        Storage::disk('local')->assertExists($p->sertifikat->first()->file_sertifikat);

        // dengan dokumentasi + keterangan
        $this->actingAs($m)->post(route('mahasiswa.prestasi.store'), $this->dataForm([
            'dokumentasi' => [0 => UploadedFile::fake()->create('foto.jpg', 50, 'image/jpeg')],
            'keterangan_dokumentasi' => [0 => 'Foto bersama sertifikat'],
        ]))->assertSessionHasNoErrors();

        $this->assertDatabaseHas('dokumentasi', ['keterangan' => 'Foto bersama sertifikat']);
    }

    public function test_mahasiswa_can_submit_prestasi_via_separate_pages_flow(): void
    {
        Storage::fake('local');
        $m = $this->mahasiswa();

        // 1. Halaman 1: Simpan data prestasi
        $this->actingAs($m)->post(route('mahasiswa.prestasi.post-step1'), [
            'nama_prestasi' => 'Olimpiade Sains Mahasiswa',
            'jenis_prestasi' => 'akademik',
            'tingkat_prestasi' => 'nasional',
            'tahun_perolehan' => 2026,
            'tahun_penyelenggaraan' => 2026,
            'penyelenggara' => 'Kementerian Riset dan Teknologi',
            'tempat_pelaksanaan' => 'Yogyakarta',
            'deskripsi' => 'Kompetisi sains bidang teknologi',
        ])->assertRedirect(route('mahasiswa.upload.sertifikat'))
            ->assertSessionHas('prestasi_draft');

        // 2. Halaman 2: Upload sertifikat dan pilih peringkat
        $this->actingAs($m)->post(route('mahasiswa.upload.sertifikat.post'), [
            'perolehan' => 'juara_1',
            'sertifikat' => UploadedFile::fake()->create('sertifikat-olimpiade.pdf', 120, 'application/pdf'),
        ])->assertRedirect(route('mahasiswa.upload.dokumentasi'));

        // 3. Halaman 3: Upload dokumentasi dan finalisasi
        $this->actingAs($m)->post(route('mahasiswa.upload.dokumentasi.post'), [
            'dokumentasi' => [0 => UploadedFile::fake()->create('foto-juara.jpg', 60, 'image/jpeg')],
            'keterangan_dokumentasi' => [0 => 'Penerimaan medali emas'],
        ])->assertRedirect();

        $p = Prestasi::where('nama_prestasi', 'Olimpiade Sains Mahasiswa')->firstOrFail();
        $this->assertSame('juara_1', $p->perolehan);
        $this->assertSame('akademik', $p->jenis_prestasi);
        $this->assertCount(1, $p->sertifikat);
        $this->assertCount(1, $p->dokumentasi);
        $this->assertNull(session('prestasi_draft'));
    }

    public function test_certificate_is_required_and_perolehan_is_validated(): void
    {
        $this->actingAs($this->mahasiswa())
            ->post(route('mahasiswa.prestasi.store'), $this->dataForm([
                'sertifikat' => null,
                'perolehan' => 'bukan-pilihan',
            ]))
            ->assertSessionHasErrors(['sertifikat', 'perolehan']);
    }

    public function test_mahasiswa_cannot_view_other_students_prestasi_or_files(): void
    {
        Storage::fake('local');
        $pemilik = $this->mahasiswa();
        $lain = $this->mahasiswa();

        $this->actingAs($pemilik)
            ->post(route('mahasiswa.prestasi.store'), $this->dataForm());
        $p = Prestasi::firstOrFail();
        $sertifikat = $p->sertifikat->first();

        $this->actingAs($lain)->get(route('mahasiswa.prestasi.show', $p))->assertForbidden();
        $this->actingAs($lain)->get(route('berkas.sertifikat', $sertifikat))->assertForbidden();

        $this->actingAs($pemilik)->get(route('berkas.sertifikat', $sertifikat))->assertOk();
        $this->actingAs($this->admin())->get(route('berkas.sertifikat', $sertifikat))->assertOk();
    }

    /* ---------- Admin: verifikasi, skor, catatan, notifikasi ---------- */

    public function test_admin_verifies_with_score_and_student_gets_notification(): void
    {
        $m = $this->mahasiswa();
        $p = $this->prestasi($m);
        $admin = $this->admin();

        // skor wajib saat terverifikasi
        $this->actingAs($admin)
            ->patch(route('admin.verifikasi.proses', $p), ['status_verifikasi' => 'terverifikasi'])
            ->assertSessionHasErrors('skor');

        $this->actingAs($admin)
            ->patch(route('admin.verifikasi.proses', $p), [
                'status_verifikasi' => 'terverifikasi', 'skor' => 85,
            ])->assertRedirect();

        $p->refresh();
        $this->assertSame('terverifikasi', $p->status_verifikasi);
        $this->assertSame('85.00', $p->skor);
        $this->assertNotNull($p->id_admin);
        $this->assertDatabaseHas('notifikasi', [
            'id_user' => $m->id, 'id_prestasi' => $p->id, 'dibaca_pada' => null,
        ]);

        // sudah diproses -> tidak bisa diverifikasi ulang
        $this->actingAs($admin)
            ->patch(route('admin.verifikasi.proses', $p), [
                'status_verifikasi' => 'ditolak', 'catatan_revisi' => 'x',
            ])->assertForbidden();

        // membuka detail menandai notifikasi sudah dibaca
        $this->actingAs($m)->get(route('mahasiswa.prestasi.show', $p))->assertOk();
        $this->assertDatabaseMissing('notifikasi', ['id_user' => $m->id, 'dibaca_pada' => null]);
    }

    public function test_perlu_perbaikan_requires_note_and_student_can_resubmit(): void
    {
        Storage::fake('local');
        $m = $this->mahasiswa();
        $p = $this->prestasi($m);
        $admin = $this->admin();

        $this->actingAs($admin)
            ->patch(route('admin.verifikasi.proses', $p), ['status_verifikasi' => 'perlu_perbaikan'])
            ->assertSessionHasErrors('catatan_revisi');

        $this->actingAs($admin)
            ->patch(route('admin.verifikasi.proses', $p), [
                'status_verifikasi' => 'perlu_perbaikan', 'catatan_revisi' => 'Sertifikat buram',
            ]);

        $this->actingAs($m)->get(route('mahasiswa.prestasi.edit', $p))->assertOk();

        $this->actingAs($m)
            ->put(route('mahasiswa.prestasi.update', $p), $this->dataForm([
                'nama_prestasi' => 'Lomba Web Nasional (revisi)',
            ]))->assertSessionHasNoErrors();

        $p->refresh();
        $this->assertSame('menunggu', $p->status_verifikasi);
        $this->assertSame('Lomba Web Nasional (revisi)', $p->nama_prestasi);

        // status menunggu -> tidak boleh diedit lagi
        $this->actingAs($m)->get(route('mahasiswa.prestasi.edit', $p))->assertForbidden();
    }

    /* ---------- Admin: cari, filter, rekap ---------- */

    public function test_admin_search_and_filters(): void
    {
        $a = $this->mahasiswa(['nama' => 'Budi Santoso', 'nim' => '2417020099']);
        $b = $this->mahasiswa(['nama' => 'Citra Dewi', 'nim' => '2417020098']);

        $this->prestasi($a, [
            'nama_prestasi' => 'Juara Coding', 'perolehan' => 'juara_1',
            'status_verifikasi' => 'terverifikasi', 'skor' => 90,
        ]);
        $this->prestasi($b, [
            'nama_prestasi' => 'Peserta Debat', 'perolehan' => 'partisipasi',
            'tingkat_prestasi' => 'lokal', 'status_verifikasi' => 'terverifikasi', 'skor' => 60,
        ]);

        $admin = $this->admin();

        $cari = fn (array $q) => $this->actingAs($admin)->get(route('admin.verifikasi.index', $q));

        $cari(['q' => 'Budi'])->assertSee('Juara Coding')->assertDontSee('Peserta Debat');
        $cari(['q' => '2417020098'])->assertSee('Peserta Debat')->assertDontSee('Juara Coding');
        $cari(['perolehan' => 'partisipasi'])->assertSee('Peserta Debat')->assertDontSee('Juara Coding');
        $cari(['tingkat_prestasi' => 'nasional'])->assertSee('Juara Coding')->assertDontSee('Peserta Debat');
        $cari(['skor_min' => 80])->assertSee('Juara Coding')->assertDontSee('Peserta Debat');
        $cari(['q' => 'tidak-ada'])->assertSee('Data tidak ditemukan');
    }

    public function test_rekapitulasi_groups_by_selected_criteria(): void
    {
        $m = $this->mahasiswa();
        $this->prestasi($m, ['status_verifikasi' => 'terverifikasi', 'skor' => 95]);
        $this->prestasi($m, ['status_verifikasi' => 'terverifikasi', 'skor' => 75, 'tingkat_prestasi' => 'lokal']);
        $this->prestasi($m, ['status_verifikasi' => 'menunggu']);

        $admin = $this->admin();

        foreach (['kategori', 'tingkat', 'tahun', 'prodi', 'perolehan', 'skor'] as $k) {
            $this->actingAs($admin)
                ->get(route('admin.rekapitulasi', ['kelompok' => $k]))
                ->assertOk();
        }

        // bawaan: hanya data terverifikasi (2 dari 3)
        $this->actingAs($admin)
            ->get(route('admin.rekapitulasi', ['kelompok' => 'tingkat']))
            ->assertSee('Nasional')->assertSee('Lokal');

        $this->actingAs($admin)
            ->get(route('admin.rekapitulasi', ['kelompok' => 'tingkat', 'tahun_perolehan' => 1999]))
            ->assertSee('Data tidak tersedia');
    }

    /* ---------- Smoke test halaman ---------- */

    public function test_all_pages_render(): void
    {
        Storage::fake('local');
        $m = $this->mahasiswa();
        $this->actingAs($m)->post(route('mahasiswa.prestasi.store'), $this->dataForm([
            'dokumentasi' => [0 => UploadedFile::fake()->create('foto.jpg', 50, 'image/jpeg')],
        ]));
        $p = Prestasi::firstOrFail();
        $admin = $this->admin();

        foreach ([
            route('mahasiswa.dashboard'),
            route('mahasiswa.prestasi.index'),
            route('mahasiswa.prestasi.create'),
            route('mahasiswa.upload.sertifikat'),
            route('mahasiswa.upload.dokumentasi'),
            route('mahasiswa.prestasi.show', $p),
            route('mahasiswa.notifikasi.index'),
            route('profil.show'),
        ] as $url) {
            $this->actingAs($m)->get($url)->assertOk();
        }

        foreach ([
            route('admin.dashboard'),
            route('admin.rekapitulasi'),
            route('admin.verifikasi.index'),
            route('admin.verifikasi.show', $p),
            route('admin.mahasiswa.index'),
            route('admin.mahasiswa.create'),
            route('admin.mahasiswa.show', $m),
            route('admin.mahasiswa.edit', $m),
            route('profil.show'),
        ] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    /* ---------- Admin: manajemen mahasiswa ---------- */

    public function test_admin_creates_student_account(): void
    {
        $this->actingAs($this->admin())->post(route('admin.mahasiswa.store'), [
            'nama' => 'Mahasiswa Baru', 'nim' => '2417029999', 'username' => 'mhsbaru',
            'email' => 'baru@example.test', 'password' => 'rahasia123',
            'prodi' => 'Sistem Informasi', 'angkatan' => 2024,
        ])->assertSessionHasNoErrors();

        $baru = User::where('nim', '2417029999')->firstOrFail();

        $this->assertSame('mahasiswa', $baru->role);
        $this->assertTrue(Hash::check('rahasia123', $baru->password));
    }
}
