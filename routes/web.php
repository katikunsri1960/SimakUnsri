<?php

use Illuminate\Support\Facades\Route;

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
            Route::get('/biodata', [App\Http\Controllers\Mahasiswa\BiodataController::class, 'index_rev'])->name('mahasiswa.biodata');

            Route::get('/krs', [App\Http\Controllers\Mahasiswa\KrsController::class, 'index'])->name('mahasiswa.krs');
            Route::prefix('krs')->group(function () {
                Route::get('/get-kelas-kuliah', [App\Http\Controllers\Mahasiswa\KrsController::class, 'get_kelas_kuliah'])->name('mahasiswa.krs.get_kelas_kuliah');
                Route::post('/store-kelas-kuliah', [App\Http\Controllers\Mahasiswa\KrsController::class, 'ambilKelasKuliah'])->name('mahasiswa.krs.store_kelas_kuliah');
                Route::post('/update-kelas-kuliah', [App\Http\Controllers\Mahasiswa\KrsController::class, 'update_kelas_kuliah'])->name('mahasiswa.krs.update_kelas_kuliah');
                Route::delete('/{pesertaKelas}/hapus-kelas-kuliah', [App\Http\Controllers\Mahasiswa\KrsController::class, 'hapus_kelas_kuliah'])->name('mahasiswa.krs.hapus_kelas_kuliah');
                Route::get('/check-kelas-diambil', [App\Http\Controllers\Mahasiswa\KrsController::class, 'checkKelasDiambil'])->name('mahasiswa.krs.check_kelas_diambil');
                Route::get('/pilih-prodi', [App\Http\Controllers\Mahasiswa\KrsController::class, 'pilih_prodi'])->name('mahasiswa.krs.pilih_prodi');
                Route::get('/pilih-mk-merdeka', [App\Http\Controllers\Mahasiswa\KrsController::class, 'pilihMataKuliahMerdeka'])->name('mahasiswa.krs.pilih_mk_merdeka');
                Route::get('/cek-prasyarat', [App\Http\Controllers\Mahasiswa\KrsController::class, 'cekPrasyarat'])->name('mahasiswa.krs.cek_prasyarat');

                Route::get('/get-aktivitas', [App\Http\Controllers\Mahasiswa\Krs\AktivitasMahasiswaController::class, 'getAktivitas'])->name('mahasiswa.krs.get-aktivitas');
                Route::get('/ambil-aktivitas/{id_matkul}', [App\Http\Controllers\Mahasiswa\Krs\AktivitasMahasiswaController::class, 'ambilAktivitas'])->name('mahasiswa.krs.ambil-aktivitas');
                Route::post('/simpan-aktivitas', [App\Http\Controllers\Mahasiswa\Krs\AktivitasMahasiswaController::class, 'simpanAktivitas'])->name('mahasiswa.krs.simpan-aktivitas');
                Route::get('/get-nama-dosen', [App\Http\Controllers\Mahasiswa\Krs\AktivitasMahasiswaController::class, 'get_dosen'])->name('mahasiswa.krs.dosen-pembimbing.get-dosen');
                Route::delete('/hapus-aktivitas/{id}', [App\Http\Controllers\Mahasiswa\Krs\AktivitasMahasiswaController::class, 'hapusAktivitas'])->name('mahasiswa.krs.hapus-aktivitas');

                Route::get('/print/{id_semester}', [App\Http\Controllers\Mahasiswa\KrsController::class, 'krs_print'])->name('mahasiswa.krs.print');
                Route::get('/print/checkDosenPA/{id_semester}', [App\Http\Controllers\Mahasiswa\KrsController::class, 'checkDosenPA'])->name('mahasiswa.krs.print.checkDosenPA');

                Route::prefix('print')->group(function () {
                    Route::get('/{id_semester}', [App\Http\Controllers\Mahasiswa\KrsController::class, 'krs_print'])->name('mahasiswa.krs.print');
                    Route::get('/checkDosenPA/{id_semester}', [App\Http\Controllers\Mahasiswa\KrsController::class, 'checkDosenPA'])->name('mahasiswa.krs.print.checkDosenPA');
                });
            });

            // Route::get('/rps/lihat-rps', [App\Http\Controllers\Mahasiswa\RencanaPembelajaranController::class, 'index'])->name('mahasiswa.lihat-rps');
            Route::get('/krs/rps/{id_matkul}', [App\Http\Controllers\Mahasiswa\RencanaPembelajaranController::class, 'getRPSData'])->name('mahasiswa.lihat-rps');


            Route::get('/kartu-hasil-studi', [App\Http\Controllers\Mahasiswa\AkademikController::class, 'khs'])->name('mahasiswa.khs');
            Route::get('/transkrip-nilai', [App\Http\Controllers\Mahasiswa\AkademikController::class, 'transkrip'])->name('mahasiswa.transkrip');
            Route::get('/biaya-kuliah', [App\Http\Controllers\Mahasiswa\BiayaKuliahController::class, 'index'])->name('mahasiswa.biaya-kuliah');
            Route::get('/bahan-tugas', [App\Http\Controllers\Mahasiswa\BahanTugasController::class, 'index'])->name('mahasiswa.bahan-tugas');
            Route::get('/jadwal-presensi', [App\Http\Controllers\Mahasiswa\JadwalPresensiController::class, 'index'])->name('mahasiswa.jadwal-presensi');
            Route::get('/pa-online', [App\Http\Controllers\Mahasiswa\PAController::class, 'index'])->name('mahasiswa.pa-online');
            Route::get('/kuisioner', [App\Http\Controllers\Mahasiswa\KuisionerController::class, 'index'])->name('mahasiswa.kuisioner');

            //Route for perkuliahan mahasiswa
            Route::prefix('perkuliahan')->group(function () {
                Route::get('/nilai-perkuliahan', [App\Http\Controllers\Mahasiswa\NilaiController::class, 'index'])->name('mahasiswa.perkuliahan.nilai-perkuliahan');
                Route::get('/nilai-perkuliahan/{id_semester}/lihat-khs', [App\Http\Controllers\Mahasiswa\NilaiController::class, 'lihat_khs'])->name('mahasiswa.perkuliahan.nilai-perkuliahan.lihat-khs');
                Route::get('/nilai-perkuliahan/{id_matkul}/histori-nilai', [App\Http\Controllers\Mahasiswa\NilaiController::class, 'histori_nilai'])->name('mahasiswa.perkuliahan.nilai-perkuliahan.histori-nilai');
                Route::get('/nilai-usept', [App\Http\Controllers\Mahasiswa\SKPIController::class, 'index'])->name('mahasiswa.perkuliahan.nilai-usept');
            });

            //Route for prestasi mahasiswa
            Route::prefix('prestasi')->group(function () {
                Route::get('/prestasi-non-pendanaan', [App\Http\Controllers\Mahasiswa\PrestasiMahasiswaController::class, 'prestasi_mahasiswa_non_pendanaan'])->name('mahasiswa.prestasi.prestasi-non-pendanaan');
                Route::get('/prestasi-non-pendanaan/tambah', [App\Http\Controllers\Mahasiswa\PrestasiMahasiswaController::class, 'tambah_prestasi_mahasiswa_non_pendanaan'])->name('mahasiswa.prestasi.prestasi-non-pendanaan.tambah');
                Route::get('/prestasi-non-pendanaan/store', [App\Http\Controllers\Mahasiswa\PrestasiMahasiswaController::class, 'store_prestasi_mahasiswa_non_pendanaan'])->name('mahasiswa.prestasi.prestasi-non-pendanaan.store');
            });


            Route::prefix('bimbingan-tugas-akhir')->group(function () {
                Route::get('/', [App\Http\Controllers\Mahasiswa\Bimbingan\BimbinganController::class, 'index'])->name('mahasiswa.bimbingan.bimbingan-tugas-akhir');
                    Route::prefix('asistensi')->group(function(){
                        Route::post('/{aktivitas}/store', [App\Http\Controllers\Mahasiswa\Bimbingan\BimbinganController::class, 'store'])->name('mahasiswa.bimbingan.bimbingan-tugas-akhir.store');
                    });
            });

            

            // Route::get('/nilai-suliet', [App\Http\Controllers\Mahasiswa\SKPIController::class, 'index'])->name('mahasiswa.nilai-suliet');
            Route::get('/kegiatan-akademik', [App\Http\Controllers\Mahasiswa\KegiatanController::class, 'akademik'])->name('mahasiswa.akademik');
            Route::get('/kegiatan-seminar', [App\Http\Controllers\Mahasiswa\KegiatanController::class, 'seminar'])->name('mahasiswa.seminar');
            Route::get('/pengajuan-cuti', [App\Http\Controllers\Mahasiswa\CutiController::class, 'index'])->name('mahasiswa.pengajuan-cuti');

            //Route Bantuan
            Route::prefix('bantuan')->group(function () {
                Route::get('/ganti-password', [App\Http\Controllers\Mahasiswa\Bantuan\GantiPasswordController::class, 'ganti_password'])->name('mahasiswa.bantuan.ganti-password');
                Route::post('/proses-ganti-password', [App\Http\Controllers\Mahasiswa\Bantuan\GantiPasswordController::class, 'proses_ganti_password'])->name('mahasiswa.bantuan.proses-ganti-password');
            });
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
                // Route::get('/kesediaan-waktu-kuliah', [App\Http\Controllers\Dosen\Perkuliahan\KesediaanWaktuDosenController::class, 'kesediaan_waktu_kuliah'])->name('dosen.perkuliahan.kesediaan-waktu-kuliah');

                Route::prefix('rencana-pembelajaran')->group(function () {
                    Route::get('/', [App\Http\Controllers\Dosen\Perkuliahan\RencanaPembelajaranController::class, 'rencana_pembelajaran'])->name('dosen.perkuliahan.rencana-pembelajaran');
                    Route::get('/rencana-pembelajaran/detail/{matkul}', [App\Http\Controllers\Dosen\Perkuliahan\RencanaPembelajaranController::class, 'detail_rencana_pembelajaran'])->name('dosen.perkuliahan.rencana-pembelajaran.detail');
                    Route::get('/rencana-pembelajaran/tambah/{matkul}', [App\Http\Controllers\Dosen\Perkuliahan\RencanaPembelajaranController::class, 'tambah_rencana_pembelajaran'])->name('dosen.perkuliahan.rencana-pembelajaran.tambah');
                    Route::post('/rencana-pembelajaran/store/{matkul}', [App\Http\Controllers\Dosen\Perkuliahan\RencanaPembelajaranController::class, 'rencana_pembelajaran_store'])->name('dosen.perkuliahan.rencana-pembelajaran.store');
                    Route::get('/rencana-pembelajaran/ubah/{rencana_ajar}', [App\Http\Controllers\Dosen\Perkuliahan\RencanaPembelajaranController::class, 'ubah_rencana_pembelajaran'])->name('dosen.perkuliahan.rencana-pembelajaran.ubah');
                    Route::post('/rencana-pembelajaran/update/{rencana_ajar}', [App\Http\Controllers\Dosen\Perkuliahan\RencanaPembelajaranController::class, 'rencana_pembelajaran_update'])->name('dosen.perkuliahan.rencana-pembelajaran.update');
                    Route::get('/rencana-pembelajaran/delete/{rencana_ajar}', [App\Http\Controllers\Dosen\Perkuliahan\RencanaPembelajaranController::class, 'rencana_pembelajaran_delete'])->name('dosen.perkuliahan.rencana-pembelajaran.delete');
                    Route::get('/rencana-pembelajaran/ubah-link/{matkul}', [App\Http\Controllers\Dosen\Perkuliahan\RencanaPembelajaranController::class, 'ubah_link_rencana_pembelajaran'])->name('dosen.perkuliahan.rencana-pembelajaran.ubah-link');
                    Route::post('/rencana-pembelajaran/update-link/{matkul}', [App\Http\Controllers\Dosen\Perkuliahan\RencanaPembelajaranController::class, 'rencana_pembelajaran_update_link'])->name('dosen.perkuliahan.rencana-pembelajaran.update-link');
                });
            });

            //Route Penilaian
            Route::prefix('penilaian')->group(function () {
                Route::get('/penilaian-perkuliahan', [App\Http\Controllers\Dosen\Penilaian\PenilaianPerkuliahanController::class, 'penilaian_perkuliahan'])->name('dosen.penilaian.penilaian-perkuliahan');
                Route::get('/penilaian-perkuliahan/detail/{kelas}', [App\Http\Controllers\Dosen\Penilaian\PenilaianPerkuliahanController::class, 'detail_penilaian_perkuliahan'])->name('dosen.penilaian.penilaian-perkuliahan.detail');
                Route::get('/penilaian-sidang', [App\Http\Controllers\Dosen\Penilaian\PenilaianSidangController::class, 'penilaian_sidang'])->name('dosen.penilaian.penilaian-sidang');

                //Detail Fitur
                //Komponen Evaluasi
                Route::get('/komponen-evaluasi/{kelas}', [App\Http\Controllers\Dosen\Penilaian\PresentasePenilaianController::class, 'komponen_evaluasi'])->name('dosen.penilaian.komponen-evaluasi');
                Route::post('/komponen-evaluasi/store/{kelas}', [App\Http\Controllers\Dosen\Penilaian\PresentasePenilaianController::class, 'komponen_evaluasi_store'])->name('dosen.penilaian.komponen-evaluasi.store');
                Route::post('/komponen-evaluasi/update/{kelas}', [App\Http\Controllers\Dosen\Penilaian\PresentasePenilaianController::class, 'komponen_evaluasi_update'])->name('dosen.penilaian.komponen-evaluasi.update');
                //Downlaod DPNA
                Route::get('/penilaian-perkuliahan/download-dpna/{kelas}/{prodi}', [App\Http\Controllers\Dosen\Penilaian\PenilaianPerkuliahanController::class, 'download_dpna'])->name('dosen.penilaian.penilaian-perkuliahan.download-dpna');
                //Upload DPNA
                Route::get('/upload-dpna/{kelas}', [App\Http\Controllers\Dosen\Penilaian\PenilaianPerkuliahanController::class, 'upload_dpna'])->name('dosen.penilaian.penilaian-perkuliahan.upload-dpna');
                Route::post('/upload-dpna/store/{kelas}/{matkul}', [App\Http\Controllers\Dosen\Penilaian\PenilaianPerkuliahanController::class, 'upload_dpna_store'])->name('dosen.penilaian.penilaian-perkuliahan.upload-dpna.store');


                // Route::get('/kesediaan-waktu-kuliah', [App\Http\Controllers\Dosen\Perkuliahan\KesediaanWaktuDosenController::class, 'kesediaan_waktu_kuliah'])->name('dosen.perkuliahan.kesediaan-waktu-kuliah');
            });

            //Route Pembimbing Mahasiswa
            Route::prefix('pembimbing')->group(function () {
                Route::prefix('bimbingan-akademik')->group(function(){
                    Route::get('/', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'bimbingan_akademik'])->name('dosen.pembimbing.bimbingan-akademik');
                    Route::get('/detail/{riwayat}', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'bimbingan_akademik_detail'])->name('dosen.pembimbing.bimbingan-akademik.detail');
                    Route::post('/approve-all/{riwayat}', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'bimbingan_akademik_approve_all'])->name('dosen.pembimbing.bimbingan-akademik.approve-all');
                });

                Route::get('/bimbingan-non-akademik', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'bimbingan_non_akademik'])->name('dosen.pembimbing.bimbingan-non-akademik');

                Route::prefix('bimbingan-tugas-akhir')->group(function(){
                    Route::get('/', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'bimbingan_tugas_akhir'])->name('dosen.pembimbing.bimbingan-tugas-akhir');
                    Route::post('/approve-pembimbing/{aktivitas}', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'approve_pembimbing'])->name('dosen.pembimbing.bimbingan-tugas-akhir.approve-pembimbing');

                    Route::prefix('asistensi')->group(function(){
                        Route::get('/{aktivitas}', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'asistensi'])->name('dosen.pembimbing.bimbingan-tugas-akhir.asistensi');
                        Route::post('/{aktivitas}/store', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'asistensi_store'])->name('dosen.pembimbing.bimbingan-tugas-akhir.asistensi.store');
                        Route::post('/approve-asistensi/{asistensi}', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'asistensi_approve'])->name('dosen.pembimbing.bimbingan-tugas-akhir.asistensi.approve');
                    });
                });

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

                Route::prefix('mahasiswa')->group(function(){
                    Route::get('/', [App\Http\Controllers\Prodi\DataMasterController::class, 'mahasiswa'])->name('prodi.data-master.mahasiswa');
                    Route::get('/mahasiswa-data', [App\Http\Controllers\Prodi\DataMasterController::class, 'mahasiswa_data'])->name('prodi.data-master.mahasiswa.data');
                    Route::post('/set-pa/{mahasiswa}', [App\Http\Controllers\Prodi\DataMasterController::class, 'set_pa'])->name('prodi.data-master.mahasiswa.set-pa');
                    Route::post('/set-kurikulum-angkatan', [App\Http\Controllers\Prodi\DataMasterController::class, 'set_kurikulum_angkatan'])->name('prodi.data-master.mahasiswa.set-kurikulum-angkatan');
                });

                Route::prefix('mata-kuliah')->group(function(){
                    Route::get('/', [App\Http\Controllers\Prodi\DataMasterController::class, 'matkul'])->name('prodi.data-master.mata-kuliah');
                    Route::get('/{matkul}/tambah-prasyarat', [App\Http\Controllers\Prodi\DataMasterController::class, 'tambah_prasyarat'])->name('prodi.data-master.mata-kuliah.tambah-prasyarat');
                    Route::post('/{matkul}/store-prasyarat', [App\Http\Controllers\Prodi\DataMasterController::class, 'tambah_prasyarat_store'])->name('prodi.data-master.mata-kuliah.store-prasyarat');
                    Route::delete('/{matkul}/delete-prasyarat', [App\Http\Controllers\Prodi\DataMasterController::class, 'hapus_prasyarat'])->name('prodi.data-master.mata-kuliah.delete-prasyarat');
                });

                Route::prefix('matkul-merdeka')->group(function(){
                    Route::get('/', [App\Http\Controllers\Prodi\DataMasterController::class, 'matkul_merdeka'])->name('prodi.data-master.matkul-merdeka');
                    Route::post('/store', [App\Http\Controllers\Prodi\DataMasterController::class, 'matkul_merdeka_store'])->name('prodi.data-master.matkul-merdeka.store');
                    Route::delete('/{matkul_merdeka}/delete', [App\Http\Controllers\Prodi\DataMasterController::class, 'matkul_merdeka_destroy'])->name('prodi.data-master.matkul-merdeka.delete');
                });

                //Ruang Perkuliahan
                Route::prefix('ruang-perkuliahan')->group(function(){
                    Route::get('/', [App\Http\Controllers\Prodi\DataMasterController::class, 'ruang_perkuliahan'])->name('prodi.data-master.ruang-perkuliahan');
                    Route::post('/', [App\Http\Controllers\Prodi\DataMasterController::class, 'ruang_perkuliahan_store'])->name('prodi.data-master.ruang-perkuliahan.store');
                    Route::patch('/{ruang_perkuliahan}/update', [App\Http\Controllers\Prodi\DataMasterController::class, 'ruang_perkuliahan_update'])->name('prodi.data-master.ruang-perkuliahan.update');
                    Route::delete('/{ruang_perkuliahan}/delete', [App\Http\Controllers\Prodi\DataMasterController::class, 'ruang_perkuliahan_destroy'])->name('prodi.data-master.ruang-perkuliahan.delete');
                });

                Route::prefix('kurikulum')->group(function(){
                    Route::get('/', [App\Http\Controllers\Prodi\DataMasterController::class, 'kurikulum'])->name('prodi.data-master.kurikulum');
                    Route::get('/detail/{kurikulum}', [App\Http\Controllers\Prodi\DataMasterController::class, 'detail_kurikulum'])->name('prodi.data-master.kurikulum.detail');
                });
            });

            //Route for Data Akademik
            Route::prefix('data-akademik')->group(function(){
                //Kelas Penjadwalan
                Route::prefix('kelas-penjadwalan')->group(function(){
                    Route::get('/', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'kelas_penjadwalan'])->name('prodi.data-akademik.kelas-penjadwalan');
                    Route::get('/{id_matkul}/detail', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'detail_kelas_penjadwalan'])->name('prodi.data-akademik.kelas-penjadwalan.detail');
                    // Route::get('/get-mata-kuliah', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'get_matkul'])->name('prodi.data-akademik.kelas-penjadwalan.get-matkul');
                    Route::get('/{id_matkul}/tambah', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'tambah_kelas_penjadwalan'])->name('prodi.data-akademik.kelas-penjadwalan.tambah');
                    Route::post('/{id_matkul}/store', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'kelas_penjadwalan_store'])->name('prodi.data-akademik.kelas-penjadwalan.store');
                });

                //Dosen Pengajar Kelas Kuliah
                Route::get('/kelas-penjadwalan/{id_matkul}/{nama_kelas_kuliah}/dosen-pengajar', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'dosen_pengajar_kelas'])->name('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar');
                Route::get('/get-nama-dosen', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'get_dosen'])->name('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar.get-dosen');
                Route::get('/get-substansi-kuliah', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'get_substansi'])->name('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar.get-substansi');
                Route::post('/kelas-penjadwalan/{id_matkul}/{nama_kelas_kuliah}/dosen-pengajar/store', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'dosen_pengajar_store'])->name('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar.store');

                Route::get('/khs', [App\Http\Controllers\Prodi\Akademik\KHSController::class, 'khs'])->name('prodi.data-akademik.khs');
                Route::get('', [App\Http\Controllers\Prodi\Akademik\KRSController::class, 'krs'])->name('prodi.data-akademik.krs');
                Route::get('/sidang-mahasiswa', [App\Http\Controllers\Prodi\Akademik\SidangMahasiswaController::class, 'sidang_mahasiswa'])->name('prodi.data-akademik.sidang-mahasiswa');
                Route::get('/transkrip-mahasiswa', [App\Http\Controllers\Prodi\Akademik\TranskripMahasiswaController::class, 'transkrip_mahasiswa'])->name('prodi.data-akademik.transkrip-mahasiswa');
                Route::get('/yudisium-mahasiswa', [App\Http\Controllers\Prodi\Akademik\YudisiumMahasiswaController::class, 'yudisium_mahasiswa'])->name('prodi.data-akademik.yudisium-mahasiswa');

                Route::prefix('tugas-akhir')->group(function(){
                    Route::get('/', [App\Http\Controllers\Prodi\Akademik\TugasAkhirController::class, 'index'])->name('prodi.data-akademik.tugas-akhir');
                    Route::post('/approve-pembimbing/{aktivitasMahasiswa}', [App\Http\Controllers\Prodi\Akademik\TugasAkhirController::class, 'approve_pembimbing'])->name('prodi.data-akademik.tugas-akhir.approve-pembimbing');
                    Route::get('/edit-pembimbing/{aktivitasMahasiswa}', [App\Http\Controllers\Prodi\Akademik\TugasAkhirController::class, 'edit_pembimbing'])->name('prodi.data-akademik.tugas-akhir.edit-pembimbing');
                });
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

            Route::prefix('kuisioner')->group(function(){
                Route::get('/', [App\Http\Controllers\Universitas\KuisionerController::class, 'index'])->name('univ.kuisioner');
                Route::post('/store', [App\Http\Controllers\Universitas\KuisionerController::class, 'store'])->name('univ.kuisioner.store');
                Route::patch('/update/{kuisioner}', [App\Http\Controllers\Universitas\KuisionerController::class, 'update'])->name('univ.kuisioner.update');
                Route::delete('/delete/{kuisioner}', [App\Http\Controllers\Universitas\KuisionerController::class, 'destroy'])->name('univ.kuisioner.delete');
            });

            Route::prefix('mahasiswa')->group(function () {
                Route::get('/', [App\Http\Controllers\Universitas\MahasiswaController::class, 'daftar_mahasiswa'])->name('univ.mahasiswa');
                Route::get('/data', [App\Http\Controllers\Universitas\MahasiswaController::class, 'daftar_mahasiswa_data'])->name('univ.mahasiswa.data');
                Route::get('/sync-mahasiswa', [App\Http\Controllers\Universitas\MahasiswaController::class, 'sync_mahasiswa'])->name('univ.mahasiswa.sync');
                Route::get('/sync-prestasi', [App\Http\Controllers\Universitas\MahasiswaController::class, 'sync_prestasi_mahasiswa'])->name('univ.mahasiswa.sync-prestasi');
            });

            Route::prefix('dosen')->group(function () {
                Route::get('/', [App\Http\Controllers\Universitas\DosenController::class, 'dosen'])->name('univ.dosen');
                // Route::get('/data', [App\Http\Controllers\Universitas\DosenController::class, 'daftar_dosen_data'])->name('univ.dosen.data');
                Route::get('/sync-dosen', [App\Http\Controllers\Universitas\DosenController::class, 'sync_dosen'])->name('univ.dosen.sync');
                Route::get('/sync-penugasan', [App\Http\Controllers\Universitas\DosenController::class, 'sync_penugasan_dosen'])->name('univ.dosen.sync-penugasan');
                Route::get('/sync-dosen-all', [App\Http\Controllers\Universitas\DosenController::class, 'sync_dosen_all'])->name('univ.dosen.sync-all');
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
                Route::post('/aktif-non-aktif-kurikulum/{kurikulum}', [App\Http\Controllers\Universitas\KurikulumController::class, 'is_active'])->name('univ.kurikulum.is-active');
            });

            Route::prefix('mata-kuliah')->group(function () {
                Route::get('/', [App\Http\Controllers\Universitas\KurikulumController::class, 'matkul'])->name('univ.mata-kuliah');
                Route::get('/data', [App\Http\Controllers\Universitas\KurikulumController::class, 'matkul_data'])->name('univ.mata-kuliah.data');
                Route::get('/sync-mata-kuliah', [App\Http\Controllers\Universitas\KurikulumController::class, 'sync_mata_kuliah'])->name('univ.mata-kuliah.sync');
                Route::get('/sync-rencana', [App\Http\Controllers\Universitas\KurikulumController::class, 'sync_rencana'])->name('univ.mata-kuliah.sync-rencana');
            });

            Route::prefix('perkuliahan')->group(function () {

                Route::prefix('nilai-perkuliahan')->group(function(){
                    Route::get('/', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'nilai_perkuliahan'])->name('univ.perkuliahan.nilai-perkuliahan');
                    Route::get('/sync', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_nilai_perkuliahan'])->name('univ.perkuliahan.nilai-perkuliahan.sync');
                    Route::get('/sync-nilai-komponen', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_nilai_komponen'])->name('univ.perkuliahan.nilai-perkuliahan.sync-nilai-komponen');
                });

                Route::prefix('kelas-kuliah')->group(function () {
                    Route::get('/', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'kelas_kuliah'])->name('univ.perkuliahan.kelas-kuliah');
                    Route::get('/data', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'kelas_data'])->name('univ.perkuliahan.kelas-kuliah.data');
                    Route::get('/sync', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_kelas_kuliah'])->name('univ.perkuliahan.kelas-kuliah.sync');
                    Route::get('/sync-pengajar-kelas', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_pengajar_kelas'])->name('univ.perkuliahan.kelas-kuliah.sync-pengajar-kelas');
                    Route::get('/sync-peserta-kelas', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_peserta_kelas'])->name('univ.perkuliahan.kelas-kuliah.sync-peserta-kelas');

                    Route::get('/sync-komponen-evaluasi-kelas', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_komponen_evaluasi_kelas'])->name('univ.perkuliahan.kelas-kuliah.sync-komponen-evaluasi');
                });

                Route::get('/aktivitas-kuliah', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'aktivitas_kuliah'])->name('univ.perkuliahan.aktivitas-kuliah');
                Route::get('/aktivitas-kuliah/sync', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_aktivitas_kuliah'])->name('univ.perkuliahan.aktivitas-kuliah.sync');

                Route::get('/aktivitas-mahasiswa', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'aktivitas_mahasiswa'])->name('univ.perkuliahan.aktivitas-mahasiswa');
                Route::get('/aktivitas-mahasiswa/sync', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_aktivitas_mahasiswa'])->name('univ.perkuliahan.aktivitas-mahasiswa.sync');
                Route::get('/aktivitas-mahasiswa/sync-anggota', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_anggota_aktivitas_mahasiswa'])->name('univ.perkuliahan.aktivitas-mahasiswa.sync-anggota');

                Route::prefix('konversi-aktivitas')->group(function () {
                    Route::get('/', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'konversi_aktivitas'])->name('univ.perkuliahan.konversi-aktivitas');
                    Route::get('/sync', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_konversi_aktivitas'])->name('univ.perkuliahan.konversi-aktivitas.sync');
                });

                Route::prefix('transkrip')->group(function(){
                    Route::get('/', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'transkrip'])->name('univ.perkuliahan.transkrip');
                    Route::get('/sync', [App\Http\Controllers\Universitas\PerkuliahanController::class, 'sync_transkrip'])->name('univ.perkuliahan.transkrip.sync');
                });


            });

            Route::prefix('pengaturan')->group(function () {
                Route::get('/periode-perkuliahan', [App\Http\Controllers\Universitas\PengaturanController::class, 'periode_perkuliahan'])->name('univ.pengaturan.periode-perkuliahan');
                Route::get('/periode-perkuliahan/sync', [App\Http\Controllers\Universitas\PengaturanController::class, 'sync_periode_perkuliahan'])->name('univ.pengaturan.periode-perkuliahan.sync');

                Route::get('/semester-aktif', [App\Http\Controllers\Universitas\PengaturanController::class, 'semester_aktif'])->name('univ.pengaturan.semester-aktif');
                Route::post('/semester-aktif', [App\Http\Controllers\Universitas\PengaturanController::class, 'semester_aktif_store'])->name('univ.pengaturan.semester-aktif.store');

                Route::get('/skala-nilai', [App\Http\Controllers\Universitas\PengaturanController::class, 'skala_nilai'])->name('univ.pengaturan.skala-nilai');
                Route::get('/skala-nilai/sync', [App\Http\Controllers\Universitas\PengaturanController::class, 'sync_skala_nilai'])->name('univ.pengaturan.skala-nilai.sync');

                // Route Pengaturan akun
                Route::prefix('akun')->group(function(){
                    Route::get('/', [App\Http\Controllers\Universitas\PengaturanController::class, 'akun'])->name('univ.pengaturan.akun');
                    Route::post('/store', [App\Http\Controllers\Universitas\PengaturanController::class, 'akun_store'])->name('univ.pengaturan.akun.store');
                    Route::patch('/update/{user}', [App\Http\Controllers\Universitas\PengaturanController::class, 'akun_update'])->name('univ.pengaturan.akun.update');
                    Route::delete('/delete/{user}', [App\Http\Controllers\Universitas\PengaturanController::class, 'akun_destroy'])->name('univ.pengaturan.akun.delete');

                    Route::post('/dosen-store', [App\Http\Controllers\Universitas\PengaturanController::class, 'akun_dosen_create'])->name('univ.pengaturan.akun.dosen-store');
                    Route::get('/get-dosen', [App\Http\Controllers\Universitas\PengaturanController::class, 'get_dosen'])->name('univ.pengaturan.akun.get-dosen');
                });
            });
        });
    });
});

