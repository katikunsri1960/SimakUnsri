@extends('layouts.doc-skpi')

@section('content')

@push('styles')

@endpush


@foreach ($data as $d)

{{-- ================= HALAMAN 1 ================= --}}
<div class="logo">
    <img src="{{ public_path('images/unsri.png') }}">
</div>

<div class="judul">
    UNIVERSITAS SRIWIJAYA<br>
    {{ strtoupper($d->nama_fakultas ?? '-') }}<br>
    SURAT KETERANGAN PENDAMPING IJAZAH (SKPI)<br>
    NOMOR : {{ $d->no_skpi ?? '........ / ........ / ........ / 2026' }}
</div>

<p style="text-align: justify; text-indent: 25px;">
Surat Keterangan Pendamping Ijazah (SKPI) adalah pelengkap Ijazah yang menerangkan capaian pembelajaran lulusan (CPL) dan prestasi serta aktivitas pemegang Ijazah selama masa studi.
</p>

<div class="section-title">A. Informasi tentang Identitas Diri Pemegang SKPI</div>
<table style="margin: 0px 0px 0px 15px;">
    <tr><td width="50%">1. Nama Lengkap</td><td>: {{ $d->nama_mahasiswa }}</td></tr>
    <tr><td>2. Tempat, Tanggal Lahir</td><td>: {{ $d->tempat_lahir }}, {{ \Carbon\Carbon::parse($d->tanggal_lahir)->translatedFormat('d F Y') }}</td></tr>
    <tr><td>3. Nomor Induk Mahasiswa</td><td>: {{ $d->nim }}</td></tr>
    <tr><td>4. Tahun Masuk</td><td>: {{ \Carbon\Carbon::parse($d->tgl_masuk)->format('Y') }}</td></tr>
    <tr><td>5. Tahun Lulus</td><td>: {{ \Carbon\Carbon::parse($d->tgl_keluar)->format('Y') }}</td></tr>
    <tr><td>6. Nomor Ijazah</td><td>: {{ $d->no_ijazah ?? '-' }}</td></tr>
    <tr><td>7. Gelar dan Singkatan</td><td>: {{ $d->gelar_panjang}} ({{$d->gelar}})</td></tr>
</table>

<div class="section-title">B. Informasi tentang Identitas Penyelenggara Program</div>
<table style="margin: 0px 0px 0px 15px;">
    <tr>
        <td width="50%">1. SK Pendirian Perguruan Tinggi</td>
        <td>: </td>
    </tr>
    <tr>
        <td colspan="2" style="padding-left: 20px;">
        Peraturan Pemerintah No.42 Tahun 1960 tanggal 29 Oktober 1960 (Lembaran Negara Tahun 1960 No.135) tentang Pendirian Universitas Sriwijaya
        </td>
    </tr>

    <tr>
        <td>2. Nama Institusi</td>
        <td>: Universitas Sriwijaya</td>
    </tr>
    
    <tr>    
        <td>3. Program Studi</td>
        <td>: {{ $d->nama_prodi }}</td>
    </tr>

    <tr>
        <td>4. Jenis dan Jenjang Pendidikan</td>
        <td>: {{ $d->jenjang }}</td>
    </tr>

    <tr>
        <td>5. Jenjang Kualifikasi Sesuai KKNI</td>
        <td>: </td>
    </tr>

    <tr>
        <td>6. Persyaratan Penerimaan</td>
        <td>: </td>
    </tr>
    
    <tr>    
        <td>7. Bahasa Pengantar Kuliah</td>
        <td>:</td>
    </tr>

    <tr>
        <td>8. Sistem Penilaian</td>
        <td>:</td>
    </tr>

    <tr>
        <td>9. Lama Studi Reguler</td>
        <td>: {{$d->lama_studi}} Bulan</td>
    </tr>

    <tr>
        <td>10. Jenis dan Jenjang Pendidikan Lanjutan</td>
        <td>: </td>
    </tr>
</table>

<div class="section-title">C. Informasi tentang Kualifikasi dan Hasil yang Dicapai Capaian Pembelajaran Lulusan</div>
<table style="margin: 0px 0px 0px 15px;">
    <tr><td>1. CPL-1</td><td>: ...................................................</td></tr>
    <tr><td>2. CPL-2</td><td>: ...................................................</td></tr>
    <tr><td>3. CPL-3</td><td>: ...................................................</td></tr>
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
            @if($total > 2000) ISTIMEWA
            @elseif($total >= 1500 && $total <= 2000) SANGAT BAIK
            @elseif($total >= 1000 && $total < 1500) BAIK
            @else CUKUP
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
        <tr><td></td><td>an. Rektor</td></tr>
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