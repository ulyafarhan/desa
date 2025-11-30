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
            
            // Lock status
            $surat->update(['status' => 'processing']);

            try {
                // --- LOGIKA PENOMORAN OTOMATIS ---
                
                // 1. Ambil Tahun & Bulan Romawi
                $tahun = date('Y');
                // date('n') menghasilkan angka 1-12 tanpa nol di depan
                $bulanRomawi = $this->getRomawi(date('n')); 
                
                // 2. Ambil Kode Klasifikasi (Default 470 jika kosong di template)
                $kodeKlasifikasi = $surat->template->kode_surat ?? '470'; 

                // 3. Hitung Urutan Surat
                $urutanTerakhir = SuratRequest::whereYear('updated_at', $tahun)
                    ->where('status', 'completed')
                    ->count();
                    
                $nomorUrut = str_pad($urutanTerakhir + 1, 3, '0', STR_PAD_LEFT);

                // 4. Format Nomor: 470 / 001 / DS-SMART / XI / 2025
                $nomorSuratFinal = "{$kodeKlasifikasi} / {$nomorUrut} / DS-SMART / {$bulanRomawi} / {$tahun}";

                // --- PEMROSESAN DATA SK KHUSUS (Jika ini Surat Keputusan) ---
                if ($surat->template->view_template === 'templates.surat_keputusan') {
                    $dataArray = json_decode($surat->data_input, true);
                    
                    // Pecah teks panjang menjadi array per baris untuk diktum
                    $data['menimbang_array'] = explode("\n", $dataArray['Menimbang (Perlu dipertimbangkan)']);
                    $data['mengingat_array'] = explode("\n", $dataArray['Mengingat (Dasar Hukum)']);
                    
                    // Pecah Diktum Memutuskan (Contoh: Pasal 1: [Isi])
                    $memutuskan_raw = $dataArray['Diktum 1 (MEMUTUSKAN)'];
                    $memutuskan_lines = explode("\n", $memutuskan_raw);
                    $memutuskan_dict = [];
                    foreach ($memutuskan_lines as $line) {
                        if (strpos($line, ':') !== false) {
                            list($key, $value) = explode(':', $line, 2);
                            $memutuskan_dict[trim($key)] = trim($value);
                        }
                    }
                    
                    // Kirim data yang sudah dipecah ke Blade
                    $data['tentang'] = $dataArray['Tentang (Subjek SK)'];
                    $data['memutuskan_array'] = $memutuskan_dict;
                } else {
                    // Jika surat biasa, ambil data input seperti biasa
                    $data = $surat->data_input; 
                }

                // Ganti Pdf::loadView agar menggunakan variabel $data yang baru:
                $pdf = Pdf::loadView($surat->template->view_template, [ // <-- Ganti dari 'templates.default_surat'
                    'surat' => $surat,
                    'nomor_surat_baru' => $nomorSuratFinal, 
                    'user' => $surat->user,
                    'pejabat' => $surat->pejabat,
                    'data' => $data // <-- Kirim data yang sudah dipecah
                ])->setPaper('a4', 'portrait');

                // --- GENERASI PDF ---
                
                $pdf = Pdf::loadView('templates.default_surat', [
                    'surat' => $surat,
                    'nomor_surat_baru' => $nomorSuratFinal, // Variabel ini dikirim ke Blade
                    'user' => $surat->user,
                    'pejabat' => $surat->pejabat,
                    'data' => $surat->data_input 
                ])->setPaper('a4', 'portrait');

                // --- SIMPAN FILE ---
                
                $fileName = 'SURAT_' . $surat->id . '_' . $surat->user->nik . '.pdf';
                $path = 'private_docs/' . $fileName;

                Storage::put($path, $pdf->output());

                // --- UPDATE DATABASE ---
                
                $surat->update([
                    'status' => 'completed',
                    'file_hasil_path' => $path,
                    'nomor_surat' => $nomorSuratFinal,
                ]);

                $this->info("Sukses: {$fileName} (No: {$nomorSuratFinal})");

            } catch (\Exception $e) {
                // Error Handling
                Log::error("Gagal memproses surat ID {$surat->id}: " . $e->getMessage());
                
                $surat->update([
                    'status' => 'failed',
                    'catatan_admin' => 'Sistem Error: ' . substr($e->getMessage(), 0, 200)
                ]);
                
                $this->error("Gagal: " . $e->getMessage());
            }
        }

        $this->info('Batch selesai.');
    }

    /**
     * Fungsi Helper untuk mengubah Angka Bulan menjadi Romawi
     * Posisinya HARUS di dalam Class, tapi di luar function handle()
     */
    private function getRomawi($bulan)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        
        return $map[(int)$bulan] ?? 'I';
    }
}