@extends('layouts.doc-ijazah')
@section('content')
@push('styles')
<style>
    /* for id no_sertificat. make it position in the right */
    @page {
        margin: 150px 45px 40px 45px;
    }

    header {
        position: fixed;
        top: -100px;
        left: 0;
        right: 0;
        text-align: center;
    }

    .page-break {
        page-break-after: always;
    }

    .judul {
        font-weight: bold;
        font-size: 20pt;
        margin-top: 10px;
        margin-bottom:10px;
        text-align: center;
        margin-left: auto;
        margin-right: auto;
    }

    #no_sertifikat {
        display: flex;
        justify-content: flex-end;
        width: 100%;
        margin-top:10px;
        margin-left: 70%;
        font-size: 10pt;
    }

    .data-diri {
        width: 100%;
        margin-top: 28px;
        text-align: left;
        font-weight: bold;
        font-size: 13pt;
    }

    .data-diri td {
        vertical-align: top;
        padding: 0px 0px !important;
    }

    .mid-word {
        font-weight: bold;
        font-size: 13pt;
        margin-top: 20px;
    }

    .gelar {
        font-weight: bold;
        font-size: 20pt;
        margin-top: 10px;
        margin-bottom: 10px;
        text-align: center;
        margin-left: auto;
        margin-right: auto;
    }

    .ttd {
        font-weight: bold;
        font-size: 13pt;
        margin-top: 10px;
    }

</style>
@endpush

@foreach ($data as $d)
    @if($d->jenjang == 'Profesi')
        <header>
            <img src="{{ public_path('images/unsri.png') }}" width="70">
        </header>

        <div id="no_sertifikat">
            <table>
                <tr>
                    <td>No. Sertifikat Nasional</td>
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

        <div class="judul">SERTIFIKAT PROFESI</div>

        <div style="text-align: center; font-size: 13pt; font-weight: bold;">
            Diberikan kepada:
        </div>

        <div class="data-diri">
            <table>
                <tr>
                    <td style="width:70px">Nama</td>
                    <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                    <td style="width: 330px; margin-right:30px">{{Str::title($d->nama_mahasiswa)}}</td>
                    <td style="width: 235px">No. Induk Mahasiswa</td>
                    <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                    <td>{{$d->nim}}</td>
                </tr>
                <tr>
                    <td style="width:70px">Lahir di</td>
                    <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                    <td style="width: 330px; margin-right:30px">{{Str::title($d->tempat_lahir)}}</td>
                    <td style="width: 235px">Tanggal Lulus</td>
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
                    <td style="width: 235px">Fakultas</td>
                    <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                    <td>{{$fakultas}}</td>
                </tr>
                <tr>
                    <td style="width:70px"></td>
                    <td style="max-width: 15px;padding-left:10px;padding-right:8px"></td>
                    <td style="width: 330px; margin-right:30px">
                    </td>
                    <td style="width: 235px;">Program Studi/Kode Prodi</td>
                    <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                        <td>{{$d->nama_prodi}}/{{$d->kode_prodi}}</td>
                </tr>
                @if ($d->is_bku)
                <tr>
                    <td style="width:70px"></td>
                    <td style="max-width: 15px;padding-left:10px;padding-right:8px"></td>
                    <td style="width: 330px; margin-right:30px"></td>
                    <td style="width: 235px">Bidang Kajian Utama</td>
                    <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                    <td>{{Str::title($d->bku_prodi_id)}}</td>
                </tr>
                @endif

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
            <center>Telah memenuhi semua persyaratan pendidikan yang ditentukan. Kepadanya diberikan sertifikat, dan yang bersangkutan berhak menggunakan gelar dan sebutan:</center>
            <div class="gelar">
                {{ $d->gelar_panjang}} ({{ $d->gelar}})         
            </div>
            <center>beserta segala hak dan kewajiban yang melekat pada sebutan tersebut</center>
        </div>
        <div class="ttd">
            <table style="width: 100%">
                <tr>
                    <td style="width: 66%"></td>
                    <td>Indralaya, {{ \Carbon\Carbon::parse($d->tanggal_wisuda)->locale('id')->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td style="width: 66%">Dekan,</td>
                    <td>Rektor,</td>
                </tr>
                <tr style="">
                    <td style="width: 66%;padding-top: 65px">{{$dekan && $dekan->gelar_depan ? $dekan->gelar_depan.' ' : ''}}{{$dekan ? Str::title($dekan->nama) : 'Belum Diisi'}}, {{$dekan && $dekan->gelar_belakang ? $dekan->gelar_belakang : ''}}
                                                <br>NIP {{$dekan && $dekan->nip ? $dekan->nip : 'Belum Diisi'}}</td>
                    <td style="padding-top: 65px">{{$rektor->gelar_depan." " ?? ''}}{{Str::title($rektor->nama)}}, {{$rektor->gelar_belakang}} <br> NIP {{$rektor->nip}}</td>
                </tr>
            </table>
        </div>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @else
        <header>
            <img src="{{ public_path('images/unsri.png') }}" width="70">
            <!-- <div class="judul">UNIVERSITAS SRIWIJAYA</div> -->
        </header>

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
                    <td style="width: 330px; margin-right:30px">{{Str::title($d->nama_mahasiswa)}}</td>
                    <td style="width: 235px">No. Induk Mahasiswa</td>
                    <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                    <td>{{$d->nim}}</td>
                </tr>
                <tr>
                    <td style="width:70px">Lahir di</td>
                    <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                    <td style="width: 330px; margin-right:30px">{{Str::title($d->tempat_lahir)}}</td>
                    <td style="width: 235px">Tanggal Lulus</td>
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
                    <td style="width: 235px">Fakultas</td>
                    <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                    <td>{{$fakultas}}</td>
                </tr>
                <tr>
                    <td style="width:70px"></td>
                    <td style="max-width: 15px;padding-left:10px;padding-right:8px"></td>
                    <td style="width: 330px; margin-right:30px">
                    </td>
                    <td style="width: 235px;">Program Studi/Kode Prodi</td>
                    <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                        <td>{{$d->nama_prodi}}/{{$d->kode_prodi}}</td>
                </tr>
                @if ($d->is_bku)
                <tr>
                    <td style="width:70px"></td>
                    <td style="max-width: 15px;padding-left:10px;padding-right:8px"></td>
                    <td style="width: 330px; margin-right:30px"></td>
                    <td style="width: 235px">Bidang Kajian Utama</td>
                    <td style="max-width: 15px;padding-left:10px;padding-right:8px">:</td>
                    <td>{{Str::title($d->bku_prodi_id)}}</td>
                </tr>
                @endif

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
                {{ $d->gelar_panjang}} ({{ $d->gelar}})         
            </div>
            <center>beserta segala hak dan kewajiban yang melekat pada sebutan tersebut</center>
        </div>
        <div class="ttd">
            <table style="width: 100%">
                <tr>
                    <td style="width: 66%"></td>
                    <td>Indralaya, {{ \Carbon\Carbon::parse($d->tanggal_wisuda)->locale('id')->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td style="width: 66%">Dekan,</td>
                    <td>Rektor,</td>
                </tr>
                <tr style="">
                    <td style="width: 66%;padding-top: 65px">{{$dekan && $dekan->gelar_depan ? $dekan->gelar_depan.' ' : ''}}{{$dekan ? Str::title($dekan->nama) : 'Belum Diisi'}}, {{$dekan && $dekan->gelar_belakang ? $dekan->gelar_belakang : ''}}
                                                <br>NIP {{$dekan && $dekan->nip ? $dekan->nip : 'Belum Diisi'}}</td>
                    <td style="padding-top: 65px">{{$rektor->gelar_depan." " ?? ''}}{{Str::title($rektor->nama)}}, {{$rektor->gelar_belakang}} <br> NIP {{$rektor->nip}}</td>
                </tr>
            </table>
        </div>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif      
    @endif
@endforeach

@endsection
