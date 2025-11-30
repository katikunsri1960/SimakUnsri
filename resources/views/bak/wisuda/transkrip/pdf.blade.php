<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Transkrip Akademik</title>

    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }

        .page-break { page-break-after: always; }

        .container {
            width: 100%;
            display: flex;
            gap: 10px;
        }

        .left-col, .right-col {
            width: 49%;
            vertical-align: top;
        }

        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 3px 4px; font-size: 9px; }

        .no-border td { border: none !important; font-size: 10px; }
        .header-title { text-align:center; font-size: 12pt; font-weight:bold; }

        .signature-table td { border: none; padding: 4px; }
    </style>
</head>

<body>

@foreach($data as $d)

@php
    $total = count($d->transkrip_mahasiswa);
    $half = ceil($total/2);

    $left = $d->transkrip_mahasiswa->slice(0, $half);
    $right = $d->transkrip_mahasiswa->slice($half);
@endphp


<div class="container">

    {{-- ========================= KOLOM KIRI ========================== --}}
    <div class="left-col">

        {{-- HEADER --}}
        <img src="{{ public_path('images/unsri.png') }}" width="70">

        <div class="header-title">
            KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI<br>
            UNIVERSITAS SRIWIJAYA<br>
            {{ strtoupper($fakultas) }}<br>
            <u>DAFTAR NILAI AKADEMIK (TRANSKRIP)</u><br>
            ACADEMIC TRANSCRIPT
        </div>

        {{-- BIODATA --}}
        <table class="no-border" style="margin-top: 10px;">
            <tr><td width="125">NAMA</td> <td>: {{ $d->nama_mahasiswa }}</td></tr>
            <tr><td>TEMPAT LAHIR</td> <td>: {{ $d->tempat_lahir }}</td></tr>
            <tr><td>TGL LAHIR</td> <td>: {{ $d->tanggal_lahir }}</td></tr>
            <tr><td>NIM</td> <td>: {{ $d->nim }}</td></tr>
            <tr><td>FAKULTAS</td> <td>: {{ $fakultas }}</td></tr>
            <tr><td>PROGRAM STUDI</td> <td>: {{ $d->jenjang }} {{ $d->nama_prodi }}</td></tr>
            <tr><td>TGL LULUS</td> <td>: {{ $d->tgl_yudisium }}</td></tr>
            <tr><td>TGL WISUDA</td> <td>: {{ $d->tgl_sk_yudisium }}</td></tr>
            <tr><td>MASA STUDI</td> <td>: {{ $d->masa_studi }}</td></tr>
            <tr><td>NO IJAZAH</td> <td>: {{ $d->no_ijazah }}</td></tr>
        </table>

        <br>

        {{-- TABEL MATA KULIAH BAGIAN KIRI --}}
        <table>
            <thead>
            <tr>
                <th width="25">NO</th>
                <th width="70">KODE</th>
                <th>MATA KULIAH</th>
                <th width="28">SKS</th>
                <th width="28">NILAI</th>
                <th width="28">BOBOT</th>
                <th width="28">K x B</th>
            </tr>
            </thead>

            <tbody>
            @foreach($left as $i => $mk)
                <tr>
                    <td align="center">{{ $i+1 }}</td>
                    <td align="center">{{ $mk->kode_mata_kuliah }}</td>
                    <td>{{ $mk->nama_mata_kuliah }}</td>
                    <td align="center">{{ $mk->sks_mata_kuliah }}</td>
                    <td align="center">{{ $mk->nilai_huruf }}</td>
                    <td align="center">{{ $mk->nilai_indeks }}</td>
                    <td align="center">{{ $mk->kali }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

    {{-- ========================= KOLOM KANAN ========================== --}}
    <div class="right-col">

        {{-- TABEL MATA KULIAH BAGIAN KANAN --}}
        <table>
            <thead>
            <tr>
                <th width="25">NO</th>
                <th width="70">KODE</th>
                <th>MATA KULIAH</th>
                <th width="28">SKS</th>
                <th width="28">NILAI</th>
                <th width="28">BOBOT</th>
                <th width="28">K x B</th>
            </tr>
            </thead>

            <tbody>
            @foreach($right as $i => $mk)             
                <tr>
                    <td align="center">{{ $i+1 }}</td>
                    <td align="center">{{ $mk->kode_mata_kuliah }}</td>
                    <td>{{ $mk->nama_mata_kuliah }}</td>
                    <td align="center">{{ $mk->sks_mata_kuliah }}</td>
                    <td align="center">{{ $mk->nilai_huruf }}</td>
                    <td align="center">{{ $mk->nilai_indeks }}</td>
                    <td align="center">{{ $mk->kali }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <br>

        {{-- IPK, SKRIPSI, TTD --}}
        <table class="no-border">
            <tr><td width="160">IPK</td> <td>: {{ $d->ipk }}</td></tr>
            <tr><td>PREDIKAT</td> <td>: {{ $d->predikat }}</td></tr>
            <tr><td>JUDUL SKRIPSI</td> <td>: {{ $d->judul_skripsi }}</td></tr>
            <tr><td>PEMBIMBING</td> <td>: {{ $d->pembimbing1 }} & {{ $d->pembimbing2 }}</td></tr>
        </table>

        <br><br>

        <table class="no-border">
            <tr>
                <td align="center">
                    Indralaya, {{ date('d F Y') }}<br>
                    Wakil Dekan Bidang Akademik<br><br><br>
                    {{ $wd1->nama }}<br>
                    NIP {{ $wd1->nip }}
                </td>
            </tr>

            <tr>
                <td align="center">
                    <br>
                    Wakil Rektor Bidang Akademik<br><br><br>
                    {{ $wr1->nama }}<br>
                    NIP {{ $wr1->nip }}
                </td>
            </tr>
        </table>

    </div>

</div>

@if(!$loop->last)
    <div class="page-break"></div>
@endif

@endforeach

</body>
</html>
