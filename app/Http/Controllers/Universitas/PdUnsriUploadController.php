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

        Log::info("ENTER uploadSinglePrestasi PDUNSRI [NIM: {$nim}]");

        // Penyiapan URL Berkas/Dokumen Bukti
        $fileSertifikat = $d->file_prestasi 
            ? asset('storage/' . $d->file_prestasi) 
            : null;

        $fileSuratTugas = $d->file_sk_tugas 
            ? asset('storage/' . $d->file_sk_tugas) 
            : $fileSertifikat;

        // Mapping Payload JSON Menggunakan Kolom id_prestasi
        $record = [
            'id_prestasi'       => $d->id_prestasi, // Menggunakan id_prestasi secara eksplisit
            'nim'               => $nim,
            'nama_mahasiswa'    => strtoupper($d->nama_mahasiswa ?? ''),
            'nama_prestasi'     => $d->nama_prestasi,
            'jenis_prestasi'    => $d->nama_jenis_prestasi ?? 'NAS',
            'kategori_prestasi' => 'Mandiri',
            'penyelenggara'     => $d->penyelenggara,
            'peringkat'         => $d->peringkat ?? '1',
            'tahun_prestasi'    => (string) $d->tahun_prestasi,
            'tanggal_sk'        => $d->tanggal_sk ?? date('Y-m-d', strtotime($d->created_at ?? 'now')),
            'file_prestasi'     => $fileSertifikat,
            'file_sk_tugas'     => $fileSuratTugas,
            'status_approval'   => 'approved',
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