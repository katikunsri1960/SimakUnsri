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


Route::get('/akun-mahasiswa', [App\Http\Controllers\Auth\CreateAccountController::class, 'createAccountMahasiswa'])->name('create-account-mahasiswa');
Route::get('/checkNim/{nim}', [App\Http\Controllers\Auth\CreateAccountController::class, 'checkNim'])->name('check-nim');
Route::post('/store-akun', [App\Http\Controllers\Auth\CreateAccountController::class, 'storeAkun'])->name('store-akun');
Route::get('/test-nusoap/{nim}/{tak_pembayaran}', [App\Http\Controllers\Auth\CreateAccountController::class, 'testNusoap'])->name('test-nusoap');
Auth::routes([
    'register' => false
]);

Route::group(['middleware' => ['auth']], function() {
    // route for mahasiswa

    Route::group(['middleware' => ['role:bak']], function(){
        Route::prefix('bak')->group(function(){
            Route::get('/', [App\Http\Controllers\Bak\DashboardController::class, 'index'])->name('bak');

            Route::prefix('beasiswa')->group(function(){
                Route::get('/', [App\Http\Controllers\Bak\BeasiswaController::class, 'index'])->name('bak.beasiswa');
            });

            Route::prefix('transkrip-nilai')->group(function(){
                Route::get('/', [App\Http\Controllers\Bak\TranskripController::class, 'index'])->name('bak.transkrip-nilai');
                Route::Get('/get-transkrip-nilai', [App\Http\Controllers\Bak\TranskripController::class, 'getTranskrip'])->name('bak.transkrip-nilai.get');
            });

            Route::prefix('pengajuan-cuti')->group(function(){
                Route::get('/', [App\Http\Controllers\Bak\PengajuanCutiController::class, 'index'])->name('bak.pengajuan-cuti');
            });
        });
    });

    Route::group(['middleware' => ['role:fakultas']], function(){
        Route::prefix('fakultas')->group(function(){
            Route::get('/', [App\Http\Controllers\Fakultas\DashboardController::class, 'index'])->name('fakultas');
        });
    });


    Route::group(['middleware' => ['role:mahasiswa']], function() {
        Route::prefix('mahasiswa')->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Mahasiswa\Dashboard\DashboardController::class, 'index'])->name('mahasiswa.dashboard');
            Route::get('/biodata', [App\Http\Controllers\Mahasiswa\Biodata\BiodataController::class, 'index_rev'])->name('mahasiswa.biodata');
            Route::prefix('ukt')->group(function(){
                Route::get('/', [App\Http\Controllers\Mahasiswa\Akademik\BiayaKuliahController::class, 'index'])->name('mahasiswa.biaya-kuliah');
                // Route::post('/store/{kelas}', [App\Http\Controllers\Mahasiswa\Akademik\NilaiController::class, 'kuisioner_store'])->name('mahasiswa.perkuliahan.nilai-perkuliahan.kuisioner.store');
            });

            Route::get('/krs-magang', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'index'])->name('mahasiswa.krs');
            // Route::get('/krs', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'generateAKM'])->name('mahasiswa.krs');
            Route::prefix('perkuliahan')->group(function () {
                Route::get('/krs', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'view'])->name('mahasiswa.krs.index');
                Route::get('/get-kelas-kuliah', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'get_kelas_kuliah'])->name('mahasiswa.krs.get_kelas_kuliah');
                Route::get('/get-kelas-kuliah-merdeka', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'get_kelas_kuliah_merdeka'])->name('mahasiswa.krs.get_kelas_kuliah_merdeka');
                Route::post('/store-kelas-kuliah', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'ambilKelasKuliah'])->name('mahasiswa.krs.store_kelas_kuliah');
                Route::post('/update-kelas-kuliah', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'update_kelas_kuliah'])->name('mahasiswa.krs.update_kelas_kuliah');
                Route::delete('/{pesertaKelas}/hapus-kelas-kuliah', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'hapus_kelas_kuliah'])->name('mahasiswa.krs.hapus_kelas_kuliah');
                Route::get('/check-kelas-diambil', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'checkKelasDiambil'])->name('mahasiswa.krs.check_kelas_diambil');
                Route::get('/pilih-prodi', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'pilih_prodi'])->name('mahasiswa.krs.pilih_prodi');
                Route::get('/pilih-mk-merdeka', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'pilihMataKuliahMerdeka'])->name('mahasiswa.krs.pilih_mk_merdeka');
                Route::get('/cek-prasyarat', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'cekPrasyarat'])->name('mahasiswa.krs.cek_prasyarat');

                Route::get('/get-aktivitas', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMahasiswaController::class, 'getAktivitas'])->name('mahasiswa.krs.get-aktivitas');
                Route::get('/ambil-aktivitas/{id_matkul}', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMahasiswaController::class, 'ambilAktivitas'])->name('mahasiswa.krs.ambil-aktivitas');
                Route::post('/simpan-aktivitas', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMahasiswaController::class, 'simpanAktivitas'])->name('mahasiswa.krs.simpan-aktivitas');
                Route::get('/get-nama-dosen', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMahasiswaController::class, 'get_dosen'])->name('mahasiswa.krs.dosen-pembimbing.get-dosen');
                Route::delete('/hapus-aktivitas/{id}', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMahasiswaController::class, 'hapusAktivitas'])->name('mahasiswa.krs.hapus-aktivitas');

                // Route::get('/get-kelas-kuliah', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'get_kelas_kuliah'])->name('mahasiswa.aktivitas.magang');
                // Route::prefix('aktivitas')->group(function () {
                //     Route::get('/magang', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMBKMController::class, 'index_magang'])->name('mahasiswa.aktivitas.magang');
                //     // Route::get('/checkDosenPA/{id_semester}', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'checkDosenPA'])->name('mahasiswa.krs.print.checkDosenPA');
                // });
                //Route for Aktivitas Magang
                Route::prefix('mbkm')->group(function () {
                    Route::get('/', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMBKMController::class, 'view'])->name('mahasiswa.perkuliahan.mbkm.view');
                    // Route::get('/daftar', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMBKMController::class, 'index'])->name('mahasiswa.perkuliahan.mbkm.index');
                    Route::get('/daftar-mbkm-pertukaran', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMBKMController::class, 'index_pertukaran'])->name('mahasiswa.perkuliahan.mbkm.pertukaran');
                    Route::get('/daftar-mbkm-non-pertukaran', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMBKMController::class, 'index_non_pertukaran'])->name('mahasiswa.perkuliahan.mbkm.non-pertukaran');
                    Route::get('/tambah-mbkm-pertukaran', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMBKMController::class, 'tambah_pertukaran'])->name('mahasiswa.perkuliahan.mbkm.tambah-pertukaran');
                    Route::get('/tambah-mbkm-non-pertukaran', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMBKMController::class, 'tambah'])->name('mahasiswa.perkuliahan.mbkm.tambah-non-pertukaran');
                    Route::post('/store-non-pertukaran', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMBKMController::class, 'store'])->name('mahasiswa.perkuliahan.mbkm.store');
                    Route::post('/store-pertukaran', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMBKMController::class, 'store_pertukaran'])->name('mahasiswa.perkuliahan.mbkm.store-pertukaran');
                    Route::get('/get-nama-dosen', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMBKMController::class, 'get_dosen'])->name('mahasiswa.perkuliahan.mbkm.get-dosen');
                    Route::delete('/hapus-aktivitas/{id}', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMBKMController::class, 'hapusAktivitas'])->name('mahasiswa.perkuliahan.mbkm.hapus-aktivitas');
                    Route::delete('/hapus-aktivitas-pertukaran/{id}', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMBKMController::class, 'hapusAktivitas_pertukaran'])->name('mahasiswa.perkuliahan.mbkm.hapus-aktivitas-pertukaran');
                    // Route::get('/dosen-pengajar', [App\Http\Controllers\Mahasiswa\Akademik\AktivitasMBKMController::class, 'dosen_pengajar_kelas'])->name('mahasiswa.perkuliahan.mbkm.dosen-pengajar');
                });

                // Route::get('/print/{id_semester}', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'krs_print'])->name('mahasiswa.krs.print');
                // Route::get('/print/checkDosenPA/{id_semester}', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'checkDosenPA'])->name('mahasiswa.krs.print.checkDosenPA');

                Route::prefix('print')->group(function () {
                    Route::get('/{id_semester}', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'krs_print'])->name('mahasiswa.krs.print');
                    Route::get('/checkDosenPA/{id_semester}', [App\Http\Controllers\Mahasiswa\Akademik\KrsController::class, 'checkDosenPA'])->name('mahasiswa.krs.print.checkDosenPA');
                });
            });

            // Route::get('/rps/lihat-rps', [App\Http\Controllers\Mahasiswa\RencanaPembelajaranController::class, 'index'])->name('mahasiswa.lihat-rps');
            Route::get('/krs/rps/{id_matkul}', [App\Http\Controllers\Mahasiswa\Akademik\RencanaPembelajaranController::class, 'getRPSData'])->name('mahasiswa.lihat-rps');

            //Route for perkuliahan mahasiswa
            Route::prefix('perkuliahan')->group(function () {
                Route::prefix('nilai-perkuliahan')->group(function(){
                    Route::get('/', [App\Http\Controllers\Mahasiswa\Akademik\NilaiController::class, 'index'])->name('mahasiswa.perkuliahan.nilai-perkuliahan');
                    Route::prefix('kuisioner')->group(function(){
                        Route::get('/{kelas}', [App\Http\Controllers\Mahasiswa\Akademik\NilaiController::class, 'kuisioner'])->name('mahasiswa.perkuliahan.nilai-perkuliahan.kuisioner');
                        Route::post('/store/{kelas}', [App\Http\Controllers\Mahasiswa\Akademik\NilaiController::class, 'kuisioner_store'])->name('mahasiswa.perkuliahan.nilai-perkuliahan.kuisioner.store');
                    });
                    Route::get('/{id_semester}/lihat-khs', [App\Http\Controllers\Mahasiswa\Akademik\NilaiController::class, 'lihat_khs'])->name('mahasiswa.perkuliahan.nilai-perkuliahan.lihat-khs');
                    Route::get('/{id_matkul}/histori-nilai', [App\Http\Controllers\Mahasiswa\Akademik\NilaiController::class, 'histori_nilai'])->name('mahasiswa.perkuliahan.nilai-perkuliahan.histori-nilai');
                });

                Route::get('/nilai-usept', [App\Http\Controllers\Mahasiswa\Akademik\NilaiUseptController::class, 'index'])->name('mahasiswa.perkuliahan.nilai-usept');
                Route::get('/under_devop', [App\Http\Controllers\Mahasiswa\Akademik\NilaiUseptController::class, 'devop'])->name('mahasiswa.perkuliahan.nilai-usept.devop');
            });

            //Route for prestasi mahasiswa
            Route::prefix('prestasi')->group(function () {
                Route::get('/prestasi-non-pendanaan', [App\Http\Controllers\Mahasiswa\Prestasi\PrestasiMahasiswaController::class, 'prestasi_mahasiswa_non_pendanaan'])->name('mahasiswa.prestasi.prestasi-non-pendanaan');
                Route::get('/prestasi-non-pendanaan/tambah', [App\Http\Controllers\Mahasiswa\Prestasi\PrestasiMahasiswaController::class, 'tambah_prestasi_mahasiswa_non_pendanaan'])->name('mahasiswa.prestasi.prestasi-non-pendanaan.tambah');
                Route::get('/prestasi-non-pendanaan/store', [App\Http\Controllers\Mahasiswa\Prestasi\PrestasiMahasiswaController::class, 'store_prestasi_mahasiswa_non_pendanaan'])->name('mahasiswa.prestasi.prestasi-non-pendanaan.store');
            });

            Route::prefix('bimbingan-tugas-akhir')->group(function () {
                Route::get('/', [App\Http\Controllers\Mahasiswa\Bimbingan\BimbinganController::class, 'index'])->name('mahasiswa.bimbingan.bimbingan-tugas-akhir');
                    Route::prefix('asistensi')->group(function(){
                        Route::post('/{aktivitas}/store', [App\Http\Controllers\Mahasiswa\Bimbingan\BimbinganController::class, 'store'])->name('mahasiswa.bimbingan.bimbingan-tugas-akhir.store');
                    });
            });

            //Route for prestasi mahasiswa
            Route::prefix('pengajuan-cuti')->group(function () {
                Route::get('/', [App\Http\Controllers\Mahasiswa\Cuti\CutiController::class, 'index'])->name('mahasiswa.pengajuan-cuti.index');
                Route::get('/tambah', [App\Http\Controllers\Mahasiswa\Cuti\CutiController::class, 'tambah'])->name('mahasiswa.pengajuan-cuti.tambah');
                Route::post('/store', [App\Http\Controllers\Mahasiswa\Cuti\CutiController::class, 'store'])->name('mahasiswa.pengajuan-cuti.store');
                Route::delete('/hapus-cuti/{id_cuti}', [App\Http\Controllers\Mahasiswa\Cuti\CutiController::class, 'delete'])->name('mahasiswa.pengajuan-cuti.delete');
            });

            // Route::get('/nilai-suliet', [App\Http\Controllers\Mahasiswa\SKPIController::class, 'index'])->name('mahasiswa.nilai-suliet');
            Route::get('/kegiatan-akademik', [App\Http\Controllers\Mahasiswa\KegiatanController::class, 'akademik'])->name('mahasiswa.akademik');
            Route::get('/kegiatan-seminar', [App\Http\Controllers\Mahasiswa\KegiatanController::class, 'seminar'])->name('mahasiswa.seminar');
            // Route::get('/pengajuan-cuti', [App\Http\Controllers\Mahasiswa\CutiController::class, 'index'])->name('mahasiswa.pengajuan-cuti');

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
                Route::prefix('jadwal-kuliah')->group(function () {
                    Route::get('/', [App\Http\Controllers\Dosen\Perkuliahan\JadwalKuliahController::class, 'jadwal_kuliah'])->name('dosen.perkuliahan.jadwal-kuliah');
                    Route::get('/jadwal-kuliah/detail/{kelas}', [App\Http\Controllers\Dosen\Perkuliahan\JadwalKuliahController::class, 'detail_kelas_kuliah'])->name('dosen.perkuliahan.jadwal-kuliah.detail');
                });
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
                    Route::post('/batal-krs/{riwayat}', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'bimbingan_akademik_batal_approve'])->name('dosen.pembimbing.bimbingan-akademik.batal-krs');
                });

                Route::get('/bimbingan-non-akademik', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'bimbingan_non_akademik'])->name('dosen.pembimbing.bimbingan-non-akademik');

                Route::prefix('bimbingan-tugas-akhir')->group(function(){
                    Route::get('/', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'bimbingan_tugas_akhir'])->name('dosen.pembimbing.bimbingan-tugas-akhir');
                    Route::post('/approve-pembimbing/{aktivitas}', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'approve_pembimbing'])->name('dosen.pembimbing.bimbingan-tugas-akhir.approve-pembimbing');
                    Route::post('/decline-pembimbing/{aktivitas}', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'pembatalan_pembimbing'])->name('dosen.pembimbing.bimbingan-tugas-akhir.decline-pembimbing');

                    Route::prefix('asistensi')->group(function(){
                        Route::get('/{aktivitas}', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'asistensi'])->name('dosen.pembimbing.bimbingan-tugas-akhir.asistensi');
                        Route::post('/{aktivitas}/store', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'asistensi_store'])->name('dosen.pembimbing.bimbingan-tugas-akhir.asistensi.store');
                        Route::post('/approve-asistensi/{asistensi}', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'asistensi_approve'])->name('dosen.pembimbing.bimbingan-tugas-akhir.asistensi.approve');
                    });

                    Route::prefix('ajuan-sidang')->group(function(){
                        Route::get('/{aktivitas}', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'ajuan_sidang'])->name('dosen.pembimbing.bimbingan-tugas-akhir.ajuan-sidang');
                        Route::post('/{aktivitas}/store', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'ajuan_sidang_store'])->name('dosen.pembimbing.bimbingan-tugas-akhir.ajuan-sidang.store');
                    });

                    Route::get('/get-dosen', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'get_dosen'])->name('dosen.pembimbing.bimbingan-tugas-akhir.get-dosen');
                });

                Route::prefix('bimbingan-non-tugas-akhir')->group(function(){
                    Route::get('/', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'bimbingan_aktivitas'])->name('dosen.pembimbing.bimbingan-non-tugas-akhir');
                    Route::post('/approve-pembimbing/{aktivitas}', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'approve_pembimbing'])->name('dosen.pembimbing.bimbingan-non-tugas-akhir.approve-pembimbing');
                    Route::post('/decline-pembimbing/{aktivitas}', [App\Http\Controllers\Dosen\Pembimbing\PembimbingMahasiswaController::class, 'pembatalan_pembimbing'])->name('dosen.pembimbing.bimbingan-non-tugas-akhir.decline-pembimbing');
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
                    Route::post('/set-kurikulum/{mahasiswa}', [App\Http\Controllers\Prodi\DataMasterController::class, 'set_kurikulum'])->name('prodi.data-master.mahasiswa.set-kurikulum');
                    Route::post('/set-kurikulum-angkatan', [App\Http\Controllers\Prodi\DataMasterController::class, 'set_kurikulum_angkatan'])->name('prodi.data-master.mahasiswa.set-kurikulum-angkatan');
                });

                Route::prefix('mata-kuliah')->group(function(){
                    Route::get('/', [App\Http\Controllers\Prodi\DataMasterController::class, 'matkul'])->name('prodi.data-master.mata-kuliah');
                    Route::get('/{kurikulum}/{matkul}/tambah-prasyarat', [App\Http\Controllers\Prodi\DataMasterController::class, 'tambah_prasyarat'])->name('prodi.data-master.mata-kuliah.tambah-prasyarat');
                    Route::post('/{matkul}/store-prasyarat', [App\Http\Controllers\Prodi\DataMasterController::class, 'tambah_prasyarat_store'])->name('prodi.data-master.mata-kuliah.store-prasyarat');
                    Route::delete('/{matkul}/delete-prasyarat', [App\Http\Controllers\Prodi\DataMasterController::class, 'hapus_prasyarat'])->name('prodi.data-master.mata-kuliah.delete-prasyarat');
                    Route::get('/{matkul}/lihat-rps', [App\Http\Controllers\Prodi\DataMasterController::class, 'lihat_rps'])->name('prodi.data-master.mata-kuliah.lihat-rps');
                    Route::post('/{matkul}/approved-all', [App\Http\Controllers\Prodi\DataMasterController::class, 'approved_rps'])->name('prodi.data-master.mata-kuliah.approved-all');
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
                    Route::get('/{id_maktul}/{id_kelas}/peserta', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'peserta_kelas'])->name('prodi.data-akademik.kelas-penjadwalan.peserta');

                    Route::get('/{id_kelas}/absensi', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'download_absensi'])->name('prodi.data-akademik.kelas-penjadwalan.absensi');
                    // Route::get('/get-mata-kuliah', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'get_matkul'])->name('prodi.data-akademik.kelas-penjadwalan.get-matkul');
                    Route::get('/{id_matkul}/tambah', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'tambah_kelas_penjadwalan'])->name('prodi.data-akademik.kelas-penjadwalan.tambah');
                    Route::post('/{id_matkul}/store', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'kelas_penjadwalan_store'])->name('prodi.data-akademik.kelas-penjadwalan.store');

                    Route::delete('/{id_matkul}/{id_kelas}/delete', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'kelas_penjadwalan_destroy'])->name('prodi.data-akademik.kelas-penjadwalan.delete');
                    Route::get('/{id_matkul}/{nama_kelas_kuliah}/dosen-pengajar', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'dosen_pengajar_kelas'])->name('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar');
                    Route::delete('/{id_matkul}/{id_kelas}/dosen-pengajar/delete', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'dosen_pengajar_destroy'])->name('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar.delete');

                    Route::get('/{id_matkul}/{id_kelas}/edit-kelas', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'edit_kelas_penjadwalan'])->name('prodi.data-akademik.kelas-penjadwalan.edit');
                    Route::post('/{id_matkul}/{id_kelas}/update', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'kelas_penjadwalan_update'])->name('prodi.data-akademik.kelas-penjadwalan.update');

                });

                //Dosen Pengajar Kelas Kuliah
                Route::get('/get-nama-dosen', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'get_dosen'])->name('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar.get-dosen');
                Route::get('/get-substansi-kuliah', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'get_substansi'])->name('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar.get-substansi');
                Route::post('/kelas-penjadwalan/{id_matkul}/{nama_kelas_kuliah}/dosen-pengajar/store', [App\Http\Controllers\Prodi\Akademik\KelasPenjadwalanController::class, 'dosen_pengajar_store'])->name('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar.store');

                Route::prefix('khs')->group(function(){
                    Route::get('/', [App\Http\Controllers\Prodi\Akademik\KHSController::class, 'khs'])->name('prodi.data-akademik.khs');
                    Route::get('/data', [App\Http\Controllers\Prodi\Akademik\KHSController::class, 'data'])->name('prodi.data-akademik.khs.data');
                });

                Route::get('', [App\Http\Controllers\Prodi\Akademik\KRSController::class, 'krs'])->name('prodi.data-akademik.krs');
                Route::get('/sidang-mahasiswa', [App\Http\Controllers\Prodi\Akademik\SidangMahasiswaController::class, 'sidang_mahasiswa'])->name('prodi.data-akademik.sidang-mahasiswa');
                Route::get('/transkrip-mahasiswa', [App\Http\Controllers\Prodi\Akademik\TranskripMahasiswaController::class, 'transkrip_mahasiswa'])->name('prodi.data-akademik.transkrip-mahasiswa');
                Route::get('/yudisium-mahasiswa', [App\Http\Controllers\Prodi\Akademik\YudisiumMahasiswaController::class, 'yudisium_mahasiswa'])->name('prodi.data-akademik.yudisium-mahasiswa');


                Route::get('/khs', [App\Http\Controllers\Prodi\Akademik\KHSController::class, 'khs'])->name('prodi.data-akademik.khs');
                Route::prefix('krs')->group(function(){
                    Route::get('/', [App\Http\Controllers\Prodi\Akademik\KRSController::class, 'krs'])->name('prodi.data-akademik.krs');
                    Route::get('/data', [App\Http\Controllers\Prodi\Akademik\KRSController::class, 'data'])->name('prodi.data-akademik.krs.data');
                    Route::get('/approve', [App\Http\Controllers\Prodi\Akademik\KRSController::class, 'approve'])->name('prodi.data-akademik.krs.approve');
                });

                Route::get('/sidang-mahasiswa', [App\Http\Controllers\Prodi\Akademik\SidangMahasiswaController::class, 'sidang_mahasiswa'])->name('prodi.data-akademik.sidang-mahasiswa');
                Route::get('/transkrip-mahasiswa', [App\Http\Controllers\Prodi\Akademik\TranskripMahasiswaController::class, 'transkrip_mahasiswa'])->name('prodi.data-akademik.transkrip-mahasiswa');
                Route::get('/yudisium-mahasiswa', [App\Http\Controllers\Prodi\Akademik\YudisiumMahasiswaController::class, 'yudisium_mahasiswa'])->name('prodi.data-akademik.yudisium-mahasiswa');

                Route::prefix('tugas-akhir')->group(function(){
                    Route::get('/', [App\Http\Controllers\Prodi\Akademik\TugasAkhirController::class, 'index'])->name('prodi.data-akademik.tugas-akhir');
                    Route::post('/approve-pembimbing/{aktivitasMahasiswa}', [App\Http\Controllers\Prodi\Akademik\TugasAkhirController::class, 'approve_pembimbing'])->name('prodi.data-akademik.tugas-akhir.approve-pembimbing');
                    Route::get('/edit-detail/{aktivitas}', [App\Http\Controllers\Prodi\Akademik\TugasAkhirController::class, 'ubah_detail_tugas_akhir'])->name('prodi.data-akademik.tugas-akhir.edit-detail');
                    Route::post('/update-detail/{aktivitas}', [App\Http\Controllers\Prodi\Akademik\TugasAkhirController::class, 'update_detail_tugas_akhir'])->name('prodi.data-akademik.tugas-akhir.update-detail');
                    Route::get('/get-nama-dosen', [App\Http\Controllers\Prodi\Akademik\TugasAkhirController::class, 'get_dosen'])->name('prodi.data-akademik.tugas-akhir.get-dosen');
                    Route::get('/tambah-dosen/{aktivitas}', [App\Http\Controllers\Prodi\Akademik\TugasAkhirController::class, 'tambah_dosen_pembimbing'])->name('prodi.data-akademik.tugas-akhir.tambah-dosen');
                    Route::post('/store-dosen/{aktivitas}', [App\Http\Controllers\Prodi\Akademik\TugasAkhirController::class, 'store_dosen_pembimbing'])->name('prodi.data-akademik.tugas-akhir.store-dosen');
                    Route::get('/edit-dosen/{bimbing}', [App\Http\Controllers\Prodi\Akademik\TugasAkhirController::class, 'edit_dosen_pembimbing'])->name('prodi.data-akademik.tugas-akhir.edit-dosen');
                    Route::post('/update-dosen/{bimbing}/{aktivitas}', [App\Http\Controllers\Prodi\Akademik\TugasAkhirController::class, 'update_dosen_pembimbing'])->name('prodi.data-akademik.tugas-akhir.update-dosen');
                    Route::delete('/delete-dosen/{bimbing}', [App\Http\Controllers\Prodi\Akademik\TugasAkhirController::class, 'delete_dosen_pembimbing'])->name('prodi.data-akademik.tugas-akhir.delete-dosen');
                });

                Route::prefix('non-tugas-akhir')->group(function(){
                    Route::get('/', [App\Http\Controllers\Prodi\Akademik\AktivitasNonTAController::class, 'index'])->name('prodi.data-akademik.non-tugas-akhir');
                    Route::post('/approve-pembimbing/{aktivitasMahasiswa}', [App\Http\Controllers\Prodi\Akademik\AktivitasNonTAController::class, 'approve_pembimbing'])->name('prodi.data-akademik.non-tugas-akhir.approve-pembimbing');
                    Route::get('/edit-detail/{aktivitas}', [App\Http\Controllers\Prodi\Akademik\AktivitasNonTAController::class, 'ubah_detail_non_tugas_akhir'])->name('prodi.data-akademik.non-tugas-akhir.edit-detail');
                    Route::post('/update-detail/{aktivitas}', [App\Http\Controllers\Prodi\Akademik\AktivitasNonTAController::class, 'update_detail_non_tugas_akhir'])->name('prodi.data-akademik.non-tugas-akhir.update-detail');
                    Route::get('/get-nama-dosen', [App\Http\Controllers\Prodi\Akademik\AktivitasNonTAController::class, 'get_dosen'])->name('prodi.data-akademik.non-tugas-akhir.get-dosen');
                    Route::get('/tambah-dosen/{aktivitas}', [App\Http\Controllers\Prodi\Akademik\AktivitasNonTAController::class, 'tambah_dosen_pembimbing'])->name('prodi.data-akademik.non-tugas-akhir.tambah-dosen');
                    Route::post('/store-dosen/{aktivitas}', [App\Http\Controllers\Prodi\Akademik\AktivitasNonTAController::class, 'store_dosen_pembimbing'])->name('prodi.data-akademik.non-tugas-akhir.store-dosen');
                    Route::get('/edit-dosen/{bimbing}', [App\Http\Controllers\Prodi\Akademik\AktivitasNonTAController::class, 'edit_dosen_pembimbing'])->name('prodi.data-akademik.non-tugas-akhir.edit-dosen');
                    Route::post('/update-dosen/{bimbing}/{aktivitas}', [App\Http\Controllers\Prodi\Akademik\AktivitasNonTAController::class, 'update_dosen_pembimbing'])->name('prodi.data-akademik.non-tugas-akhir.update-dosen');
                    Route::delete('/delete-dosen/{bimbing}', [App\Http\Controllers\Prodi\Akademik\AktivitasNonTAController::class, 'delete_dosen_pembimbing'])->name('prodi.data-akademik.non-tugas-akhir.delete-dosen');
                });
            });


            //Route for Data Aktivitas
            Route::prefix('data-aktivitas')->group(function(){
                //Route for Data Aktivitas Mahasiswa
                Route::prefix('aktivitas-mahasiswa')->group(function(){
                    Route::get('/', [App\Http\Controllers\Prodi\Akademik\AktivitasMahasiswaKonversiController::class, 'index'])->name('prodi.data-aktivitas.aktivitas-mahasiswa.index');
                    Route::get('/tambah', [App\Http\Controllers\Prodi\Akademik\AktivitasMahasiswaKonversiController::class, 'create'])->name('prodi.data-aktivitas.aktivitas-mahasiswa.create');
                    Route::get('/get-nama-mk', [App\Http\Controllers\Prodi\Akademik\AktivitasMahasiswaKonversiController::class, 'get_mk_konversi'])->name('prodi.data-aktivitas.aktivitas-mahasiswa.get_mk');
                    Route::get('/get-mata-kuliah/{id_kurikulum}', [App\Http\Controllers\Prodi\Akademik\AktivitasMahasiswaKonversiController::class, 'getMataKuliah'])->name('get-mata-kuliah');
                    Route::post('/store', [App\Http\Controllers\Prodi\Akademik\AktivitasMahasiswaKonversiController::class, 'store'])->name('prodi.data-aktivitas.aktivitas-mahasiswa.store');
                    Route::get('/aktivitas-mahasiswa/ubah/{rencana_ajar}', [App\Http\Controllers\Prodi\Akademik\AktivitasMahasiswaKonversiController::class, 'edit'])->name('prodi.data-aktivitas.aktivitas-mahasiswa.ubah');
                    Route::post('/aktivitas-mahasiswa/update/{rencana_ajar}', [App\Http\Controllers\Prodi\Akademik\AktivitasMahasiswaKonversiController::class, 'update'])->name('prodi.data-aktivitas.aktivitas-mahasiswa.update');
                    Route::delete('/delete/{id}', [App\Http\Controllers\Prodi\Akademik\AktivitasMahasiswaKonversiController::class, 'delete'])->name('prodi.data-aktivitas.aktivitas-mahasiswa.delete');
                });
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

                Route::prefix('/pengisian-krs')->group(function(){
                    Route::get('/', [App\Http\Controllers\Prodi\Monitoring\MonitoringDosenController::class, 'pengisian_krs'])->name('prodi.monitoring.pengisian-krs');
                    Route::get('/mahasiswa-aktif', [App\Http\Controllers\Prodi\Monitoring\MonitoringDosenController::class, 'mahasiswa_aktif'])->name('prodi.monitoring.pengisian-krs.mahasiswa-aktif');
                    Route::get('/mahasiswa-aktif-min-tujuh', [App\Http\Controllers\Prodi\Monitoring\MonitoringDosenController::class, 'mahasiswa_aktif_min_tujuh'])->name('prodi.monitoring.pengisian-krs.mahasiswa-aktif-min-tujuh');
                });
            });

            //Route Bantuan
            Route::prefix('bantuan')->group(function () {
                Route::get('/ganti-password', [App\Http\Controllers\Prodi\Bantuan\GantiPasswordController::class, 'ganti_password'])->name('prodi.bantuan.ganti-password');
                Route::post('/proses-ganti-password', [App\Http\Controllers\Prodi\Bantuan\GantiPasswordController::class, 'proses_ganti_password'])->name('prodi.bantuan.proses-ganti-password');
            });
        });

    });

    Route::group(['middleware' => ['role:univ']], function() {
        Route::get('/universitas', [App\Http\Controllers\Universitas\DashboardController::class, 'index'])->name('univ');
        Route::prefix('universitas')->group(function () {

            Route::get('/data-krs', [App\Http\Controllers\Universitas\DashboardController::class, 'index'])->name('univ.data-krs');

            Route::prefix('beasiswa')->group(function() {
                Route::get('/', [App\Http\Controllers\Universitas\BeasiswaController::class, 'index'])->name('univ.beasiswa');
                Route::post('/store', [App\Http\Controllers\Universitas\BeasiswaController::class, 'store'])->name('univ.beasiswa.store');
                Route::patch('/update/{beasiswa}', [App\Http\Controllers\Universitas\BeasiswaController::class, 'update'])->name('univ.beasiswa.update');
                Route::delete('/delete/{beasiswa}', [App\Http\Controllers\Universitas\BeasiswaController::class, 'delete'])->name('univ.beasiswa.delete');
                Route::get('/data', [App\Http\Controllers\Universitas\BeasiswaController::class, 'data'])->name('univ.beasiswa.data');
            });

            Route::prefix('p-bayar')->group(function(){
                Route::get('/', [App\Http\Controllers\Universitas\PenundaanBayarController::class, 'index'])->name('univ.p-bayar');
                Route::post('/store', [App\Http\Controllers\Universitas\PenundaanBayarController::class, 'store'])->name('univ.p-bayar.store');
                Route::patch('/update/{penundaan}', [App\Http\Controllers\Universitas\PenundaanBayarController::class, 'update'])->name('univ.p-bayar.update');
                Route::delete('/delete/{penundaan}', [App\Http\Controllers\Universitas\PenundaanBayarController::class, 'destroy'])->name('univ.p-bayar.delete');
            });

            Route::prefix('pembayaran-manual')->group(function(){
                Route::get('/', [App\Http\Controllers\Universitas\PembayaranManualMahasiswaController::class, 'index'])->name('univ.pembayaran-manual');
                Route::post('/store', [App\Http\Controllers\Universitas\PembayaranManualMahasiswaController::class, 'store'])->name('univ.pembayaran-manual.store');
                Route::patch('/update/{idmanual}', [App\Http\Controllers\Universitas\PembayaranManualMahasiswaController::class, 'update'])->name('univ.pembayaran-manual.update');
                Route::delete('/delete/{idmanual}', [App\Http\Controllers\Universitas\PembayaranManualMahasiswaController::class, 'destroy'])->name('univ.pembayaran-manual.delete');
            });

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

            Route::prefix('monitoring')->group(function(){
                Route::prefix('pengisian-krs')->group(function () {
                    Route::get('/', [App\Http\Controllers\Universitas\MonitoringController::class, 'pengisian_krs'])->name('univ.monitoring.pengisian-krs');
                    Route::get('/data', [App\Http\Controllers\Universitas\MonitoringController::class, 'pengisian_krs_data'])->name('univ.monitoring.pengisian-krs.data');
                    Route::get('/detail-mahasiswa-aktif/{prodi}', [App\Http\Controllers\Universitas\MonitoringController::class, 'detail_mahasiswa_aktif'])->name('univ.monitoring.pengisian-krs.detail-mahasiswa-aktif');
                    Route::get('/detail-aktif-min-tujuh/{prodi}', [App\Http\Controllers\Universitas\MonitoringController::class, 'detail_aktif_min_tujuh'])->name('univ.monitoring.pengisian-krs.detail-aktif-min-tujuh');
                    Route::get('/detail-isi-krs/{prodi}', [App\Http\Controllers\Universitas\MonitoringController::class, 'detail_isi_krs'])->name('univ.monitoring.pengisian-krs.detail-isi-krs');
                });
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
                    Route::get('/data', [App\Http\Controllers\Universitas\PengaturanController::class, 'akun_data'])->name('univ.pengaturan.akun.data');
                    Route::post('/store', [App\Http\Controllers\Universitas\PengaturanController::class, 'akun_store'])->name('univ.pengaturan.akun.store');
                    Route::patch('/update/{user}', [App\Http\Controllers\Universitas\PengaturanController::class, 'akun_update'])->name('univ.pengaturan.akun.update');
                    Route::delete('/delete/{user}', [App\Http\Controllers\Universitas\PengaturanController::class, 'akun_destroy'])->name('univ.pengaturan.akun.delete');

                    Route::post('/dosen-store', [App\Http\Controllers\Universitas\PengaturanController::class, 'akun_dosen_create'])->name('univ.pengaturan.akun.dosen-store');
                    Route::get('/get-dosen', [App\Http\Controllers\Universitas\PengaturanController::class, 'get_dosen'])->name('univ.pengaturan.akun.get-dosen');

                    Route::get('/get-fakultas', [App\Http\Controllers\Universitas\PengaturanController::class, 'get_fakultas'])->name('univ.pengaturan.akun.get-fakultas');
                    Route::post('/fakultas-store', [App\Http\Controllers\Universitas\PengaturanController::class, 'akun_fakultas_create'])->name('univ.pengaturan.akun.fakultas-store');

                    Route::get('/get-mahasiswa', [App\Http\Controllers\Universitas\PengaturanController::class, 'get_mahasiswa'])->name('univ.pengaturan.akun.get-mahasiswa');
                    Route::post('/mahasiswa-store', [App\Http\Controllers\Universitas\PengaturanController::class, 'akun_mahasiswa_create'])->name('univ.pengaturan.akun.mahasiswa-store');
                });
            });
        });
    });
});

