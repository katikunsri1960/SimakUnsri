@extends('layouts.doc-skpi')

@section('content')

@push('styles')

@endpush

<style>
    /* Data wajib */
    .nomor {
        width: 20px;
        padding: 0 !important;
        text-align: right;
        white-space: nowrap;
    }

    .titik {
        width: 8px;
        padding: 0 !important;
        text-align: left;
    }

    .keterangan {
        width: 230px;
        padding: 0 0 0 5px !important;
    }

    .isi {
        padding: 0 !important;
    }

    .peraturan-isi {
        width: 100%;
        text-align: left;
        padding: 0 0 0 5px !important;
        line-height: 1.25;
    }

    /* Data CPL */
    .section-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .section-table td {
        padding: 0;
        vertical-align: top;
    }

    .section-number {
        width: 20px;
        font-weight: bold;
        text-align: left;
    }

    .section-text {
        font-weight: bold;
        text-align: left;
    }

    .sub-section {
        padding-top: 2px !important;
        padding-left: 20px;
        font-weight: bold;
    }


    /* =========================
    TABEL CPL
   ========================= */

    .cpl-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px; /* jarak antar setiap TR */
        margin-left: 15px;
        margin-top: 15px;
    }

    .cpl-table td {
        padding: 0;
        margin-bottom: 5px;
        vertical-align: top;
    }

    .cpl-number {
        width: 55px;
        white-space: nowrap;
        text-align: right;
        padding-right: 5px !important;
    }

    .cpl-colon {
        width: 15px;
        text-align: center;
        white-space: nowrap;
    }

    .cpl-content {
        padding-left: 5px !important;
        padding-right: 20px !important;
        text-align: justify;
        line-height: 1.25;
    }

    /* Jarak setelah tabel */
    .table-bottom-space {
        margin-bottom: 100px;
    }
</style>

@foreach ($data as $d)

{{-- ================= HALAMAN 1 ================= --}}
<div class="logo">
    <img src="{{ public_path('images/unsri.png') }}">
</div>

<div class="judul">
    UNIVERSITAS SRIWIJAYA<br>
    SURAT KETERANGAN PENDAMPING IJAZAH (SKPI)<br>
    NOMOR : {{ $d->no_sk_skpi ?? '........ / ........ / ........ / 2026' }}
</div>

<p style="text-align: justify; text-indent: 25px;">
Surat Keterangan Pendamping Ijazah (SKPI) adalah pelengkap Ijazah yang menerangkan capaian pembelajaran lulusan (CPL) dan prestasi serta aktivitas pemegang Ijazah selama masa studi.
</p>

<!-- <div class="section-title">A. Informasi tentang Identitas Diri Pemegang SKPI</div> -->
<table class="section-table">
    <tr>
        <td class="section-number">A.</td>
        <td class="section-text">
            Informasi tentang Identitas Diri Pemegang SKPI
        </td>
    </tr>
</table>
<table style="margin: 0 0 20px 15px; border-collapse: collapse; width: 100%;">
    <tr>
        <td class="nomor">1</td>
        <td class="titik">.</td>
        <td class="keterangan">Nama Lengkap</td>
        <td class="isi">: {{ $d->nama_mahasiswa }}</td>
    </tr>

    <tr>
        <td class="nomor">2</td>
        <td class="titik">.</td>
        <td class="keterangan">Tempat, Tanggal Lahir</td>
        <td class="isi">
            : {{ $d->tempat_lahir }},
            {{ \Carbon\Carbon::parse($d->tanggal_lahir)->translatedFormat('d F Y') }}
        </td>
    </tr>

    <tr>
        <td class="nomor">3</td>
        <td class="titik">.</td>
        <td class="keterangan">Nomor Induk Mahasiswa</td>
        <td class="isi">: {{ $d->nim }}</td>
    </tr>

    <tr>
        <td class="nomor">4</td>
        <td class="titik">.</td>
        <td class="keterangan">Tahun Masuk</td>
        <td class="isi">
            : {{ \Carbon\Carbon::parse($d->tgl_masuk)->format('Y') }}
        </td>
    </tr>

    <tr>
        <td class="nomor">5</td>
        <td class="titik">.</td>
        <td class="keterangan">Tahun Lulus</td>
        <td class="isi">
            : {{ \Carbon\Carbon::parse($d->tgl_keluar)->format('Y') }}
        </td>
    </tr>

    <tr>
        <td class="nomor">6</td>
        <td class="titik">.</td>
        <td class="keterangan">Nomor Ijazah</td>
        <td class="isi">: {{ $d->no_ijazah ?? '-' }}</td>
    </tr>

    <tr>
        <td class="nomor">7</td>
        <td class="titik">.</td>
        <td class="keterangan">Gelar dan Singkatan</td>
        <td class="isi">: {{ $d->gelar_panjang }} ({{ $d->gelar }})</td>
    </tr>
</table>

<!-- <div class="section-title">B. Informasi tentang Identitas Penyelenggara Program</div> -->
<table class="section-table">
    <tr>
        <td class="section-number">B.</td>
        <td class="section-text">
            Informasi tentang Identitas Penyelenggara Program
        </td>
    </tr>
</table>
<table style="margin: 0px 0px 20px 15px; width: 100%;">
   <!-- <table class="peraturan-table"> -->
    <tr>
        <td class="nomor">1</td>
        <td class="titik">.</td>
        <td class="keterangan">SK Pendirian Perguruan Tinggi</td>
        <td class="isi">: </td>
    </tr>
    <tr>
        <td class="nomor"> </td>
        <td class="titik"> </td>
        <td class="keterangan" colspan="2" style="padding-left: 5px;">
            Peraturan Pemerintah No.42 Tahun 1960 tanggal 29 Oktober 1960 
            (Lembaran Negara Tahun 1960 No.135) tentang Pendirian Universitas Sriwijaya
        </td>
    </tr>
<!-- </table> -->

    <tr>
        <td class="nomor">2</td>
        <td class="titik">.</td>
        <td class="keterangan">Nama Institusi</td>
        <td class="isi">: Universitas Sriwijaya</td>
    </tr>
    
    <tr>
        <td class="nomor">3</td>
        <td class="titik">.</td>
        <td class="keterangan">Fakultas</td>
        <td class="isi">: {{str_replace('Fakultas ', '', $d->nama_fakultas ?? '-')}}</td>
    </tr>

    <tr>
        <td class="nomor">4</td>
        <td class="titik">.</td>
        <td class="keterangan">Program Studi</td>
        <td class="isi">: {{ $d->nama_prodi }}</td>
    </tr>

    <tr>
        <td class="nomor">5</td>
        <td class="titik">.</td>
        <td class="keterangan">Jenis dan Jenjang Pendidikan</td>
        <td class="isi">: {{ $d->jenjang }}</td>
    </tr>

    <tr>
        <td class="nomor">6</td>
        <td class="titik">.</td>
        <td class="keterangan">Jenjang Kualifikasi Sesuai KKNI</td>
        <td class="isi">:
            @if($d->jenjang == 'D3')
                5
            @elseif($d->jenjang == 'D4' || $d->jenjang == 'S1')
                6
            @endif
        </td>
    </tr>

    <tr>
        <td class="nomor">7</td>
        <td class="titik">.</td>
        <td class="keterangan">Persyaratan Penerimaan</td>
        <td class="isi">: Lulusan SMA/SMK/Sederajat</td>
    </tr>

    <tr>
        <td class="nomor">8</td>
        <td class="titik">.</td>
        <td class="keterangan">Bahasa Pengantar Kuliah</td>
        <td class="isi">: Bahasa Indonesia</td>
    </tr>

    <tr>
        <td class="nomor">9</td>
        <td class="titik">.</td>
        <td class="keterangan">Sistem Penilaian</td>
        <td class="isi">: Skala 0-4: A=4, B=3, C=2, D=1, E=0.</td>
    </tr>

    <tr>
        <td class="nomor">10</td>
        <td class="titik">.</td>
        <td class="keterangan">Lama Studi Reguler</td>
        <td class="isi">:
            @if($d->jenjang == 'D3')
                3 Tahun
            @elseif($d->jenjang == 'S1')
                4 Tahun
            @endif
        </td>
    </tr>

    <tr>
        <td class="nomor">11</td>
        <td class="titik">.</td>
        <td class="keterangan">Jenis dan Jenjang Pendidikan Lanjutan</td>
        <td class="isi">:
            @if($d->jenjang == 'D3')
                Sarjana / Diploma Empat / Sederajat
            @elseif($d->jenjang == 'S1')
                Profesi / Magister / Sederajat
            @endif
        </td>
    </tr>
</table>

<table class="section-table">
    <tr>
        <td class="section-number">C.</td>
        <td class="section-text">
            Informasi tentang Kualifikasi dan Hasil yang Dicapai Capaian Pembelajaran
        </td>
    </tr>

    <tr>
        <td></td>
        <td class="sub-section">
            Lulusan
        </td>
    </tr>
</table>
@php
    $cpl_mahasiswa = $cpl_list->get($d->id_kurikulum, collect());
@endphp

<table class="cpl-table"  style="margin: 0px 0px 0px 15px; width: 100%;">
    <tbody>

        @if($cpl_mahasiswa->isNotEmpty())

            @foreach($cpl_mahasiswa as $index => $item)
                <tr>
                    <td class="cpl-number">
                        {{-- CPL-{{ $index + 1 }} --}}
                        {{ $item->kode_cpl }}
                    </td>

                    <td class="cpl-colon">
                        :
                    </td>

                    <td class="cpl-content">
                        {{ $item->nama_cpl }}
                    </td>
                </tr>
            @endforeach

        @else

            <tr>
                <td colspan="3" class="cpl-content">
                    Data CPL Belum Diisi
                </td>
            </tr>

        @endif

    </tbody>
</table>

<div class="page-break"></div>


{{-- ================= HALAMAN 2 ================= --}}
<div class="section-title">D. Prestasi dan Aktivitas Pemegang SKPI</div>

@php $total = 0; @endphp

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
        <div style="margin:25px 0 5px 0; font-weight:bold;">
            {{ $bidang->nama_bidang }}
        </div>

        <table class="table-border">
            <thead>
                <tr>
                    <th width="5%" class="text-center text-td">No</th>
                    <th width="20%" class="text-center text-td">Jenis Kegiatan</th>
                    <th width="40%" class="text-center text-td">Nama Kegiatan</th>
                    <th width="30%" class="text-center text-td">Detail Kriteria</th>
                    <th width="5%" class="text-center text-td">Skor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $i => $row)
                <tr>
                    <td class="text-center text-td">{{ $i+1 }}</td>
                    <td class="text-left text-td">{{ $row->nama_jenis_skpi }}</td>
                    <td class="text-left text-td">{{ $row->nama_kegiatan }}</td>
                    <td class="text-left text-td">{{ $row->jenisSkpi->kriteria }}</td>
                    <!-- <td class="text-center text-td">{{ $row->tahun ?? '-' }}</td> -->
                    <td class="text-center text-td">{{ $row->skor }}</td>
                </tr>

                @php $total += $row->skor ?? 0; @endphp
                @endforeach
            </tbody>
        </table>
    @endif

@endforeach

<br>

<table>
    <tr>
        <td width="40%" class="bold">TOTAL SKOR SKPI</td>
        <td width="2%" class="bold">:</td>
        <td class="bold">{{ $total }}</td>
    </tr>
    <tr>
        <td class="bold">PREDIKAT KUALITAS SKPI</td>
        <td class="bold">:</td>
        <td class="bold">
            @if($total > 500) 
                ISTIMEWA
            @elseif($total >= 250 && $total <= 500) 
                SANGAT BAIK
            @else 
                BAIK
            @endif
        </td>
    </tr>
</table>

<div class="ttd">
    <table>
        <tr>
            <td width="44%"></td>
            <td>
                Indralaya, 
                {{ \Carbon\Carbon::parse($d->tanggal_wisuda)->translatedFormat('d F Y') }}
            </td>
        </tr>
        <tr><td></td><td>a.n. Rektor</td></tr>
        <tr><td></td><td>{{ $wr1->jabatan }}</td></tr>
        <tr>
            <td></td>
            <td style="padding-top: 70px;">
                    {{ $wr1->gelar_depan }} {{ $wr1->nama }}
                    {{ $wr1->gelar_belakang ? ', '.$wr1->gelar_belakang : '' }}
                <br>
                NIP {{ strtoupper($wr1->nip) }}
            </td>
        </tr>
    </table>
</div>

@if(!$loop->last)
<div class="page-break"></div>
@endif

@endforeach

@endsection