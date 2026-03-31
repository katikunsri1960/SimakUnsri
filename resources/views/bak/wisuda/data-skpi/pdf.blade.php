@extends('layouts.doc-ijazah')

@section('content')

@push('styles')
<style>
    @page { margin: 50mm 30mm 20mm 30mm; }

    header {
        position: fixed;
        display: block !important;
        top: -102px;
        width: 100% !important;
        left: 0px;
        right: 0px;
        height: 50px;
        text-align: center;
    }

    header img{
        display: block;
        margin-left: auto;
        margin-right: auto;
        margin-top: -40px !important;
        position: static;
        height: 80px;
        /* padding-left:20px; */
    }

    body {
        font-family: "Times New Roman", serif;
        font-size: 12pt;
        line-height: 1.5;
    }

    .judul {
        text-align: center;
        font-weight: bold;
        font-size: 12pt;
        margin-bottom: 10px;
    }

    .subjudul {
        text-align: center;
        font-size: 12pt;
        margin-bottom: 15px;
    }

    .section-title {
        font-weight: bold;
        margin-top: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        padding: 3px;
        vertical-align: top;
    }

    .table-border td, .table-border th {
        border: 1px solid black;
        padding: 5px;
    }

    .text-center {
        text-align: center;
    }

    .text-left {
        text-align: left;
    }

    .text-td{
        font-family: "Times New Roman", serif;
        font-size: 10pt;
    }

    .bold {
        font-weight: bold;
    }

    .ttd {
        margin-top: 40px;
    }

    .page-break {
        page-break-after: always;
    }
</style>
@endpush

@foreach ($data as $d)

<!-- ================= HALAMAN DEPAN ================= -->
<header>
    <img src="{{ public_path('images/unsri.png') }}">
</header>
<div class="judul">
    UNIVERSITAS SRIWIJAYA<br>
    {{ strtoupper($d->nama_fakultas ?? '-') }}<br>
    SURAT KETERANGAN PENDAMPING IJAZAH (SKPI)<br>
    <!-- PROGRAM STUDI {{ strtoupper($d->nama_prodi ?? '-') }}<br> -->
    NOMOR : {{ $d->no_skpi ?? '....................' }}
</div>

<p style="text-align: justify; text-indent: 30px; margin-bottom: 20px;">
Surat Keterangan Pendamping Ijazah (SKPI) adalah pelengkap Ijazah yang menerangkan capaian pembelajaran lulusan (CPL) dan prestasi serta aktivitas pemegang Ijazah selama masa studi.
</p>

<div class="section-title">A. Informasi Identitas Diri</div>
<table>
    <tr><td width="40%">1. Nama Lengkap</td><td>: {{ $d->nama_mahasiswa }}</td></tr>
    <tr><td>2. Tempat, Tanggal Lahir</td><td>: {{ $d->tempat_lahir }}, {{ \Carbon\Carbon::parse($d->tanggal_lahir)->translatedFormat('d F Y') }}</td></tr>
    <tr><td>3. NIM</td><td>: {{ $d->nim }}</td></tr>
    <tr><td>4. Tahun Masuk</td><td>: {{ \Carbon\Carbon::parse($d->tgl_masuk)->format('Y') }}</td></tr>
    <tr><td>5. Tahun Lulus</td><td>: {{ \Carbon\Carbon::parse($d->tgl_keluar)->format('Y') }}</td></tr>
    <tr><td>6. Nomor Ijazah</td><td>: {{ $d->no_ijazah ?? '-' }}</td></tr>
    <tr><td>7. Gelar</td><td>: {{ $d->gelar ?? '-' }}</td></tr>
</table>

<div class="section-title">B. Informasi Penyelenggara Program</div>
<table>
    <tr><td width="40%">1. Nama Institusi</td><td>: Universitas Sriwijaya</td></tr>
    <tr><td>2. Program Studi</td><td>: {{ $d->nama_prodi }}</td></tr>
    <tr><td>3. Jenjang</td><td>: {{ $d->jenjang }}</td></tr>
    <tr><td>4. Sistem Penilaian</td><td>: Skala 0 - 4</td></tr>
</table>

<div class="section-title">C. Capaian Pembelajaran Lulusan</div>
<table>
    <tr><td>1. CPL-1</td><td>: ...................................................</td></tr>
    <tr><td>2. CPL-2</td><td>: ...................................................</td></tr>
    <tr><td>3. CPL-3</td><td>: ...................................................</td></tr>
</table>

<!-- PAGE BREAK -->
<div class="page-break"></div>

<!-- ================= HALAMAN BELAKANG ================= -->

<div class="section-title">D. Prestasi dan Aktivitas Pemegang SKPI</div>

@php
    $total = 0;
@endphp

@foreach($skpi_bidang as $bidang)

    @php
    
       $rows = collect($d->skpi ?? [])
            ->filter(function ($item) use ($bidang) {
                return optional($item->jenisSkpi)->bidang_id == $bidang->id
                    && $item->approved == 3;
            })
            ->values();
    @endphp

    @if($rows->count())
        <div style="margin:10px 0px 10px 0px; font-weight:bold;">
            {{ $bidang->nama_bidang }}
        </div>

        <table class="table-border">
            <thead>
                <tr>
                    <th width="5%" class="text-center text-td">No</th>
                    <th width="20%" class="text-center text-td">Jenis</th>
                    <th width="35%" class="text-center text-td">Nama Kegiatan</th>
                    <th width="15%" class="text-center text-td">Tahun</th>
                    <!-- <th width="15%" class="text-center text-td">Kategori</th> -->
                    <th width="10%" class="text-center text-td">Skor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $i => $row)
                
                <tr>
                    <td class="text-center text-td">{{ $i+1 }}</td>
                    <td class="text-left text-td">{{ $row->nama_jenis_skpi }}</td>
                    <td class="text-left text-td">{{ $row->nama_kegiatan }}</td>
                    <td class="text-center text-td">{{ $row->tahun ?? '-' }}</td>
                    <!-- <td class="text-left text-td">{{ $row->kriteria }}</td> -->
                    <td class="text-center text-td">{{ $row->skor }}</td>
                </tr>

                @php
                    $total += $row->skor ?? 0;
                @endphp

                @endforeach
            </tbody>
        </table>
    @endif

@endforeach

<br>

<table>
    <tr>
        <td width="30%" class="bold">Total Skor SKPI</td>
        <td width="2%">: </td>
        <td width="68%" class="bold">{{ $total }}</td>
    </tr>
    <tr>
        <td width="30%" class="bold">Predikat</td>
        <td width="2%">: </td>
        <td width="68%" class="bold">
            @php
                if($total >= 100) echo 'ISTIMEWA';
                elseif($total >= 80) echo 'SANGAT BAIK';
                elseif($total >= 60) echo 'BAIK';
                else echo 'CUKUP';
            @endphp
        </td>
    </tr>
</table>

<div class="ttd">
    <table>
        <tr>
            <td width="45%"></td>
            <td>Indralaya, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td></td>
            <td>an. Rektor</td>
        </tr>
        <tr>
            <td></td>
            <td>Wakil Rektor Bidang Akademik</td>
        </tr>
        <tr>
            <td></td>
            <td style="padding-top: 60px;">
                <strong>Prof. Dr. Ir. Rujito Agus Suwignyo, M.Agr.</strong><br>
                NIP 196209091985031006
            </td>
        </tr>
    </table>
</div>

@if(!$loop->last)
    <div class="page-break"></div>
@endif

@endforeach

@endsection