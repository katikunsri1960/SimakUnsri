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
        Route::get('/mahasiswa', [App\Http\Controllers\Mahasiswa\DashboardController::class, 'index'])->name('mahasiswa');
    });

    Route::group(['middleware' => ['role:dosen']], function() {
        Route::get('/dosen', [App\Http\Controllers\Dosen\DashboardController::class, 'index'])->name('dosen');
    });

    Route::group(['middleware' => ['role:univ']], function() {
        Route::get('/universitas', [App\Http\Controllers\Universitas\DashboardController::class, 'index'])->name('univ');
    });
});

