<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Helvetica;
            text-align: center;
            margin: 0px 50px 0px 50px;
        }

        .judul {
            font-size: 35pt;
            margin: 30px 110px 0px 110px;
        }

        .sub {
            font-size: 28pt;
            margin: 0px 110px 0px 110px;
            font-weight: bold;
        }

        .fakultas{
            font-size: 30pt;
            margin: 20px 110px 0px 110px;
        }

        .img{
            margin: 40px 0px 40px 0px;
        }

        .footer {
            position: relative;
            width: 100%;
            font-size: 28pt;
        }

        /* PERBAIKAN vertical-align */
        .info-mahasiswa td {
            vertical-align: top;
        }

        .text-upper{ text-transform: uppercase; }
        .text-bold{ font-weight: bold; }
        .page-break { page-break-after: always; }
    </style>

</head>
<body>

<div class='header'>
    <div class="judul">UNIVERSITAS SRIWIJAYA</div>

    <div class="sub">
        WISUDA KE-{{ $periode_wisuda -> periode }}
    </div>

    <div class="fakultas text-upper">
        {{ $fakultas -> nama_fakultas }}
    </div>
    <div class="img">
        <img src="{{ public_path('images/unsri.png') }}" width="220">
    </div>
    <div class="footer">
        UNIVERSITAS SRIWIJAYA<br>
        INDRALAYA, {{ idDate($periode_wisuda->tanggal_wisuda) }}
    </div>
</div>

<div class="page-break"></div>

<div class="sub-header text-upper" >
    <div style="margin:250px 110px 0px 110px; font-size: 30pt;">
        {{ $fakultas -> nama_fakultas }}
    </div>
    <div style="margin:10px 110px 0px 110px; font-size: 20pt;">
        {{ $fakultas -> nama_fakultas }}
    </div>
</div>

<div class="page-break"></div>

<div class='content text-upper'>
@foreach($data as $d)
    <div class="img">
        <img src="{{ $d->pas_foto }}" alt="Foto" width="150">
    </div>
    <div style="font-weight:bold; font-size: 25pt; margin-bottom:20px">
        {{$d->nama_mahasiswa}}
    </div>
    <table class="info-mahasiswa" style="font-size: 15pt; margin-bottom:50px; width:100%; text-align:left;">
        <tr>
            <td class="text-bold" width="200px">NOMOR INDUK MAHASISWA</td>
            <td> : </td>
            <td>{{$d->nim}}</td>
        </tr>

        <tr>
            <td class="text-bold" width="300px">FAKULTAS/PROGRAM</td>
            <td> : </td>

            <!-- nowrap ditambahkan -->
            <td style="white-space: nowrap; vertical-align: top;">
                {{ str_replace('Fakultas', '', $d->nama_fakultas) }} / {{$d->jenjang}}
            </td>
        </tr>

        <tr>
            <td class="text-bold" width="300px">PROGRAM STUDI</td>
            <td> : </td>
            <td>{{$d->nama_prodi}}</td>
        </tr>

        <tr>
            <td class="text-bold" width="300px">TEMPAT/TANGGAL LAHIR</td>
            <td> : </td>
            <td>{{$d->tempat_lahir}} / {{$d->tanggal_lahir}}</td>
        </tr>

        <tr>
            <td class="text-bold" width="300px">IPK</td>
            <td> : </td>
            <td>{{$d->ipk}}</td>
        </tr>

        <tr>
            <td class="text-bold" width="300px">TERDAFTAR DI UNSRI</td>
            <td> : </td>
            <td>{{$d->nim}}</td>
        </tr>

        <tr>
            <td class="text-bold" width="300px">TANGGAL YUDISIUM</td>
            <td> : </td>
            <td>{{$d->tgl_sk_yudisium}}</td>
        </tr>
    </table>


    {{-- Tambahkan kondisi untuk pagebreak --}}
    @if(!$loop->last)
        <div class="page-break"></div>
    @endif

@endforeach
</div>



</body>
</html>
