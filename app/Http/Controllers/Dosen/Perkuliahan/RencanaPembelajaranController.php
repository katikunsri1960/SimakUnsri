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

    public function tambah_rencana_pembelajaran(string $id_matkul)
    {
        // dd($semester_aktif->id_semester);
        $matkul = MataKuliah::where('id_matkul', $id_matkul)->first();

        return view('dosen.perkuliahan.rencana-pembelajaran.store', ['matkul' => $matkul]);
    }

    public function rencana_pembelajaran_store(Request $request, string $id_matkul)
    {
        // dd($request->all());
        //Define variable
        $semester_aktif = SemesterAktif::with(['semester'])->first();
        $matkul = MataKuliah::where('id_matkul',$id_matkul)->get();

        //Validate request data
        $data = $request->validate([
            'link_rps' => 'required',
            'pertemuan.*' => 'required',
            'materi_indo.*' => 'required',
            'materi_inggris.*' => 'required'
        ]);

        MataKuliah::update();

        $jumlah_pertemuan=count($request->pertemuan);

        if($rencana_pertemuan >= '16'){
             //Count jumlah dosen pengajar kelas kuliah
            $jumlah_dosen=count($request->dosen_kelas_kuliah);

            for($i=0;$i<$jumlah_dosen;$i++){
                //Generate id aktivitas mengajar
                $id_aktivitas_mengajar = Uuid::uuid4()->toString();

                if(is_null($request->substansi_kuliah)){
                    //Store data to table tanpa substansi kuliah
                    DosenPengajarKelasKuliah::create(['feeder'=> 0,'id_aktivitas_mengajar'=> $id_aktivitas_mengajar, 'id_registrasi_dosen'=> $dosen_pengajar[$i]['id_registrasi_dosen'], 'id_dosen'=> $dosen_pengajar[$i]['id_dosen'], 'urutan' => $i+1, 'id_kelas_kuliah'=> $kelas[0]['id_kelas_kuliah'], 'rencana_minggu_pertemuan'=> $request->rencana_minggu_pertemuan[$i], 'realisasi_minggu_pertemuan'=> 0, 'id_jenis_evaluasi'=> $request->evaluasi[$i], 'id_prodi'=> $prodi_id, 'id_semester'=> $semester_aktif->id_semester]);

                }else{
                    //Get sks substansi total
                    $substansi_kuliah = SubstansiKuliah::where('id_substansi',$request->substansi_kuliah[$i])->get();

                    //Store data to table dengan substansi kuliah
                    DosenPengajarKelasKuliah::create(['feeder'=> 0, 'id_aktivitas_mengajar'=> $id_aktivitas_mengajar, 'id_registrasi_dosen'=> $dosen_pengajar[$i]['id_registrasi_dosen'], 'id_dosen'=> $dosen_pengajar[$i]['id_dosen'], 'urutan' => $i+1, 'id_kelas_kuliah'=> $kelas[0]['id_kelas_kuliah'], 'id_substansi' => $substansi_kuliah->first()->id_substansi, 'sks_substansi_total' => $substansi_kuliah->first()->sks_mata_kuliah, 'rencana_minggu_pertemuan'=> $request->rencana_minggu_pertemuan[$i], 'realisasi_minggu_pertemuan'=> 0, 'id_jenis_evaluasi'=> $request->evaluasi[$i], 'id_prodi'=> $prodi_id, 'id_semester'=> $semester_aktif->id_semester]);
                }

            }
            return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
        }else{
            return redirect()->back()->with('error', 'Total Rencana Minggu Pertemuan Dosen Tidak Berjumlah 16');
        }
    }
}
