<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mahasiswa\KrsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route("login");
});

Auth::routes([
    'register' => false
]);

Route::group(['middleware' => ['auth']], function() {
    // route for mahasiswa
    Route::group(['middleware' => ['role:mahasiswa']], function() {
        Route::prefix('mahasiswa')->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Mahasiswa\DashboardController::class, 'index'])->name('mahasiswa.dashboard');
            Route::get('/biodata', [App\Http\Controllers\Mahasiswa\BiodataController::class, 'index'])->name('mahasiswa.biodata');

            Route::get('/kartu-rencana-studi', [App\Http\Controllers\Mahasiswa\KrsController::class, 'krs'])->name('mahasiswa.krs');
            Route::get('/ambil-krs', [App\Http\Controllers\Mahasiswa\AkademikController::class, 'create_krs'])->name('mahasiswa.create-krs');
            Route::post('/ambil-krs/{id_kelas_kuliah}', [KrsController::class, 'ambilKrs'])->name('ambil-krs');

            Route::get('/kartu-hasil-studi', [App\Http\Controllers\Mahasiswa\AkademikController::class, 'khs'])->name('mahasiswa.khs');
            Route::get('/transkrip-nilai', [App\Http\Controllers\Mahasiswa\AkademikController::class, 'transkrip'])->name('mahasiswa.transkrip');
            Route::get('/biaya-kuliah', [App\Http\Controllers\Mahasiswa\BiayaKuliahController::class, 'index'])->name('mahasiswa.biaya-kuliah');
            Route::get('/bahan-tugas', [App\Http\Controllers\Mahasiswa\BahanTugasController::class, 'index'])->name('mahasiswa.bahan-tugas');
            Route::get('/jadwal-presensi', [App\Http\Controllers\Mahasiswa\JadwalPresensiController::class, 'index'])->name('mahasiswa.jadwal-presensi');
            Route::get('/pa-online', [App\Http\Controllers\Mahasiswa\PAController::class, 'index'])->name('mahasiswa.pa-online');
            Route::get('/kuisioner', [App\Http\Controllers\Mahasiswa\KuisionerController::class, 'index'])->name('mahasiswa.kuisioner');
            Route::get('/nilai', [App\Http\Controllers\Mahasiswa\NilaiController::class, 'index'])->name('mahasiswa.nilai');
            Route::get('/skpi', [App\Http\Controllers\Mahasiswa\SKPIController::class, 'index'])->name('mahasiswa.skpi');
            Route::get('/kegiatan-akademik', [App\Http\Controllers\Mahasiswa\KegiatanController::class, 'akademik'])->name('mahasiswa.akademik');
            Route::get('/kegiatan-seminar', [App\Http\Controllers\Mahasiswa\KegiatanController::class, 'seminar'])->name('mahasiswa.seminar');
            Route::get('/pengajuan-cuti', [App\Http\Controllers\Mahasiswa\CutiController::class, 'index'])->name('mahasiswa.pengajuan-cuti');
        });
    });

    //route for dosen
    Route::group(['middleware' => ['role:dosen']], function() {
        Route::get('/dosen', [App\Http\Controllers\Dosen\DashboardController::class, 'index'])->name('dosen');
        Route::prefix('dosen')->group(function () {

            //Route Menu Utama
            Route::prefix('profile-dosen')->group(function () {
                Route::get('/biodata-dosen', [App\Http\Controllers\Dosen\BiodataDosenController::class, 'biodata_dosen'])->name('dosen.profile.biodata');
                // Route::get('/aktivitas-dosen', [App\Http\Controllers\Dosen\AktivitasDosenController::class, 'aktivitas_Dosen'])->name('dosen.profile.aktivitas');
                Route::get('/mengajar-dosen', [App\Http\Controllers\Dosen\MengajarDosenController::class, 'mengajar_dosen'])->name('dosen.profile.mengajar');
                Route::get('/riwayat-pendidikan-dosen', [App\Http\Controllers\Dosen\RiwayatPendidikanDosenController::class, 'riwayat_pendidikan_dosen'])->name('dosen.profile.riwayat_pendidikan');

                Route::prefix('aktivitas-dosen')->group(function () {
                    Route::get('/penelitian-dosen', [App\Http\Controllers\Dosen\AktivitasDosenController::class, 'penelitian_dosen'])->name('dosen.profile.aktivitas.penelitian');
                    Route::get('/publikasi-dosen', [App\Http\Controllers\Dosen\AktivitasDosenController::class, 'publikasi_dosen'])->name('dosen.profile.aktivitas.publikasi');
                    Route::get('/pengabdian-dosen', [App\Http\Controllers\Dosen\AktivitasDosenController::class, 'pengabdian_dosen'])->name('dosen.profile.aktivitas.pengabdian');
                });
            });

            Route::prefix('kalender-akademik')->group(function () {
                Route::get('/', [App\Http\Controllers\Dosen\KalenderAkademikController::class, 'kalender_akademik'])->name('dosen.kalender_akademik');
            });

            Route::prefix('pengumuman')->group(function () {
                Route::get('/', [App\Http\Controllers\Dosen\PengumumanController::class, 'pengumuman'])->name('dosen.pengumuman');
            });

            //Route Perkuliahan
            Route::prefix('perkuliahan')->group(function () {
                Route::get('/jadwal-kuliah', [App\Http\Controllers\Dosen\Perkuliahan\JadwalKuliahController::class, 'jadwal_kuliah'])->name('dosen.perkuliahan.jadwal-kuliah');
                Route::get('/jadwal-bimbingan', [App\Http\Controllers\Dosen\Perkuliahan\JadwalBimbinganController::class, 'jadwal_bimbingan'])->name('dosen.perkuliahan.jadwal-bimbingan');

                //Detail Fitur
                Route::get('/kesediaan-waktu-bimbingan', [App\Http\Controllers\Dosen\Perkuliahan\KesediaanWaktuDosenController::class, 'kesediaan_waktu_bimbingan'])->name('dosen.perkuliahan.kesediaan-waktu-bimbingan');
                Route::get('/kesediaan-waktu-kuliah', [App\Http\Controllers\Dosen\Perkuliahan\KesediaanWaktuDosenController::class, 'kesediaan_waktu_kuliah'])->name('dosen.perkuliahan.kesediaan-waktu-kuliah');
            });

            //Route Penilaian
            Route::prefix('penilaian')->group(function () {
                Route::get('/penilaian-perkuliahan', [App\Http\Controllers\Dosen\Penilaian\PenilaianPerkuliahanController::class, 'penilaian_perkuliahan'])->name('dosen.penilaian.penilaian-perkuliahan');
                Route::get('/penilaian-sidang', [App\Http\Controllers\Dosen\Penilaian\PenilaianSidangController::class, 'penilaian_sidang'])->name('dosen.penilaian.penilaian-sidang');

                //Detail Fitur
                Route::get('/presentase-penilaian-perkuliahan', [App\Http\Controllers\Dosen\Penilaian\PresentasePenilaianController::class, 'presentase_penilaian_perkuliahan'])->name('dosen.penilaian.presentase-penilaian-perkuliahan');
                // Route::get('/kesediaan-waktu-kuliah', [App\Http\Controllers\Dosen\Perkuliahan\KesediaanWaktuDosenController::class, 'kesediaan_waktu_kuliah'])->name('dosen.perkuliahan.kesediaan-waktu-kuliah');
            });

            //Route Pembimbing Mahasiswa
            Route::prefix('pembimbing')->group(function () {
                Route::get('/bimbingan-akademik', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'bimbingan_akademik'])->name('dosen.pembimbing.bimbingan-akademik');
                Route::get('/bimbingan-non-akademik', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'bimbingan_non_akademik'])->name('dosen.pembimbing.bimbingan-non-akademik');
                Route::get('/bimbingan-tugas-akhir', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'bimbingan_tugas_akhir'])->name('dosen.pembimbing.bimbingan-tugas-akhir');
            });

            //Route Bantuan
            Route::prefix('bantuan')->group(function () {
                Route::get('/ganti-password', [App\Http\Controllers\Dosen\Bantuan\GantiPasswordController::class, 'ganti_password'])->name('dosen.bantuan.ganti-password');
                Route::post('/proses-ganti-password', [App\Http\Controllers\Dosen\Bantuan\GantiPasswordController::class, 'proses_ganti_password'])->name('dosen.bantuan.proses-ganti-password');
            });
        });
    });

    Route::group(['middleware' => ['role:prodi']], function() {
        Route::get('/prodi', [App\Http\Controllers\Prodi\DashboardController::class, 'index'])->name('prodi');
        Route::prefix('prodi')->group(function() {
            //Route for Data Master
            Route::prefix('data-master')->group(function(){
                Route::get('/dosen', [App\Http\Controllers\Prodi\DataMasterController::class, 'dosen'])->name('prodi.data-master.dosen');
                Route::get('/mahasiswa', [App\Http\Controllers\Prodi\DataMasterController::class, 'mahasiswa'])->name('prodi.data-master.mahasiswa');
                Route::get('/mata-kuliah', [App\Http\Controllers\Prodi\DataMasterController::class, 'matkul'])->name('prodi.data-master.mata-kuliah');

                //Ruang Perkuliahan
                Route::get('/ruang-perkuliahan', [App\Http\Controllers\Prodi\DataMasterController::class, 'ruang_perkuliahan'])->name('prodi.data-master.ruang-perkuliahan');
                Route::post('/ruang-perkuliahan', [App\Http\Controllers\Prodi\DataMasterController::class, 'ruang_perkuliahan_store'])->name('prodi.data-master.ruang-perkuliahan.store');
                Route::patch('/ruang-perkuliahan/{ruang_perkuliahan}/update', [App\Http\Controllers\Prodi\DataMasterController::class, 'ruang_perkuliahan_update'])->name('prodi.data-master.ruang-perkuliahan.update');
                Route::delete('/ruang-perkuliahan/{ruang_perkuliahan}/delete', [App\Http\Controllers\Prodi\DataMasterController::class, 'ruang_perkuliahan_destroy'])->name('prodi.data-master.ruang-perkuliahan.delete');

                Route::get('/kurikulum', [App\Http\Controllers\Prodi\DataMasterController::class, 'kurikulum'])->name('prodi.data-master.kurikulum');
            });

            //Route for Data Akademik
            Route::prefix('data-akademik')->group(function(){
                //Kelas Penjadwalan
                Route::get('/kelas-penjadwalan', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'kelas_penjadwalan'])->name('prodi.data-akademik.kelas-penjadwalan');
                Route::get('/get-mata-kuliah', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'get_matkul'])->name('prodi.data-akademik.kelas-penjadwalan.get-matkul');
                Route::get('/kelas-penjadwalan-tambah', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'tambah_kelas_penjadwalan'])->name('prodi.data-akademik.kelas-penjadwalan.tambah');
                Route::post('/kelas-penjadwalan-store', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'kelas_penjadwalan_store'])->name('prodi.data-akademik.kelas-penjadwalan.store');

                Route::get('/khs', [App\Http\Controllers\Prodi\Akademik\KHSController::class, 'khs'])->name('prodi.data-akademik.khs');
                Route::get('/krs', [App\Http\Controllers\Prodi\Akademik\KRSController::class, 'krs'])->name('prodi.data-akademik.krs');
                Route::get('/sidang-mahasiswa', [App\Http\Controllers\Prodi\Akademik\SidangMahasiswaController::class, 'sidang_mahasiswa'])->name('prodi.data-akademik.sidang-mahasiswa');
                Route::get('/transkrip-mahasiswa', [App\Http\Controllers\Prodi\Akademik\TranskripMahasiswaController::class, 'transkrip_mahasiswa'])->name('prodi.data-akademik.transkrip-mahasiswa');
                Route::get('/yudisium-mahasiswa', [App\Http\Controllers\Prodi\Akademik\YudisiumMahasiswaController::class, 'yudisium_mahasiswa'])->name('prodi.data-akademik.yudisium-mahasiswa');
            });


            //Route for Data Aktivitas
            Route::prefix('data-aktivitas')->group(function(){
                Route::get('/aktivitas-penelitian', [App\Http\Controllers\Prodi\Aktivitas\AktivitasMahasiswaController::class, 'aktivitas_penelitian'])->name('prodi.data-aktivitas.aktivitas-penelitian');
                Route::get('/aktivitias-lomba', [App\Http\Controllers\Prodi\Aktivitas\AktivitasMahasiswaController::class, 'aktivitas_lomba'])->name('prodi.data-aktivitas.aktivitas-lomba');
                Route::get('/aktivitas-organisasi', [App\Http\Controllers\Prodi\Aktivitas\AktivitasMahasiswaController::class, 'aktivitas_organisasi'])->name('prodi.data-aktivitas.aktivitas-organisasi');
            });

            //Route for Report
            Route::prefix('report')->group(function(){
                Route::get('/kemahasiswaan', [App\Http\Controllers\Prodi\Report\ReportKemahasiswaanController::class, 'index'])->name('prodi.report.kemahasiswaan');
                Route::get('/mahasiswa-aktif', [App\Http\Controllers\Prodi\Report\ReportMahasiswaAktifController::class, 'index'])->name('prodi.report.mahasiswa-aktif');
                Route::get('/perkuliahan', [App\Http\Controllers\Prodi\Report\ReportPerkuliahanMahasiswaController::class, 'index'])->name('prodi.report.perkuliahan');
                Route::get('/aktivitas-mahasiswa', [App\Http\Controllers\Prodi\Report\ReportAktivitasMahasiswaController::class, 'index'])->name('prodi.report.aktivitas-mahasiswa');
            });

            //Route for Monitoring
            Route::prefix('monitoring')->group(function(){
                Route::get('/entry-nilai', [App\Http\Controllers\Prodi\Monitoring\MonitoringDosenController::class, 'monitoring_nilai'])->name('prodi.monitoring.entry-nilai');
                Route::get('/pengajaran-dosen', [App\Http\Controllers\Prodi\Monitoring\MonitoringDosenController::class, 'monitoring_pengajaran'])->name('prodi.monitoring.pengajaran-dosen');
            });

            //Route Bantuan
            Route::prefix('bantuan')->group(function () {
                Route::get('/ganti-password', [App\Http\Controllers\Prodi\Bantuan\GantiPasswordController::class, 'ganti_password'])->name('prodi.bantuan.ganti-password');
                Route::post('/proses-ganti-password', [App\Http\Controllers\Prodi\Bantuan\GantiPasswordController::class, 'proses_ganti_password'])->name('prodi.bantuan.proses-ganti-password');
            });
        });

    });

    Route::group(['middleware' => ['role:fakultas']], function() {
        Route::get('/fakultas', [App\Http\Controllers\Fakultas\DashboardController::class, 'index'])->name('fakultas');

    });

    Route::group(['middleware' => ['role:univ']], function() {
        Route::get('/universitas', [App\Http\Controllers\Universitas\DashboardController::class, 'index'])->name('univ');
        Route::prefix('universitas')->group(function () {

            Route::prefix('mahasiswa')->group(function () {
                Route::get('/', [App\Http\Controllers\Universitas\MahasiswaController::class, 'daftar_mahasiswa'])->name('univ.mahasiswa');
                Route::get('/data', [App\Http\Controllers\Universitas\MahasiswaController::class, 'daftar_mahasiswa_data'])->name('univ.mahasiswa.data');
                Route::get('/sync-mahasiswa', [App\Http\Controllers\Universitas\MahasiswaController::class, 'sync_mahasiswa'])->name('univ.mahasiswa.sync');
            });

            Route::prefix('dosen')->group(function () {
                Route::get('/', [App\Http\Controllers\Universitas\DosenController::class, 'dosen'])->name('univ.dosen');
                // Route::get('/data', [App\Http\Controllers\Universitas\DosenController::class, 'daftar_dosen_data'])->name('univ.dosen.data');
                Route::get('/sync-dosen', [App\Http\Controllers\Universitas\DosenController::class, 'sync_dosen'])->name('univ.dosen.sync');
                Route::get('/sync-penugasan', [App\Http\Controllers\Universitas\DosenController::class, 'sync_penugasan_dosen'])->name('univ.dosen.sync-penugasan');
            });

            Route::prefix('referensi')->group(function () {
                Route::get('/prodi', [App\Http\Controllers\Universitas\ReferensiController::class, 'prodi'])->name('univ.referensi.prodi');
                Route::get('/sync-prodi', [App\Http\Controllers\Universitas\ReferensiController::class, 'sync_prodi'])->name('univ.referensi.prodi.sync');
                Route::get('/sync-referensi', [App\Http\Controllers\Universitas\ReferensiController::class, 'sync_referensi'])->name('univ.referensi.sync');
            });

            Route::prefix('kurikulum')->group(function () {
                Route::get('/', [App\Http\Controllers\Universitas\KurikulumController::class, 'index'])->name('univ.kurikulum');
                Route::get('/detail-kurikulum/{kurikulum}', [App\Http\Controllers\Universitas\KurikulumController::class, 'detail_kurikulum'])->name('univ.kurikulum.detail');
                Route::get('/sync-kurikulum', [App\Http\Controllers\Universitas\KurikulumController::class, 'sync_kurikulum'])->name('univ.kurikulum.sync');
            });

            Route::prefix('mata-kuliah')->group(function () {
                Route::get('/', [App\Http\Controllers\Universitas\KurikulumController::class, 'matkul'])->name('univ.mata-kuliah');
                Route::get('/data', [App\Http\Controllers\Universitas\KurikulumController::class, 'matkul_data'])->name('univ.mata-kuliah.data');
                Route::get('/sync-mata-kuliah', [App\Http\Controllers\Universitas\KurikulumController::class, 'sync_mata_kuliah'])->name('univ.mata-kuliah.sync');
                Route::get('/sync-rencana', [App\Http\Controllers\Universitas\KurikulumController::class, 'sync_rencana'])->name('univ.mata-kuliah.sync-rencana');
            });

            Route::prefix('perkuliahan')->group(function () {
                Route::get('/nilai-perkuliahan', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'nilai_perkuliahan'])->name('univ.perkuliahan.nilai-perkuliahan');
                Route::get('/nilai-perkuliahan/sync', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_nilai_perkuliahan'])->name('univ.perkuliahan.nilai-perkuliahan.sync');

                Route::get('/kelas-kuliah', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'kelas_kuliah'])->name('univ.perkuliahan.kelas-kuliah');
                Route::get('/kelas-kuliah/data', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'kelas_data'])->name('univ.perkuliahan.kelas-kuliah.data');
                Route::get('/kelas-kuliah/sync', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_kelas_kuliah'])->name('univ.perkuliahan.kelas-kuliah.sync');
                Route::get('/kelas-kuliah/sync-pengajar-kelas', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_pengajar_kelas'])->name('univ.perkuliahan.kelas-kuliah.sync-pengajar-kelas');
                Route::get('/kelas-kuliah/sync-peserta-kelas', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_peserta_kelas'])->name('univ.perkuliahan.kelas-kuliah.sync-peserta-kelas');

                Route::get('/aktivitas-kuliah', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'aktivitas_kuliah'])->name('univ.perkuliahan.aktivitas-kuliah');
                Route::get('/aktivitas-kuliah/sync', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_aktivitas_kuliah'])->name('univ.perkuliahan.aktivitas-kuliah.sync');

                Route::get('/aktivitas-mahasiswa', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'aktivitas_mahasiswa'])->name('univ.perkuliahan.aktivitas-mahasiswa');
                Route::get('/aktivitas-mahasiswa/sync', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_aktivitas_mahasiswa'])->name('univ.perkuliahan.aktivitas-mahasiswa.sync');
                Route::get('/aktivitas-mahasiswa/sync-anggota', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_anggota_aktivitas_mahasiswa'])->name('univ.perkuliahan.aktivitas-mahasiswa.sync-anggota');
            });
        });
    });
});

