<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Transkrip Akademik</title>

    <style>
    body {
        font-family: "Times New Roman", serif;
        font-size: 10px;
        margin: 5px;
    }

    table { border-collapse: collapse; 
        width: 100%; 
            page-break-inside: auto;
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }

    /* Header tabel tetap bergaris */
    thead th {
        border: 1px solid #000;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    /* Hilangkan garis antar baris isi */
    tbody td {
        border-left: 1px solid #000;
        border-right: 1px solid #000;
        padding: 2px 4px;
    }

    /* Tambahkan border bawah tiap halaman */
    tfoot {
        display: table-row-group;
    }

    tfoot td.footer-border {
        border-bottom: 1px solid #000;
    }

    .no-border td {
        border: none !important;
        padding: 2px 0;
    }

    .ipk-table tr {
        vertical-align: top;
        margin-bottom: 50px; /* bebas atur */
    }

    .header-title {
        text-align:center;
        font-weight:bold;
    }

    .text-upper{ text-transform: uppercase; }

    .header-title .title1 { font-size: 18px; }
    .header-title .title2 { font-size: 16px; font-style: italic; }
    .header-title .title3 { font-size: 14px; margin-top: 20px; }
    .header-title .title4 { font-size: 12px; }

    .page-break { page-break-after: always; }
    </style>

</head>

<body>

@foreach($data as $d)

{{-- ========================================================= --}}
{{--                         HEADER                           --}}
{{-- ========================================================= --}}
<table class="no-border">
    <tr>
        <td width="90">
            <img src="{{ public_path('images/unsri.png') }}" width="90">
        </td>
        <td align="center">
            <div class="header-title">
                <div class="title1">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</div>
                <div class="title2">MINISTRY OF HIGHER EDUCATION, SCIENCE, AND TECHNOLOGY</div>
                <div class="title1">UNIVERSITAS SRIWIJAYA</div>
                <div class="title2">SRIWIJAYA UNIVERSITY</div>
                <div class="title1">{{ strtoupper($fakultas->nama_fakultas) }}</div>
                <div class="title2">{{ strtoupper($fakultas->nama_fakultas_eng) }}</div>
                <div class="title3 mt-100">DAFTAR NILAI AKADEMIK (TRANSKRIP)</div>
                <div class="title4">ACADEMIC TRANSCRIPT</div>
            </div>
        </td>
    </tr>
</table>

<br>

{{-- ========================================================= --}}
{{--                        BIODATA                           --}}
{{-- ========================================================= --}}
<table class="no-border text-upper">
    <tr><td width="220">NAMA (NAME)</td> <td>: {{ strtoupper($d->nama_mahasiswa) }}</td></tr>
    <tr><td>TEMPAT LAHIR (PLACE OF BIRTH)</td> <td>: {{ $d->tempat_lahir }}</td></tr>
    <tr><td>TANGGAL LAHIR (DATE OF BIRTH)</td> <td>: {{ $d->tanggal_lahir }}</td></tr>
    <tr><td>NIM (STUDENT REGISTRATION NUMBER)</td> <td>: {{ $d->nim }}</td></tr>
    <tr><td>FAKULTAS (FACULTY)</td> <td>: {{ $fakultas->nama_fakultas }}</td></tr>
    <tr><td>STRATA PENDIDIKAN (EDUCATION PROGRAM)</td> <td>: {{ $d->jenjang }}</td></tr>
    <tr><td>PROGRAM STUDI (STUDY PROGRAM)</td> <td>: {{ $d->nama_prodi }}</td></tr>

    <tr><td>TANGGAL LULUS (DATE OF COMPLETION)</td> <td>: {{ $d->tgl_yudisium }}</td></tr>
    <tr><td>TANGGAL WISUDA (CONVOCATION DATE)</td> <td>: {{ $d->tgl_sk_yudisium }}</td></tr>
    <tr><td>MASA STUDI (LENGTH OF STUDY)</td> <td>: {{ $d->masa_studi }}</td></tr>
    <tr><td>NOMOR IJAZAH (CERTIFICATE NUMBER)</td> <td>: {{ $d->no_ijazah }}</td></tr>
    <tr><td>KODE UNIVERSITAS (UNIVERSITY CODE)</td> <td>: {{ $kode_univ }}</td></tr>
</table>

<br>

{{-- ========================================================= --}}
{{--                TABEL MATA KULIAH — SINGLE COLUMN          --}}
{{-- ========================================================= --}}
<table>
    <thead>
        <tr>
            <th rowspan="2" width="20">NO</th>
            <th rowspan="2" width="40">KODE<br>(CODE)</th>
            <th rowspan="2">MATA KULIAH (SUBJECT)</th>
            <th width="52">SKS (K)</th>
            <th width="35">NILAI</th>
            <th width="60">BOBOT (B)</th>
            <th width="30">K × B</th>
        </tr>
        <tr>
            <th width="52">CREDIT (C)</th>
            <th width="35">GRADE</th>
            <th width="60">WEIGHT (W)</th>
            <th width="30">C × W</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalSks = 0;
            $totalBobot = 0;
        @endphp

        @foreach($d->transkrip_mahasiswa as $i => $mk)
            @php
                $sks = floor($mk->sks_mata_kuliah);
                $bobot = $mk->nilai_indeks * $sks;

                $totalSks += $sks;
                $totalBobot += $bobot;
            @endphp

            <tr style="padding: 0; margin: 0;">
                <td align="center">{{ $i+1 }}</td>
                <td align="center">{{ $mk->kode_mata_kuliah }}</td>
                <td align="left">{{ strtoupper($mk->nama_mata_kuliah) }}</td>
                <td align="center">{{ $sks }}</td>
                <td align="center">{{ $mk->nilai_huruf }}</td>
                <td align="center">{{ $mk->nilai_indeks }}</td>

                {{-- 🔥 Kolom baru hasil perkalian indeks × SKS --}}
                <td align="center">{{ $bobot}}</td>
            </tr>
        @endforeach

        <tr style="border: 1px solid #000; text-align:center;">
            <td colspan="3">TOTAL</td>
            <td>{{ $totalSks }}</td>
            <td colspan="2"></td>
            <td>{{$totalBobot}}</td>
        </tr>
    </tbody>



    <!-- FOOTER UNTUK GARIS DI BAWAH SETIAP HALAMAN -->
    <!-- <tfoot>
        <tr>
            <td colspan="7" class="footer-border"></td>
        </tr>
    </tfoot> -->
</table>

<br>

{{-- ========================================================= --}}
{{--                   IPK + SKRIPSI + PEMBIMBING              --}}
{{-- ========================================================= --}}
<table class="no-border ipk-table">
    <tr>
        <td colspan="2">INDEKS PRESTASI KUMULATIF / <em>GRADE POINT AVERAGE</em></td>
        <!-- <td></td> -->
        <td width="200" colspan="2">: {{$totalBobot}}/{{$totalSks}} = {{ $d->ipk }}</td>
    </tr>

    <tr>
        <td width="100"  colspan="2">PREDIKAT KELULUSAN <em>(OVERALL RATING)</em></td>
        <!-- <td></td> -->
        <td width="200" colspan="2">: {{ $d->predikat }}</td>
    </tr>

    <tr>
        <td width="200">JUDUL SKRIPSI<br><em>(FINAL PROJECT TITLE)</em></td>
        <td colspan="3">: {{ $d->judul_skripsi }}</td>
    </tr>

    <tr>
        <td width="200">PEMBIMBING SKRIPSI<br><em>(FINAL PROJECT ADVISORS)</em></td>
        <td>
    <span style="float:left;">: </span>
    <span style="display:block; margin-left: 10px;">
        @foreach($d->aktivitas_mahasiswa->bimbing_mahasiswa as $pembimbing)
            <div>{{ $pembimbing->pembimbing_ke }}. {{ $pembimbing->nama_dosen }}</div>
        @endforeach
    </span>
</td>


    </tr>
</table>


<br><br>

{{-- ========================================================= --}}
{{--                        TANDA TANGAN                       --}}
{{-- ========================================================= --}}
<table class="no-border" width="100%" style="font-size: 10px;">
    <tr>
        <td width="40%"></td>
        <td width="20%"></td>
        <td width="40%">
            INDRALAYA, 
            @php
                $tgl = \Carbon\Carbon::now();

                // Format Indonesia dengan BULAN kapital
                $indo = strtoupper($tgl->translatedFormat('j F Y'));

                // Format Inggris
                $day = $tgl->format('j');

                // Superscript "st nd rd th" tanpa redeclare error
                $supFn = function ($str) {
                    return strtr($str, [
                        'st' => 'ˢᵗ',
                        'nd' => 'ⁿᵈ',
                        'rd' => 'ʳᵈ',
                        'th' => 'ᵗʰ',
                    ]);
                };

                // Tentukan suffix bahasa Inggris
                $suffix = $tgl->format('S'); // st, nd, rd, th
                $supSuffix = $supFn($suffix);

                $english = $day . $supSuffix . ' ' . strtoupper($tgl->format('F Y'));
            @endphp

            {{ $indo }} ({{ $english }})

        </td>

    </tr>

    <tr>
        <td width="40%">
            {{strtoupper($wd1->jabatan)}}
        </td>
        <td width="20%"></td>
        <td width="40%">
            {{strtoupper($wr1->jabatan)}}
        </td>
    </tr>
    <tr>
        <td width="40%">
            {{strtoupper($wd1->jabatan)}}
        </td>
        <td width="20%"></td>
        <td width="40%">
            {{strtoupper($wr1->jabatan)}}
        </td>
    </tr>

    <tr>
        <td width="40%"></td>
        <td style="text-align:center; vertical-align:bottom;">
            <img src="{{ $d->pas_foto }}" alt="Foto" width="100">
        </td>
        <td width="40%"></td>
    </tr>

    <tr>
        <td width="40%">
            {{$wd1->gelar_depan}} {{strtoupper($wd1->nama)}}{{$wd1->gelar_belakang ? ', '.$wd1->gelar_belakang : ''}}
        </td>
        <td width="20%"></td>
        <td width="40%">
            {{$wr1->gelar_depan}} {{strtoupper($wr1->nama)}}{{$wr1->gelar_belakang ? ', '.$wr1->gelar_belakang : ''}}
        </td>
    </tr>
    <tr>
        <td width="40%">
            NIP {{strtoupper($wd1->nip)}}
        </td>
        <td width="20%"></td>
        <td width="40%">
            NIP {{strtoupper($wr1->nip)}}
        </td>
    </tr>
</table>

@if(!$loop->last)
    <div class="page-break"></div>
@endif

@endforeach

</body>
</html>
