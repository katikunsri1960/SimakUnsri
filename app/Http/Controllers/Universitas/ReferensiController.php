<?php

namespace App\Http\Controllers\Universitas;

use App\Models\ProgramStudi;
use App\Models\Wilayah;
use App\Models\LevelWilayah;
use App\Models\Negara;
use App\Services\Feeder\FeederAPI;
use App\Http\Controllers\Controller;
use App\Models\Referensi\AllPt;
use Illuminate\Http\Request;

class ReferensiController extends Controller
{
    public function prodi()
    {
        $data = ProgramStudi::all();

        return view('universitas.referensi.prodi', [
            'data' => $data,
        ]);
    }

    private function sync($act, $limit, $offset, $order)
    {
        $get = new FeederAPI($act, $offset, $limit, $order);

        $data = $get->runWS();

        return $data;
    }

    public function sync_prodi()
    {
        $act = 'GetProdi';
        $offset = 0;
        $limit = 0;
        $order = '';

        $prodi = new FeederAPI($act, $offset, $limit, $order);

        $prodi = $prodi->runWS();

        if (!empty($prodi['data'])) {
            foreach ($prodi['data'] as $p) {
                ProgramStudi::updateOrCreate(['id_prodi' => $p['id_prodi']], $p);
            }
        }

        return redirect()->route('univ.referensi.prodi');

    }

    public function sync_all_pt()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $act = 'GetAllPT';
        $offset = 0;
        $limit = 500;
        $order = 'id_perguruan_tinggi';
        $countAct = "GetCountPerguruanTinggi";

        $count = $this->sync($countAct, $limit, $offset, "");
        // dd($count);
        for ($i = 0; $i < $count['data']; $i += $limit) {
            $req = $this->sync($act, $limit, $i, $order);

            if (isset($req['data']) && is_array($req['data'])) {
                AllPt::upsert($req['data'], 'id_perguruan_tinggi');
            }
        }

        return redirect()->route('univ.referensi.prodi')->with('success', 'Sinkronisasi Data Perguruan Tinggi Berhasil!');
    }

    public function sync_referensi()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $ref = [
            ['act' => 'GetLevelWilayah', 'primary' => 'id_level_wilayah', 'model' => LevelWilayah::class],
            ['act' => 'GetWilayah', 'primary' =>'id_wilayah', 'model' => Wilayah::class],
            ['act' => 'GetNegara', 'primary' => 'id_negara', 'model' => Negara::class],
            ['act' => 'GetStatusMahasiswa', 'primary' => 'id_status_mahasiswa', 'model' => \App\Models\StatusMahasiswa::class],
            ['act' => 'GetSemester', 'primary' => 'id_semester', 'model' => \App\Models\Semester::class],
            ['act' => 'GetJenisKeluar', 'primary' => 'id_jenis_keluar', 'model' => \App\Models\JenisKeluar::class],
            ['act' => 'GetJenisPendaftaran', 'primary' => 'id_jenis_daftar', 'model' => \App\Models\JenisDaftar::class],
            ['act' => 'GetJalurMasuk', 'primary' => 'id_jalur_masuk', 'model' => \App\Models\JalurMasuk::class],
            ['act' => 'GetJenisEvaluasi', 'primary' => 'id_jenis_evaluasi', 'model' => \App\Models\JenisEvaluasi::class],
            ['act' => 'GetIkatanKerjaSdm', 'primary' => 'id_ikatan_kerja', 'model' => \App\Models\IkatanKerja::class],
            ['act' => 'GetJenisSubstansi', 'primary' => 'id_jenis_substansi', 'model' => \App\Models\JenisSubstansi::class],
            ['act' => 'GetListSubstansiKuliah', 'primary' => 'id_substansi', 'model' => \App\Models\Perkuliahan\SubstansiKuliah::class],
            ['act' => 'GetPembiayaan', 'primary' => 'id_pembiayaan', 'model' => \App\Models\Referensi\Pembiayaan::class],
            ['act' => 'GetJenisAktivitasMahasiswa', 'primary' => 'id_jenis_aktivitas_mahasiswa', 'model' => \App\Models\Referensi\JenisAktivitasMahasiswa::class],
            ['act' => 'GetKategoriKegiatan', 'primary' => 'id_kategori_kegiatan', 'model' => \App\Models\Referensi\KategoriKegiatan::class],
            ['act' => 'GetAgama', 'primary' => 'id_agama', 'model' => \App\Models\Referensi\Agama::class],
            ['act' => 'GetAlatTransportasi' , 'primary' => 'id_alat_transportasi', 'model' => \App\Models\Referensi\AlatTransportasi::class],
            ['act' => 'GetPekerjaan' , 'primary' => 'id_pekerjaan', 'model' => \App\Models\Referensi\Pekerjaan::class],
            ['act' => 'GetJenisPrestasi' , 'primary' => 'id_jenis_prestasi', 'model' => \App\Models\Referensi\JenisPrestasi::class],
            ['act' => 'GetTingkatPrestasi' , 'primary' => 'id_tingkat_prestasi', 'model' => \App\Models\Referensi\TingkatPrestasi::class],
            // ['act' => 'GetAllPT', 'primary' => 'id_perguruan_tinggi', 'model' => \App\Models\Referensi\AllPt::class],
        ];

        foreach ($ref as $r) {
            $act = $r['act'];
            $offset = 0;
            $limit = 0;
            $order = '';

            $data = $this->sync($act, $limit, $offset, $order);

            if (isset($data['data']) && !empty($data['data'])) {

                if ($act == 'GetWilayah') {
                    $data['data'] = array_map(function($d) {
                        $d['id_wilayah'] = trim($d['id_wilayah']);
                        $d['id_induk_wilayah'] = trim($d['id_induk_wilayah']);
                        return $d;
                    }, $data['data']);
                }

                if ($act == 'GetJenisSubstansi') {
                    $data['data'] = array_map(function($d) {
                        $d['id_jenis_substansi'] = trim($d['id_jenis_substansi']);
                        return $d;
                    }, $data['data']);
                }

                if($act == 'GetStatusMahasiswa')
                {
                    // add new status mahasiswa id_status_mahasiswa = 'K', nama_status_mahasiswa = 'Keluar'
                    $newStatusMahasiswa = [
                        ['id_status_mahasiswa' => 'K', 'nama_status_mahasiswa' => 'Keluar'],
                        ['id_status_mahasiswa' => 'D', 'nama_status_mahasiswa' => 'Drop-Out/Putus Studi'],
                        ['id_status_mahasiswa' => 'U', 'nama_status_mahasiswa' => 'Menunggu Ujian'],
                        ['id_status_mahasiswa' => 'L', 'nama_status_mahasiswa' => 'Lulus'],
                    ];
                    array_push($data['data'], ...$newStatusMahasiswa);
                }

                if($act == 'GetAgama')
                {
                    $agama = [
                        ['id_agama' => 98, 'nama_agama' => 'Tidak Diisi'],
                    ];
                    array_push($data['data'], ...$agama);
                }

                if($act == 'GetAlatTransportasi')
                {
                    $at = [
                        ['id_alat_transportasi' => 2, 'nama_alat_transportasi' => 'Kendaraan Pribadi'],
                    ];
                    array_push($data['data'], ...$at);
                }

                $r['model']::upsert($data['data'], $r['primary']);
            }
        }

        return redirect()->route('univ.referensi.prodi')->with('success', 'Sinkronisasi Data Referensi Berhasil!');

    }
}
