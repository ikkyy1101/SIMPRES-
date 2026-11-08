<?php

namespace App\Services;

use App\Models\Prestasi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Penyimpanan sertifikat & dokumentasi prestasi.
 *
 * Berkas disimpan pada disk PRIVAT ("local" = storage/app/private) dan hanya
 * dapat dibuka lewat BerkasController yang memeriksa hak akses (NF-14).
 */
class BerkasPrestasiService
{
    public const DISK = 'local';

    /**
     * @param  array<int,string>  $baru  daftar path yang baru ditulis (untuk rollback)
     */
    public function simpanSertifikat(
        Prestasi $prestasi,
        UploadedFile $file,
        array &$baru
    ): void {
        $path = $file->store(
            'prestasi/'.$prestasi->id.'/sertifikat',
            self::DISK
        );

        if (! $path) {
            throw new \RuntimeException('Gagal menyimpan sertifikat.');
        }

        $baru[] = $path;

        $prestasi->sertifikat()->create([
            'file_sertifikat' => $path,
            'nama_file' => $file->getClientOriginalName(),
            'tgl_upload' => now()->toDateString(),
        ]);
    }

    /**
     * Memindahkan berkas sertifikat sementara ke folder permanen prestasi.
     *
     * @param  array<int,string>  $baru
     */
    public function simpanSertifikatDariPath(
        Prestasi $prestasi,
        string $tempPath,
        string $originalName,
        array &$baru
    ): void {
        $targetDir = 'prestasi/'.$prestasi->id.'/sertifikat';
        $filename = basename($tempPath);
        $finalPath = $targetDir.'/'.$filename;

        Storage::disk(self::DISK)->makeDirectory($targetDir);
        if (Storage::disk(self::DISK)->exists($tempPath)) {
            Storage::disk(self::DISK)->move($tempPath, $finalPath);
        }

        $baru[] = $finalPath;

        $prestasi->sertifikat()->create([
            'file_sertifikat' => $finalPath,
            'nama_file' => $originalName,
            'tgl_upload' => now()->toDateString(),
        ]);
    }

    /**
     * @param  array<int,UploadedFile>  $files
     * @param  array<int,string|null>  $keterangan
     * @param  array<int,string>  $baru
     */
    public function simpanDokumentasi(
        Prestasi $prestasi,
        array $files,
        array $keterangan,
        array &$baru
    ): void {
        foreach ($files as $index => $file) {
            $path = $file->store(
                'prestasi/'.$prestasi->id.'/dokumentasi',
                self::DISK
            );

            if (! $path) {
                throw new \RuntimeException('Gagal menyimpan dokumentasi.');
            }

            $baru[] = $path;

            $prestasi->dokumentasi()->create([
                'file_dokumentasi' => $path,
                'nama_file' => $file->getClientOriginalName(),
                'keterangan' => $keterangan[$index] ?? null,
                'tgl_upload' => now()->toDateString(),
            ]);
        }
    }

    /**
     * @param  array<int,string>  $paths
     */
    public function hapus(array $paths): void
    {
        if ($paths !== []) {
            Storage::disk(self::DISK)->delete($paths);
        }
    }

    public function hapusFolder(Prestasi $prestasi): void
    {
        Storage::disk(self::DISK)->deleteDirectory(
            'prestasi/'.$prestasi->id
        );
    }
}
