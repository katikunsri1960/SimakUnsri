<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\KomponenEvaluasiKelas;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\NilaiKomponenEvaluasi;
use App\Models\Perkuliahan\NilaiPerkuliahan;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class ImportDPNA implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    protected $kelas;
    protected $matkul;

    public function __construct(string $kelas, string $matkul)
    {
        $this->kelas = $kelas;
        $this->matkul = $matkul;
        $this->sks_matkul = MataKuliah::where('id_matkul', $matkul)->first()->sks_mata_kuliah;
        $this->semester_kelas = KelasKuliah::where('id_kelas_kuliah', $kelas)->first()->id_semester;
        $this->nama_semester_kelas = KelasKuliah::where('id_kelas_kuliah', $kelas)->first()->nama_semester;
    }

   public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // dd($row);
            // Insert nilai komponen evaluasi
            $mahasiswa_kelas = PesertaKelasKuliah::where('nim', $row['nim'])->where('id_kelas_kuliah', $this->kelas)->first();
            $komponen_evaluasi = KomponenEvaluasiKelas::where('id_kelas_kuliah', $this->kelas)->orderBy('nomor_urut')->get();
            $nilai_komponen = NilaiKomponenEvaluasi::where('id_kelas', $this->kelas)->orderBy('urutan')->get();
            $nilai_perkuliahan = NilaiPerkuliahan::where('id_kelas_kuliah', $this->kelas)->get();
            
            for($i=0;$i<count($komponen_evaluasi);$i++){
                // dd($komponen_evaluasi);
                if($nilai_komponen->isEmpty()){
                    if($komponen_evaluasi[$i]['nomor_urut'] == 1){
                        NilaiKomponenEvaluasi::create([
                            'id_registrasi_mahasiswa' => $mahasiswa_kelas->id_registrasi_mahasiswa,
                            'id_komponen_evaluasi' => $komponen_evaluasi[$i]['id_komponen_evaluasi'],
                            'nilai_komp_eval' => $row['nilai_aktivitas_partisipatif'],
                            'id_prodi' => $mahasiswa_kelas->id_prodi,
                            'nama_program_studi' => $mahasiswa_kelas->nama_program_studi,
                            'id_periode' => $this->semester_kelas,
                            'id_matkul' => $this->matkul,
                            'nama_mata_kuliah' => $mahasiswa_kelas->nama_mata_kuliah,
                            'id_kelas' => $this->kelas,
                            'nama_kelas_kuliah' => $row['nama_kelas_kuliah'],
                            'sks_mata_kuliah' => $this->sks_matkul,
                            'nim' => $row['nim'],
                            'nama_mahasiswa' => $row['nama_mahasiswa'],
                            'id_jns_eval' => $komponen_evaluasi[$i]['id_jenis_evaluasi'],
                            'nama' => $komponen_evaluasi[$i]['nama'],
                            'nama_inggris' => $komponen_evaluasi[$i]['nama_inggris'],
                            'urutan' => $komponen_evaluasi[$i]['nomor_urut'],
                            'bobot' => $komponen_evaluasi[$i]['bobot_evaluasi'],
                            'angkatan' => $mahasiswa_kelas->angkatan,
                            'status_sync' => 'belum sync',
                            'feeder' => 0
                        ]);
                    }else if($komponen_evaluasi[$i]['nomor_urut'] == 2){
                        NilaiKomponenEvaluasi::create([
                            'id_registrasi_mahasiswa' => $mahasiswa_kelas->id_registrasi_mahasiswa,
                            'id_komponen_evaluasi' => $komponen_evaluasi[$i]['id_komponen_evaluasi'],
                            'nilai_komp_eval' => $row['nilai_hasil_proyek'],
                            'id_prodi' => $mahasiswa_kelas->id_prodi,
                            'nama_program_studi' => $mahasiswa_kelas->nama_program_studi,
                            'id_periode' => $this->semester_kelas,
                            'id_matkul' => $this->matkul,
                            'nama_mata_kuliah' => $mahasiswa_kelas->nama_mata_kuliah,
                            'id_kelas' => $this->kelas,
                            'nama_kelas_kuliah' => $row['nama_kelas_kuliah'],
                            'sks_mata_kuliah' => $this->sks_matkul,
                            'nim' => $row['nim'],
                            'nama_mahasiswa' => $row['nama_mahasiswa'],
                            'id_jns_eval' => $komponen_evaluasi[$i]['id_jenis_evaluasi'],
                            'nama' => $komponen_evaluasi[$i]['nama'],
                            'nama_inggris' => $komponen_evaluasi[$i]['nama_inggris'],
                            'urutan' => $komponen_evaluasi[$i]['nomor_urut'],
                            'bobot' => $komponen_evaluasi[$i]['bobot_evaluasi'],
                            'angkatan' => $mahasiswa_kelas->angkatan,
                            'status_sync' => 'belum sync',
                            'feeder' => 0
                        ]);
                    }else if($komponen_evaluasi[$i]['nomor_urut'] == 3){
                        NilaiKomponenEvaluasi::create([
                            'id_registrasi_mahasiswa' => $mahasiswa_kelas->id_registrasi_mahasiswa,
                            'id_komponen_evaluasi' => $komponen_evaluasi[$i]['id_komponen_evaluasi'],
                            'nilai_komp_eval' => $row['nilai_tugas'],
                            'id_prodi' => $mahasiswa_kelas->id_prodi,
                            'nama_program_studi' => $mahasiswa_kelas->nama_program_studi,
                            'id_periode' => $this->semester_kelas,
                            'id_matkul' => $this->matkul,
                            'nama_mata_kuliah' => $mahasiswa_kelas->nama_mata_kuliah,
                            'id_kelas' => $this->kelas,
                            'nama_kelas_kuliah' => $row['nama_kelas_kuliah'],
                            'sks_mata_kuliah' => $this->sks_matkul,
                            'nim' => $row['nim'],
                            'nama_mahasiswa' => $row['nama_mahasiswa'],
                            'id_jns_eval' => $komponen_evaluasi[$i]['id_jenis_evaluasi'],
                            'nama' => $komponen_evaluasi[$i]['nama'],
                            'nama_inggris' => $komponen_evaluasi[$i]['nama_inggris'],
                            'urutan' => $komponen_evaluasi[$i]['nomor_urut'],
                            'bobot' => $komponen_evaluasi[$i]['bobot_evaluasi'],
                            'angkatan' => $mahasiswa_kelas->angkatan,
                            'status_sync' => 'belum sync',
                            'feeder' => 0
                        ]);
                    }else if($komponen_evaluasi[$i]['nomor_urut'] == 4){
                        NilaiKomponenEvaluasi::create([
                            'id_registrasi_mahasiswa' => $mahasiswa_kelas->id_registrasi_mahasiswa,
                            'id_komponen_evaluasi' => $komponen_evaluasi[$i]['id_komponen_evaluasi'],
                            'nilai_komp_eval' => $row['nilai_kuis'],
                            'id_prodi' => $mahasiswa_kelas->id_prodi,
                            'nama_program_studi' => $mahasiswa_kelas->nama_program_studi,
                            'id_periode' => $this->semester_kelas,
                            'id_matkul' => $this->matkul,
                            'nama_mata_kuliah' => $mahasiswa_kelas->nama_mata_kuliah,
                            'id_kelas' => $this->kelas,
                            'nama_kelas_kuliah' => $row['nama_kelas_kuliah'],
                            'sks_mata_kuliah' => $this->sks_matkul,
                            'nim' => $row['nim'],
                            'nama_mahasiswa' => $row['nama_mahasiswa'],
                            'id_jns_eval' => $komponen_evaluasi[$i]['id_jenis_evaluasi'],
                            'nama' => $komponen_evaluasi[$i]['nama'],
                            'nama_inggris' => $komponen_evaluasi[$i]['nama_inggris'],
                            'urutan' => $komponen_evaluasi[$i]['nomor_urut'],
                            'bobot' => $komponen_evaluasi[$i]['bobot_evaluasi'],
                            'angkatan' => $mahasiswa_kelas->angkatan,
                            'status_sync' => 'belum sync',
                            'feeder' => 0
                        ]);
                    }else if($komponen_evaluasi[$i]['nomor_urut'] == 5){
                        NilaiKomponenEvaluasi::create([
                            'id_registrasi_mahasiswa' => $mahasiswa_kelas->id_registrasi_mahasiswa,
                            'id_komponen_evaluasi' => $komponen_evaluasi[$i]['id_komponen_evaluasi'],
                            'nilai_komp_eval' => $row['nilai_uts'],
                            'id_prodi' => $mahasiswa_kelas->id_prodi,
                            'nama_program_studi' => $mahasiswa_kelas->nama_program_studi,
                            'id_periode' => $this->semester_kelas,
                            'id_matkul' => $this->matkul,
                            'nama_mata_kuliah' => $mahasiswa_kelas->nama_mata_kuliah,
                            'id_kelas' => $this->kelas,
                            'nama_kelas_kuliah' => $row['nama_kelas_kuliah'],
                            'sks_mata_kuliah' => $this->sks_matkul,
                            'nim' => $row['nim'],
                            'nama_mahasiswa' => $row['nama_mahasiswa'],
                            'id_jns_eval' => $komponen_evaluasi[$i]['id_jenis_evaluasi'],
                            'nama' => $komponen_evaluasi[$i]['nama'],
                            'nama_inggris' => $komponen_evaluasi[$i]['nama_inggris'],
                            'urutan' => $komponen_evaluasi[$i]['nomor_urut'],
                            'bobot' => $komponen_evaluasi[$i]['bobot_evaluasi'],
                            'angkatan' => $mahasiswa_kelas->angkatan,
                            'status_sync' => 'belum sync',
                            'feeder' => 0
                        ]);
                    }else if($komponen_evaluasi[$i]['nomor_urut'] == 6){
                        NilaiKomponenEvaluasi::create([
                            'id_registrasi_mahasiswa' => $mahasiswa_kelas->id_registrasi_mahasiswa,
                            'id_komponen_evaluasi' => $komponen_evaluasi[$i]['id_komponen_evaluasi'],
                            'nilai_komp_eval' => $row['nilai_uas'],
                            'id_prodi' => $mahasiswa_kelas->id_prodi,
                            'nama_program_studi' => $mahasiswa_kelas->nama_program_studi,
                            'id_periode' => $this->semester_kelas,
                            'id_matkul' => $this->matkul,
                            'nama_mata_kuliah' => $mahasiswa_kelas->nama_mata_kuliah,
                            'id_kelas' => $this->kelas,
                            'nama_kelas_kuliah' => $row['nama_kelas_kuliah'],
                            'sks_mata_kuliah' => $this->sks_matkul,
                            'nim' => $row['nim'],
                            'nama_mahasiswa' => $row['nama_mahasiswa'],
                            'id_jns_eval' => $komponen_evaluasi[$i]['id_jenis_evaluasi'],
                            'nama' => $komponen_evaluasi[$i]['nama'],
                            'nama_inggris' => $komponen_evaluasi[$i]['nama_inggris'],
                            'urutan' => $komponen_evaluasi[$i]['nomor_urut'],
                            'bobot' => $komponen_evaluasi[$i]['bobot_evaluasi'],
                            'angkatan' => $mahasiswa_kelas->angkatan,
                            'status_sync' => 'belum sync',
                            'feeder' => 0
                        ]);
                    }else{
                        return back()->with('error', 'Id jenis evaluasi tidak terdata');
                    }
                }else{
                    if($komponen_evaluasi[$i]['nomor_urut'] == 1){
                        NilaiKomponenEvaluasi::update([
                            'nilai_komp_eval' => $row['nilai_aktivitas_partisipatif'],
                        ])->where('id_komponen_evaluasi', $komponen_evaluasi[$i]['id_komponen_evaluasi'])
                        ->where('id_kelas', $this->kelas)
                        ->where('id_matkul', $this->matkul)
                        ->where('id_registrasi_mahasiswa', $mahasiswa_kelas->id_registrasi_mahasiswa);
                    }else if($komponen_evaluasi[$i]['nomor_urut'] == 2){
                        NilaiKomponenEvaluasi::update([
                            'nilai_komp_eval' => $row['nilai_hasil_proyek'],
                        ])->where('id_komponen_evaluasi', $komponen_evaluasi[$i]['id_komponen_evaluasi'])
                        ->where('id_kelas', $this->kelas)
                        ->where('id_matkul', $this->matkul)
                        ->where('id_registrasi_mahasiswa', $mahasiswa_kelas->id_registrasi_mahasiswa);
                    }else if($komponen_evaluasi[$i]['nomor_urut'] == 3){
                        NilaiKomponenEvaluasi::update([
                            'nilai_komp_eval' => $row['nilai_tugas'],
                        ])->where('id_komponen_evaluasi', $komponen_evaluasi[$i]['id_komponen_evaluasi'])
                        ->where('id_kelas', $this->kelas)
                        ->where('id_matkul', $this->matkul)
                        ->where('id_registrasi_mahasiswa', $mahasiswa_kelas->id_registrasi_mahasiswa);
                    }else if($komponen_evaluasi[$i]['nomor_urut'] == 4){
                        NilaiKomponenEvaluasi::update([
                            'nilai_komp_eval' => $row['nilai_kuis'],
                        ])->where('id_komponen_evaluasi', $komponen_evaluasi[$i]['id_komponen_evaluasi'])
                        ->where('id_kelas', $this->kelas)
                        ->where('id_matkul', $this->matkul)
                        ->where('id_registrasi_mahasiswa', $mahasiswa_kelas->id_registrasi_mahasiswa);
                    }else if($komponen_evaluasi[$i]['nomor_urut'] == 5){
                        NilaiKomponenEvaluasi::update([
                            'nilai_komp_eval' => $row['nilai_uts'],
                        ])->where('id_komponen_evaluasi', $komponen_evaluasi[$i]['id_komponen_evaluasi'])
                        ->where('id_kelas', $this->kelas)
                        ->where('id_matkul', $this->matkul)
                        ->where('id_registrasi_mahasiswa', $mahasiswa_kelas->id_registrasi_mahasiswa);
                    }else if($komponen_evaluasi[$i]['nomor_urut'] == 6){
                        NilaiKomponenEvaluasi::update([
                            'nilai_komp_eval' => $row['nilai_uas'],
                        ])->where('id_komponen_evaluasi', $komponen_evaluasi[$i]['id_komponen_evaluasi'])
                        ->where('id_kelas', $this->kelas)
                        ->where('id_matkul', $this->matkul)
                        ->where('id_registrasi_mahasiswa', $mahasiswa_kelas->id_registrasi_mahasiswa);
                    }else{
                        return back()->with('error', 'Id jenis evaluasi tidak terdata');
                    }
                }
            }
                

            if($nilai_perkuliahan->isEmpty()){
                // Insert nilai perkuliahan
                NilaiPerkuliahan::create([
                    'id_prodi' => $mahasiswa_kelas->id_prodi,
                    'nama_program_studi' => $mahasiswa_kelas->nama_program_studi,
                    'id_semester' => $this->semester_kelas,
                    'nama_semester' => $this->nama_semester_kelas,
                    'id_matkul' => $this->matkul,
                    'kode_mata_kuliah' => $row['kode_mata_kuliah'],
                    'nama_mata_kuliah' => $row['nama_mata_kuliah'],
                    'sks_mata_kuliah' => $this->sks_matkul,
                    'id_kelas_kuliah' => $this->kelas,
                    'nama_kelas_kuliah' => $row['nama_kelas_kuliah'],
                    'id_registrasi_mahasiswa' => $mahasiswa_kelas->id_registrasi_mahasiswa,
                    'id_mahasiswa' => $mahasiswa_kelas->id_mahasiswa,
                    'nim' => $row['nim'],
                    'nama_mahasiswa' => $row['nama_mahasiswa'],
                    'jurusan' => $mahasiswa_kelas->nama_program_studi,
                    'angkatan' => $mahasiswa_kelas->angkatan,
                    'nilai_angka' => number_format($row['nilai_angka'],2),
                    'nilai_indeks' => number_format($row['nilai_indeks'],2),
                    'nilai_huruf' => $row['nilai_huruf'],
                ]);
            }else{
                NilaiPerkuliahan::where('id_kelas_kuliah', $this->kelas)->update([
                    'nilai_angka' => number_format($row['nilai_angka'],2),
                    'nilai_indeks' => number_format($row['nilai_indeks'],2),
                    'nilai_huruf' => $row['nilai_huruf'],
                ]);
            }
        }
    }

}
