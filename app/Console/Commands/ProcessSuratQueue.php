<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SuratRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProcessSuratQueue extends Command
{
    protected $signature = 'desa:process-queue';
    protected $description = 'Memproses antrian surat warga menjadi PDF (Max 5 per run)';

    public function handle()
    {
        $this->info('Memulai pemrosesan antrian surat...');

        // 1. Ambil Antrian (Batch 5)
        $antrian = SuratRequest::where('status', 'in_queue')
            ->orderBy('created_at', 'asc')
            ->limit(5)
            ->get();

        if ($antrian->isEmpty()) {
            $this->info('Tidak ada antrian surat.');
            return;
        }

        foreach ($antrian as $surat) {
            $this->info("Memproses Surat ID: {$surat->id}");
            
            // Lock status agar tidak diproses worker lain
            $surat->update(['status' => 'processing']);

            try {
                // --- A. LOGIKA PENOMORAN OTOMATIS ---
                $tahun = date('Y');
                $bulanRomawi = $this->getRomawi(date('n'));
                $kodeKlasifikasi = $surat->template->kode_surat ?? '470'; 

                $urutanTerakhir = SuratRequest::whereYear('updated_at', $tahun)
                    ->where('status', 'completed')
                    ->count();
                    
                $nomorUrut = str_pad($urutanTerakhir + 1, 3, '0', STR_PAD_LEFT);
                $nomorSuratFinal = "{$kodeKlasifikasi}/{$nomorUrut}/DS-SMART/{$bulanRomawi}/{$tahun}";

                // --- B. PERSIAPAN DATA & REPLACE PLACEHOLDER ---
                $rawData = $surat->data_input; // Ini sudah ARRAY (casting model)
                $viewData = []; 
                $bodyTextProcessed = $surat->template->body_text; // Ambil teks asli dari template

                // Cek apakah ini Surat Keputusan (SK) atau Surat Biasa
                if ($surat->template->view_template === 'templates.surat_keputusan') {
                    // LOGIKA SK KEPALA DESA (Kompleks)
                    $menimbang = $rawData['Menimbang (Perlu dipertimbangkan)'] ?? '';
                    $mengingat = $rawData['Mengingat (Dasar Hukum)'] ?? '';
                    $memutuskan = $rawData['Diktum 1 (MEMUTUSKAN)'] ?? '';

                    $viewData['menimbang_array'] = !empty($menimbang) ? explode("\n", $menimbang) : [];
                    $viewData['mengingat_array'] = !empty($mengingat) ? explode("\n", $mengingat) : [];
                    $viewData['tentang'] = $rawData['Tentang (Subjek SK)'] ?? 'PENETAPAN';

                    $memutuskan_lines = !empty($memutuskan) ? explode("\n", $memutuskan) : [];
                    $memutuskan_dict = [];
                    foreach ($memutuskan_lines as $line) {
                        if (strpos($line, ':') !== false) {
                            list($k, $v) = explode(':', $line, 2);
                            $memutuskan_dict[trim($k)] = trim($v);
                        } else {
                            $memutuskan_dict[] = trim($line);
                        }
                    }
                    $viewData['memutuskan_array'] = $memutuskan_dict;

                } else {
                    // LOGIKA SURAT BIASA (SKTM, SKU, dll)
                    $viewData = $rawData;

                    // *** FITUR PENTING: REPLACE PLACEHOLDER [Key] dengan Value ***
                    if ($bodyTextProcessed) {
                        foreach ($rawData as $key => $value) {
                            // Cari [Jenis Usaha], ganti dengan "Warung Sembako"
                            $bodyTextProcessed = str_ireplace("[{$key}]", $value, $bodyTextProcessed);
                        }
                        
                        // Replace data user standar juga jika ada di teks
                        $bodyTextProcessed = str_ireplace("[Nama]", $surat->user->name, $bodyTextProcessed);
                        $bodyTextProcessed = str_ireplace("[NIK]", $surat->user->nik, $bodyTextProcessed);
                    }
                }

                // --- C. GENERASI PDF ---
                // Gunakan view template yang benar, fallback ke default
                $viewTemplate = $surat->template->view_template ?: 'templates.default_surat';

                $pdf = Pdf::loadView($viewTemplate, [
                    'surat' => $surat,
                    'nomor_surat_baru' => $nomorSuratFinal, 
                    'user' => $surat->user,
                    'pejabat' => $surat->pejabat,
                    'data' => $viewData, // Data tabel
                    'processed_body' => $bodyTextProcessed // Teks paragraf yang sudah diganti
                ])->setPaper('a4', 'portrait');

                // --- D. SIMPAN FILE ---
                $fileName = 'SURAT_' . $surat->id . '_' . $surat->user->nik . '.pdf';
                $path = 'private_docs/' . $fileName;
                Storage::put($path, $pdf->output());

                // --- E. UPDATE DATABASE ---
                $surat->update([
                    'status' => 'completed',
                    'file_hasil_path' => $path,
                    'nomor_surat' => $nomorSuratFinal,
                ]);

                $this->info("Sukses: {$fileName} (No: {$nomorSuratFinal})");

            } catch (\Exception $e) {
                Log::error("Gagal ID {$surat->id}: " . $e->getMessage());
                $surat->update(['status' => 'failed', 'catatan_admin' => 'Error Sistem: ' . substr($e->getMessage(), 0, 100)]);
            }
        }
        $this->info('Batch selesai.');
    }

    private function getRomawi($bulan) {
        $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        return $map[(int)$bulan] ?? 'I';
    }
}