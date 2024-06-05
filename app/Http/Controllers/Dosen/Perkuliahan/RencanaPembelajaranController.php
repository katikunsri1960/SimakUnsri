<?php

namespace App\Http\Controllers\Dosen\Perkuliahan;

use App\Http\Controllers\Controller;
use App\Models\SemesterAktif;
use App\Models\Perkuliahan\RencanaPembelajaran;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\KelasKuliah;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RencanaPembelajaranController extends Controller
{
    public function rencana_pembelajaran()
    {
        $semester_aktif = SemesterAktif::with(['semester'])->first();
        $id_dosen = auth()->user()->fk_id;

        // dd($semester_aktif->id_semester);
        $data = KelasKuliah::with(['matkul.kurikulum', 'matkul.rencana_pembelajaran'])->whereHas('dosen_pengajar', function($query) use ($id_dosen){
            $query->where('id_dosen', $id_dosen);
        })
        ->where('id_semester', $semester_aktif->id_semester)
        ->select('kelas_kuliahs.*')
        ->addSelect(DB::raw('(select count(id) from rencana_pembelajarans where rencana_pembelajarans.id_matkul=kelas_kuliahs.id_matkul) AS jumlah_rps'))
        ->addSelect(DB::raw('(select count(approved) from rencana_pembelajarans where rencana_pembelajarans.id_matkul=kelas_kuliahs.id_matkul and approved=1) AS jumlah_approved'))
        ->orderBy('kode_mata_kuliah', 'ASC')
        ->get();

        $data_matkul = $data->unique('id_matkul')->values();

        // dd($data_matkul);

        return view('dosen.perkuliahan.rencana-pembelajaran.index', ['data' => $data_matkul]);
    }

    public function detail_rencana_pembelajaran(string $id_matkul)
    {
        // dd($semester_aktif->id_semester);
        $matkul = MataKuliah::where('id_matkul', $id_matkul)->first();

        $data = RencanaPembelajaran::where('id_matkul', $id_matkul)
        ->orderBy('pertemuan', 'ASC')
        ->get();

        // dd($data_matkul);

        return view('dosen.perkuliahan.rencana-pembelajaran.detail', ['data' => $data, 'matkul' => $matkul]);
    }
}
