<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Panduan;

class PanduanSeeder extends Seeder
{
    public function run(): void
    {
        $panduans = [
            [
                'judul' => 'Pembuatan KTP Elektronik (e-KTP) Baru',
                'icon' => 'CreditCard',
                'langkah_langkah' => [
                    ['isi' => 'Pastikan telah berusia 17 tahun atau sudah menikah.'],
                    ['isi' => 'Minta Surat Pengantar dari RT dan RW setempat.'],
                    ['isi' => 'Datang ke Kantor Desa membawa Fotokopi Kartu Keluarga (KK).'],
                    ['isi' => 'Petugas Desa akan memverifikasi data dan memberikan formulir F1.21.'],
                    ['isi' => 'Bawa berkas ke Kantor Kecamatan untuk perekaman foto dan sidik jari (Biometrik).'],
                    ['isi' => 'Tunggu proses pencetakan KTP oleh Disdukcapil (biasanya 14 hari kerja).'],
                ],
            ],
            [
                'judul' => 'Pengurusan KTP Hilang atau Rusak',
                'icon' => 'CreditCard',
                'langkah_langkah' => [
                    ['isi' => 'Jika hilang, buat Surat Keterangan Kehilangan dari Polsek setempat.'],
                    ['isi' => 'Jika rusak, bawa fisik KTP yang rusak.'],
                    ['isi' => 'Datang ke Kantor Desa dengan membawa Fotokopi KK dan Surat Kehilangan/KTP Rusak.'],
                    ['isi' => 'Petugas akan membuatkan Surat Pengantar Cetak Ulang.'],
                    ['isi' => 'Bawa berkas ke Disdukcapil atau Kecamatan untuk pencetakan ulang.'],
                ],
            ],
            [
                'judul' => 'Pembuatan Kartu Keluarga (KK) Baru',
                'icon' => 'Home',
                'langkah_langkah' => [
                    ['isi' => 'Siapkan Surat Pengantar RT/RW.'],
                    ['isi' => 'Bawa Buku Nikah/Akta Perkawinan (bagi pengantin baru).'],
                    ['isi' => 'Bawa Surat Keterangan Pindah (bagi pendatang baru).'],
                    ['isi' => 'Datang ke Kantor Desa untuk mengisi formulir F1.01.'],
                    ['isi' => 'Berkas akan diproses oleh Desa untuk diteruskan ke Kecamatan/Disdukcapil.'],
                ],
            ],
            [
                'judul' => 'Penambahan Anggota Keluarga (Kelahiran)',
                'icon' => 'User',
                'langkah_langkah' => [
                    ['isi' => 'Siapkan Surat Keterangan Lahir dari Bidan/Rumah Sakit.'],
                    ['isi' => 'Bawa KK asli dan KTP orang tua.'],
                    ['isi' => 'Minta Pengantar RT/RW.'],
                    ['isi' => 'Lapor ke Kantor Desa untuk update data KK.'],
                    ['isi' => 'Proses pembuatan KK baru sekaligus Akta Kelahiran.'],
                ],
            ],
            [
                'judul' => 'Pembuatan Akta Kelahiran',
                'icon' => 'FileText',
                'langkah_langkah' => [
                    ['isi' => 'Siapkan Surat Keterangan Lahir asli dari medis.'],
                    ['isi' => 'Fotokopi Buku Nikah orang tua (legalisir KUA jika perlu).'],
                    ['isi' => 'Fotokopi KK dan KTP orang tua.'],
                    ['isi' => 'Isi formulir permohonan Akta di Kantor Desa.'],
                    ['isi' => 'Berkas lengkap dibawa ke Disdukcapil.'],
                ],
            ],
            [
                'judul' => 'Pembuatan Akta Kematian',
                'icon' => 'FileText',
                'langkah_langkah' => [
                    ['isi' => 'Minta Surat Keterangan Kematian dari Rumah Sakit atau Visum Dokter (jika ada).'],
                    ['isi' => 'Jika meninggal di rumah, minta Surat Keterangan Kematian dari Desa (Formulir A.2.29).'],
                    ['isi' => 'Bawa KTP asli dan KK almarhum/almarhumah untuk ditarik.'],
                    ['isi' => 'Bawa KTP pelapor dan 2 orang saksi.'],
                    ['isi' => 'Proses penerbitan Akta Kematian di Disdukcapil.'],
                ],
            ],
            [
                'judul' => 'Surat Keterangan Tidak Mampu (SKTM)',
                'icon' => 'FileText',
                'langkah_langkah' => [
                    ['isi' => 'Wajib mendapatkan Surat Pengantar dari RT dan RW yang menyatakan kondisi ekonomi.'],
                    ['isi' => 'Bawa Fotokopi KTP dan KK.'],
                    ['isi' => 'Bawa bukti pendukung (misal: kartu KIP/KIS jika ada, atau foto kondisi rumah).'],
                    ['isi' => 'Ajukan permohonan lewat Aplikasi DESA-Smart atau datang langsung.'],
                    ['isi' => 'Petugas Desa akan memverifikasi dan menandatangani surat.'],
                ],
            ],
            [
                'judul' => 'Pengantar SKCK (Catatan Kepolisian)',
                'icon' => 'Briefcase',
                'langkah_langkah' => [
                    ['isi' => 'Minta Pengantar RT/RW.'],
                    ['isi' => 'Siapkan Fotokopi KTP dan KK.'],
                    ['isi' => 'Ajukan surat "Pengantar SKCK" melalui dashboard DESA-Smart.'],
                    ['isi' => 'Setelah surat pengantar dari desa terbit, bawa ke Polsek setempat.'],
                    ['isi' => 'Di Polsek, lengkapi dengan pas foto 4x6 (latar merah) dan sidik jari.'],
                ],
            ],
            [
                'judul' => 'Surat Keterangan Usaha (SKU)',
                'icon' => 'Briefcase',
                'langkah_langkah' => [
                    ['isi' => 'Pastikan Anda memiliki usaha yang berjalan di wilayah desa.'],
                    ['isi' => 'Minta Pengantar RT/RW yang membenarkan adanya usaha tersebut.'],
                    ['isi' => 'Ajukan permohonan lewat aplikasi DESA-Smart dengan menyertakan detail usaha.'],
                    ['isi' => 'Jika diperlukan, petugas desa akan melakukan survei lokasi.'],
                    ['isi' => 'SKU diterbitkan dan bisa digunakan untuk persyaratan bank/izin.'],
                ],
            ],
            [
                'judul' => 'Surat Keterangan Domisili (Perorangan)',
                'icon' => 'Home',
                'langkah_langkah' => [
                    ['isi' => 'Bawa KTP asli (dari daerah asal) dan Fotokopinya.'],
                    ['isi' => 'Bawa surat pengantar dari RT/RW tempat tinggal sekarang.'],
                    ['isi' => 'Sertakan pas foto 3x4 (opsional tergantung kebutuhan).'],
                    ['isi' => 'Ajukan "Surat Domisili" di aplikasi DESA-Smart.'],
                    ['isi' => 'Surat ini berlaku sementara (biasanya 6 bulan) untuk keperluan administrasi.'],
                ],
            ],
            [
                'judul' => 'Surat Keterangan Domisili Usaha/Lembaga',
                'icon' => 'Briefcase',
                'langkah_langkah' => [
                    ['isi' => 'Siapkan Akta Pendirian Perusahaan/Lembaga (jika ada).'],
                    ['isi' => 'Fotokopi KTP Penanggung Jawab.'],
                    ['isi' => 'Bukti kepemilikan tempat usaha (Sertifikat/Sewa Menyewa) atau Izin Tetangga.'],
                    ['isi' => 'Ajukan permohonan ke Kantor Desa.'],
                    ['isi' => 'Dilakukan verifikasi lapangan sebelum surat diterbitkan.'],
                ],
            ],
            [
                'judul' => 'Surat Pindah Datang (Masuk Desa)',
                'icon' => 'Home',
                'langkah_langkah' => [
                    ['isi' => 'Bawa Surat Keterangan Pindah WNI (SKPWNI) dari Disdukcapil daerah asal.'],
                    ['isi' => 'Bawa KTP dan KK asli (jika masih memegang yang lama).'],
                    ['isi' => 'Lapor ke RT/RW tempat tujuan untuk mendapatkan izin tinggal.'],
                    ['isi' => 'Serahkan berkas SKPWNI ke Kantor Desa untuk dibuatkan KK baru di alamat ini.'],
                ],
            ],
            [
                'judul' => 'Surat Pindah Keluar (Pindah Domisili)',
                'icon' => 'Home',
                'langkah_langkah' => [
                    ['isi' => 'Lunasi kewajiban administrasi desa (PBB, dll) jika ada.'],
                    ['isi' => 'Minta pengantar RT/RW.'],
                    ['isi' => 'Bawa KK asli dan Fotokopi.'],
                    ['isi' => 'Isi formulir permohonan pindah (F1-08) di Kantor Desa.'],
                    ['isi' => 'Desa akan menerbitkan pengantar untuk dibawa ke Kecamatan/Disdukcapil guna penerbitan SKPWNI.'],
                ],
            ],
            [
                'judul' => 'Pengantar Nikah (N1, N2, N4)',
                'icon' => 'User',
                'langkah_langkah' => [
                    ['isi' => 'Minta Surat Pengantar RT/RW.'],
                    ['isi' => 'Fotokopi KTP & KK Calon Pengantin (Catin) dan Orang Tua.'],
                    ['isi' => 'Fotokopi Ijazah Terakhir & Akta Kelahiran.'],
                    ['isi' => 'Surat Pernyataan Belum Menikah (materai) atau Akta Cerai (jika Janda/Duda).'],
                    ['isi' => 'Pas foto 2x3 dan 4x6 (latar biru) masing-masing 4 lembar.'],
                    ['isi' => 'Suntik Imunisasi TT bagi Catin Wanita di Puskesmas.'],
                    ['isi' => 'Bawa semua berkas ke Desa untuk mendapatkan formulir N1-N4 guna didaftarkan ke KUA.'],
                ],
            ],
            [
                'judul' => 'Surat Izin Keramaian',
                'icon' => 'FileText',
                'langkah_langkah' => [
                    ['isi' => 'Buat proposal atau surat permohonan acara (Hajatan, Pentas Seni, dll).'],
                    ['isi' => 'Minta izin lingkungan/tetangga diketahui RT/RW.'],
                    ['isi' => 'Bawa Fotokopi KTP Penanggung Jawab.'],
                    ['isi' => 'Datang ke Kantor Desa untuk mendapatkan Surat Pengantar Izin Keramaian.'],
                    ['isi' => 'Bawa surat pengantar tersebut ke Polsek setempat untuk izin final.'],
                ],
            ],
        ];

        foreach ($panduans as $panduan) {
            Panduan::updateOrCreate(
                ['judul' => $panduan['judul']], // Mencegah duplikasi judul
                [
                    'icon' => $panduan['icon'],
                    'langkah_langkah' => $panduan['langkah_langkah'],
                    'is_active' => true,
                ]
            );
        }
    }
}