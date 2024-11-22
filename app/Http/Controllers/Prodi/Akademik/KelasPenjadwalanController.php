<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use App\Models\Perkuliahan\KomponenEvaluasiKelas;
use App\Models\Perkuliahan\SubstansiKuliah;
use App\Models\Referensi\PeriodePerkuliahan;
use App\Models\RuangPerkuliahan;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Semester;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\JenisEvaluasi;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\SemesterAktif;
use Ramsey\Uuid\Uuid;



class KelasPenjadwalanController extends Controller
{
    public function kelas_penjadwalan()
    {

        $semester_aktif = SemesterAktif::first();
        // dd($semester_aktif);
        $prodi_id = auth()->user()->fk_id;        
        
        $data = ListKurikulum::with(['mata_kuliah' => function ($query) use ($prodi_id, $semester_aktif) {
            $query->with(['matkul_konversi' => function ($query) use ($prodi_id) {
                $query->where('id_prodi', $prodi_id);
            }, 'kelas_kuliah' => function($q) use ($prodi_id, $semester_aktif){
                $q->where('id_prodi', $prodi_id);
                $q->where('id_semester', $semester_aktif->id_semester);
            }])->withCount(['kelas_kuliah as jumlah_kelas' => function($q) use ($prodi_id, $semester_aktif) {
                $q->where('id_prodi', $prodi_id);
                $q->where('id_semester', $semester_aktif->id_semester);
            }]);
        }])
        ->where('id_prodi', $prodi_id)
        ->where('is_active', 1)
        ->get();
        // dd($data);
        return view('prodi.data-akademik.kelas-penjadwalan.index', ['data' => $data, 'semester_aktif' => $semester_aktif]);
    }

    public function detail_kelas_penjadwalan($id_matkul)
    {
        $semester_aktif = SemesterAktif::first();
        $prodi_id = auth()->user()->fk_id;
        $mata_kuliah = MataKuliah::where('id_matkul', $id_matkul)->first();
        $data = KelasKuliah::with(['peserta_kelas','dosen_pengajar', 'dosen_pengajar.dosen', 'ruang_perkuliahan', 'semester'])
                            ->where('id_matkul', $id_matkul)
                            ->where('id_prodi', $prodi_id)
                            ->where('id_semester', $semester_aktif->id_semester)
                            ->get();
        // dd($data);
        return view('prodi.data-akademik.kelas-penjadwalan.detail', ['data' => $data, 'id_matkul' => $id_matkul, 'matkul' => $mata_kuliah]);
    }

    public function download_absensi($id_kelas)
    {
        $data = KelasKuliah::with(['peserta_kelas' => function($q) {
            $q->orderBy('nim');
        }, 'dosen_pengajar' => function($q) {
            $q->orderBy('urutan');
        }, 'dosen_pengajar.dosen', 'ruang_perkuliahan', 'semester', 'matkul', 'prodi'])->where('id', $id_kelas)->first();

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $sectionStyle = array(
            'orientation' => 'landscape',
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
        $table->addCell(5000)->addText($data->prodi->nama_jenjang_pendidikan. " ".$data->prodi->nama_program_studi." (".$data->prodi->kode_program_studi.")", array('name' => 'Arial', 'size' => 10));

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
        $table->addCell(5000)->addText($data->ruang_perkuliahan->nama_ruang." (".$data->ruang_perkuliahan->lokasi.")", array('name' => 'Arial', 'size' => 10));

        $table->addRow();
        $table->addCell(2000)->addText('Dosen', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(500)->addText(':', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $cell = $table->addCell(5000);

        $listStyle = array(
            'listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_BULLET_FILLED,
            'inde' => array('left' => 0, 'hanging' => 0) // Adjust the negative value as needed
        );

        // Iterate over the dosen_pengajar array and add each dosen name as a bullet point with the custom list style
        // foreach ($data->dosen_pengajar as $dosenPengajar) {
        //     $cell->addListItem($dosenPengajar->dosen->nama_dosen, 0, array('name' => 'Arial', 'size' => 10), $listStyle);
        // }

        $italicStyle = ['name' => 'Arial', 'size' => 10, 'italic' => true];
        
        if (!$data->dosen_pengajar || $data->dosen_pengajar->isEmpty()) {
            $cell->addText('Dosen Pengajar Belum Diisi', $italicStyle);
        } else {
            foreach ($data->dosen_pengajar as $dosenPengajar) {
                $cell->addListItem($dosenPengajar->dosen->nama_dosen, 0, ['name' => 'Arial', 'size' => 10, 'bold' => true], $listStyle);
            }
        }

        $table->addRow();
        $table->addCell(2000)->addText('Jadwal Hari', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(500)->addText(':', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(5000)->addText($data->jadwal_hari, array('name' => 'Arial', 'size' => 10));

        $table->addRow();
        $table->addCell(2000)->addText('Jadwal Jam', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(500)->addText(':', array('name' => 'Arial', 'size' => 10, 'bold' => true));
        $table->addCell(5000)->addText($data->jadwal_jam_mulai.' - '.$data->jadwal_jam_selesai. " WIB", array('name' => 'Arial', 'size' => 10));





        $section->addTextBreak(1);

         $tableStyle = array(
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 50
        );
        $firstRowStyle = array('bgColor' => 'ffffff');
        $phpWord->addTableStyle('Fancy Table', $tableStyle, $firstRowStyle);
        $pertemuan = 16;
        // Add table with the defined style
        $table = $section->addTable('Fancy Table');
        $table->addRow();
        $table->addCell(500, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER, 'vMerge' => 'restart'])->addText('NO', array('name' => 'Arial', 'size' => 9, 'bold' => true), ['align' => 'center', 'valign' => 'center']);
        $table->addCell(2400, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER,'vMerge' => 'restart'])->addText('NIM', array('name' => 'Arial', 'size' => 9, 'bold' => true), ['align' => 'center', 'valign' => 'center']);
        $table->addCell(4000, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER,'vMerge' => 'restart'])->addText('NAMA', array('name' => 'Arial', 'size' => 9, 'bold' => true), ['align' => 'center', 'valign' => 'center']);
        $table->addCell(800, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER,'gridSpan' => $pertemuan])->addText('TANDA TANGAN', array('name' => 'Arial', 'size' => 9, 'bold' => true), ['align' => 'center', 'valign' => 'center']);

        // Second row
        $table->addRow();
        $table->addCell(500, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER, 'vMerge' => 'continue']);
        $table->addCell(2400, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER, 'vMerge' => 'continue']);
        $table->addCell(4000, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER, 'vMerge' => 'continue']);
        for ($i = 1; $i <= $pertemuan; $i++) {
            $table->addCell(800, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER])->addText('tgl', array('name' => 'Arial', 'size' => 9, 'bold' => true, 'italic' => true), ['align' => 'center', 'valign' => 'center']);
        }


        $no = 1;
        foreach ($data->peserta_kelas as $peserta) {
            $table->addRow();
            $table->addCell(500, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER])->addText($no++, array('name' => 'Arial', 'size' => 9), ['align' => 'center', 'valign' => 'center']);
            $table->addCell(2400, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER])->addText($peserta->mahasiswa->nim, array('name' => 'Arial', 'size' => 9),['align' => 'center']);
            $table->addCell(4000, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER])->addText($peserta->mahasiswa->nama_mahasiswa, array('name' => 'Arial', 'size' => 9),['valign' => 'center']);
            for ($i=1; $i <= $pertemuan; $i++) {
                $table->addCell(800, ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER]);
            }
        }

        $filename = 'Daftar Hadir '.$data->matkul->kode_mata_kuliah.' '.$data->nama_kelas_kuliah.'.docx';
        $folderPath = storage_path('app/public/absensi/');
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

    public function tambah_kelas_penjadwalan($id_matkul)
    {
        // dd($id_matkul);
        $prodi_id = auth()->user()->fk_id;
        $ruang = RuangPerkuliahan::where('id_prodi', $prodi_id)->get();
        $mata_kuliah = MataKuliah::where('id_matkul', $id_matkul)->get();
        // dd($mata_kuliah);
        return view('prodi.data-akademik.kelas-penjadwalan.create', ['ruang' => $ruang, 'mata_kuliah' => $mata_kuliah]);
    }

    public function kelas_penjadwalan_store(Request $request, $id_matkul)
    {
        // dd($id_matkul);
        //Define variable
        $prodi_id = auth()->user()->fk_id;
        $kelas = KelasKuliah::where('id_prodi',$prodi_id)->get();
        $semester_aktif = SemesterAktif::first();
        $id_kelas = Uuid::uuid4()->toString();
        $kode_tahun = substr($semester_aktif->id_semester,-3);
        $tahun_aktif = date('Y');
        $detik = "00";

        //Validate request data
        $data = $request->validate([
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required',
            'bulan_mulai' => 'required',
            'bulan_akhir' => 'required',
            'kapasitas_kelas' => 'required',
            'ruang_kelas' => 'required',
            'mode_kelas' => [
                'required',
                Rule::in(['O','F','M'])
            ],
            'lingkup_kelas' => [
                'required',
                Rule::in(['1','2','3'])
            ],
            'jadwal_hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'menit_mulai' => 'required',
            'menit_selesai' => 'required'
        ]);

        //Generate tanggal pelaksanaan
        $tanggal_mulai_kelas = $tahun_aktif."-".$request->bulan_mulai."-".$request->tanggal_mulai;
        $tanggal_akhir_kelas = $tahun_aktif."-".$request->bulan_akhir."-".$request->tanggal_akhir;

        //Generate jam pelaksanaan
        $jam_mulai_kelas = $request->jam_mulai.":".$request->menit_mulai.":".$detik;
        $jam_selesai_kelas = $request->jam_selesai.":".$request->menit_selesai.":".$detik;

        //Generate nama kelas
        $check_lokasi_ruang = RuangPerkuliahan::where('id', $request->ruang_kelas)->get();
        $check_kelas = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->get();
        // dd(count($check_kelas));
        if(strval($check_lokasi_ruang[0]['lokasi']) == "Indralaya"){
            if(count($check_kelas) <= 70){
                $kode_nama_L = $kode_tahun."L";
                $check_kelas_L = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_L}%")->get();
                if(count($check_kelas_L) < 10){
                    if(count($check_kelas_L) < 9){
                        $nama_kelas_kuliah = $kode_nama_L.count($check_kelas_L)+1;
                    }else{
                        $nama_kelas_kuliah = $kode_nama_L."0";
                    }
                }else{
                    $kode_nama_A = $kode_tahun."A";
                    $check_kelas_A = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_A}%")->get();
                    if(count($check_kelas_A) < 10){
                        if(count($check_kelas_A) < 9){
                            $nama_kelas_kuliah = $kode_nama_A.count($check_kelas_A)+1;
                        }else{
                            $nama_kelas_kuliah = $kode_nama_A."0";
                        }
                    }else{
                        $kode_nama_B = $kode_tahun."B";
                        $check_kelas_B = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_B}%")->get();
                        if(count($check_kelas_B) < 10){
                            if(count($check_kelas_B) < 9){
                                $nama_kelas_kuliah = $kode_nama_B.count($check_kelas_B)+1;
                            }else{
                                $nama_kelas_kuliah = $kode_nama_B."0";
                            }
                        }else{
                            $kode_nama_C = $kode_tahun."C";
                            $check_kelas_C = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_C}%")->get();
                            if(count($check_kelas_C) < 10){
                                if(count($check_kelas_C) < 9){
                                    $nama_kelas_kuliah = $kode_nama_C.count($check_kelas_C)+1;
                                }else{
                                    $nama_kelas_kuliah = $kode_nama_C."0";
                                }
                            }else{
                                $kode_nama_D = $kode_tahun."D";
                                $check_kelas_D = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $request->nama_mata_kuliah)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_D}%")->get();
                                if(count($check_kelas_D) < 10){
                                    if(count($check_kelas_D) < 9){
                                        $nama_kelas_kuliah = $kode_nama_D.count($check_kelas_D)+1;
                                    }else{
                                        $nama_kelas_kuliah = $kode_nama_D."0";
                                    }
                                }else{
                                    $kode_nama_E = $kode_tahun."E";
                                    $check_kelas_E = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_E}%")->get();
                                    if(count($check_kelas_E) < 10){
                                        if(count($check_kelas_E) < 9){
                                            $nama_kelas_kuliah = $kode_nama_E.count($check_kelas_E)+1;
                                        }else{
                                            $nama_kelas_kuliah = $kode_nama_E."0";
                                        }
                                    }else{
                                        $kode_nama_F = $kode_tahun."F";
                                        $check_kelas_F = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_F}%")->get();
                                        if(count($check_kelas_F) < 10){
                                            if(count($check_kelas_F) < 9){
                                                $nama_kelas_kuliah = $kode_nama_F.count($check_kelas_F)+1;
                                            }else{
                                                $nama_kelas_kuliah = $kode_nama_F."0";
                                            }
                                        }else{
                                            $kode_nama_G = $kode_tahun."G";
                                            $check_kelas_G = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_G}%")->get();
                                            if(count($check_kelas_G) < 10){
                                                if(count($check_kelas_G) < 9){
                                                    $nama_kelas_kuliah = $kode_nama_G.count($check_kelas_G)+1;
                                                }else{
                                                    $nama_kelas_kuliah = $kode_nama_G."0";
                                                }
                                            }else{
                                                $kode_nama_H = $kode_tahun."H";
                                                $check_kelas_H = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_H}%")->get();
                                                if(count($check_kelas_H) < 10){
                                                    if(count($check_kelas_H) < 9){
                                                        $nama_kelas_kuliah = $kode_nama_H.count($check_kelas_H)+1;
                                                    }else{
                                                        $nama_kelas_kuliah = $kode_nama_H."0";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }else{
                return redirect()->back()->with('error', 'Jumlah kelas sudah melebihi batas');
            }
        }else if(strval($check_lokasi_ruang[0]['lokasi']) == "Palembang"){
            if(count($check_kelas) <= 70){
                $kode_nama_P = $kode_tahun."P";
                $check_kelas_P = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_P}%")->get();
                if(count($check_kelas_P) < 10){
                    if(count($check_kelas_P) < 9){
                        $nama_kelas_kuliah = $kode_nama_P.count($check_kelas_P)+1;
                    }else{
                        $nama_kelas_kuliah = $kode_nama_P."0";
                    }
                }else{
                    $kode_nama_M = $kode_tahun."M";
                    $check_kelas_M = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_M}%")->get();
                    if(count($check_kelas_M) < 10){
                        if(count($check_kelas_M) < 9){
                            $nama_kelas_kuliah = $kode_nama_M.count($check_kelas_M)+1;
                        }else{
                            $nama_kelas_kuliah = $kode_nama_M."0";
                        }
                    }else{
                        $kode_nama_N = $kode_tahun."N";
                        $check_kelas_N = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_N}%")->get();
                        if(count($check_kelas_N) < 10){
                            if(count($check_kelas_N) < 9){
                                $nama_kelas_kuliah = $kode_nama_N.count($check_kelas_N)+1;
                            }else{
                                $nama_kelas_kuliah = $kode_nama_N."0";
                            }
                        }else{
                            $kode_nama_R = $kode_tahun."R";
                            $check_kelas_R = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_R}%")->get();
                            if(count($check_kelas_R) < 10){
                                if(count($check_kelas_R) < 9){
                                    $nama_kelas_kuliah = $kode_nama_R.count($check_kelas_R)+1;
                                }else{
                                    $nama_kelas_kuliah = $kode_nama_R."0";
                                }
                            }else{
                                $kode_nama_S = $kode_tahun."S";
                                $check_kelas_S = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_S}%")->get();
                                if(count($check_kelas_S) < 10){
                                    if(count($check_kelas_S) < 9){
                                        $nama_kelas_kuliah = $kode_nama_S.count($check_kelas_S)+1;
                                    }else{
                                        $nama_kelas_kuliah = $kode_nama_S."0";
                                    }
                                }else{
                                    $kode_nama_T = $kode_tahun."T";
                                    $check_kelas_T = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_T}%")->get();
                                    if(count($check_kelas_T) < 10){
                                        if(count($check_kelas_T) < 9){
                                            $nama_kelas_kuliah = $kode_nama_T.count($check_kelas_T)+1;
                                        }else{
                                            $nama_kelas_kuliah = $kode_nama_T."0";
                                        }
                                    }else{
                                        $kode_nama_U = $kode_tahun."U";
                                        $check_kelas_U = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_U}%")->get();
                                        if(count($check_kelas_U) < 10){
                                            if(count($check_kelas_U) < 9){
                                                $nama_kelas_kuliah = $kode_nama_U.count($check_kelas_U)+1;
                                            }else{
                                                $nama_kelas_kuliah = $kode_nama_U."0";
                                            }
                                        }else{
                                            $kode_nama_V = $kode_tahun."V";
                                            $check_kelas_V = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_V}%")->get();
                                            if(count($check_kelas_V) < 10){
                                                if(count($check_kelas_V) < 9){
                                                    $nama_kelas_kuliah = $kode_nama_V.count($check_kelas_V)+1;
                                                }else{
                                                    $nama_kelas_kuliah = $kode_nama_V."0";
                                                }
                                            }else{
                                                $kode_nama_W = $kode_tahun."W";
                                                $check_kelas_W = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_W}%")->get();
                                                if(count($check_kelas_W) < 10){
                                                    if(count($check_kelas_W) < 9){
                                                        $nama_kelas_kuliah = $kode_nama_W.count($check_kelas_W)+1;
                                                    }else{
                                                        $nama_kelas_kuliah = $kode_nama_W."0";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }else{
                return redirect()->back()->with('error', 'Jumlah kelas sudah melebihi batas');
            }
        }else{
            return redirect()->back()->with('error', 'Lokasi tidak ada');
        }
        // dd($nama_kelas_kuliah);

        //Store data to table
        KelasKuliah::create(['ruang_perkuliahan_id'=> $request->ruang_kelas, 'feeder' => 0, 'id_kelas_kuliah'=> $id_kelas, 'id_prodi'=> $prodi_id, 'id_semester'=> $semester_aktif->id_semester, 'id_matkul'=> $id_matkul, 'nama_kelas_kuliah'=> $nama_kelas_kuliah, 'tanggal_mulai_efektif'=> $tanggal_mulai_kelas, 'tanggal_akhir_efektif'=> $tanggal_akhir_kelas, 'kapasitas'=> $request->kapasitas_kelas, 'mode'=> $request->mode_kelas, 'lingkup'=> $request->lingkup_kelas, 'jadwal_hari'=> $request->jadwal_hari, 'jadwal_jam_mulai'=> $jam_mulai_kelas, 'jadwal_jam_selesai'=> $jam_selesai_kelas]);

        return redirect()->route('prodi.data-akademik.kelas-penjadwalan.detail', $id_matkul)->with('success', 'Data Berhasil di Tambahkan');
    }

    public function dosen_pengajar_kelas($id_matkul,$nama_kelas_kuliah)
    {
        // dd($id_matkul);
        $prodi_id = auth()->user()->fk_id;
        $evaluasi = JenisEvaluasi::get();
        $kelas = KelasKuliah::leftjoin('ruang_perkuliahans','ruang_perkuliahans.id','ruang_perkuliahan_id')
                            ->leftjoin('semesters','semesters.id_semester','kelas_kuliahs.id_semester')
                            ->leftjoin('mata_kuliahs','mata_kuliahs.id_matkul','kelas_kuliahs.id_matkul')
                            ->select('*','kelas_kuliahs.tanggal_mulai_efektif','kelas_kuliahs.tanggal_akhir_efektif')
                            ->where('kelas_kuliahs.id_matkul', $id_matkul)
                            ->where('kelas_kuliahs.nama_kelas_kuliah', $nama_kelas_kuliah)
                            ->where('kelas_kuliahs.id_prodi', $prodi_id)
                            ->get();

        // dd($kelas);
        // $pengajar = DosenPengajarKelasKuliah::where('')
        return view('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar', ['evaluasi' => $evaluasi, 'kelas' => $kelas]);
    }

    public function get_dosen(Request $request)
    {
        $search = $request->get('q');
        $tahun_ajaran = SemesterAktif::with('semester')->first();
        $tahun_berjalan = $tahun_ajaran->semester->id_tahun_ajaran;

        $query = PenugasanDosen::where('id_tahun_ajaran', $tahun_berjalan)
                                ->orderBy('nama_dosen', 'asc');

        if ($search) {
            $query->where('nama_dosen', 'like', "%{$search}%")
                  ->orWhere('nama_program_studi', 'like', "%{$search}%");
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function get_substansi(Request $request)
    {
        $search = $request->get('q');
        $prodi_id = auth()->user()->fk_id;

        // $query = SubstansiKuliah::where('id_prodi', $prodi_id)
                                // ->orderby('nama_substansi', 'asc');
        $query = SubstansiKuliah::orderby('nama_substansi', 'asc');
        if ($search) {
            $query->where('nama_substansi', 'like', "%{$search}%");
                //   ->where('id_prodi', $prodi_id);
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function dosen_pengajar_store(Request $request, $id_matkul, $nama_kelas_kuliah)
    {
        // dd($request->all());
        //Define variable
        $prodi_id = auth()->user()->fk_id;
        $kelas = KelasKuliah::with('matkul')->where('id_prodi',$prodi_id)->where('id_matkul',$id_matkul)->where('nama_kelas_kuliah',$nama_kelas_kuliah)->first();
        $semester_aktif = SemesterAktif::first();
        $rencana_prodi = PeriodePerkuliahan::where('id_semester', $semester_aktif->id_semester)->where('id_prodi', $prodi_id)->first();

        //Validate request data
        $data = $request->validate([
            'dosen_kelas_kuliah.*' => 'required',
            'rencana_minggu_pertemuan.*' => 'required',
            'evaluasi.*' => [
                'required',
                Rule::in(['1','2','3','4'])
            ]
        ]);

        //Validasi jumlah total recana minggu pertemuan dosen
        $jumlah_data_pertemuan=count($request->rencana_minggu_pertemuan);
        $rencana_pertemuan = 0;
        for($j=0;$j<$jumlah_data_pertemuan;$j++){
            $rencana_pertemuan = $rencana_pertemuan + $request->rencana_minggu_pertemuan[$j];
        }

        if ($rencana_pertemuan == 0) {
            return redirect()->back()->with('error', 'Rencana Pertemuan tidak boleh 0');
        }

        if ($rencana_pertemuan > $rencana_prodi->jumlah_minggu_pertemuan) {
            return redirect()->back()->with('error', 'Rencana Pertemuan Melebihi Batas Jumlah Minggu Pertemuan Pada Periode Perkuliahan');
        }

        // dd($rencana_pertemuan);
        try {
            DB::beginTransaction();

            $dosen_pengajar = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran)->whereIn('id_registrasi_dosen', $request->dosen_kelas_kuliah)->get();

            if($dosen_pengajar->count() == 0 || $dosen_pengajar->count() != count($request->dosen_kelas_kuliah)){
                $dosen_pengajar = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)->whereIn('id_registrasi_dosen', $request->dosen_kelas_kuliah)->get();
            }
            //Count jumlah dosen pengajar kelas kuliah
            $jumlah_dosen=count($request->dosen_kelas_kuliah);

            for($i=0;$i<$jumlah_dosen;$i++){
                //Generate id aktivitas mengajar
                $id_aktivitas_mengajar = Uuid::uuid4()->toString();
                $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran)->where('id_registrasi_dosen', $request->dosen_kelas_kuliah[$i])->first();
                if(!$dosen)
                {
                    $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)->where('id_registrasi_dosen', $request->dosen_kelas_kuliah[$i])->first();
                }

                $dosen_kelas = DosenPengajarKelasKuliah::where('id_kelas_kuliah', $kelas->id_kelas_kuliah)->where('id_dosen','!=',$dosen->id_dosen)->get();
                $count_dosen_pengajar = count($dosen_kelas);
                $dosen_rencana_ajar = 0;

                if($dosen_kelas){
                    for($k=0;$k<$count_dosen_pengajar;$k++){
                        $dosen_rencana_ajar = $dosen_rencana_ajar + $dosen_kelas[$k]['rencana_minggu_pertemuan'];
                    }

                    $total_rencana_pertemuan = $rencana_pertemuan + $dosen_rencana_ajar;

                    if ($total_rencana_pertemuan > $rencana_prodi->jumlah_minggu_pertemuan) {
                        return redirect()->back()->with('error', 'Rencana Pertemuan Melebihi Batas Jumlah Minggu Pertemuan Pada Periode Perkuliahan');
                    }

                    for($d=0;$d<$count_dosen_pengajar;$d++){
                        $update_sks_substansi = round(($dosen_kelas[$d]['rencana_minggu_pertemuan']/$total_rencana_pertemuan) * $kelas->matkul->sks_mata_kuliah, 2);

                        DosenPengajarKelasKuliah::where('id_kelas_kuliah',
                        $dosen_kelas[$d]['id_kelas_kuliah'])->where('id_dosen',
                        $dosen_kelas[$d]['id_dosen'])->update(['feeder' => 0,'sks_substansi_total' => $update_sks_substansi]);

                    }

                    $sks_substansi = round(($rencana_pertemuan/$total_rencana_pertemuan) * $kelas->matkul->sks_mata_kuliah, 2);

                    $sks_dosen_pengajar = DosenPengajarKelasKuliah::where('id_kelas_kuliah', $kelas->id_kelas_kuliah)->where('id_dosen','!=',$dosen->id_dosen)->sum('sks_substansi_total');

                    $sks_substansi_total = $sks_substansi + $sks_dosen_pengajar;

                    $remaining_sks = $kelas->matkul->sks_mata_kuliah - $sks_dosen_pengajar;

                    $different_sks = $kelas->matkul->sks_mata_kuliah - $sks_substansi_total;

                    // dd($remaining_sks);

                    if($sks_substansi_total > $kelas->matkul->sks_mata_kuliah){
                        $sks_substansi = $remaining_sks;
                    }else if($sks_substansi_total < $kelas->matkul->sks_mata_kuliah+1){
                        for($d=0;$d<$count_dosen_pengajar;$d++){
                            $update_sks_substansi = round(($dosen_kelas[$d]['rencana_minggu_pertemuan']/$total_rencana_pertemuan) * $kelas->matkul->sks_mata_kuliah, 2);
    
                            if($dosen_kelas[$d]['urutan'] == 1 && ($sks_substansi_total < $kelas->matkul->sks_mata_kuliah+1)){
    
                                $update_sks_substansi = $update_sks_substansi + $different_sks;
                                
                                DosenPengajarKelasKuliah::where('id_kelas_kuliah', $dosen_kelas[$d]['id_kelas_kuliah'])
                                                        ->where('id_dosen', $dosen_kelas[$d]['id_dosen'])
                                                        ->update(['feeder' => 0, 'sks_substansi_total' => $update_sks_substansi]);

                                break;
                            }
    
                        }
                    }

                    if(is_null($request->substansi_kuliah)){
                        //Store data to table tanpa substansi kuliah
                        DosenPengajarKelasKuliah::create(['feeder'=> 0,'id_aktivitas_mengajar'=> $id_aktivitas_mengajar, 'id_registrasi_dosen'=> $dosen->id_registrasi_dosen, 'id_dosen'=> $dosen->id_dosen, 'urutan' => $count_dosen_pengajar+1, 'id_kelas_kuliah'=> $kelas->id_kelas_kuliah, 'sks_substansi_total' => $sks_substansi, 'rencana_minggu_pertemuan'=> $request->rencana_minggu_pertemuan[$i], 'realisasi_minggu_pertemuan'=> 0, 'id_jenis_evaluasi'=> $request->evaluasi[$i], 'id_prodi'=> $prodi_id, 'id_semester'=> $semester_aktif->id_semester]);
    
                    }else{
                        //Get sks substansi total
                        $substansi_kuliah = SubstansiKuliah::where('id_substansi',$request->substansi_kuliah[$i])->get();
    
                        //Store data to table dengan substansi kuliah
                        DosenPengajarKelasKuliah::create(['feeder'=> 0, 'id_aktivitas_mengajar'=> $id_aktivitas_mengajar, 'id_registrasi_dosen'=> $dosen->id_registrasi_dosen, 'id_dosen'=> $dosen->id_dosen, 'urutan' => $count_dosen_pengajar+1, 'id_kelas_kuliah'=> $kelas->id_kelas_kuliah, 'id_substansi' => $substansi_kuliah->first()->id_substansi, 'sks_substansi_total' => $substansi_kuliah->first()->sks_mata_kuliah, 'rencana_minggu_pertemuan'=> $request->rencana_minggu_pertemuan[$i], 'realisasi_minggu_pertemuan'=> 0, 'id_jenis_evaluasi'=> $request->evaluasi[$i], 'id_prodi'=> $prodi_id, 'id_semester'=> $semester_aktif->id_semester]);
                    }

                }else{

                    if(is_null($request->substansi_kuliah)){
                        //Store data to table tanpa substansi kuliah
                        DosenPengajarKelasKuliah::create(['feeder'=> 0,'id_aktivitas_mengajar'=> $id_aktivitas_mengajar, 'id_registrasi_dosen'=> $dosen->id_registrasi_dosen, 'id_dosen'=> $dosen->id_dosen, 'urutan' => $count_dosen_pengajar+1, 'id_kelas_kuliah'=> $kelas->id_kelas_kuliah, 'sks_substansi_total' => $kelas->matkul->sks_mata_kuliah, 'rencana_minggu_pertemuan'=> $request->rencana_minggu_pertemuan[$i], 'realisasi_minggu_pertemuan'=> 0, 'id_jenis_evaluasi'=> $request->evaluasi[$i], 'id_prodi'=> $prodi_id, 'id_semester'=> $semester_aktif->id_semester]);
    
                    }else{
                        //Get sks substansi total
                        $substansi_kuliah = SubstansiKuliah::where('id_substansi',$request->substansi_kuliah[$i])->get();
    
                        //Store data to table dengan substansi kuliah
                        DosenPengajarKelasKuliah::create(['feeder'=> 0, 'id_aktivitas_mengajar'=> $id_aktivitas_mengajar, 'id_registrasi_dosen'=> $dosen->id_registrasi_dosen, 'id_dosen'=> $dosen->id_dosen, 'urutan' => $count_dosen_pengajar+1, 'id_kelas_kuliah'=> $kelas->id_kelas_kuliah, 'id_substansi' => $substansi_kuliah->first()->id_substansi, 'sks_substansi_total' => $substansi_kuliah->first()->sks_mata_kuliah, 'rencana_minggu_pertemuan'=> $request->rencana_minggu_pertemuan[$i], 'realisasi_minggu_pertemuan'=> 0, 'id_jenis_evaluasi'=> $request->evaluasi[$i], 'id_prodi'=> $prodi_id, 'id_semester'=> $semester_aktif->id_semester]);
                    } 
                }
            }

            DB::commit();

            return redirect()->route('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar.manajemen', ['id_matkul' => $id_matkul, 'id_kelas' => $kelas->id_kelas_kuliah])->with('success', 'Data Berhasil di Tambahkan');

        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. '. $th->getMessage());
        }

    }

    public function manajemen_dosen_pengajar_kelas($id_kelas)
    {
        // dd($id_matkul);
        // $prodi_id = auth()->user()->fk_id;
        $kelas = KelasKuliah::with(['peserta_kelas','dosen_pengajar.dosen', 'ruang_perkuliahan', 'semester'])
                            ->leftjoin('mata_kuliahs','mata_kuliahs.id_matkul','kelas_kuliahs.id_matkul')
                            ->select('*','kelas_kuliahs.tanggal_mulai_efektif','kelas_kuliahs.tanggal_akhir_efektif')
                            // ->where('kelas_kuliahs.id_matkul', $id_matkul)
                            ->where('kelas_kuliahs.id_kelas_kuliah', $id_kelas)
                            // ->where('kelas_kuliahs.id_prodi', $prodi_id)
                            ->first();
        // dd($kelas)

        return view('prodi.data-akademik.kelas-penjadwalan.manajemen-dosen', ['kelas' => $kelas]);
    }

    public function edit_dosen_pengajar($id)
    {
        $evaluasi = JenisEvaluasi::get();
        $data = DosenPengajarKelasKuliah::with(['dosen','kelas_kuliah','kelas_kuliah.matkul'])
                                        ->where('dosen_pengajar_kelas_kuliahs.id', $id)
                                        ->first();
        
        // dd($data);

        return view('prodi.data-akademik.kelas-penjadwalan.edit-dosen-pengajar', [
            'data' => $data,
            'evaluasi' => $evaluasi
        ]);
    }

    public function update_dosen_pengajar($id, Request $request)
    {
        // dd($id);
        $prodi_id = auth()->user()->fk_id;
        $data_dosen = DosenPengajarKelasKuliah::where('id',$id)->first();
        $kelas = KelasKuliah::with('matkul')->where('id_kelas_kuliah', $data_dosen->id_kelas_kuliah)->first();
        $semester_aktif = SemesterAktif::first();
        $rencana_prodi = PeriodePerkuliahan::where('id_semester', $semester_aktif->id_semester)->where('id_prodi', $prodi_id)->first();

        //Validate request data
        $data = $request->validate([
            'dosen_pengajar' => 'required',
            'rencana_minggu_pertemuan' => 'required',
            'realisasi_minggu_pertemuan' => 'required',
            'evaluasi.*' => [
                'required',
                Rule::in(['1','2','3','4'])
            ]
        ]);

        //Validasi jumlah total recana dan realisasi minggu pertemuan dosen
        if ((int) $request->rencana_minggu_pertemuan[0] == 0) {
            return redirect()->back()->with('error', 'Rencana Pertemuan tidak boleh 0');
        }

        if ((int) $request->rencana_minggu_pertemuan[0] > $rencana_prodi->jumlah_minggu_pertemuan+1) {
            return redirect()->back()->with('error', 'Rencana Pertemuan Melebihi Batas Jumlah Minggu Pertemuan Pada Periode Perkuliahan');
        }

        if ((int) $request->realisasi_minggu_pertemuan[0] > $rencana_prodi->jumlah_minggu_pertemuan+1) {
            return redirect()->back()->with('error', 'Realisasi Pertemuan Melebihi Batas Jumlah Minggu Pertemuan Pada Periode Perkuliahan');
        }
        // dd($rencana_pertemuan);
        try {
            DB::beginTransaction();
            
            $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran)->where('id_registrasi_dosen', $request->dosen_pengajar)->first();
            
            if(!$dosen)
            {
                $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)->where('id_registrasi_dosen', $request->dosen_pengajar)->first();
            }

            $dosen_pengajar = DosenPengajarKelasKuliah::with('kelas_kuliah')->where('dosen_pengajar_kelas_kuliahs.id', '!=', $id)
                                                ->whereHas('kelas_kuliah', function ($query) use ($data_dosen) {
                                                    $query->where('id_kelas_kuliah', $data_dosen->id_kelas_kuliah);
                                                })->get();
            $count_dosen_pengajar = count($dosen_pengajar);
            $dosen_rencana_ajar = 0;
            $dosen_realisasi_ajar = 0;

            if ($dosen_pengajar) {
                foreach ($dosen_pengajar as $pengajar) {
                    $dosen_rencana_ajar += $pengajar['rencana_minggu_pertemuan'];
                    $dosen_realisasi_ajar += $pengajar['realisasi_minggu_pertemuan'];
                }
        
                $total_rencana_pertemuan = (int) $request->rencana_minggu_pertemuan[0] + $dosen_rencana_ajar;
                $total_realisasi_pertemuan = (int) $request->realisasi_minggu_pertemuan[0] + $dosen_realisasi_ajar;
        
                if ($total_rencana_pertemuan > $rencana_prodi->jumlah_minggu_pertemuan+1) {
                    return redirect()->back()->with('error', 'Rencana Pertemuan Melebihi Batas Jumlah Minggu Pertemuan Pada Periode Perkuliahan');
                }

                if ($total_realisasi_pertemuan > $rencana_prodi->jumlah_minggu_pertemuan+1) {
                    return redirect()->back()->with('error', 'Realisasi Pertemuan Melebihi Batas Jumlah Minggu Pertemuan Pada Periode Perkuliahan');
                }

                for($d=0;$d<$count_dosen_pengajar;$d++){
                    $update_sks_substansi = round(($dosen_pengajar[$d]['rencana_minggu_pertemuan']/$total_rencana_pertemuan) * $kelas->matkul->sks_mata_kuliah, 2);

                    DosenPengajarKelasKuliah::where('id_kelas_kuliah',
                    $dosen_pengajar[$d]['id_kelas_kuliah'])->where('id_dosen',
                    $dosen_pengajar[$d]['id_dosen'])->update(['feeder' => 0,'sks_substansi_total' => $update_sks_substansi]);

                }

                $sks_substansi = round(($request->rencana_minggu_pertemuan[0]/$total_rencana_pertemuan) * $kelas->matkul->sks_mata_kuliah, 2);

                $sks_dosen_pengajar = DosenPengajarKelasKuliah::where('dosen_pengajar_kelas_kuliahs.id_kelas_kuliah', $data_dosen->id_kelas_kuliah)->where('id', '!=', $id)->sum('sks_substansi_total');

                $sks_substansi_total = $sks_substansi + $sks_dosen_pengajar;

                $remaining_sks = $kelas->matkul->sks_mata_kuliah - $sks_dosen_pengajar;
                $different_sks = $kelas->matkul->sks_mata_kuliah - $sks_substansi_total;

                // dd($remaining_sks);

                if($sks_substansi_total > $kelas->matkul->sks_mata_kuliah){
                    $sks_substansi = $remaining_sks;
                }else if($sks_substansi_total < $kelas->matkul->sks_mata_kuliah+1){
                    for($d=0;$d<$count_dosen_pengajar;$d++){
                        $update_sks_substansi = round(($dosen_pengajar[$d]['rencana_minggu_pertemuan']/$total_rencana_pertemuan) * $kelas->matkul->sks_mata_kuliah, 2);

                        if($dosen_pengajar[$d]['urutan'] == 1 && ($sks_substansi_total < $kelas->matkul->sks_mata_kuliah+1)){

                            $update_sks_substansi = $update_sks_substansi + $different_sks;
                            
                            DosenPengajarKelasKuliah::where('id_kelas_kuliah', $dosen_pengajar[$d]['id_kelas_kuliah'])
                                                    ->where('id_dosen', $dosen_pengajar[$d]['id_dosen'])
                                                    ->update(['feeder' => 0,'sks_substansi_total' => $update_sks_substansi]);

                            break;
                        }

                    }
                }
                
                // Update the DosenPengajarKelasKuliah
                $updateFields = [
                    'rencana_minggu_pertemuan' => $request->rencana_minggu_pertemuan[0],
                    'realisasi_minggu_pertemuan' => $request->realisasi_minggu_pertemuan[0],
                    'id_jenis_evaluasi' => $request->evaluasi[0],
                    'sks_substansi_total' => $sks_substansi
                ];
                
                if ($data_dosen->feeder == 1) {
                    $updateFields['feeder'] = 0;
                } else {
                    $updateFields['id_registrasi_dosen'] = $dosen->id_registrasi_dosen;
                    $updateFields['id_dosen'] = $dosen->id_dosen;
                }
                
                DosenPengajarKelasKuliah::where('id', $id)->update($updateFields);

            }else{
                
                $sks_substansi = ($request->rencana_minggu_pertemuan[0]/$rencana_prodi->jumlah_minggu_pertemuan) * $kelas->matkul->sks_mata_kuliah;
                // Update the DosenPengajarKelasKuliah
                $updateFields = [
                    'rencana_minggu_pertemuan' => $request->rencana_minggu_pertemuan[0],
                    'realisasi_minggu_pertemuan' => $request->realisasi_minggu_pertemuan[0],
                    'id_jenis_evaluasi' => $request->evaluasi[0],
                    'sks_substansi_total' => $sks_substansi
                ];
                
                if ($data_dosen->feeder == 1) {
                    $updateFields['feeder'] = 0;
                } else {
                    $updateFields['id_registrasi_dosen'] = $dosen->id_registrasi_dosen;
                    $updateFields['id_dosen'] = $dosen->id_dosen;
                }
                
                DosenPengajarKelasKuliah::where('id', $id)->update($updateFields);
            }

            DB::commit();

            return redirect()->route('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar.manajemen', ['id_kelas' => $data_dosen->id_kelas_kuliah])->with('success', 'Data Berhasil di Tambahkan');

        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. '. $th->getMessage());
        }
    }

    public function dosen_pengajar_destroy($id)
    {
        $prodi_id = auth()->user()->fk_id;
        $dosen = DosenPengajarKelasKuliah::where('id', $id)->first();
        $kelas = KelasKuliah::with('matkul')->where('id_kelas_kuliah', $dosen->id_kelas_kuliah)->first();
        $semester_aktif = SemesterAktif::first();
        $rencana_prodi = PeriodePerkuliahan::where('id_semester', $semester_aktif->id_semester)->where('id_prodi', $prodi_id)->first();

        if($dosen->feeder == 1){
            return redirect()->back()->with('error', 'Data Dosen Sudah di Sinkronisasi.');
        }

        $dosen_pengajar = DosenPengajarKelasKuliah::with('kelas_kuliah')->where('dosen_pengajar_kelas_kuliahs.id', '!=', $id)
                                                ->whereHas('kelas_kuliah', function ($query) use ($dosen) {
                                                    $query->where('id_kelas_kuliah', $dosen->id_kelas_kuliah);
                                                })
                                                ->get();

        $count_dosen_pengajar = count($dosen_pengajar);
        $dosen_rencana_ajar = 0;

        if($count_dosen_pengajar == 1){
            return redirect()->back()->with('error', 'Data Dosen Tidak Boleh Kosong.');
        }

        try {
            DB::beginTransaction();

            if ($dosen_pengajar) {

                foreach ($dosen_pengajar as $pengajar) {
                    $dosen_rencana_ajar += $pengajar['rencana_minggu_pertemuan'];
                }

                $total_rencana_pertemuan = $dosen_rencana_ajar;

                for($d=0;$d<$count_dosen_pengajar;$d++){
                    $update_sks_substansi = round(($dosen_pengajar[$d]['rencana_minggu_pertemuan']/$total_rencana_pertemuan) * $kelas->matkul->sks_mata_kuliah, 2);

                    DosenPengajarKelasKuliah::where('id_kelas_kuliah',
                    $dosen_pengajar[$d]['id_kelas_kuliah'])->where('id_dosen',
                    $dosen_pengajar[$d]['id_dosen'])->update(['feeder' => 0, 'urutan' => $d+1, 'sks_substansi_total' => $update_sks_substansi]);

                }

                $sks_dosen_pengajar = DosenPengajarKelasKuliah::where('dosen_pengajar_kelas_kuliahs.id_kelas_kuliah', $dosen->id_kelas_kuliah)->where('id', '!=', $id)->sum('sks_substansi_total');

                $sks_substansi_total = $sks_dosen_pengajar;

                $different_sks = $kelas->matkul->sks_mata_kuliah - $sks_substansi_total;

                // dd($remaining_sks);

               if($sks_substansi_total < $kelas->matkul->sks_mata_kuliah+1){
                    for($d=0;$d<$count_dosen_pengajar;$d++){
                        $update_sks_substansi = round(($dosen_pengajar[$d]['rencana_minggu_pertemuan']/$total_rencana_pertemuan) * $kelas->matkul->sks_mata_kuliah, 2);

                        if($dosen_pengajar[$d]['urutan'] == 1 && ($sks_substansi_total < $kelas->matkul->sks_mata_kuliah+1)){

                            $update_sks_substansi = $update_sks_substansi + $different_sks;
                            
                            DosenPengajarKelasKuliah::where('id_kelas_kuliah', $dosen_pengajar[$d]['id_kelas_kuliah'])
                                                    ->where('id_dosen', $dosen_pengajar[$d]['id_dosen'])
                                                    ->update(['feeder' => 0,'sks_substansi_total' => $update_sks_substansi]);

                            break;
                        }

                    }
                }

                DosenPengajarKelasKuliah::where('id', $id)->where('feeder', 0)->delete();

            }else{
                
                DosenPengajarKelasKuliah::where('id', $id)->where('feeder', 0)->delete();
            }

            DB::commit();

            return redirect()->back()->with('success', 'Data Pengajar Berhasil di Hapus!!');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pengajar Gagal di Hapus. '. $th->getMessage());
        }
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

            return redirect()->route('prodi.data-akademik.kelas-penjadwalan.detail', $id_matkul)->with('success', 'Data Kelas Berhasil di Hapus!!');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Kelas Gagal di Hapus. '. $th->getMessage());
        }
    }

    public function edit_kelas_penjadwalan($id_matkul, $id_kelas)
    {
        // dd($id_matkul);
        $semester_aktif = SemesterAktif::first();
        $prodi_id = auth()->user()->fk_id;
        $mata_kuliah = MataKuliah::where('id_matkul', $id_matkul)->first();
        $kelas = KelasKuliah::leftJoin('ruang_perkuliahans', 'ruang_perkuliahans.id', 'kelas_kuliahs.ruang_perkuliahan_id')
        ->where('id_kelas_kuliah', $id_kelas)
        ->where('kelas_kuliahs.id_prodi', $prodi_id)
        ->where('kelas_kuliahs.id_semester', $semester_aktif->id_semester)
        ->first();

        // dd($kelas);

        $ruang = RuangPerkuliahan::where('id_prodi', $prodi_id)->where('lokasi', $kelas->lokasi)->get();

        return view('prodi.data-akademik.kelas-penjadwalan.edit', ['kelas' => $kelas, 'matkul' => $mata_kuliah, 'ruang' => $ruang]);
    }

    public function kelas_penjadwalan_update(Request $request, $id_matkul, $id_kelas)
    {
        // dd($request->all());

        try {
            DB::beginTransaction();

            $tahun_aktif = date('Y');
            $detik = "00";

            //Validate request data
            $data = $request->validate([
                'tanggal_mulai' => 'required',
                'tanggal_akhir' => 'required',
                'bulan_mulai' => 'required',
                'bulan_akhir' => 'required',
                'kapasitas_kelas' => 'required',
                'ruang_kelas' => 'required',
                'mode_kelas' => [
                    'required',
                    Rule::in(['O','F','M'])
                ],
                'lingkup_kelas' => [
                    'required',
                    Rule::in(['1','2','3'])
                ],
                'jadwal_hari' => 'required',
                'jam_mulai' => 'required',
                'jam_selesai' => 'required',
                'menit_mulai' => 'required',
                'menit_selesai' => 'required'
            ]);

            //Generate tanggal pelaksanaan
            $tanggal_mulai_kelas = $tahun_aktif."-".$request->bulan_mulai."-".$request->tanggal_mulai;
            $tanggal_akhir_kelas = $tahun_aktif."-".$request->bulan_akhir."-".$request->tanggal_akhir;

            //Generate jam pelaksanaan
            $jam_mulai_kelas = $request->jam_mulai.":".$request->menit_mulai.":".$detik;
            $jam_selesai_kelas = $request->jam_selesai.":".$request->menit_selesai.":".$detik;

            $data_kelas = PesertaKelasKuliah::where('id_kelas_kuliah', $id_kelas)->count();

            // dd((int) $request->kapasitas_kelas >= $data_kelas);

            if((int) $request->kapasitas_kelas >= $data_kelas){
                KelasKuliah::where('id_kelas_kuliah', $id_kelas)->where('feeder', 0)->update(['ruang_perkuliahan_id' => $request->ruang_kelas,'tanggal_mulai_efektif'=> $tanggal_mulai_kelas, 'tanggal_akhir_efektif'=> $tanggal_akhir_kelas, 'kapasitas'=> $request->kapasitas_kelas, 'mode'=> $request->mode_kelas, 'lingkup'=> $request->lingkup_kelas, 'jadwal_hari'=> $request->jadwal_hari, 'jadwal_jam_mulai'=> $jam_mulai_kelas, 'jadwal_jam_selesai'=> $jam_selesai_kelas]);
            }else{
                return redirect()->back()->with('error', 'Ubah kapasitas tidak boleh lebih kecil dari jumlah peserta kelas kuliah!!');
            }
            DB::commit();

            return redirect()->back()->with('success', 'Data Kelas Berhasil di Ubah!!');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Kelas Gagal di Ubah. '. $th->getMessage());
        }
    }

    public function peserta_kelas($id_matkul, $id_kelas)
    {
        $kelas = KelasKuliah::where('id', $id_kelas)->first();
        $matkul = MataKuliah::where('id', $id_matkul)->first();
        $peserta = PesertaKelasKuliah::with('mahasiswa')->where('id_kelas_kuliah', $kelas->id_kelas_kuliah)->get();
        // dd($id_matkul);
        return view('prodi.data-akademik.kelas-penjadwalan.peserta-kelas', ['peserta' => $peserta, 'kelas' => $kelas, 'matkul' => $matkul]);
    }
}
