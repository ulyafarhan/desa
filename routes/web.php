<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\ChatController;
use App\Models\Panduan;
use App\Http\Controllers\AiChatController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public Routes (Bisa diakses Siapa Saja)
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
        'dynamicGuides' => $guides,
    ]);
})->name('panduan');

Route::get('/kontak', function () {
    return Inertia::render('Kontak', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('kontak');

// --- CHAT SYSTEM (GLOBAL) ---
// Diletakkan di sini agar Tamu (Guest) bisa akses tanpa error 401.
// Controller akan otomatis mendeteksi apakah pengirimnya User Login atau Tamu.
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
Route::get('/chat/history', [ChatController::class, 'getHistory'])->name('chat.history');


/*
|--------------------------------------------------------------------------
| Authenticated Routes (Wajib Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Manajemen Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Lihat Dokumen Aman (Secure Streaming)
    Route::get('/dokumen/lihat/{surat}', [DocumentController::class, 'show'])->name('dokumen.show');
    Route::get('/api/ai-chat', [AiChatController::class, 'index'])->name('ai.chat.index');
    Route::post('/api/ai-chat', [AiChatController::class, 'store'])->name('ai.chat.store');
    Route::delete('/api/ai-chat', [AiChatController::class, 'destroy'])->name('ai.chat.clear');
});


/*
|--------------------------------------------------------------------------
| Warga Area (Wajib Login & Email Verified)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Warga
    Route::get('/dashboard', [WargaController::class, 'index'])->name('dashboard');
    
    // Formulir Pengajuan Surat Dinamis
    Route::get('/formulir/{template}', [WargaController::class, 'showForm'])->name('warga.form');
    Route::post('/formulir', [WargaController::class, 'store'])->name('warga.store'); 
    
});

require __DIR__.'/auth.php';