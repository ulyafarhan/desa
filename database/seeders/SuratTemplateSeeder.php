<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratTemplate;

class SuratTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Data master template surat dengan body_text spesifik
        $templates = [
            // 1. SURAT KETERANGAN TIDAK MAMPU (SKTM)
            [
                'kode_surat' => '411',
                'judul_surat' => 'Surat Keterangan Tidak Mampu (SKTM)',
                'deskripsi' => 'Surat untuk keperluan pengajuan bantuan sosial atau pendidikan (Beasiswa).',
                'view_template' => 'templates.default_surat',
                'form_schema' => json_encode(['Keperluan' => 'text', 'Penghasilan Bulanan' => 'text']),
                'body_text' => 'Menerangkan bahwa nama tersebut tergolong keluarga tidak mampu berdasarkan data yang ada di Desa Smart Digital. Surat ini dibuat untuk keperluan pengajuan bantuan sosial/pendidikan.',
                'is_active' => true,
            ],
            // 2. SURAT KETERANGAN DOMISILI (SKD)
            [
                'kode_surat' => '470',
                'judul_surat' => 'Surat Keterangan Domisili (SKD)',
                'deskripsi' => 'Surat keterangan domisili tempat tinggal saat ini.',
                'view_template' => 'templates.default_surat',
                'form_schema' => json_encode(['Tujuan Surat' => 'text', 'Status Rumah' => 'select:Milik Sendiri,Kontrak/Sewa,Menumpang', 'Lama Domisili' => 'text']),
                'body_text' => 'Orang tersebut yang namanya tertulis di atas adalah benar-benar penduduk dan berdomisili di wilayah Desa Smart Digital, Kecamatan Maju Jaya. Surat ini dibuat untuk keperluan [Tujuan Surat] yang diajukan.',
                'is_active' => true,
            ],
            // 3. SURAT KETERANGAN USAHA (SKU)
            [
                'kode_surat' => '503',
                'judul_surat' => 'Surat Keterangan Usaha (SKU)',
                'deskripsi' => 'Surat pengantar untuk keperluan perizinan atau pinjaman modal usaha.',
                'view_template' => 'templates.default_surat',
                'form_schema' => json_encode(['Nama Usaha' => 'text', 'Jenis Usaha' => 'text', 'Lokasi Usaha' => 'text', 'Tahun Mulai Usaha' => 'number']),
                'body_text' => 'Menerangkan bahwa yang bersangkutan memiliki dan menjalankan usaha [Jenis Usaha] yang berlokasi di [Lokasi Usaha]. Surat ini digunakan sebagai pengantar administrasi untuk pengurusan perizinan atau pengajuan modal usaha.',
                'is_active' => true,
            ],
            // 4. SURAT PENGANTAR SKCK (Tersulit, butuh riwayat)
            [
                'kode_surat' => '471',
                'judul_surat' => 'Surat Pengantar SKCK',
                'deskripsi' => 'Pengantar Desa untuk membuat Surat Keterangan Catatan Kepolisian (SKCK).',
                'view_template' => 'templates.default_surat',
                // Mengambil Riwayat Pidana dari Admin
                'form_schema' => json_encode(['Tujuan Polsek/Polres' => 'text', 'Riwayat Sekolah' => 'text', 'Riwayat Pidana (Diisi Admin)' => 'textarea']),
                'body_text' => 'Surat Pengantar ini diberikan untuk melengkapi persyaratan pengajuan Surat Keterangan Catatan Kepolisian (SKCK) di [Tujuan Polsek/Polres]. Segala keterangan mengenai riwayat pidana berada di bawah tanggung jawab pemohon.',
                'is_active' => true,
            ],
            // 5. SURAT KEPUTUSAN (SK) KEPALA DESA (Struktur berbeda)
            [
                'kode_surat' => '100', 
                'judul_surat' => 'Surat Keputusan (SK) Kepala Desa',
                'deskripsi' => 'Keputusan internal untuk penetapan jabatan atau aturan desa.',
                'view_template' => 'templates.surat_keputusan', 
                'form_schema' => json_encode(['Tentang (Subjek SK)' => 'text', 'Menimbang (Perlu dipertimbangkan)' => 'textarea', 'Mengingat (Dasar Hukum)' => 'textarea', 'Diktum 1 (MEMUTUSKAN)' => 'textarea']), // Ganti text ke textarea agar bisa dipecah per baris
                'body_text' => 'Surat Keputusan ini berfungsi sebagai penetapan legal yang dikeluarkan oleh Kepala Desa.',
                'is_active' => true,
            ],
            // 6. SURAT PENGANTAR NIKAH (N1-N4)
            [
                'kode_surat' => '474',
                'judul_surat' => 'Surat Pengantar Nikah (N1-N4)',
                'deskripsi' => 'Pengantar wajib dari Desa untuk pengajuan pernikahan ke KUA.',
                'view_template' => 'templates.default_surat',
                'form_schema' => json_encode(['Nama Calon Pasangan' => 'text', 'NIK Calon Pasangan' => 'text', 'Agama Calon Pasangan' => 'select:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu', 'Tujuan KUA' => 'text', 'Status Pernikahan Sebelumnya' => 'select:Jejaka/Perawan,Duda/Janda']),
                'body_text' => 'Orang tersebut adalah benar warga Desa Smart Digital dan berstatus [Status Pernikahan Sebelumnya]. Surat Pengantar ini dibuat sebagai kelengkapan persyaratan pengajuan pernikahan ke Kantor Urusan Agama (KUA) [Tujuan KUA].',
                'is_active' => true,
            ],
            // 7. SURAT PENGANTAR RT/RW
            [
                'kode_surat' => '473',
                'judul_surat' => 'Surat Pengantar RT/RW',
                'deskripsi' => 'Pengantar Desa berdasarkan rekomendasi dari Ketua RT dan RW.',
                'view_template' => 'templates.default_surat',
                'form_schema' => json_encode(['Tujuan Surat' => 'text', 'Nomor Surat Pengantar RT' => 'text', 'Nomor Surat Pengantar RW' => 'text']),
                'body_text' => 'Menerangkan bahwa surat pengantar ini dibuat berdasarkan rekomendasi Ketua RT dan RW setempat. Digunakan untuk keperluan [Tujuan Surat] yang bersangkutan.',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            SuratTemplate::updateOrCreate(
                ['kode_surat' => $template['kode_surat']],
                $template
            );
        }
    }
}