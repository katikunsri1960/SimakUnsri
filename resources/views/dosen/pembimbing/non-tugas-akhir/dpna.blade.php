@extends('layouts.doc-nologo')
@section('title')
DPNA Konversi Aktivitas
@endsection
@section('content')
@include('swal')
<div style="text-transform: uppercase; font-family: Arial, Helvetica, sans-serif">
    <div class="container-fluid">
        <table style="width: 100%" class="table-pdf">
            <tr>
                <td class="text-judul3 text-center">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</td>
            </tr>
            <tr>
                <td class="text-judul3 text-center"><strong>UNIVERSITAS SRIWIJAYA</strong></td>
            </tr>
        </table>
    </div>
    <table class="text-10" style="width: 100%; margin-top:1.0rem">

        <tr>
            <td class="text-12 text-center text-upper" height="10" colspan="3">
                <strong>DPNA KONVERSI AKTIVITAS</strong>
            </td>
        </tr>
    </table>
    <div style="text-align: center; margin-top:1.0rem; margin-bottom:1.0rem">
        <table style="width: 50%; margin: 0 auto; font-size:10pt; font-weight:bold;">
            <tr>
                <td>NAMA</td>
                <td>:</td>
                <td style="padding-left:0.5rem">{{$data->anggota_aktivitas_personal->mahasiswa->nama_mahasiswa}}</td>
            </tr>
            <tr>
                <td>NIM</td>
                <td>:</td>
                <td style="padding-left:0.5rem">{{$data->anggota_aktivitas_personal->mahasiswa->nim}}</td>
            </tr>
            <tr>
                <td>TAHUN AKADEMIK</td>
                <td>:</td>
                <td style="padding-left:0.5rem">{{$data->semester ? $data->semester->nama_semester : '-'}}</td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="table-responsive">
        <table id="header" class="text-10" border="1" rules="all" style="width: 100%">
            <thead>
                <tr>

                    <th style="padding: 0.5rem">Judul Aktivitas</th>
                    <th>Kode MK Konversi</th>
                    <th>MK Konversi</th>
                    <th>Nilai Angka</th>
                    <th>Nilai Huruf</th>
                </tr>
            </thead>
            <tbody >
                <tr >
                    <td style="padding: 0.5rem">{{$data->judul}}</td>
                    <td class="text-center">
                        {{$nilai->matkul ? $nilai->matkul->kode_mata_kuliah : '-'}}
                    </td>
                    <td class="text-center">
                        {{$nilai->matkul ? $nilai->matkul->nama_mata_kuliah. ' (' .  $nilai->matkul->sks_mata_kuliah.' SKS) ' : '-'}}
                    </td>
                    <td class="text-center">{{$nilai->nilai_angka}}</td>
                    <td class="text-center">{{$nilai->nilai_huruf}}</td>
                </tr>
            </tbody>

        </table>
    </div>
</div>
<div style="margin-top: 1.0rem; font-size:10pt;page-break-inside: avoid;" class="text-right" id="ttd">
    <table style="width: 100%">
        <tr class="text-right">
            <td></td>
            <td> Inderalaya, {{ $today}}</td>
        </tr>
        <tr class="text-right">
            <td></td>
            <td>Dosen Pembimbing,</td>
        </tr>
        @if ($data->bimbing_mahasiswa->count() > 0)

            @foreach ($data->bimbing_mahasiswa as $d)
                <tr class="text-right">
                    <td>

                    </td>
                    <td style="padding-top: 2.5rem;padding-bottom:0.5rem">
                        {{$d->dosen->nama_dosen}} &nbsp;(...............................................)
                    </td>
                </tr>
            @endforeach

        @endif
    </table>
</div>

{{-- </div> --}}
@endsection
