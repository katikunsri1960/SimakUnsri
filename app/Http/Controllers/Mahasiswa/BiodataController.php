<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\BiodataMahasiswa;
use App\Models\Mahasiswa\RiwayatPendidikan;


class BiodataController extends Controller
{
    public function index()
    {
        $id_reg = auth()->user()->fk_id;
        

        $data = RiwayatPendidikan::
                // with(['biodata', 'biodata.wilayah', 'jalur_masuk', 'biodata.wilayah.kab_kota', ])
                leftJoin('biodata_mahasiswas','biodata_mahasiswas.id_mahasiswa','=','riwayat_pendidikans.id_mahasiswa')
                ->leftJoin('jalur_masuks','jalur_masuks.id_jalur_masuk','=','riwayat_pendidikans.id_jalur_daftar')
                ->leftJoin('wilayahs','wilayahs.id_wilayah','=','biodata_mahasiswas.id_wilayah')
                ->select('riwayat_pendidikans.*', 'biodata_mahasiswas.*',  'id_jalur_masuk', 'nama_jalur_masuk', 'id_level_wilayah', 'id_induk_wilayah')
                
                // ->where('id_registrasi_mahasiswa', '55f28aa8-168c-432e-b553-e64d150c423d')//PT Asal
                // ->where('id_registrasi_mahasiswa', '3905aa74-281c-4bf2-8170-b14d2fdbb0ae')//id_wilayah 000000
                // ->where('riwayat_pendidikans.id_mahasiswa', '09827b92-fb07-417b-b5d3-d214d2e45cea')//Lulus
                
                ->where('id_registrasi_mahasiswa', $id_reg)//Default
                ->first();
                // dd($data);

        $riwayat_pendidikan = RiwayatPendidikan::leftJoin('program_studis','program_studis.id_prodi','=','riwayat_pendidikans.id_prodi')
                ->select('id_registrasi_mahasiswa', 'id_mahasiswa', 'nim', 'nama_mahasiswa', 'id_jenis_daftar', 'nama_jenis_daftar', 'id_jalur_daftar', 'id_periode_masuk',
                        'nama_periode_masuk', 'id_jenis_keluar', 'keterangan_keluar', 'tanggal_keluar', 'id_perguruan_tinggi', 'nama_perguruan_tinggi',
                        'riwayat_pendidikans.id_prodi', 'riwayat_pendidikans.nama_program_studi', 'sks_diakui', 'status', 'id_jenjang_pendidikan', 'nama_jenjang_pendidikan', 'fakultas_id',)
                ->where('id_registrasi_mahasiswa', $id_reg)//Default
                ->where('id_jenis_keluar', 1)
                ->groupBy('id_registrasi_mahasiswa', 'id_mahasiswa', 'nim', 'nama_mahasiswa', 'id_jenis_daftar', 'nama_jenis_daftar', 'id_jalur_daftar', 'id_periode_masuk',
                        'nama_periode_masuk', 'id_jenis_keluar', 'keterangan_keluar', 'tanggal_keluar', 'id_perguruan_tinggi', 'nama_perguruan_tinggi',
                        'riwayat_pendidikans.id_prodi', 'riwayat_pendidikans.nama_program_studi', 'sks_diakui', 'status', 'id_jenjang_pendidikan', 'nama_jenjang_pendidikan', 'fakultas_id',)
                ->orderBy('id_jenjang_pendidikan', 'DESC')
                ->get();

        $pt_asal = RiwayatPendidikan::leftJoin('program_studis','program_studis.id_prodi','=','riwayat_pendidikans.id_prodi')
                ->select('id_registrasi_mahasiswa', 'id_mahasiswa', 'nim', 'nama_mahasiswa', 'id_jenis_daftar', 'nama_jenis_daftar', 'id_jalur_daftar', 'id_periode_masuk',
                        'nama_periode_masuk', 'id_jenis_keluar', 'keterangan_keluar', 'tanggal_keluar', 'id_perguruan_tinggi', 'nama_perguruan_tinggi', 'nama_perguruan_tinggi_asal', 'nama_program_studi_asal', 
                        'riwayat_pendidikans.id_prodi', 'riwayat_pendidikans.nama_program_studi', 'sks_diakui', 'status', 'id_jenjang_pendidikan', 'nama_jenjang_pendidikan', 'fakultas_id',)
                ->where('id_registrasi_mahasiswa', $id_reg)//Default
                ->whereNotIn('id_jenis_daftar', [1,0])
                ->groupBy('id_registrasi_mahasiswa', 'id_mahasiswa', 'nim', 'nama_mahasiswa', 'id_jenis_daftar', 'nama_jenis_daftar', 'id_jalur_daftar', 'id_periode_masuk',
                'nama_periode_masuk', 'id_jenis_keluar', 'keterangan_keluar', 'tanggal_keluar', 'id_perguruan_tinggi', 'nama_perguruan_tinggi', 'nama_perguruan_tinggi_asal', 'nama_program_studi_asal', 
                        'riwayat_pendidikans.id_prodi', 'riwayat_pendidikans.nama_program_studi', 'sks_diakui', 'status', 'id_jenjang_pendidikan', 'nama_jenjang_pendidikan', 'fakultas_id',)
                ->get();


        //CEK WILAYAH
        $cek_id_wil = $data->biodata->wilayah->id_wilayah;

        if ($cek_id_wil == NULL || $cek_id_wil == 999999 || $data->biodata->wilayah->kab_kota == NULL) {
            
            $provinsi= array(
                "nama_wilayah" => "Tidak diisi"
            );
        }
        else
        {
            // Kode untuk kondisi ketika $cek_id_wil bukan NULL atau bukan 999999
            $id_wil = $data->biodata->wilayah->kab_kota->id_induk_wilayah;

            $provinsi= Wilayah::where('id_wilayah', $id_wil)
                    ->whereNotNull('id_wilayah')
                    ->first();
        }
                    // dd($riwayat_pendidikan);

        return view('mahasiswa.biodata.index', compact('data',
         'provinsi',
         'pt_asal',
         'riwayat_pendidikan'
        ));
    }
}
