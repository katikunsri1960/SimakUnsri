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
use Illuminate\Support\Facades\Log;

class ImportDPNA implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    protected $kelas;
    protected $matkul, $sks_matkul, $semester_kelas, $nama_semester_kelas;

    public function __construct(string $kelas, string $matkul)
    {
        $dbKelas = KelasKuliah::where('id_kelas_kuliah', $kelas)->first();
        $this->kelas = $kelas;
        $this->matkul = $matkul;
        $this->sks_matkul = MataKuliah::where('id_matkul', $matkul)->first()->sks_mata_kuliah;
        $this->semester_kelas = $dbKelas->id_semester;
        $this->nama_semester_kelas = $dbKelas->nama_semester;
    }

    public function collection(Collection $rows)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');

        // Log::info('Starting import process');
        // $nilai_komponen = NilaiKomponenEvaluasi::where('id_kelas', $this->kelas)->orderBy('urutan')->get();
        // $nilai_perkuliahan = NilaiPerkuliahan::where('id_kelas_kuliah', $this->kelas)->get();

        foreach ($rows as $index => $row) {
            // dd($row);

            try {
                $mahasiswa_kelas = PesertaKelasKuliah::where('nim', $row['nim'])->where('id_kelas_kuliah', $this->kelas)->first();

                if (!$mahasiswa_kelas) {
                    Log::error("Mahasiswa not found for NIM: {$row['nim']} in class: {$this->kelas}");
                    continue;
                }

                $nilai_komponen = NilaiKomponenEvaluasi::where('id_kelas', $this->kelas)->where('id_registrasi_mahasiswa', $mahasiswa_kelas->id_registrasi_mahasiswa)->get();

                $komponen_evaluasi = KomponenEvaluasiKelas::where('id_kelas_kuliah', $this->kelas)->orderBy('nomor_urut')->get();

                foreach ($komponen_evaluasi as $komponen) {

                    $nilai_field = $this->getNilaiField($komponen['nomor_urut']);

                    $nilai_exact = $row[$nilai_field] != null ? floatval(str_replace(',','.', trim($row[$nilai_field]))) : 0;

                    NilaiKomponenEvaluasi::updateOrCreate(
                        ['id_komponen_evaluasi' => $komponen['id_komponen_evaluasi'], 'id_kelas' => $this->kelas, 'id_registrasi_mahasiswa' => $mahasiswa_kelas->id_registrasi_mahasiswa, 'urutan' => $komponen['nomor_urut']],
                        [
                            'feeder' => 0,
                            'id_prodi' => $mahasiswa_kelas->id_prodi,
                            'nama_program_studi' => $mahasiswa_kelas->nama_program_studi,
                            'id_periode' => $this->semester_kelas,
                            'id_matkul' => $this->matkul,
                            'nama_mata_kuliah' => $mahasiswa_kelas->nama_mata_kuliah,
                            'nama_kelas_kuliah' => $row['nama_kelas_kuliah'],
                            'sks_mata_kuliah' => $this->sks_matkul,
                            'nama_mahasiswa' => $row['nama_mahasiswa'],
                            'nim' => $row['nim'],
                            'nama_mahasiswa' => $row['nama_mahasiswa'],
                            'id_jns_eval' => $komponen['id_jenis_evaluasi'],
                            'nama' => $komponen['nama'],
                            'nama_inggris' => $komponen['nama_inggris'],
                            'bobot' => $komponen['bobot_evaluasi'],
                            'angkatan' => $mahasiswa_kelas->angkatan,
                            'status_sync' => 'belum sync',
                            'nilai_komp_eval' => $nilai_exact,
                        ]
                    );

                }
                // if ($nilai_komponen->count() == 0) {
                //     $komponen_evaluasi = KomponenEvaluasiKelas::where('id_kelas_kuliah', $this->kelas)->orderBy('nomor_urut')->get();

                //     foreach ($komponen_evaluasi as $komponen) {

                //         $nilai_field = $this->getNilaiField($komponen['nomor_urut']);

                //         NilaiKomponenEvaluasi::create([
                //             'feeder' => 0,
                //             'id_registrasi_mahasiswa' => $mahasiswa_kelas->id_registrasi_mahasiswa,
                //             'id_komponen_evaluasi' => $komponen['id_komponen_evaluasi'],
                //             'nilai_komp_eval' => $row[$nilai_field],
                //             'id_prodi' => $mahasiswa_kelas->id_prodi,
                //             'nama_program_studi' => $mahasiswa_kelas->nama_program_studi,
                //             'id_periode' => $this->semester_kelas,
                //             'id_matkul' => $this->matkul,
                //             'nama_mata_kuliah' => $mahasiswa_kelas->nama_mata_kuliah,
                //             'id_kelas' => $this->kelas,
                //             'nama_kelas_kuliah' => $row['nama_kelas_kuliah'],
                //             'sks_mata_kuliah' => $this->sks_matkul,
                //             'nim' => $row['nim'],
                //             'nama_mahasiswa' => $row['nama_mahasiswa'],
                //             'id_jns_eval' => $komponen['id_jenis_evaluasi'],
                //             'nama' => $komponen['nama'],
                //             'nama_inggris' => $komponen['nama_inggris'],
                //             'urutan' => $komponen['nomor_urut'],
                //             'bobot' => $komponen['bobot_evaluasi'],
                //             'angkatan' => $mahasiswa_kelas->angkatan,
                //             'status_sync' => 'belum sync',
                //         ]);
                //     }

                // } else {

                    // $komponen_evaluasi = KomponenEvaluasiKelas::where('id_kelas_kuliah', $this->kelas)->orderBy('nomor_urut')->get();

                    // foreach ($komponen_evaluasi as $komponen) {

                    //     $nilai_field = $this->getNilaiField($komponen['nomor_urut']);

                    //     NilaiKomponenEvaluasi::updateOrCreate(
                    //         ['id_komponen_evaluasi' => $komponen['id_komponen_evaluasi'], 'id_kelas' => $this->kelas, 'id_registrasi_mahasiswa' => $mahasiswa_kelas->id_registrasi_mahasiswa, 'urutan' => $komponen['nomor_urut']],
                    //         [
                    //             'feeder' => 0,
                    //             'id_prodi' => $mahasiswa_kelas->id_prodi,
                    //             'nama_program_studi' => $mahasiswa_kelas->nama_program_studi,
                    //             'id_periode' => $this->semester_kelas,
                    //             'id_matkul' => $this->matkul,
                    //             'nama_mata_kuliah' => $mahasiswa_kelas->nama_mata_kuliah,
                    //             'nama_kelas_kuliah' => $row['nama_kelas_kuliah'],
                    //             'sks_mata_kuliah' => $this->sks_matkul,
                    //             'nama_mahasiswa' => $row['nama_mahasiswa'],
                    //             'nim' => $row['nim'],
                    //             'nama_mahasiswa' => $row['nama_mahasiswa'],
                    //             'id_jns_eval' => $komponen['id_jenis_evaluasi'],
                    //             'nama' => $komponen['nama'],
                    //             'nama_inggris' => $komponen['nama_inggris'],
                    //             'bobot' => $komponen['bobot_evaluasi'],
                    //             'angkatan' => $mahasiswa_kelas->angkatan,
                    //             'status_sync' => 'belum sync',
                    //             'nilai_komp_eval' => $row[$nilai_field] ?? 0,
                    //         ]
                    //     );

                    // }
                // }

                $nilai_perkuliahan = NilaiPerkuliahan::where('id_kelas_kuliah', $this->kelas)->where('id_registrasi_mahasiswa', $mahasiswa_kelas->id_registrasi_mahasiswa)->first();
                $nilai_angka_ = trim($row['nilai_angka']);
                $nilai_indeks = trim($row['nilai_indeks']);

                if ($nilai_perkuliahan) {
                    NilaiPerkuliahan::where('id_kelas_kuliah', $this->kelas)
                        ->where('id_registrasi_mahasiswa', $mahasiswa_kelas->id_registrasi_mahasiswa)
                        ->update([
                            'feeder' => 0,
                            'nilai_angka' => $nilai_angka_,
                            'nilai_indeks' => $nilai_indeks,
                            'nilai_huruf' => $row['nilai_huruf'],
                        ]);
                } else {
                    NilaiPerkuliahan::create([
                        'feeder' => 0,
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
                        'nilai_angka' => $nilai_angka_,
                        'nilai_indeks' => $nilai_indeks,
                        'nilai_huruf' => $row['nilai_huruf'],
                    ]);
                }


            } catch (\Exception $e) {
                Log::error('Error processing row: ' . $e->getMessage());
            }
        }

        // Log::info('Finished import process');
    }

    private function getNilaiField($nomor_urut)
    {
        $fields = [
            1 => 'nilai_aktivitas_partisipatif',
            2 => 'nilai_hasil_proyek',
            3 => 'nilai_tugas',
            4 => 'nilai_kuis',
            5 => 'nilai_uts',
            6 => 'nilai_uas',
        ];

        return $fields[$nomor_urut] ?? null;
    }
}
