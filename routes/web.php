<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\WargaController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------
| Authenticated & Warga Routes (Requires Auth)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Rute Profile Bawaan Laravel
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rute Secure Document View (Membutuhkan AUTH saja, karena otorisasi dilakukan di Controller)
    Route::get('/dokumen/lihat/{surat}', [DocumentController::class, 'show'])->name('dokumen.show');
});


/*
|--------------------------------------------------------------------------
| Warga Dashboard & Pengajuan Routes (Requires Auth & Verified)
|--------------------------------------------------------------------------
| Warga harus terautentikasi dan diverifikasi (verified) untuk mengakses 
| dashboard dan formulir pengajuan.
*/

// Catatan: Middleware 'verified' di Breeze umumnya merujuk pada email_verified. 
// Dalam sistem kita, ini memastikan user sudah melewati langkah verifikasi awal.
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Utama Warga: Ganti rute dashboard bawaan
    Route::get('/dashboard', [WargaController::class, 'index'])->name('dashboard');
    
    // Rute Formulir Pengajuan Surat
    Route::get('/formulir/{template}', [WargaController::class, 'showForm'])->name('warga.form');
    Route::post('/formulir', [WargaController::class, 'store'])->name('warga.store'); 

});


require __DIR__.'/auth.php';