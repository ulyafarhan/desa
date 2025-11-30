<?php

namespace App\Http\Controllers;

use App\Models\SuratRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function show(SuratRequest $surat)
    {
        // 1. KEAMANAN: Pastikan User Login
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Akses Ditolak');
        }

        // 2. OTORISASI: Siapa yang boleh lihat?
        // - Admin & Staff: Boleh lihat semua surat
        // - Warga: HANYA boleh lihat surat miliknya sendiri
        if ($user->role === 'warga' && $surat->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses ke dokumen ini.');
        }

        // 3. CEK FISIK FILE
        // Pastikan path file ada di database dan fisiknya ada di storage
        if (!$surat->file_hasil_path || !Storage::exists($surat->file_hasil_path)) {
            abort(404, 'File dokumen fisik tidak ditemukan di server.');
        }

        // 4. STREAMING FILE (Secure View)
        // 'inline' berarti buka di browser. Kalau mau auto-download, ganti jadi 'attachment'
        return response()->file(Storage::path($surat->file_hasil_path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="SURAT-DESA-'.$surat->id.'.pdf"'
        ]);
    }
}