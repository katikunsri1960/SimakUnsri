<?php

namespace App\Console\Commands;

use App\Models\Dosen\BiodataDosen;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\KomponenEvaluasiKelas;
use App\Models\Perkuliahan\NilaiKomponenEvaluasi;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\NilaiPerkuliahan;
use App\Models\ProgramStudi;
use App\Models\SemesterAktif;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class GenerateNilaiLewatMasaPengisian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-nilai-lewat-masa-pengisian';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');

        // $semester_aktif = SemesterAktif::first();

        $semester_aktif = ['id_semester' => '20252', 'nama_semester' => '2025/2026 Genap'];

        if (!$semester_aktif) {
            $this->info('Semester aktif tidak ditemukan');
            return;
        }

        // $prodi = ProgramStudi::where('status', 'A')
        //         ->whereHas('kelas_kuliah', function ($query) use ($semester_aktif) {
        //             $query->where('id_semester', $semester_aktif['id_semester'])
        //                 ->whereHas('peserta_kelas_approved')
        //                 ->whereDoesntHave('komponen_evaluasi')
        //                 ->whereDoesntHave('nilai_komponen')
        //                 ->whereDoesntHave('nilai_perkuliahan');
        //         })
        //         ->get();

        $prodi = ProgramStudi::where('status', 'A')
                ->whereHas('kelas_kuliah', function ($query) use ($semester_aktif) {
                    $query->where('id_semester', $semester_aktif['id_semester'])
                        ->whereHas('peserta_kelas_approved', function ($q) {
                            $q->whereNotExists(function ($sub) {
                                $sub->select(DB::raw(1))
                                    ->from('nilai_perkuliahans')
                                    ->whereColumn('nilai_perkuliahans.id_kelas_kuliah', 'peserta_kelas_kuliahs.id_kelas_kuliah')
                                    ->whereColumn('nilai_perkuliahans.id_registrasi_mahasiswa', 'peserta_kelas_kuliahs.id_registrasi_mahasiswa');
                            });
                        });
                })
                ->get();

        $this->info('Jumlah prodi ditemukan: ' . $prodi->count());
        $this->info('Semester aktif dipakai: ' . $semester_aktif['id_semester']);
        $this->info('Database connection: ' . DB::connection()->getDatabaseName());

        if ($prodi->isEmpty()) {
            $this->info('Tidak ada prodi yang match kondisi.');
            return;
        }

        foreach ($prodi as $p) {
            $proses = $this->proses_nilai($p->id_prodi, $semester_aktif['id_semester'], $semester_aktif['nama_semester']);

            $this->info('Prodi: '.$p->nama_jenjang_pendidikan.' '.$p->nama_program_studi);
            $this->info('Kelas Kuliah Diproses: '.$proses['kelas_kuliah']);
            $this->info('Komponen Evaluasi Diproses: '.$proses['komponen_evaluasi']);
            $this->info('Nilai Komponen Diproses: '.$proses['nilai_komponen']);
            $this->info('Nilai Perkuliahan Diproses: '.$proses['nilai_perkuliahan']);
            $this->info('----------------------------------------');

            // return;
        }

    }

    private function proses_nilai($prodi, $semester, $nama_semester)
    {
        $kelas_kuliah = KelasKuliah::with(['peserta_kelas_approved', 'matkul', 'komponen_evaluasi'])
                    ->whereHas('peserta_kelas_approved')
                    ->where('id_prodi', $prodi)
                    ->where('id_semester', $semester)
                    ->get();

        $nilaiAngka = 86;
        $nilaiIndeks = 4.00;
        $nilaiHuruf = 'A';

        $kelas_kuliah_proses = 0;
        $komponen_evaluasi_proses = 0;
        $nilai_komponen_proses = 0;
        $nilai_perkuliahan_proses = 0;

        foreach ($kelas_kuliah as $k) {

            DB::beginTransaction();

            // 1. Komponen evaluasi: reuse kalau sudah ada, generate kalau belum
            if ($k->komponen_evaluasi->isEmpty()) {
                $bobot_participatory = 10 / 100;
                $bobot_project = 20 / 100;
                $bobot_assignment = 15 / 100;
                $bobot_quiz = 15 / 100;
                $bobot_midterm = 20 / 100;
                $bobot_finalterm = 20 / 100;

                $komponen_evaluasi = collect([
                    KomponenEvaluasiKelas::create(['feeder'=>0,'id_komponen_evaluasi'=>Uuid::uuid4()->toString(),'id_kelas_kuliah'=>$k->id_kelas_kuliah,'id_jenis_evaluasi'=>2,'nama'=>'-','nama_inggris'=>'Participatory Activity','nomor_urut'=>1,'bobot_evaluasi'=>$bobot_participatory]),
                    KomponenEvaluasiKelas::create(['feeder'=>0,'id_komponen_evaluasi'=>Uuid::uuid4()->toString(),'id_kelas_kuliah'=>$k->id_kelas_kuliah,'id_jenis_evaluasi'=>3,'nama'=>'-','nama_inggris'=>'Project Outcomes','nomor_urut'=>2,'bobot_evaluasi'=>$bobot_project]),
                    KomponenEvaluasiKelas::create(['feeder'=>0,'id_komponen_evaluasi'=>Uuid::uuid4()->toString(),'id_kelas_kuliah'=>$k->id_kelas_kuliah,'id_jenis_evaluasi'=>4,'nama'=>'TGS','nama_inggris'=>'Assignment','nomor_urut'=>3,'bobot_evaluasi'=>$bobot_assignment]),
                    KomponenEvaluasiKelas::create(['feeder'=>0,'id_komponen_evaluasi'=>Uuid::uuid4()->toString(),'id_kelas_kuliah'=>$k->id_kelas_kuliah,'id_jenis_evaluasi'=>4,'nama'=>'QIZ','nama_inggris'=>'Quiz','nomor_urut'=>4,'bobot_evaluasi'=>$bobot_quiz]),
                    KomponenEvaluasiKelas::create(['feeder'=>0,'id_komponen_evaluasi'=>Uuid::uuid4()->toString(),'id_kelas_kuliah'=>$k->id_kelas_kuliah,'id_jenis_evaluasi'=>4,'nama'=>'UTS','nama_inggris'=>'Midterm Exam','nomor_urut'=>5,'bobot_evaluasi'=>$bobot_midterm]),
                    KomponenEvaluasiKelas::create(['feeder'=>0,'id_komponen_evaluasi'=>Uuid::uuid4()->toString(),'id_kelas_kuliah'=>$k->id_kelas_kuliah,'id_jenis_evaluasi'=>4,'nama'=>'UAS','nama_inggris'=>'Finalterm Exam','nomor_urut'=>6,'bobot_evaluasi'=>$bobot_finalterm]),
                ]);

                $komponen_evaluasi_proses += $komponen_evaluasi->count();
            } else {
                $komponen_evaluasi = $k->komponen_evaluasi;
            }

            $mahasiswa_kelas = $k->peserta_kelas_approved;

            // 2. Pre-fetch mahasiswa yang SUDAH punya nilai_perkuliahan di kelas ini
            $sudahAdaNilaiPerkuliahan = NilaiPerkuliahan::where('id_kelas_kuliah', $k->id_kelas_kuliah)
                ->pluck('id_registrasi_mahasiswa')
                ->toArray();

            // 3. Pre-fetch kombinasi (id_komponen_evaluasi + id_registrasi_mahasiswa) yang sudah ada
            $idKomponenList = $komponen_evaluasi->pluck('id_komponen_evaluasi')->toArray();
            $sudahAdaNilaiKomponen = NilaiKomponenEvaluasi::whereIn('id_komponen_evaluasi', $idKomponenList)
                ->get(['id_komponen_evaluasi', 'id_registrasi_mahasiswa'])
                ->map(fn($row) => $row->id_komponen_evaluasi . '|' . $row->id_registrasi_mahasiswa)
                ->toArray();

            foreach ($mahasiswa_kelas as $mk) {

                $sudahPunyaNilaiPerkuliahan = in_array($mk->id_registrasi_mahasiswa, $sudahAdaNilaiPerkuliahan);

                foreach ($komponen_evaluasi as $komponen) {
                    $key = $komponen->id_komponen_evaluasi . '|' . $mk->id_registrasi_mahasiswa;

                    if (in_array($key, $sudahAdaNilaiKomponen)) {
                        continue; // mahasiswa ini sudah punya nilai komponen ini
                    }

                    NilaiKomponenEvaluasi::create([
                        'feeder' => 0,
                        'id_komponen_evaluasi' => $komponen->id_komponen_evaluasi,
                        'id_kelas' => $k->id_kelas_kuliah,
                        'id_registrasi_mahasiswa' => $mk->id_registrasi_mahasiswa,
                        'urutan' => $komponen->nomor_urut,
                        'id_prodi' => $mk->id_prodi,
                        'nama_program_studi' => $mk->nama_program_studi,
                        'id_periode' => $k->id_semester,
                        'id_matkul' => $mk->id_matkul,
                        'nama_mata_kuliah' => $mk->nama_mata_kuliah,
                        'nama_kelas_kuliah' => $k->nama_kelas_kuliah,
                        'sks_mata_kuliah' => $k->matkul->sks_mata_kuliah,
                        'nama_mahasiswa' => $mk->nama_mahasiswa,
                        'nim' => $mk->nim,
                        'id_jns_eval' => $komponen->id_jenis_evaluasi,
                        'nama' => $komponen->nama,
                        'nama_inggris' => $komponen->nama_inggris,
                        'bobot' => $komponen->bobot_evaluasi,
                        'angkatan' => $mk->angkatan,
                        'status_sync' => 'belum sync',
                        'nilai_komp_eval' => $nilaiAngka,
                    ]);

                    $nilai_komponen_proses++;
                }

                if (!$sudahPunyaNilaiPerkuliahan) {
                    NilaiPerkuliahan::create([
                        'feeder' => 0,
                        'id_prodi' => $mk->id_prodi,
                        'nama_program_studi' => $mk->nama_program_studi,
                        'id_semester' => $semester,
                        'nama_semester' => $nama_semester,
                        'id_matkul' => $k->matkul->id_matkul,
                        'kode_mata_kuliah' => $k->matkul->kode_mata_kuliah,
                        'nama_mata_kuliah' => $k->matkul->nama_mata_kuliah,
                        'sks_mata_kuliah' => $k->matkul->sks_mata_kuliah,
                        'id_kelas_kuliah' => $k->id_kelas_kuliah,
                        'nama_kelas_kuliah' => $k->nama_kelas_kuliah,
                        'id_registrasi_mahasiswa' => $mk->id_registrasi_mahasiswa,
                        'id_mahasiswa' => $mk->id_mahasiswa,
                        'nim' => $mk->nim,
                        'nama_mahasiswa' => $mk->nama_mahasiswa,
                        'jurusan' => $mk->nama_program_studi,
                        'angkatan' => $mk->angkatan,
                        'nilai_angka' => $nilaiAngka,
                        'nilai_indeks' => $nilaiIndeks,
                        'nilai_huruf' => $nilaiHuruf,
                    ]);

                    $nilai_perkuliahan_proses++;
                }
            }

            DB::commit();
            $kelas_kuliah_proses++;
        }

        return [
            'kelas_kuliah' => $kelas_kuliah_proses,
            'komponen_evaluasi' => $komponen_evaluasi_proses,
            'nilai_komponen' => $nilai_komponen_proses,
            'nilai_perkuliahan' => $nilai_perkuliahan_proses
        ];
    }

    // private function proses_nilai($prodi, $semester, $nama_semester)
    // {
    //     $kelas_kuliah = KelasKuliah::with('peserta_kelas_approved','matkul')
    //                 ->whereHas('peserta_kelas_approved')
    //                 ->whereDoesntHave('komponen_evaluasi')
    //                 ->whereDoesntHave('nilai_komponen')
    //                 ->whereDoesntHave('nilai_perkuliahan')
    //                 ->where('id_prodi', $prodi)
    //                 ->where('id_semester', $semester)
    //                 ->get();

    //     // Set Nilai default untuk mahasiswa
    //     $nilaiAngka = 86;
    //     $nilaiIndeks = 4.00;
    //     $nilaiHuruf = 'A';

    //     $kelas_kuliah_proses = 0;
    //     $komponen_evaluasi_proses = 0;
    //     $nilai_komponen_proses = 0;
    //     $nilai_perkuliahan_proses = 0;

    //     foreach ($kelas_kuliah as $k) {

    //         //Generate id aktivitas mengajar
    //         $id_komp_eval1 = Uuid::uuid4()->toString();
    //         $id_komp_eval2 = Uuid::uuid4()->toString();
    //         $id_komp_eval3 = Uuid::uuid4()->toString();
    //         $id_komp_eval4 = Uuid::uuid4()->toString();
    //         $id_komp_eval5 = Uuid::uuid4()->toString();
    //         $id_komp_eval6 = Uuid::uuid4()->toString();

    //         //Penyesuaian format bobot komponen evaluasi
    //         $bobot_participatory = 10/100;
    //         $bobot_project = 20/100;
    //         $bobot_assignment = 15/100;
    //         $bobot_quiz = 15/100;
    //         $bobot_midterm = 20/100;
    //         $bobot_finalterm = 20/100;

    //         DB::beginTransaction();

    //         // Getting Komponen Evaluasi Kelas
    //         $komponen_evaluasi = [];

    //         //Store data participatory
    //         $komponen_evaluasi[] = KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval1, 'id_kelas_kuliah'=> $k->id_kelas_kuliah, 'id_jenis_evaluasi'=> 2,  'nama'=> '-', 'nama_inggris'=> 'Participatory Activity', 'nomor_urut'=> 1, 'bobot_evaluasi'=> $bobot_participatory]);

    //         //Store data project
    //         $komponen_evaluasi[] = KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval2, 'id_kelas_kuliah'=> $k->id_kelas_kuliah, 'id_jenis_evaluasi'=> 3,  'nama'=> '-', 'nama_inggris'=> 'Project Outcomes', 'nomor_urut'=> 2, 'bobot_evaluasi'=> $bobot_project]);

    //         //Store data assignment
    //         $komponen_evaluasi[] = KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval3, 'id_kelas_kuliah'=> $k->id_kelas_kuliah, 'id_jenis_evaluasi'=> 4,  'nama'=> 'TGS', 'nama_inggris'=> 'Assignment', 'nomor_urut'=> 3, 'bobot_evaluasi'=> $bobot_assignment]);

    //         //Store data quiz
    //         $komponen_evaluasi[] = KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval4, 'id_kelas_kuliah'=> $k->id_kelas_kuliah, 'id_jenis_evaluasi'=> 4,  'nama'=> 'QIZ', 'nama_inggris'=> 'Quiz', 'nomor_urut'=> 4, 'bobot_evaluasi'=> $bobot_quiz]);

    //         //Store data midterm
    //         $komponen_evaluasi[] = KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval5, 'id_kelas_kuliah'=> $k->id_kelas_kuliah, 'id_jenis_evaluasi'=> 4,  'nama'=> 'UTS', 'nama_inggris'=> 'Midterm Exam', 'nomor_urut'=> 5, 'bobot_evaluasi'=> $bobot_midterm]);

    //         //Store data finalterm
    //         $komponen_evaluasi[] = KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval6, 'id_kelas_kuliah'=> $k->id_kelas_kuliah, 'id_jenis_evaluasi'=> 4,  'nama'=> 'UAS', 'nama_inggris'=> 'Finalterm Exam', 'nomor_urut'=> 6, 'bobot_evaluasi'=> $bobot_finalterm]);

    //         $komponen_evaluasi_proses += 6;


    //         //Getting Mahasiswa Kelas Kuliah
    //         $mahasiswa_kelas = $k->peserta_kelas_approved;

    //         foreach ($mahasiswa_kelas as $mk) {

    //             foreach ($komponen_evaluasi as $komponen) {

    //                 NilaiKomponenEvaluasi::create([
    //                     'feeder' => 0,
    //                     'id_komponen_evaluasi' => $komponen->id_komponen_evaluasi, 
    //                     'id_kelas' => $k->id_kelas_kuliah, 
    //                     'id_registrasi_mahasiswa' => $mk->id_registrasi_mahasiswa, 
    //                     'urutan' => $komponen->nomor_urut,
    //                     'id_prodi' => $mk->id_prodi,
    //                     'nama_program_studi' => $mk->nama_program_studi,
    //                     'id_periode' => $k->id_semester,
    //                     'id_matkul' => $mk->id_matkul,
    //                     'nama_mata_kuliah' => $mk->nama_mata_kuliah,
    //                     'nama_kelas_kuliah' => $k->nama_kelas_kuliah,
    //                     'sks_mata_kuliah' => $k->matkul->sks_mata_kuliah,
    //                     'nama_mahasiswa' => $mk->nama_mahasiswa,
    //                     'nim' => $mk->nim,
    //                     'id_jns_eval' => $komponen->id_jenis_evaluasi,
    //                     'nama' => $komponen->nama,
    //                     'nama_inggris' => $komponen->nama_inggris,
    //                     'bobot' => $komponen->bobot_evaluasi,
    //                     'angkatan' => $mk->angkatan,
    //                     'status_sync' => 'belum sync',
    //                     'nilai_komp_eval' => $nilaiAngka,
    //                 ]);

    //                 $nilai_komponen_proses++;

    //             }

    //             NilaiPerkuliahan::create([
    //                 'feeder' => 0,
    //                 'id_prodi' => $mk->id_prodi,
    //                 'nama_program_studi' => $mk->nama_program_studi,
    //                 'id_semester' => $semester,
    //                 'nama_semester' => $nama_semester,
    //                 'id_matkul' => $k->matkul->id_matkul,
    //                 'kode_mata_kuliah' => $k->matkul->kode_mata_kuliah,
    //                 'nama_mata_kuliah' => $k->matkul->nama_mata_kuliah,
    //                 'sks_mata_kuliah' => $k->matkul->sks_mata_kuliah,
    //                 'id_kelas_kuliah' => $k->id_kelas_kuliah,
    //                 'nama_kelas_kuliah' => $k->nama_kelas_kuliah,
    //                 'id_registrasi_mahasiswa' => $mk->id_registrasi_mahasiswa,
    //                 'id_mahasiswa' => $mk->id_mahasiswa,
    //                 'nim' => $mk->nim,
    //                 'nama_mahasiswa' => $mk->nama_mahasiswa,
    //                 'jurusan' => $mk->nama_program_studi,
    //                 'angkatan' => $mk->angkatan,
    //                 'nilai_angka' => $nilaiAngka,
    //                 'nilai_indeks' => $nilaiIndeks,
    //                 'nilai_huruf' => $nilaiHuruf,
    //             ]);

    //             $nilai_perkuliahan_proses++;

    //         }

    //         DB::commit();

    //         $kelas_kuliah_proses++;
    //     }

    //     return [
    //         'kelas_kuliah' => $kelas_kuliah_proses,
    //         'komponen_evaluasi' => $komponen_evaluasi_proses,
    //         'nilai_komponen' => $nilai_komponen_proses,
    //         'nilai_perkuliahan' => $nilai_perkuliahan_proses
    //     ];
    // }

}
