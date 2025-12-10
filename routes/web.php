<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\ChatController;
use App\Models\Panduan;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public Routes (Halaman Depan)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

Route::get('/panduan', function () {
    // Ambil panduan yang aktif
    $guides = Panduan::where('is_active', true)->latest()->get();

    return Inertia::render('Panduan', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'dynamicGuides' => $guides, // <-- Kirim data ke Vue
    ]);
})->name('panduan');

Route::get('/kontak', function () {
    return Inertia::render('Kontak', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('kontak');

// Route Chat Publik
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
Route::get('/chat/history', [ChatController::class, 'getHistory'])->name('chat.history');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Wajib Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Manajemen Profil Bawaan
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Lihat Dokumen Aman (Streaming)
    // Otorisasi detail (apakah user berhak lihat) ditangani di dalam Controller
    Route::get('/dokumen/lihat/{surat}', [DocumentController::class, 'show'])->name('dokumen.show');
    Route::get('/chat/history', [ChatController::class, 'getHistory'])->name('chat.history');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
});

/*
|--------------------------------------------------------------------------
| Warga Area (Wajib Login & Verified)
|--------------------------------------------------------------------------
| Area ini memuat Dashboard Warga dan Formulir Pengajuan.
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Warga (Menggantikan Dashboard default Breeze)
    Route::get('/dashboard', [WargaController::class, 'index'])->name('dashboard');
    
    // Formulir Pengajuan Surat Dinamis
    Route::get('/formulir/{template}', [WargaController::class, 'showForm'])->name('warga.form');
    Route::post('/formulir', [WargaController::class, 'store'])->name('warga.store'); 

    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/history', [ChatController::class, 'getHistory'])->name('chat.history');

});

require __DIR__.'/auth.php';