<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\PrestasiMahasiswa;
use App\Services\PdUnsri\PdUnsriUpload;
use Illuminate\Support\Facades\Log;

class PdUnsriUploadController extends Controller
{
    /**
     * Helper Method untuk memproses upload 1 record ke PDUNSRI
     */
    public function uploadSinglePrestasi(PrestasiMahasiswa $d): array
    {
        $nim = optional($d->riwayat_pendidikan)->nim ?? 'UNKNOWN';
        $aktivitas = $d->aktivitas_mahasiswa;

        Log::info("ENTER uploadSinglePrestasi PDUNSRI [NIM: {$nim}]");

        // Tanggal SK Pembimbing / Tugas dari Aktivitas
        $tglSk = optional($aktivitas)->tanggal_sk_tugas 
            ? date('Y-m-d', strtotime($aktivitas->tanggal_sk_tugas)) 
            : date('Y-m-d', strtotime($d->created_at));

        // File / URL SK Tugas Pembimbing
        $fileSkTugas = $d->sk_pembimbing 
            ?? optional($aktivitas)->sk_tugas 
            ?? $d->file_prestasi;

        // 1. SUSUN ARRAY DATA MAHASISWA (ANGGOTA AKTIVITAS)
        $arrayMahasiswa = [];
        $anggotaAktivitas = optional($aktivitas)->anggota_aktivitas;

        if ($anggotaAktivitas && $anggotaAktivitas->count() > 0) {
            foreach ($anggotaAktivitas as $mhs) {
                $arrayMahasiswa[] = [
                    'nim'            => $mhs->nim,
                    'nama_mahasiswa' => strtoupper($mhs->nama_mahasiswa),
                    'nama_peran'     => $mhs->nama_jenis_peran,
                ];
            }
        } else {
            $arrayMahasiswa[] = [
                'nim'            => $nim,
                'nama_mahasiswa' => strtoupper($d->nama_mahasiswa ?? ''),
                'nama_peran'     => 'Personal',
            ];
        }

        // 2. SUSUN ARRAY DATA DOSEN PEMBIMBING
        $arrayDosen = [];
        $bimbinganDosen = optional($aktivitas)->bimbing_mahasiswa;

        if ($bimbinganDosen && $bimbinganDosen->count() > 0) {
            foreach ($bimbinganDosen as $dos) {
                $arrayDosen[] = [
                    'nidn/nuptk'             => (string) ($dos->nidn ?? $dos->nuptk),
                    'nama_dosen'             => $dos->nama_dosen,
                    'pembimbing_ke'          => $dos->pembimbing_ke,
                    'file_surat_tugas'       => $fileSkTugas,
                ];
            }
        }

        // Mapping Payload JSON Lengkap untuk PDUNSRI
        $record = [
            'id_prestasi'       => $d->id_prestasi,
            'nim'               => $nim,
            'nama_mahasiswa'    => strtoupper($d->nama_mahasiswa ?? ''),
            'nama_prestasi'     => $d->nama_prestasi,
            'jenis_prestasi'    => $d->nama_jenis_prestasi ?? 'Lain-lain',
            'kategori_prestasi' => 'Mandiri',
            'penyelenggara'     => $d->penyelenggara,
            'peringkat'         => $d->peringkat,
            'tahun_prestasi'    => (string) $d->tahun_prestasi,
            'tanggal_sk'        => $tglSk,
            'file_prestasi'     => $d->file_prestasi,   // String URL Sertifikat
            'file_sk_tugas'     => $fileSkTugas,        // String URL SK Pembimbing
            'status_approval'   => '3',
            'mahasiswa'         => $arrayMahasiswa,     // Array Data Mahasiswa
            'dosen'             => $arrayDosen,         // Array Data Dosen Pembimbing
        ];

        try {
            $req = new PdUnsriUpload($record);
            $result = $req->uploadPrestasiMahasiswa();

            Log::info("DEBUG PDUNSRI RESULT [NIM: {$nim}]", [
                'payload' => $record,
                'result'  => $result
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error("EXCEPTION UPLOAD PDUNSRI [NIM: {$nim}]: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'error_code' => 500,
                'error_desc' => 'Exception PDUNSRI: ' . $e->getMessage()
            ];
        }
    }
}