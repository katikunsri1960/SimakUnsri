<?php

namespace App\Http\Controllers\Universitas;

use App\Models\ProgramStudi;
use App\Models\Wilayah;
use App\Models\LevelWilayah;
use App\Models\Negara;
use App\Services\Feeder\FeederAPI;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

                $r['model']::upsert($data['data'], $r['primary']);
            }
        }

        return redirect()->route('univ.referensi.prodi')->with('success', 'Sinkronisasi Data Referensi Berhasil!');

    }
}
