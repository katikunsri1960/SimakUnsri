<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Wilayah;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Mahasiswa\BiodataMahasiswa;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;


class BiodataController extends Controller
{
    public function index()
    {
        $id_reg = auth()->user()->fk_id;
        $bio = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)->pluck('id_mahasiswa')->first();
        $semester_aktif = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                        ->first();
                        // dd($semester_aktif);
        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                ->where('id_semester', $semester_aktif->id_semester)
                // ->limit(10)
                ->get();
                // dd($akm);

        $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->count();


        $data = BiodataMahasiswa::with(['riwayat_pendidikan', 'riwayat_pendidikan.jalur_masuk'])
                ->leftJoin('wilayahs','wilayahs.id_wilayah','=','biodata_mahasiswas.id_wilayah')
                ->where('id_mahasiswa', $bio)//Default
                // ->where('id_mahasiswa', '3cc994cf-5c0d-4c03-a585-0ba1ea0ef1dd')//PT Asal
                // ->where('id_mahasiswa', '0121ccfb-b750-443e-8539-d2b500206fec')//id_wilayah 000000
                // ->where('id_mahasiswa', '0023fd6c-146d-490a-83a1-5646547439ec')//Lulus
                // ->where('id_mahasiswa', 'f76e5695-41ab-4dc3-8db1-871711fcf011')//Riwayat Pendidikan
                // ->where('id_mahasiswa', '027d305e-b101-4c74-8164-f770a304b4f9')//Penerima KPS
                ->first();
                // dd($data);

        //CEK WILAYAH
        $cek_id_wil = $data->id_wilayah;
        $kab_kota= Wilayah::where('id_wilayah', $data->id_induk_wilayah)->where('id_level_wilayah', 2)->pluck('nama_wilayah')->first();
        $id_kab_kota= Wilayah::where('id_wilayah', $data->id_induk_wilayah)->where('id_level_wilayah', 2)->pluck('id_induk_wilayah')->first();
        //  dd($kab_kota);

        if ($cek_id_wil == 999999 || $id_kab_kota == NULL) {
                $kab_kota="-";
                $provinsi= "-";
        }
        else
        {
                $provinsi= Wilayah::where('id_wilayah', $id_kab_kota)
                        ->whereNotNull('id_wilayah')
                        ->where('id_level_wilayah', 1)
                        ->pluck( 'nama_wilayah',)
                        ->first();
        }
        // dd($kab_kota);


        $riwayat_pendidikan = RiwayatPendidikan::leftJoin('program_studis','program_studis.id_prodi','=','riwayat_pendidikans.id_prodi')
                ->leftJoin('aktivitas_kuliah_mahasiswas', 'aktivitas_kuliah_mahasiswas.id_registrasi_mahasiswa','=', 'riwayat_pendidikans.id_registrasi_mahasiswa')
                ->leftJoin('anggota_aktivitas_mahasiswas', 'anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa', '=', 'riwayat_pendidikans.id_registrasi_mahasiswa')
                ->leftJoin('aktivitas_mahasiswas', 'aktivitas_mahasiswas.id_aktivitas', '=', 'anggota_aktivitas_mahasiswas.id_aktivitas',)
                ->leftJoin('bimbing_mahasiswas', 'bimbing_mahasiswas.id_aktivitas', '=', 'anggota_aktivitas_mahasiswas.id_aktivitas',)
                ->select('riwayat_pendidikans.id_registrasi_mahasiswa', 'id_mahasiswa', 'riwayat_pendidikans.nim', 'riwayat_pendidikans.nama_mahasiswa', 'id_jenis_daftar', 'nama_jenis_daftar', 'id_jalur_daftar', 'riwayat_pendidikans.id_periode_masuk',
                        'nama_periode_masuk', 'id_jenis_keluar', 'keterangan_keluar', 'tanggal_keluar', 'id_perguruan_tinggi', 'nama_perguruan_tinggi', 'nama_perguruan_tinggi_asal', 'nama_program_studi_asal', 'riwayat_pendidikans.tanggal_daftar',
                        'riwayat_pendidikans.id_prodi', 'riwayat_pendidikans.nama_program_studi', 'sks_diakui', 'status', 'id_jenjang_pendidikan', 'nama_jenjang_pendidikan', 'fakultas_id','biaya_kuliah_smt', 'aktivitas_kuliah_mahasiswas.id_semester',
                        'aktivitas_kuliah_mahasiswas.sks_total', 'anggota_aktivitas_mahasiswas.id_aktivitas', 'bimbing_mahasiswas.nama_dosen', 'aktivitas_mahasiswas.id_jenis_aktivitas', 'aktivitas_kuliah_mahasiswas.id_status_mahasiswa', 'aktivitas_kuliah_mahasiswas.nama_status_mahasiswa')

                ->where('riwayat_pendidikans.id_registrasi_mahasiswa', $id_reg)//Default
                ->whereNotIn('id_status_mahasiswa', ['N'])


                // ->where('id_registrasi_mahasiswa', '55f28aa8-168c-432e-b553-e64d150c423d')//PT Asal
                // ->where('id_registrasi_mahasiswa', '3905aa74-281c-4bf2-8170-b14d2fdbb0ae')//id_wilayah 000000
                // ->where('id_registrasi_mahasiswa', '83fea028-b1ac-461d-90f2-ed554c7dc5c8')//Lulus
                // ->where('id_registrasi_mahasiswa', '00f72769-60b2-4dbe-aba3-c44b1ddfd225')//Riwayat Pendidikan
                // ->where('id_registrasi_mahasiswa', '3979bafc-4e64-4dca-8290-c2dc25ac9c3d')//Penerima KPS

                ->groupBy('riwayat_pendidikans.id_registrasi_mahasiswa', 'id_mahasiswa', 'riwayat_pendidikans.nim', 'riwayat_pendidikans.nama_mahasiswa', 'id_jenis_daftar', 'nama_jenis_daftar', 'id_jalur_daftar', 'riwayat_pendidikans.id_periode_masuk',
                        'nama_periode_masuk', 'id_jenis_keluar', 'keterangan_keluar', 'tanggal_keluar', 'id_perguruan_tinggi', 'nama_perguruan_tinggi', 'nama_perguruan_tinggi_asal', 'nama_program_studi_asal', 'riwayat_pendidikans.tanggal_daftar',
                        'riwayat_pendidikans.id_prodi', 'riwayat_pendidikans.nama_program_studi', 'sks_diakui', 'status', 'id_jenjang_pendidikan', 'nama_jenjang_pendidikan', 'fakultas_id','biaya_kuliah_smt', 'aktivitas_kuliah_mahasiswas.id_semester',
                        'aktivitas_kuliah_mahasiswas.sks_total', 'anggota_aktivitas_mahasiswas.id_aktivitas', 'bimbing_mahasiswas.nama_dosen', 'aktivitas_mahasiswas.id_jenis_aktivitas', 'aktivitas_kuliah_mahasiswas.id_status_mahasiswa', 'aktivitas_kuliah_mahasiswas.nama_status_mahasiswa')
                ->orderBy('id_jenjang_pendidikan', 'DESC')
                ->orderBy('aktivitas_kuliah_mahasiswas.id_semester', 'DESC')
                ->limit(1)
                ->get();
                // dd($riwayat_pendidikan);

        return view('mahasiswa.biodata.index', compact('data','provinsi','riwayat_pendidikan', 'id_kab_kota', 'kab_kota', 'semester_aktif','akm','semester_ke',));
    }

    public function index_rev()
    {
        $id_reg = auth()->user()->fk_id;
        $data = RiwayatPendidikan::with(['biodata', 'pembimbing_akademik'])->where('id_registrasi_mahasiswa', $id_reg)->first();

        // dd($data);
        $cek_id_wil = $data->biodata->id_wilayah;
        $kab_kota= Wilayah::where('id_wilayah', $data->biodata->id_induk_wilayah)->where('id_level_wilayah', 2)->pluck('nama_wilayah')->first();
        $id_kab_kota= Wilayah::where('id_wilayah', $data->biodata->id_induk_wilayah)->where('id_level_wilayah', 2)->pluck('id_induk_wilayah')->first();
        //  dd($kab_kota);

        if ($cek_id_wil == 999999 || $id_kab_kota == NULL) {
                $kab_kota="-";
                $provinsi= "-";
        }
        else
        {
                $provinsi= Wilayah::where('id_wilayah', $id_kab_kota)
                        ->whereNotNull('id_wilayah')
                        ->where('id_level_wilayah', 1)
                        ->pluck( 'nama_wilayah',)
                        ->first();
        }

        $riwayat_pendidikan = RiwayatPendidikan::where('id_mahasiswa', $data->id_mahasiswa)->get();

        return view('mahasiswa.biodata.index', compact('data','provinsi','riwayat_pendidikan', 'id_kab_kota', 'kab_kota'));


    }
}
