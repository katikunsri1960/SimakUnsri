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
            Route::get('/biodata', [App\Http\Controllers\Mahasiswa\BiodataController::class, 'index'])->name('mahasiswa.biodata');
            Route::get('/kartu-rencana-studi', [App\Http\Controllers\Mahasiswa\AkademikController::class, 'krs'])->name('mahasiswa.krs');
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

            Route::prefix('profile-dosen')->group(function () {
                Route::get('/biodata-dosen', [App\Http\Controllers\Dosen\BiodataDosenController::class, 'biodata_dosen'])->name('dosen.profile.biodata');
                // Route::get('/sync-prodi', [App\Http\Controllers\Universitas\ReferensiController::class, 'sync_prodi'])->name('univ.referensi.prodi.sync');
            });

            // Route::prefix('kurikulum')->group(function () {
            //     Route::get('/', [App\Http\Controllers\Universitas\KurikulumController::class, 'index'])->name('univ.kurikulum');
            //     Route::get('/sync-kurikulum', [App\Http\Controllers\Universitas\KurikulumController::class, 'sync_kurikulum'])->name('univ.kurikulum.sync');
            // });

            // Route::prefix('mata-kuliah')->group(function () {
            //     Route::get('/', [App\Http\Controllers\Universitas\KurikulumController::class, 'matkul'])->name('univ.mata-kuliah');
            //     Route::get('/data', [App\Http\Controllers\Universitas\KurikulumController::class, 'matkul_data'])->name('univ.mata-kuliah.data');
            //     Route::get('/sync-mata-kuliah', [App\Http\Controllers\Universitas\KurikulumController::class, 'sync_mata_kuliah'])->name('univ.mata-kuliah.sync');
            // });
        });
    });

    Route::group(['middleware' => ['role:prodi']], function() {
        Route::get('/prodi', [App\Http\Controllers\Prodi\DashboardController::class, 'index'])->name('prodi');


    });

    Route::group(['middleware' => ['role:fakultas']], function() {
        Route::get('/fakultas', [App\Http\Controllers\Fakultas\DashboardController::class, 'index'])->name('fakultas');

    });

    Route::group(['middleware' => ['role:univ']], function() {
        Route::get('/universitas', [App\Http\Controllers\Universitas\DashboardController::class, 'index'])->name('univ');
        Route::prefix('universitas')->group(function () {

            Route::prefix('referensi')->group(function () {
                Route::get('/prodi', [App\Http\Controllers\Universitas\ReferensiController::class, 'prodi'])->name('univ.referensi.prodi');
                Route::get('/sync-prodi', [App\Http\Controllers\Universitas\ReferensiController::class, 'sync_prodi'])->name('univ.referensi.prodi.sync');
            });

            Route::prefix('kurikulum')->group(function () {
                Route::get('/', [App\Http\Controllers\Universitas\KurikulumController::class, 'index'])->name('univ.kurikulum');
                Route::get('/sync-kurikulum', [App\Http\Controllers\Universitas\KurikulumController::class, 'sync_kurikulum'])->name('univ.kurikulum.sync');
            });

            Route::prefix('mata-kuliah')->group(function () {
                Route::get('/', [App\Http\Controllers\Universitas\KurikulumController::class, 'matkul'])->name('univ.mata-kuliah');
                Route::get('/data', [App\Http\Controllers\Universitas\KurikulumController::class, 'matkul_data'])->name('univ.mata-kuliah.data');
                Route::get('/sync-mata-kuliah', [App\Http\Controllers\Universitas\KurikulumController::class, 'sync_mata_kuliah'])->name('univ.mata-kuliah.sync');
            });
        });
    });
});

