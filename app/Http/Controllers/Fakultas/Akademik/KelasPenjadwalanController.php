<?php

namespace App\Http\Controllers\Fakultas\Akademik;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Semester;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\JenisEvaluasi;
use App\Models\SemesterAktif;
use Illuminate\Validation\Rule;
use App\Models\RuangPerkuliahan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Perkuliahan\SubstansiKuliah;
use App\Models\Referensi\PeriodePerkuliahan;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\KomponenEvaluasiKelas;
use App\Models\Perkuliahan\DosenPengajarKelasKuliah;



class KelasPenjadwalanController extends Controller
{
    public function kelas_penjadwalan()
    {
        $prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
        ->orderBy('id_jenjang_pendidikan')
        ->orderBy('nama_program_studi')
        ->get();

        $id_prodi_fak=$prodi_fak->pluck('id_prodi');      

        $semester_aktif = SemesterAktif::first();
        // dd($prodi_fak);
        // $prodi_id = auth()->user()->fk_id;        
        
        $data = ListKurikulum::with(['mata_kuliah' => function ($query) use ($id_prodi_fak, $semester_aktif) {
            $query->with(['matkul_konversi' => function ($query) use ($id_prodi_fak) {
                $query->whereIn('id_prodi', $id_prodi_fak);
            }, 'kelas_kuliah' => function($q) use ($id_prodi_fak, $semester_aktif){
                $q->whereIn('id_prodi', $id_prodi_fak);
                $q->where('id_semester', $semester_aktif->id_semester);
            }])->withCount(['kelas_kuliah as jumlah_kelas' => function($q) use ($id_prodi_fak, $semester_aktif) {
                $q->whereIn('id_prodi', $id_prodi_fak);
                $q->where('id_semester', $semester_aktif->id_semester);
            }]);
        }])
        ->whereIn('id_prodi', $id_prodi_fak)
        ->where('is_active', 1)
        ->get();
        // dd($data);
        return view('fakultas.data-akademik.kelas-penjadwalan.index', ['data' => $data, 'semester_aktif' => $semester_aktif]);
    }

    public function detail_kelas_penjadwalan($id_matkul)
    {
        $prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
        ->orderBy('id_jenjang_pendidikan')
        ->orderBy('nama_program_studi')
        ->get();

        $id_prodi_fak=$prodi_fak->pluck('id_prodi');

        $semester_aktif = SemesterAktif::first();
        // $prodi_id = auth()->user()->fk_id;
        $mata_kuliah = MataKuliah::where('id_matkul', $id_matkul)->first();
        $data = KelasKuliah::with(['peserta_kelas','dosen_pengajar', 'dosen_pengajar.dosen', 'ruang_perkuliahan', 'ruang_ujian', 'semester'])
                            ->where('id_matkul', $id_matkul)
                            ->whereIn('id_prodi', $id_prodi_fak)
                            ->where('id_semester', $semester_aktif->id_semester)
                            ->get();
        // dd($data);
        $tgl_ujian = [];
        $hari_ujian = [];
        $jam_mulai_ujian = [];
        $jam_selesai_ujian = [];

        foreach ($data as $d) {
            $tgl_ujian[] = Carbon::parse($d->jadwal_mulai_ujian)->locale('id')->translatedFormat('d F Y');
            $hari_ujian[] = Carbon::parse($d->jadwal_mulai_ujian)->locale('id')->translatedFormat('l');
            $jam_mulai_ujian[] = Carbon::parse($d->jadwal_mulai_ujian)->format('H:i');
            $jam_selesai_ujian[] = Carbon::parse($d->jadwal_selesai_ujian)->format('H:i');
        }


                    
        // dd($tgl_ujian, $hari_ujian, $jam_mulai_ujian, $jam_selesai_ujian);
        return view('fakultas.data-akademik.kelas-penjadwalan.detail', ['data' => $data, 'id_matkul' => $id_matkul, 'matkul' => $mata_kuliah, 'tgl_ujian'=>$tgl_ujian, 'hari_ujian'=>$hari_ujian, 'jam_mulai_ujian'=>$jam_mulai_ujian, 'jam_selesai_ujian'=>$jam_selesai_ujian]);
    }

    public function download_absensi($id_kelas)
    {
        $data = KelasKuliah::with(['peserta_kelas' => function($q) {
            $q->orderBy('nim');
        }, 'dosen_pengajar' => function($q) {
            $q->orderBy('urutan');
        }, 'dosen_pengajar.dosen', 'ruang_perkuliahan','ruang_ujian', 'semester', 'matkul', 'prodi'])->where('id', $id_kelas)->first();

        $tgl_ujian= Carbon::parse($data->jadwal_mulai_ujian)->locale('id')->translatedFormat('d F Y');;
        $hari_ujian = Carbon::parse($data->jadwal_mulai_ujian)->locale('id')->translatedFormat('l');
        $mulai_ujian = Carbon::parse($data->jadwal_mulai_ujian)->format('H:i');
        $selesai_ujian = Carbon::parse($data->jadwal_selesai_ujian)->format('H:i');

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $sectionStyle = array(
            'orientation' => 'portrait',
            'marginLeft' => 600,
            'marginRight' => 600,
            'marginTop' => 600,
            'marginBottom' => 600
        );

        // Add section with the defined properties
        $section = $phpWord->addSection($sectionStyle);

        $section->addText('DAFTAR HADIR', array('name' => 'Arial', 'size' => 14, 'bold' => true), array('align' => 'center'));

        $tableStyle = array('alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER);
        $table = $section->addTable($tableStyle);

        // Add rows and cells for each piece of text
        $table->addRow();
        $table->addCell(2000)->addText('Program Studi', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(500)->addText(':', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(5000)->addText(strtoupper($data->prodi->nama_jenjang_pendidikan). " ".strtoupper($data->prodi->nama_program_studi)." (".strtoupper($data->prodi->kode_program_studi).")", array('name' => 'Arial', 'size' => 10));

        $table->addRow();
        $table->addCell(2000)->addText('Tahun Akademik', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(500)->addText(':', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(5000)->addText($data->semester->nama_semester, array('name' => 'Arial', 'size' => 10));

        $table->addRow();
        $table->addCell(2000)->addText('Mata Kuliah', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(500)->addText(':', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(5000)->addText($data->matkul->kode_mata_kuliah.' - '.$data->matkul->nama_mata_kuliah, array('name' => 'Arial', 'size' => 10));

        $table->addRow();
        $table->addCell(2000)->addText('Kelas', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(500)->addText(':', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(5000)->addText($data->nama_kelas_kuliah, array('name' => 'Arial', 'size' => 10));

        $table->addRow();
        $table->addCell(2000)->addText('Ruang', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(500)->addText(':', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(5000)->addText($data->ruang_ujian->nama_ruang." (".$data->ruang_ujian->lokasi.")", array('name' => 'Arial', 'size' => 10));

        $table->addRow();
        $table->addCell(2000)->addText('Dosen', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(500)->addText(':', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        
        $cell = $table->addCell(5000);

        $listStyle = array(
            'listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_BULLET_FILLED,
            'inde' => array('left' => 0, 'hanging' => 0) // Adjust the negative value as needed
        );

        $italicStyle = ['name' => 'Arial', 'size' => 10, 'italic' => true];

        // Iterate over the dosen_pengajar array and add each dosen name as a bullet point with the custom list style
        if (!$data->dosen_pengajar || $data->dosen_pengajar->isEmpty()) {
            $cell->addText('Dosen Pengajar Belum Diisi', $italicStyle);
        } else {
            foreach ($data->dosen_pengajar as $dosenPengajar) {
                $cell->addListItem($dosenPengajar->dosen->nama_dosen, 0, ['name' => 'Arial', 'size' => 10, 'bold' => true], $listStyle);
            }
        }

        // dd($dosen);

        $table->addRow();
        $table->addCell(2000)->addText('Jadwal Hari', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(500)->addText(':', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(5000)->addText(strtoupper($hari_ujian), array('name' => 'Arial', 'size' => 10));

        $table->addRow();
        $table->addCell(2000)->addText('Jadwal Jam', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(500)->addText(':', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(5000)->addText($mulai_ujian.' - '.$selesai_ujian. " WIB", array('name' => 'Arial', 'size' => 10));

        $section->addTextBreak(1);

         $tableStyle = array(
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 50
        );
        $firstRowStyle = array('bgColor' => 'ffffff');
        $phpWord->addTableStyle('Fancy Table', $tableStyle, $firstRowStyle);
        
        // Add table with the defined style
        $table = $section->addTable('Fancy Table');
        $table->addRow();
        $table->addCell(500, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER, 'vMerge' => 'restart'])->addText('NO', array('name' => 'Arial', 'size' => 9, 'bold' => true), ['align' => 'center', 'valign' => 'center']);
        $table->addCell(2400, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER,'vMerge' => 'restart'])->addText('NIM', array('name' => 'Arial', 'size' => 9, 'bold' => true), ['align' => 'center', 'valign' => 'center']);
        $table->addCell(4000, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER,'vMerge' => 'restart'])->addText('NAMA', array('name' => 'Arial', 'size' => 9, 'bold' => true), ['align' => 'center', 'valign' => 'center']);
        $table->addCell(4000, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER,'vMerge' => 'continue'])->addText('TANDA TANGAN', array('name' => 'Arial', 'size' => 9, 'bold' => true), ['align' => 'center', 'valign' => 'center']);
        $table->addCell(4000, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER,'vMerge' => 'continue'])->addText('KETERANGAN', array('name' => 'Arial', 'size' => 9, 'bold' => true), ['align' => 'center', 'valign' => 'center']);

        // Second row
        $table->addRow();
        $table->addCell(500, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER, 'vMerge' => 'continue']);
        $table->addCell(2400, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER, 'vMerge' => 'continue']);
        $table->addCell(4000, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER, 'vMerge' => 'continue']);
        $table->addCell(4000, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER, 'vMerge' => 'continue']);
        $table->addCell(4000, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER, 'vMerge' => 'continue']);


        $no = 1;
        foreach ($data->peserta_kelas as $peserta) {
            $table->addRow();
            $table->addCell(500, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER])->addText($no++, array('name' => 'Arial', 'size' => 9), ['align' => 'center', 'valign' => 'center']);
            $table->addCell(2400, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER])->addText($peserta->mahasiswa->nim, array('name' => 'Arial', 'size' => 9),['align' => 'center']);
            $table->addCell(4000, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER])->addText($peserta->mahasiswa->nama_mahasiswa, array('name' => 'Arial', 'size' => 9),['valign' => 'center']);
            $table->addCell(800, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER]);
            $table->addCell(800, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER]);
        }

        $section->addTextBreak(1);
        $tableStyle = array('alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER);
        $table = $section->addTable($tableStyle);

        // Add rows and cells for each piece of text
        $table->addRow();
        $table->addCell(6000)->addText('', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(6000)->addText($data->ruang_ujian->lokasi.', '.$tgl_ujian, array('name' => 'Arial', 'size' => 10), ['align' => 'right', 'valign' => 'center']);

        $table->addRow();
        $table->addCell(6000, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER, 'vMerge' => 'restart'])->addText('PENGAWAS I', array('name' => 'Arial', 'size' => 9, 'bold' => true), ['align' => 'left', 'valign' => 'center']);
        $table->addCell(6000, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER, 'vMerge' => 'restart'])->addText('PENGAWAS II', array('name' => 'Arial', 'size' => 9, 'bold' => true), ['align' => 'right', 'valign' => 'center']);

        $table->addRow(600);
        
        $table->addRow();
        $table->addCell(6000, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER])->addText('(..................................)', array('name' => 'Arial', 'size' => 9), ['align' => 'left', 'valign' => 'center']);
        $table->addCell(6000, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER])->addText('(..................................)', array('name' => 'Arial', 'size' => 9),['align' => 'right', 'valign' => 'center']); 
        // Keterangan// 

        
        $filename = 'Daftar Hadir '.$data->matkul->kode_mata_kuliah.' '.$data->nama_kelas_kuliah.'.docx';
        $folderPath = storage_path('app/public/absensi_ujian/');
        $path = $folderPath . $filename;

        // Check if the folder exists
        if (!file_exists($folderPath) || !is_dir($folderPath)) {
            // Create the folder
            mkdir($folderPath, 0755, true);
        }

        // Save the file
        $phpWord->save($path);

        return response()->download($path);

    }

    public function kelas_penjadwalan_destroy($id_matkul, $id_kelas)
    {
        // dd($id_matkul);
        $peserta = PesertaKelasKuliah::where('id_kelas_kuliah', $id_kelas)->first();

        $dosen = DosenPengajarKelasKuliah::where('id_kelas_kuliah', $id_kelas)->count();

        // dd($dosen);

        if($peserta){
            return redirect()->back()->with('error', 'Data Kelas tidak bisa dihapus karena sudah ada peserta');
        }

        try {
            DB::beginTransaction();

            if($dosen){
                DosenPengajarKelasKuliah::where('id_kelas_kuliah', $id_kelas)->delete();
                KomponenEvaluasiKelas::where('id_kelas_kuliah', $id_kelas)->delete();
                KelasKuliah::where('id_kelas_kuliah', $id_kelas)->delete();
            }else{
                KomponenEvaluasiKelas::where('id_kelas_kuliah', $id_kelas)->delete();
                KelasKuliah::where('id_kelas_kuliah', $id_kelas)->delete();
            }

            DB::commit();

            return redirect()->route('fakultas.data-akademik.kelas-penjadwalan.detail', $id_matkul)->with('success', 'Data Kelas Berhasil di Hapus!!');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Kelas Gagal di Hapus. '. $th->getMessage());
        }
    }

    public function edit_kelas_penjadwalan($id_matkul, $id_kelas)
    {
        // dd($id_matkul);
        $semester_aktif = SemesterAktif::first();
        
        $prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
        ->orderBy('id_jenjang_pendidikan')
        ->orderBy('nama_program_studi')
        ->get();

        $id_prodi_fak=$prodi_fak->pluck('id_prodi');      

        $mata_kuliah = MataKuliah::where('id_matkul', $id_matkul)->first();
        $kelas = KelasKuliah::leftJoin('ruang_perkuliahans', 'ruang_perkuliahans.id', 'kelas_kuliahs.ruang_perkuliahan_id')
        ->where('id_kelas_kuliah', $id_kelas)
        ->whereIn('kelas_kuliahs.id_prodi', $id_prodi_fak)
        ->where('kelas_kuliahs.id_semester', $semester_aktif->id_semester)
        ->first();

        // dd($kelas);

        $ruang = RuangPerkuliahan::whereIn('id_prodi', $id_prodi_fak)->where('lokasi', $kelas->lokasi)->get();

        return view('fakultas.data-akademik.kelas-penjadwalan.edit', ['kelas' => $kelas, 'matkul' => $mata_kuliah, 
        'ruang' => $ruang
        ]);
    }

    public function kelas_penjadwalan_update(Request $request, $id_matkul, $id_kelas)
    {
        try {
            DB::beginTransaction();

            // Validasi data dari request
            $data = $request->validate([
                'lokasi_ujian_id' => 'required',
                'jadwal_mulai_ujian' => 'required',
                'jadwal_selesai_ujian' => 'required',
            ]);

            // Update data pada tabel KelasKuliah
            KelasKuliah::where('id_kelas_kuliah', $id_kelas)->update([
                'lokasi_ujian_id' => $request->lokasi_ujian_id,
                'jadwal_mulai_ujian' => $request->jadwal_mulai_ujian,
                'jadwal_selesai_ujian' => $request->jadwal_selesai_ujian,
            ]);

            DB::commit();

            // Redirect ke halaman index dengan pesan sukses
            return redirect()->route('fakultas.data-akademik.kelas-penjadwalan.detail', $id_matkul)
                            ->with('success', 'Data Kelas Berhasil diubah!');
        } catch (\Throwable $th) {
            DB::rollback();

            // Redirect kembali ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Data Kelas Gagal diubah. ' . $th->getMessage());
        }
    }


    public function peserta_kelas($id_matkul, $id_kelas)
    {
        $kelas = KelasKuliah::where('id', $id_kelas)->first();
        $matkul = MataKuliah::where('id', $id_matkul)->first();
        $peserta = PesertaKelasKuliah::with('mahasiswa')->where('id_kelas_kuliah', $kelas->id_kelas_kuliah)->get();
        // dd($id_matkul);
        return view('fakultas.data-akademik.kelas-penjadwalan.peserta-kelas', ['peserta' => $peserta, 'kelas' => $kelas, 'matkul' => $matkul]);
    }
}
