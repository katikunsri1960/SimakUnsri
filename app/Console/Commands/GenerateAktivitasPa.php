<?php

namespace App\Console\Commands;

use App\Models\Dosen\BiodataDosen;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\ProgramStudi;
use App\Models\SemesterAktif;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class GenerateAktivitasPa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-aktivitas-pa';

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

        $semester_aktif = SemesterAktif::first();

        if (! $semester_aktif) {
            $this->info('Semester aktif tidak ditemukan');

            return;
        }

        $id_semester = $semester_aktif->id_semester;
        $year_part = substr($id_semester, 0, 4);

        $next_year = (int) $year_part + 1;

        if (substr($id_semester, -1) == '1') {
            $tanggal_mulai = "$year_part-08-01";
            $tanggal_akhir = "$year_part-12-31";
        } else {
            $tanggal_mulai = "$next_year-01-01";
            $tanggal_akhir = "$next_year-07-31";
        }

        $prodi = ProgramStudi::where('status', 'A')->get();

        foreach ($prodi as $p) {
            $proses = $this->proses_pa($p->id_prodi, $id_semester, $tanggal_mulai, $tanggal_akhir);

            $this->info('Prodi: '.$p->nama_jenjang_pendidikan.' '.$p->nama_program_studi);
            $this->info('Aktivitas Diproses: '.$proses['aktivitas']);
            $this->info('Anggota Diproses: '.$proses['anggota']);

            // return;
        }

    }

    private function proses_pa($prodi, $semester, $tanggal_mulai, $tanggal_akhir)
    {

        $judul = 'Pembimbing Akademik '; // + nama dosen
        $id_jenis_aktivitas = 7;
        $nama_jenis_aktivitas = 'Bimbingan akademis';
        $jenis_anggota = 1;
        $nama_jenis_anggota = 'Kelompok';

        $dosen_pa = RiwayatPendidikan::whereNull('id_jenis_keluar')
            ->whereNotNull('dosen_pa')
            ->where('id_prodi', $prodi)
            ->select('dosen_pa')
            ->distinct()
            ->get();

        $anggota_proses = 0;
        $aktivitas_proses = 0;
        // info bimbing akademis table bimbing mahasiswa
        $id_kategori_kegiatan = 110601;
        $nama_kategori_kegiatan = 'Melakukan pembinaan kegiatan mahasiswa di bidang akademik (PA) dan kemahasiswaan (BEM, Maperwa, dan lain-lain)';

        foreach ($dosen_pa as $d) {
            $bimbing = BimbingMahasiswa::join('aktivitas_mahasiswas as am', 'am.id_aktivitas', 'bimbing_mahasiswas.id_aktivitas')
                ->where('bimbing_mahasiswas.id_dosen', $d->dosen_pa)
                ->where('am.id_semester', $semester)
                ->where('am.id_prodi', $prodi)
                ->where('am.id_jenis_aktivitas', $id_jenis_aktivitas)
                ->first();

            DB::beginTransaction();

            if (! $bimbing) {
                $uuid = Uuid::uuid4()->toString();
                $dosen = BiodataDosen::where('id_dosen', $d->dosen_pa)->select('nama_dosen', 'id_dosen', 'nidn', 'nama_dosen')->first();
                $nama_dosen = $dosen->nama_dosen;

                $aktivitas = AktivitasMahasiswa::create([
                    'approve_krs' => 1,
                    'feeder' => 0,
                    'id_aktivitas' => $uuid,
                    'program_mbkm' => 0,
                    'nama_program_mbkm' => 'Mandiri',
                    'jenis_anggota' => $jenis_anggota,
                    'nama_jenis_anggota' => $nama_jenis_anggota,
                    'id_jenis_aktivitas' => $id_jenis_aktivitas,
                    'nama_jenis_aktivitas' => $nama_jenis_aktivitas,
                    'id_prodi' => $prodi,
                    'id_semester' => $semester,
                    'judul' => $judul.$nama_dosen,
                    'tanggal_mulai' => $tanggal_mulai,
                    'tanggal_selesai' => $tanggal_akhir,
                    'untuk_kampus_merdeka' => 1,
                    'asal_data' => 9,
                ]);

                $uuidBimbing = Uuid::uuid4()->toString();

                $createBimbing = BimbingMahasiswa::create([
                    'feeder' => 0,
                    'approved' => 1,
                    'approved_dosen' => 1,
                    'id_bimbing_mahasiswa' => $uuidBimbing,
                    'id_aktivitas' => $aktivitas->id_aktivitas,
                    'judul' => $aktivitas->judul,
                    'id_kategori_kegiatan' => $id_kategori_kegiatan,
                    'nama_kategori_kegiatan' => $nama_kategori_kegiatan,
                    'id_dosen' => $dosen->id_dosen,
                    'nidn' => $dosen->nidn,
                    'nama_dosen' => $dosen->nama_dosen,
                    'pembimbing_ke' => 1,
                ]);

                $aktivitas_proses++;

            } else {
                $aktivitas = AktivitasMahasiswa::where('id_aktivitas', $bimbing->id_aktivitas)->first();
            }

            $mahasiswa = RiwayatPendidikan::where('dosen_pa', $d->dosen_pa)
                ->whereNull('id_jenis_keluar')
                ->where('id_prodi', $prodi)
                ->get();

            foreach ($mahasiswa as $m) {
                $check = AnggotaAktivitasMahasiswa::where('id_registrasi_mahasiswa', $m->id_registrasi_mahasiswa)
                    ->where('id_aktivitas', $aktivitas->id_aktivitas)
                    ->first();

                if (! $check) {
                    $uuidAnggota = Uuid::uuid4()->toString();
                    $anggota = AnggotaAktivitasMahasiswa::create([
                        'feeder' => 0,
                        'id_anggota' => $uuidAnggota,
                        'id_aktivitas' => $aktivitas->id_aktivitas,
                        'judul' => $aktivitas->judul,
                        'id_registrasi_mahasiswa' => $m->id_registrasi_mahasiswa,
                        'nim' => $m->nim,
                        'nama_mahasiswa' => $m->nama_mahasiswa,
                        'jenis_peran' => '2',
                        'nama_jenis_peran' => 'Anggota',
                        'status_sync' => 'belum sync',
                    ]);

                    $anggota_proses++;
                }
            }

            DB::commit();

        }

        return [
            'aktivitas' => $aktivitas_proses,
            'anggota' => $anggota_proses,
        ];

    }
}
