<?php

namespace App\Http\Controllers;

use App\Models\SuratTemplate;
use App\Models\SuratRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WargaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil template aktif, kecuali template SK (karena itu untuk admin)
        $templates = SuratTemplate::where('is_active', true)
            ->where('view_template', '!=', 'templates.surat_keputusan') 
            ->get(['id', 'judul_surat', 'deskripsi', 'form_schema']); 

        $riwayat = SuratRequest::where('user_id', $user->id)
            ->with(['template:id,judul_surat'])
            ->latest()
            ->get(['id', 'template_id', 'status', 'nomor_surat', 'created_at']);

        return Inertia::render('Warga/Dashboard', [
            'user' => $user->only('id', 'name', 'nik', 'status_akun'),
            'templates' => $templates,
            'riwayat' => $riwayat,
        ]);
    }
    
    public function showForm(SuratTemplate $template)
    {
        // 1. Cek Status Akun: Wajib verified
        if (Auth::user()->status_akun !== 'verified') {
             // PERBAIKAN 1: Ganti 'warga.dashboard' jadi 'dashboard'
             return redirect()->route('dashboard')
                ->withErrors(['message' => 'Akun Anda belum diverifikasi oleh Admin Desa.']);
        }
        
        $schema = json_decode($template->form_schema, true);
        
        return Inertia::render('Warga/FormulirPengajuan', [
            'template' => $template->only('id', 'judul_surat', 'deskripsi'),
            'form_schema' => $schema,
            'user_data' => Auth::user()->only('name', 'nik', 'alamat_lengkap', 'tempat_lahir', 'tanggal_lahir') 
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:surat_templates,id',
            'form_data' => 'required|array', 
        ]);

        $user = Auth::user();
        if ($user->status_akun !== 'verified') {
            return back()->withErrors(['message' => 'Akun belum diverifikasi. Pengajuan dibatalkan.']);
        }

        SuratRequest::create([
            'user_id' => $user->id,
            'template_id' => $request->template_id,
            'data_input' => $request->form_data, 
            'status' => 'pending', 
        ]);

        // PERBAIKAN 2: Ganti 'warga.dashboard' jadi 'dashboard'
        return redirect()->route('dashboard')
            ->with('success', 'Pengajuan surat berhasil dikirim! Silakan tunggu proses persetujuan Admin.');
    }
}