@extends('layouts.doc-ijazah')
@section('content')
@push('styles')
<style>
    /* for id no_sertificat. make it position in the right */
    #no_sertifikat {
        display: flex;
        justify-content: flex-end;
        width: 100%;
        margin-top: 70px;
        margin-left: 54%;
        font-size: 10pt;
    }


    .data-diri {
        width: 100%;
        margin-top: 32px;
        text-align: left;
        font-weight: bold;
        font-size: 12pt;
    }

    .mid-word {
        font-weight: bold;
        font-size: 13pt;
        margin-top: 20px;
    }

    .gelar {
        font-weight: bold;
        font-size: 20pt;
        margin-top: 20px;
        margin-bottom: 20px;
        text-align: center;
        margin-left: auto;
        margin-right: auto;
    }


</style>
@endpush
@foreach ($data as $d)
    @push('header')
    <center>
    @if (file_exists(public_path('images/unsri.png')))
    <img src="{{ public_path('images/unsri.png') }}" alt="Logo" style="width: 70px">
    @endif
    </center>
    @endpush
    <div id="no_sertifikat">
        <table>
            <tr>
                <td>No. Ijazah Nasional</td>
                <td style="padding-left: 5px; padding-right: 5px">:</td>
                <td>{{$d->no_ijazah}}</td>
            </tr>
            <tr>
                <td>Kode Universitas</td>
                <td style="padding-left: 5px; padding-right: 5px">:</td>
                <td>{{$kode_univ}}</td>
            </tr>
        </table>
    </div>

    <div class="data-diri">
        <table>
            <tr>
                <td style="width:70px">Nama</td>
                <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                <td style="width: 330px; margin-right:30px">{{$d->nama_mahasiswa}}</td>
                <td style="width: 20%">No. Induk Mahasiswa</td>
                <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                <td>{{$d->nim}}</td>
            </tr>
            <tr>
                <td style="width:70px">Lahir di</td>
                <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                <td style="width: 330px; margin-right:30px">{{$d->tempat_lahir}}</td>
                <td style="width: 20%">Tanggal Lulus</td>
                <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                <td>
                     {{ \Carbon\Carbon::parse($d->tanggal_keluar)->locale('id')->translatedFormat('d F Y') }}
                </td>
            </tr>
             <tr>
                <td style="width:70px">Tanggal</td>
                <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                <td style="width: 330px; margin-right:30px">
                    {{ \Carbon\Carbon::parse($d->tanggal_lahir)->locale('id')->translatedFormat('d F Y') }}
                </td>
                <td style="width: 240px">Fakultas</td>
                <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                <td>{{$fakultas}}</td>
            </tr>
            <tr>
                <td style="width:70px"></td>
                <td style="max-width: 15px;padding-left:10px;padding-right:8px"></td>
                <td style="width: 330px; margin-right:30px">
                </td>
                <td style="width: 240px">Program Studi/Kode Prodi</td>
                <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                    <td>{{$d->nama_prodi}}/{{$d->kode_prodi}}</td>
            </tr>
             <tr>
                <td style="width:70px"></td>
                <td style="max-width: 15px;padding-left:10px;padding-right:8px"></td>
                <td style="width: 330px; margin-right:30px"></td>
                <td style="width: 20%">Bidang Kajian Utama</td>
                <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                <td>Contoh BKU</td>
            </tr>
            {{-- <tr>
                <td style="width:60px">Lahir di</td>
                <td style="max-width: 3%">:</td>
                <td style="width:120px">{{$d->tempat_lahir}}</td>
                <td style="width: 20%">No. Induk Mahasiswa</td>
                <td style="width: 5%">:</td>
                <td style="width:30%">{{$d->nim}}</td>
            </tr> --}}
        </table>
    </div>
    <div class="mid-word">
        <center>Telah memenuhi semua persyaratan pendidikan yang ditentukan. Kepadanya diberikan ijazah dan sebutan</center>
        <div class="gelar">
            Sarjana Teknik (S.T.)
        </div>
        <center>beserta segala hak dan kewajiban yang melekat pada sebutan tersebut</center>
    </div>
@endforeach

@endsection
