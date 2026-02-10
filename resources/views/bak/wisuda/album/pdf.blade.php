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

        .page {
            width: 100%;
            height: 100%;
            page-break-after: always;

            display: table;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .page-center {
            display: table-cell;
            vertical-align: middle;   /* CENTER VERTIKAL PALING STABIL */
            text-align: center;
            width: 100%;
        }

        .info-mahasiswa {
            margin: 0 auto;
            width: auto;              /* lebar mengikuti isi */
            display: inline-table;    /* penting agar tidak full width */
            text-align: left;
            /*border: 1px solid #000;   /* opsional kalau mau kotak */
            padding: 10px 20px;
        }

        .info-mahasiswa td:first-child {
            white-space: nowrap;   /* label tidak turun baris */
            padding-right: 10px;
        }

        .info-mahasiswa td:nth-child(2) {
            padding: 0 10px;
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
        PROGRAM STUDI {{ $prodi->nama_program_studi }} ({{ $prodi->nama_jenjang_pendidikan }})
    </div>
</div>
<div class="page-break"></div>

@foreach($data as $d)
<div class="page text-upper">
    <div class="page-center">

        <div class="img">
            <img src="storage/{{ $d->pas_foto }}" width="150">
        </div>

        <div style="font-weight:bold; font-size:25pt; margin-bottom:20px">
            {{ $d->nama_mahasiswa }}
        </div>

        <table class="info-mahasiswa" style="font-size:15pt; margin-bottom:50px;">
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
                <td>{{$d->tempat_lahir}} / {{idDate($d->tanggal_lahir)}}</td>
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
                <td>{{ idDate($d->tgl_keluar) }}</td>
            </tr>
        </table>
    </div>
</div>
@endforeach
</body>
</html>
