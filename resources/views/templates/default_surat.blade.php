<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $surat->template->judul_surat }} - {{ $user->name }}</title>
    <style>
        /* Reset dan Font Dasar */
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0; /* DomPDF menangani margin halaman */
        }

        /* Kontainer Utama untuk Margin Kertas (Opsional saat preview di browser) */
        .container {
            padding: 0 1cm; /* Padding kiri kanan agar tidak terlalu mepet */
        }

        /* --- KOP SURAT --- */
        .header {
            text-align: center;
            /* Garis bawah tebal khas surat dinas */
            border-bottom: 3px solid #000; 
            padding-bottom: 5px;
            margin-bottom: 10px;
            position: relative;
        }

        /* Placeholder Logo (Jika nanti ada) */
        .header-logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 70px;
            height: auto;
        }

        .header h3 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header p {
            margin: 2px 0;
            font-size: 11pt;
        }

        /* --- JUDUL & NOMOR --- */
        .title-section {
            text-align: center;
            margin-bottom: 15px;
        }

        .title-section h4 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .title-section p {
            margin: 2px 0 0 0;
            font-size: 12pt;
        }

        /* --- ISI SURAT --- */
        .content {
            text-align: justify; /* Rata kanan kiri */
        }

        .content p {
            margin-bottom: 10px;
            line-height: 1.4;
            text-indent: 30px; /* Menjorok ke dalam untuk paragraf baru */
        }
        
        /* Kalimat pembuka tidak perlu menjorok */
        .pembuka {
            text-indent: 0 !important;
        }

        /* --- TABEL BIODATA --- */
        /* Menggunakan tabel agar titik dua sejajar rapi */
        .table-biodata {
            width: 100%;
            margin-left: 30px; /* Sedikit menjorok ke kanan */
            margin-bottom: 10px;
            border-collapse: collapse;
        }

        .table-biodata td {
            vertical-align: top;
            padding: 3px 0;
        }

        .label-col {
            width: 160px; /* Lebar kolom label */
        }

        .separator-col {
            width: 20px;
            text-align: center;
        }

        .data-col {
            font-weight: bold; /* Data penduduk dicetak tebal sesuai referensi */
        }

        /* --- AREA TANDA TANGAN --- */
        .ttd-container {
            width: 100%;
            margin-top: 10px;
            /* Trik untuk memastikan area TTD tidak terpotong halaman */
            page-break-inside: avoid; 
        }

        .ttd-box {
            float: right;
            width: 320px; /* Lebar area tanda tangan kanan */
            text-align: center;
            position: relative; /* Penting untuk posisi stempel absolut */
        }

        /* Gambar Tanda Tangan */
        .ttd-img {
            height: 70px; /* Sesuaikan tinggi TTD */
            margin: 5px auto;
            display: block;
            position: relative;
            z-index: 1; /* TTD di bawah stempel */
        }

        /* Gambar Stempel (Cap) */
        .stempel-img {
            position: absolute;
            /* Mengatur posisi agar menimpa TTD */
            top: 40px; 
            right: 30px;
            height: 110px; /* Stempel biasanya lebih besar */
            opacity: 0.85; /* Sedikit transparan agar terlihat 'basah' */
            z-index: 2; /* Stempel di atas TTD */
            transform: rotate(-5deg); /* Sedikit miring agar realistis */
        }

        .pejabat-name {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 2px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- Jika ada logo desa, uncomment baris di bawah ini --}}
            {{-- <img src="{{ storage_path('app/public/logo-desa.png') }}" class="header-logo"> --}}

            {{-- GANTI DATA HARDCODE INI DENGAN SETTING DESA ANDA NANTI --}}
            <h3>PEMERINTAH KABUPATEN CONTOH</h3>
            <h3>KECAMATAN MAJU JAYA</h3>
            <h3>DESA SMART DIGITAL</h3>
            <p>Jalan Poros Desa No. 1, Kode Pos 12345</p>
        </div>

        <div class="title-section">
            <h4>{{ strtoupper($surat->template->judul_surat) }}</h4>
            <p>Nomor: {{ $nomor_surat_baru ?? $surat->nomor_surat ?? '......./......./.......' }}</p>
        </div>

        <div class="content">
            <p class="pembuka">Yang bertanda tangan di bawah ini, {{ $pejabat->jabatan }} Desa Smart Digital, Kecamatan Maju Jaya, Kabupaten Contoh, dengan ini menerangkan bahwa:</p>

            <table class="table-biodata">
                <tr>
                    <td class="label-col">Nama Lengkap</td>
                    <td class="separator-col">:</td>
                    <td class="data-col" style="text-transform: uppercase;">{{ $user->name }}</td>
                </tr>
                <tr>
                    <td class="label-col">NIK</td>
                    <td class="separator-col">:</td>
                    <td class="data-col">{{ $user->nik }}</td>
                </tr>
                <tr>
                    <td class="label-col">Tempat/Tgl Lahir</td>
                    <td class="separator-col">:</td>
                    <td>{{ $user->tempat_lahir ?? '(Tempat)' }}, {{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->isoFormat('D MMMM Y') : '(Tgl Lahir)' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Alamat</td>
                    <td class="separator-col">:</td>
                    <td>{{ $user->alamat_lengkap ?? 'Desa Smart Digital' }}</td>
                </tr>
                
                {{-- Loop Data Input Dinamis Warga (Keperluan, dll) --}}
                @if(is_array($data) && count($data) > 0)
                    <tr><td colspan="3" style="border-bottom: 1px dotted #ccc; height: 5px;"></td></tr>
                    <tr><td colspan="3" style="height: 5px;"></td></tr>
                    
                    @foreach($data as $key => $value)
                    <tr>
                        <td class="label-col">{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                        <td class="separator-col">:</td>
                        {{-- Jika key adalah 'keperluan', cetak tebal --}}
                        <td class="{{ $key == 'keperluan' ? 'data-col' : '' }}">{{ $value }}</td>
                    </tr>
                    @endforeach
                @endif
            </table>

            <p>
                {!! nl2br(e($processed_body ?? $surat->template->body_text)) !!}
            </p>

            <p>
                Demikian surat keterangan ini kami buat dengan sebenarnya dan untuk dapat dipergunakan
                sebagaimana mestinya.
            </p>
        </div>

        <div class="ttd-container">
            <div class="ttd-box">
                {{-- Titah Mangsa (Tempat, Tanggal Surat) --}}
                <p>
                    Dikeluarkan di: Desa Smart<br>
                    Pada Tanggal: {{ \Carbon\Carbon::parse($surat->created_at)->isoFormat('D MMMM Y') }}
                </p>
                <p>
                    <b>{{ strtoupper($pejabat->jabatan) }}</b>
                </p>
                
                <div style="position: relative; height: 100px; width: 100%;">
                    @if($pejabat->path_gambar_stempel)
                        {{-- Stempel ditaruh duluan di HTML tapi diatur Z-index nya di CSS agar di atas --}}
                        <img src="{{ storage_path('app/public/' . $pejabat->path_gambar_stempel) }}" class="stempel-img">
                    @endif

                    @if($pejabat->path_gambar_ttd)
                        <img src="{{ storage_path('app/public/' . $pejabat->path_gambar_ttd) }}" class="ttd-img">
                    @else
                        <div style="height: 70px;"></div>
                    @endif
                </div>

                <p class="pejabat-name">{{ strtoupper($pejabat->nama_pejabat) }}</p>
                @if($pejabat->nip)
                    <p>NIP. {{ $pejabat->nip }}</p>
                @endif
            </div>
            <div style="clear: both;"></div> </div>
    </div>
</body>
</html>