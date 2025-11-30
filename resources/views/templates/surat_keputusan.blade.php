<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keputusan Kepala Desa</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            text-align: justify;
        }
        .header { text-align: center; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 25px; }
        .title-section { text-align: center; margin-bottom: 30px; }
        .diktum-list { margin-left: 30px; margin-bottom: 20px; }
        .diktum-item { margin-bottom: 5px; }
        .memutuskan { text-align: center; font-weight: bold; text-decoration: underline; margin: 30px 0 20px 0; }
        /* TTD SK biasanya ditaruh di tengah */
        .ttd-sk-area { width: 400px; margin: 50px auto 0 auto; text-align: center; }
        .ttd-img { height: 70px; margin-top: 5px; display: block; margin-left: auto; margin-right: auto; }
        .pejabat-name { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>PEMERINTAH KABUPATEN CONTOH</h3>
            <h3>KECAMATAN MAJU JAYA</h3>
            <h3>DESA SMART DIGITAL</h3>
            <p>Jalan Poros Desa No. 1, Kode Pos 12345</p>
        </div>

        <div class="title-section">
            <p>KEPUTUSAN KEPALA DESA SMART DIGITAL</p>
            <p>NOMOR: {{ $nomor_surat_baru ?? $surat->nomor_surat ?? '......./......./.......' }}</p>
            <p style="margin-top: 15px;">TENTANG</p>
            <p style="font-weight: bold; text-transform: uppercase; text-decoration: underline;">
                {{ $data['tentang'] ?? 'PENETAPAN NAMA PEJABAT SEMENTARA' }}
            </p>
            <p style="margin-top: 30px;">KEPALA DESA SMART DIGITAL,</p>
        </div>

        <div class="diktum-list">
            <p style="text-indent: 0; margin-bottom: 5px;">MENIMBANG:</p>
            <ol type="a" style="margin: 0;">
                @foreach($data['menimbang_array'] ?? [] as $item)
                    <li class="diktum-item">{{ $item }}</li>
                @endforeach
            </ol>
        </div>

        <div class="diktum-list">
            <p style="text-indent: 0; margin-bottom: 5px;">MENGINGAT:</p>
            <ol type="1" style="margin: 0;">
                @foreach($data['mengingat_array'] ?? [] as $item)
                    <li class="diktum-item">{{ $item }}</li>
                @endforeach
            </ol>
        </div>

        <p class="memutuskan">MEMUTUSKAN:</p>

        <div class="diktum-list">
            <p style="text-indent: 0; margin-bottom: 5px;">MENETAPKAN:</p>
            <dl>
                @foreach($data['memutuskan_array'] ?? [] as $key => $value)
                    <dt style="font-weight: bold;">{{ strtoupper($key) }}:</dt>
                    <dd style="margin-left: 20px; margin-bottom: 10px;">{{ $value }}</dd>
                @endforeach
            </dl>
        </div>
        
        <div class="ttd-sk-area">
            <p>
                Ditetapkan di: Desa Smart<br>
                Pada Tanggal: {{ \Carbon\Carbon::parse($surat->created_at)->isoFormat('D MMMM Y') }}
            </p>
            <p style="margin-top: 10px;">
                <b>KEPALA DESA SMART DIGITAL</b>
            </p>
            
            @if($pejabat->path_gambar_ttd)
                <img src="{{ storage_path('app/public/' . $pejabat->path_gambar_ttd) }}" class="ttd-img">
            @endif

            <p class="pejabat-name">{{ strtoupper($pejabat->nama_pejabat) }}</p>
            @if($pejabat->nip)
                <p>NIP. {{ $pejabat->nip }}</p>
            @endif
        </div>
    </div>
</body>
</html>