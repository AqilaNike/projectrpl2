<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// ─── Auth ───────────────────────────────────────────────────────────────────
// Redirect root to the named login route to avoid duplicate route names
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login',   [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',  [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Patient Routes ──────────────────────────────────────────────────────────
Route::prefix('pasien')->name('patient.')->middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/home',            [PatientController::class, 'home'])->name('home');
    Route::get('/ambil-antrean',   [PatientController::class, 'ambilAntrean'])->name('ambil-antrean');
    Route::get('/api/dokter',      [PatientController::class, 'getDokterByPoli'])->name('api.dokter');
    Route::get('/api/jadwal',      [PatientController::class, 'getJadwalByDokter'])->name('api.jadwal');
    Route::post('/antrean',        [PatientController::class, 'storeAntrean'])->name('store-antrean');
    Route::get('/tiket/{antrean}', [PatientController::class, 'tiket'])->name('tiket');
    Route::post('/antrean/{antrean}/batal', [PatientController::class, 'batalAntrean'])->name('batal-antrean');
    Route::get('/jadwal-saya',     [PatientController::class, 'jadwalSaya'])->name('jadwal');
});

// ─── Admin Routes ────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,doctor'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/monitor',   [AdminController::class, 'monitor'])->name('monitor');

    // Queue Management
    Route::get('/antrean',                           [AdminController::class, 'antrean'])->name('antrean');
    Route::post('/antrean/{antrean}/panggil',         [AdminController::class, 'panggilAntrean'])->name('panggil');
    Route::post('/antrean/{antrean}/selesaikan',      [AdminController::class, 'selesaikanAntrean'])->name('selesaikan');
    Route::post('/antrean/{antrean}/batal',           [AdminController::class, 'batalkanAntrean'])->name('batal');

    // Schedule Management
    Route::get('/jadwal',                             [AdminController::class, 'jadwal'])->name('jadwal');
    Route::post('/jadwal',                            [AdminController::class, 'storeJadwal'])->name('jadwal.store');
    Route::post('/poli/{poli}/kuota',                 [AdminController::class, 'updateKuotaPoli'])->name('poli.kuota');
    Route::post('/poli/{poli}/toggle',                [AdminController::class, 'togglePoli'])->name('poli.toggle');

    // Extra Pages
    Route::get('/notifikasi',                         [AdminController::class, 'notifikasi'])->name('notifikasi');
    Route::get('/pengaturan',                         [AdminController::class, 'pengaturan'])->name('pengaturan');
});


