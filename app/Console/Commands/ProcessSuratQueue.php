<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SuratRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProcessSuratQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'desa:process-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memproses antrian surat warga menjadi PDF (Max 5 per run)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pemrosesan antrian surat...');

        // 1. Ambil Antrian (Batch 5)
        // Menggunakan 'lockForUpdate' (opsional tapi bagus) untuk mencegah race condition jika server sibuk
        $antrian = SuratRequest::where('status', 'in_queue')
            ->orderBy('created_at', 'asc')
            ->limit(5)
            ->get();

        if ($antrian->isEmpty()) {
            $this->info('Tidak ada antrian surat.');
            return;
        }

        foreach ($antrian as $surat) {
            $this->info("Memproses Surat ID: {$surat->id} - " . ($surat->template->judul_surat ?? 'Tanpa Judul'));
            
            // Ubah status jadi processing agar tidak diambil worker lain
            $surat->update(['status' => 'processing']);

            try {
                // --- A. LOGIKA PENOMORAN OTOMATIS ---
                
                $tahun = date('Y');
                $bulanRomawi = $this->getRomawi(date('n')); // Menggunakan date('n') 1-12
                
                // Ambil Kode Klasifikasi (Default 470 jika kosong)
                $kodeKlasifikasi = $surat->template->kode_surat ?? '470'; 

                // Hitung Urutan Surat yang sudah completed di tahun ini
                $urutanTerakhir = SuratRequest::whereYear('updated_at', $tahun)
                    ->where('status', 'completed')
                    ->count();
                    
                // Format: 001, 002, dst.
                $nomorUrut = str_pad($urutanTerakhir + 1, 3, '0', STR_PAD_LEFT);

                // Rakit Nomor Surat: 470 / 001 / DS-SMART / XI / 2025
                $nomorSuratFinal = "{$kodeKlasifikasi} / {$nomorUrut} / DS-SMART / {$bulanRomawi} / {$tahun}";


                // --- B. PERSIAPAN DATA (VIEW DATA) ---
                
                // Ambil raw data (sudah array karena casting di Model)
                $rawData = $surat->data_input;
                $viewData = [];

                // Cek jenis template untuk pemrosesan data khusus
                if ($surat->template->view_template === 'templates.surat_keputusan') {
                    // --- LOGIKA KHUSUS SK KEPALA DESA ---
                    
                    // Ambil string dari input, jika kosong default ke string kosong
                    $menimbangText = $rawData['Menimbang (Perlu dipertimbangkan)'] ?? '';
                    $mengingatText = $rawData['Mengingat (Dasar Hukum)'] ?? '';
                    $memutuskanText = $rawData['Diktum 1 (MEMUTUSKAN)'] ?? '';

                    // Pecah teks textarea menjadi array per baris (explode newline)
                    $viewData['menimbang_array'] = !empty($menimbangText) ? explode("\n", $menimbangText) : [];
                    $viewData['mengingat_array'] = !empty($mengingatText) ? explode("\n", $mengingatText) : [];
                    
                    $viewData['tentang'] = $rawData['Tentang (Subjek SK)'] ?? 'PENETAPAN';

                    // Pecah Diktum Memutuskan (Contoh input: "Pasal 1: Isi putusan")
                    $memutuskan_lines = !empty($memutuskanText) ? explode("\n", $memutuskanText) : [];
                    $memutuskan_dict = [];
                    
                    foreach ($memutuskan_lines as $line) {
                        // Pisahkan Key dan Value jika ada titik dua
                        if (strpos($line, ':') !== false) {
                            list($key, $value) = explode(':', $line, 2);
                            $memutuskan_dict[trim($key)] = trim($value);
                        } else {
                            // Jika tidak ada format "Key: Value", masukkan sebagai item biasa
                            $memutuskan_dict[] = trim($line);
                        }
                    }
                    $viewData['memutuskan_array'] = $memutuskan_dict;

                } else {
                    // --- LOGIKA SURAT BIASA (SKTM, Domisili, dll) ---
                    // Langsung pakai data apa adanya
                    $viewData = $rawData; 
                }


                // --- C. GENERASI PDF ---
                
                // Gunakan view yang didefinisikan di database (Dinamis)
                // Default fallback ke 'templates.default_surat' jika kosong (safety)
                $viewTemplate = $surat->template->view_template ?: 'templates.default_surat';

                $pdf = Pdf::loadView($viewTemplate, [
                    'surat' => $surat,
                    'nomor_surat_baru' => $nomorSuratFinal, // Variabel penting untuk view
                    'user' => $surat->user,
                    'pejabat' => $surat->pejabat,
                    'data' => $viewData // Data yang sudah diproses (array biasa atau array SK)
                ])->setPaper('a4', 'portrait');


                // --- D. SIMPAN FILE ---
                
                $fileName = 'SURAT_' . $surat->id . '_' . ($surat->user->nik ?? 'unknown') . '.pdf';
                $path = 'private_docs/' . $fileName;

                // Simpan ke storage (pastikan folder private_docs ada)
                Storage::put($path, $pdf->output());


                // --- E. UPDATE DATABASE (FINALISASI) ---
                
                $surat->update([
                    'status' => 'completed',
                    'file_hasil_path' => $path,
                    'nomor_surat' => $nomorSuratFinal,
                ]);

                $this->info("Sukses: {$fileName} (No: {$nomorSuratFinal})");

            } catch (\Exception $e) {
                // Error Handling agar worker tidak mati total
                Log::error("Gagal memproses surat ID {$surat->id}: " . $e->getMessage());
                
                $surat->update([
                    'status' => 'failed',
                    'catatan_admin' => 'Sistem Error: ' . substr($e->getMessage(), 0, 200)
                ]);
                
                $this->error("Gagal ID {$surat->id}: " . $e->getMessage());
            }
        }

        $this->info('Batch selesai.');
    }

    /**
     * Fungsi Helper untuk mengubah Angka Bulan menjadi Romawi
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