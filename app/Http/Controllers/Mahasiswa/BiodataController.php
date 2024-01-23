<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Mahasiswa\BiodataMahasiswa;
use App\Models\Mahasiswa\RiwayatPendidikan;


class BiodataController extends Controller
{
    public function index()
    {
        $id_reg = auth()->user()->fk_id;
        $bio = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)->pluck('id_mahasiswa')->first();

        $data = BiodataMahasiswa::with(['riwayat_pendidikan', 'riwayat_pendidikan.jalur_masuk'])
                ->leftJoin('wilayahs','wilayahs.id_wilayah','=','biodata_mahasiswas.id_wilayah')
                ->where('id_mahasiswa', $bio)//Default
                // ->where('id_mahasiswa', '3cc994cf-5c0d-4c03-a585-0ba1ea0ef1dd')//PT Asal
                // ->where('id_mahasiswa', '0121ccfb-b750-443e-8539-d2b500206fec')//id_wilayah 000000
                // ->where('id_mahasiswa', '0023fd6c-146d-490a-83a1-5646547439ec')//Lulus
                // ->where('id_mahasiswa', '027d305e-b101-4c74-8164-f770a304b4f9')//Penerima KPS
                ->first();
                // dd($data);
                
        //CEK WILAYAH
        $cek_id_wil = $data->id_wilayah;
        $kab_kota= Wilayah::where('id_wilayah', $data->id_induk_wilayah)->pluck('nama_wilayah')->first();;
        $id_kab_kota= Wilayah::where('id_wilayah', $data->id_induk_wilayah)->pluck('id_induk_wilayah')->first();
        //  dd($kab_kota);
        
        if ($cek_id_wil == 999999 || $id_kab_kota == NULL) {
                $kab_kota="-";
                $provinsi= "-";
        }
        else
        {
                $provinsi= Wilayah::where('id_wilayah', $id_kab_kota)
                        ->whereNotNull('id_wilayah')
                        ->pluck('nama_wilayah')
                        ->first();
        }
        // dd($provinsi);


        $riwayat_pendidikan = RiwayatPendidikan::leftJoin('program_studis','program_studis.id_prodi','=','riwayat_pendidikans.id_prodi')
                ->select('id_registrasi_mahasiswa', 'id_mahasiswa', 'nim', 'nama_mahasiswa', 'id_jenis_daftar', 'nama_jenis_daftar', 'id_jalur_daftar', 'id_periode_masuk',
                        'nama_periode_masuk', 'id_jenis_keluar', 'keterangan_keluar', 'tanggal_keluar', 'id_perguruan_tinggi', 'nama_perguruan_tinggi', 'nama_perguruan_tinggi_asal', 'nama_program_studi_asal', 
                        'riwayat_pendidikans.id_prodi', 'riwayat_pendidikans.nama_program_studi', 'sks_diakui', 'status', 'id_jenjang_pendidikan', 'nama_jenjang_pendidikan', 'fakultas_id',)
                ->where('id_registrasi_mahasiswa', $id_reg)//Default
                ->groupBy('id_registrasi_mahasiswa', 'id_mahasiswa', 'nim', 'nama_mahasiswa', 'id_jenis_daftar', 'nama_jenis_daftar', 'id_jalur_daftar', 'id_periode_masuk',
                        'nama_periode_masuk', 'id_jenis_keluar', 'keterangan_keluar', 'tanggal_keluar', 'id_perguruan_tinggi', 'nama_perguruan_tinggi', 'nama_perguruan_tinggi_asal', 'nama_program_studi_asal', 
                        'riwayat_pendidikans.id_prodi', 'riwayat_pendidikans.nama_program_studi', 'sks_diakui', 'status', 'id_jenjang_pendidikan', 'nama_jenjang_pendidikan', 'fakultas_id',)
                ->orderBy('id_jenjang_pendidikan', 'DESC')
                ->get();
                // dd($riwayat_pendidikan);
          
        return view('mahasiswa.biodata.index', compact('data','provinsi','riwayat_pendidikan', 'id_kab_kota', 'kab_kota'));
    }
}