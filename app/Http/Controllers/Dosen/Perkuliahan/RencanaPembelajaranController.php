<?php

namespace App\Http\Controllers\Dosen\Perkuliahan;

use App\Http\Controllers\Controller;
use App\Models\SemesterAktif;
use App\Models\Perkuliahan\RencanaPembelajaran;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class RencanaPembelajaranController extends Controller
{
    public function rencana_pembelajaran()
    {
        $semester_aktif = SemesterAktif::with(['semester'])->first();
        $id_dosen = auth()->user()->fk_id;

        // dd($semester_aktif->id_semester);
        $data = KelasKuliah::with(['matkul.matkul_kurikulum', 'matkul.rencana_pembelajaran'])->whereHas('dosen_pengajar', function($query) use ($id_dosen){
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
        $id_dosen = auth()->user()->fk_id;
        $semester_aktif = SemesterAktif::with(['semester'])->first();
        $matkul = MataKuliah::where('id_matkul',$id_matkul)->first();
        $rps = RencanaPembelajaran::where('id_matkul',$id_matkul)->get();

        if(is_null($matkul->link_rps) || $request->link_rps != ''){

            //Validate request data
            $data = $request->validate([
                'link_rps' => 'required|url',
                'pertemuan.*' => 'required',
                'materi_indo.*' => 'required',
                'materi_inggris.*' => 'required'
            ]);

            //Update link RPS
            MataKuliah::where('id_matkul', $id_matkul)->update(['link_rps' => $request->link_rps]);

        }else{

            $data = $request->validate([
                'pertemuan.*' => 'required',
                'materi_indo.*' => 'required',
                'materi_inggris.*' => 'required'
            ]);

        }

        //Hitung jumlah RPS yang di buat
        $jumlah_pertemuan=count($request->pertemuan);

        if(count($rps) < 16){
            if($jumlah_pertemuan > 0){
                $data_dosen = DosenPengajarKelasKuliah::with('kelas_kuliah')->whereHas('kelas_kuliah', function ($query) use ($id_matkul, $semester_aktif){
                    $query->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester);
                })
                ->where('id_dosen', $id_dosen)
                ->where('urutan', '1')
                ->first();
    
                // dd($data_dosen);
                if($data_dosen){
                    for($i=0;$i<$jumlah_pertemuan;$i++){
                        //Generate id rencana ajar
                        $id_rencana_ajar = Uuid::uuid4()->toString();
    
                        //Store data to table tanpa substansi kuliah
                        RencanaPembelajaran::create(['feeder'=> 0, 'approved'=> 0, 'id_rencana_ajar'=> $id_rencana_ajar, 'id_matkul'=> $id_matkul, 'nama_mata_kuliah'=> $matkul->nama_mata_kuliah, 'kode_mata_kuliah' => $matkul->kode_mata_kuliah, 'sks_mata_kuliah'=> $matkul->sks_mata_kuliah, 'id_prodi'=> $matkul->id_prodi, 'nama_program_studi'=> $matkul->nama_program_studi, 'pertemuan'=> $request->pertemuan[$i], 'materi_indonesia'=> $request->materi_indo[$i], 'materi_inggris'=> $request->materi_inggris[$i], 'status_sync'=> 'belum sync']);
                    }
                }else{
                    return redirect()->back()->with('error', 'Anda Bukan Koordinator Mata Kuliah');
                }
                return redirect()->route('dosen.perkuliahan.rencana-pembelajaran.detail', ['matkul' => $matkul->id_matkul])->with('success', 'Data Berhasil di Tambahkan');
            }else{
                return redirect()->back()->with('error', 'Silahkan Mengisi RPS Terlebih Dahulu');
            }
        }else{
            return redirect()->back()->with('error', 'RPS Sudah di Isi');
        }
    }

    public function ubah_rencana_pembelajaran(string $id_rencana_ajar)
    {
        // dd($semester_aktif->id_semester);
        $rps = RencanaPembelajaran::where('id_rencana_ajar', $id_rencana_ajar)->first();

        return view('dosen.perkuliahan.rencana-pembelajaran.update', ['rps' => $rps]);
    }

    public function rencana_pembelajaran_update(Request $request, string $id_rencana_ajar)
    {
        // dd($request->all());
        //Define variable
        $id_dosen = auth()->user()->fk_id;
        $rps = RencanaPembelajaran::where('id_rencana_ajar',$id_rencana_ajar)->first();
        $semester_aktif = SemesterAktif::with(['semester'])->first();

        //Validate request data
        $data = $request->validate([
            'pertemuan' => 'required',
            'materi_indo' => 'required',
            'materi_inggris' => 'required'
        ]);

        if($rps->approved != 1){
            $data_dosen = DosenPengajarKelasKuliah::with('kelas_kuliah')->whereHas('kelas_kuliah', function ($query) use ($rps, $semester_aktif){
                $query->where('id_matkul', $rps->id_matkul)->where('id_semester', $semester_aktif->id_semester);
            })
            ->where('id_dosen', $id_dosen)
            ->where('urutan', '1')
            ->first();

            // dd($data_dosen);
            if($data_dosen){
                RencanaPembelajaran::where('id_rencana_ajar', $id_rencana_ajar)->update(['pertemuan'=> $request->pertemuan, 'materi_indonesia'=> $request->materi_indo, 'materi_inggris'=> $request->materi_inggris]);

            }else{
                return redirect()->back()->with('error', 'Anda Bukan Koordinator Mata Kuliah');
            }
            return redirect()->route('dosen.perkuliahan.rencana-pembelajaran.detail', ['matkul' => $rps->id_matkul])->with('success', 'Data Berhasil di Ubah');
        }else{
            return redirect()->back()->with('error', 'RPS Sudah di Setujui Kaprodi');
        }
    }

    public function rencana_pembelajaran_delete(string $id_rencana_ajar)
    {
        // dd($request->all());
        //Define variable
        $id_dosen = auth()->user()->fk_id;
        $rps = RencanaPembelajaran::where('id_rencana_ajar',$id_rencana_ajar)->first();
        $semester_aktif = SemesterAktif::with(['semester'])->first();

        if($rps->approved != 1){
            $data_dosen = DosenPengajarKelasKuliah::with('kelas_kuliah')->whereHas('kelas_kuliah', function ($query) use ($rps, $semester_aktif){
                $query->where('id_matkul', $rps->id_matkul)->where('id_semester', $semester_aktif->id_semester);
            })
            ->where('id_dosen', $id_dosen)
            ->where('urutan', '1')
            ->first();

            // dd($data_dosen);
            if($data_dosen){

                RencanaPembelajaran::where('id_rencana_ajar', $id_rencana_ajar)->delete();

            }else{
                return redirect()->back()->with('error', 'Anda Bukan Koordinator Mata Kuliah');
            }
            return redirect()->back()->with('success', 'Data Berhasil di Hapus');
        }else{
            return redirect()->back()->with('error', 'RPS Sudah di Setujui Kaprodi');
        }
    }

    public function ubah_link_rencana_pembelajaran(string $matkul)
    {
        // dd($semester_aktif->id_semester);
        $matkul = MataKuliah::where('id_matkul', $matkul)->first();

        return view('dosen.perkuliahan.rencana-pembelajaran.update-link', ['matkul' => $matkul]);
    }

    public function rencana_pembelajaran_update_link(Request $request, string $matkul)
    {
        // dd($request->all());
        //Define variable
        $id_dosen = auth()->user()->fk_id;
        $semester_aktif = SemesterAktif::with(['semester'])->first();

        //Validate request data
        $data = $request->validate([
            'link_rps' => 'required',
        ]);

        $data_dosen = DosenPengajarKelasKuliah::with('kelas_kuliah')->whereHas('kelas_kuliah', function ($query) use ($matkul, $semester_aktif){
            $query->where('id_matkul', $matkul)->where('id_semester', $semester_aktif->id_semester);
        })
        ->where('id_dosen', $id_dosen)
        ->where('urutan', '1')
        ->first();

        // dd($data_dosen);
        if($data_dosen){

            MataKuliah::where('id_matkul', $matkul)->update(['link_rps'=> $request->link_rps]);

        }else{
            return redirect()->back()->with('error', 'Anda Bukan Koordinator Mata Kuliah');
        }
        return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
    }
}
