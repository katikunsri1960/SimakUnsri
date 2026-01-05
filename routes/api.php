<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ApiKehadiranDosen;
use App\Http\Controllers\API\ApiKehadiranMahasiswa;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware(['auth:sanctum', 'role:univ'])->group(function () {
    Route::get('/kehadiran-dosen', [ApiKehadiranDosen::class, 'kehadiran_dosen']);
    Route::get('/kehadiran-mahasiswa', [ApiKehadiranMahasiswa::class, 'kehadiran_mahasiswa']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

