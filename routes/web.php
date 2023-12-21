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
    Route::group(['middleware' => ['role:mahasiswa'], 'as' => 'mahasiswa.'], function() {
        Route::get('/dashboard', [App\Http\Controllers\Mahasiswa\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/biodata', [App\Http\Controllers\Mahasiswa\BiodataController::class, 'index'])->name('biodata');
        Route::get('/krs', [App\Http\Controllers\Mahasiswa\KrsController::class, 'index'])->name('krs');
        Route::get('/biaya-kuliah', [App\Http\Controllers\Mahasiswa\BiayaKuliahController::class, 'index'])->name('biaya-kuliah');
        Route::get('/bahan-tugas', [App\Http\Controllers\Mahasiswa\BahanTugasController::class, 'index'])->name('bahan-tugas');
        Route::get('/jadwal-presensi', [App\Http\Controllers\Mahasiswa\JadwalPresensiController::class, 'index'])->name('jadwal-presensi');
        Route::get('/pa-online', [App\Http\Controllers\Mahasiswa\PAController::class, 'index'])->name('pa-online');
        Route::get('/kuisioner', [App\Http\Controllers\Mahasiswa\KuisionerController::class, 'index'])->name('kuisioner');
        Route::get('/nilai', [App\Http\Controllers\Mahasiswa\NilaiController::class, 'index'])->name('nilai');
        Route::get('/skpi', [App\Http\Controllers\Mahasiswa\SKPIController::class, 'index'])->name('skpi');
        Route::get('/kegiatan-akademik', [App\Http\Controllers\Mahasiswa\KegiatanController::class, 'akademik'])->name('kegiatan-akademik');
        Route::get('/kegiatan-seminar', [App\Http\Controllers\Mahasiswa\KegiatanController::class, 'seminar'])->name('kegiatan-seminar');
        Route::get('/pengajuan-cuti', [App\Http\Controllers\Mahasiswa\CutiController::class, 'index'])->name('pengajuan-cuti');       
    });

    Route::group(['middleware' => ['role:dosen']], function() {
        Route::get('/dosen', [App\Http\Controllers\Dosen\DashboardController::class, 'index'])->name('dosen');


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

