@extends('layouts.doc-formulir')
@section('content')
<div class="container-fluid">
    <table style="width: 100%">
        <tr>
            <td style="text-align:center; padding-left:30px">
                <img src="{{public_path('images/unsri.png')}}" alt="unsri" style="width: 80px">
            </td>
            <td style="text-align:center"><h3>FORMULIR PERMOHONAN MENGIKUTI WISUDA<br>{{Str::upper($pt->nama_perguruan_tinggi)}}</h3></td>
        </tr>
    </table>
    <hr>
</div>
<div class="container-fluid table-responsive ml-3">
    <center>No. Registrasi: <strong></strong></center>
    <div style="font-size: 12px">
        <div class="" style="margin-top: 20px">
            Yth. Rektor
        </div>
        <div class="">
            u.p Kepala BAK
        </div>
        <div class="">
            {{$pt->nama_perguruan_tinggi}}
        </div>
        <div id="" style="margin-top: 10px; margin-bottom:10px">
            Yang bertanda tangan di bawah ini:
        </div>
    </div>

    <div id="table-nama" style="font-size: 12px">
        <table>
            <tr>
                <td>Nama / NIM</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->nama_mahasiswa." / ".$riwayat->nim}}</td>
            </tr>
            <tr>
                <td>Fakultas / Program</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{Str::upper($riwayat->prodi->fakultas->nama_fakultas)}}</td>
            </tr>
            <tr>
                <td>Jurusan / Program Studi </td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{Str::upper($riwayat->prodi->nama_program_studi)}}</td>
            </tr>
            <tr>
                <td>Tempat Kuliah</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{Str::upper($data->lokasi_kuliah)}}</td>
            </tr>
            <tr>
                <td>NIK </td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? $riwayat->biodata->nik : ''}}</td>
            </tr>
            <tr>
                <td>Tempat Lahir</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? $riwayat->biodata->tempat_lahir : ''}}</td>
            </tr>
            <tr>
                <td>Tanggal Lahir</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? Str::upper($riwayat->biodata->id_tanggal_lahir) : ''}}</td>
            </tr>
            <tr>
                <td>IPK</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td></td>
            </tr>
            <tr>
                <td>Alamat Alumni</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td></td>
            </tr>
            <tr>
                <td>No. Telepon</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? $riwayat->biodata->handphone : ''}}</td>
            </tr>
            <tr>
                <td>E-Mail</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? $riwayat->biodata->email : ''}}</td>
            </tr>
            <tr>
                <td>Nama Orang Tua</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? $riwayat->biodata->nama_ayah." DAN ".$riwayat->biodata->nama_ibu_kandung : ''}}</td>
            </tr>
            <tr>
                <td>Alamat Orang Tua</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? $riwayat->biodata->alamat : ''}}</td>
            </tr>
            <tr>
                <td>Terdaftar di Unsri</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>Bulan: {{date('m', strtotime($riwayat->tanggal_daftar))}} Tahun: {{date('Y', strtotime($riwayat->tanggal_daftar))}}</td>
            </tr>
            <tr>
                <td>Tanggal Yudisium</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{Str::upper($data->id_tanggal_sk_yudisium)}}</td>
            </tr>
            <tr>
                <td>Masa Studi</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$data->masa_studi}}</td>
            </tr>
        </table>
        <div id="" style="margin-top: 20px">
            Judul T.A./Skripsi/Tesis/Disertasi:
        </div>
        <div class="">alkdsjfa;lksdjf;alsdkjf;alsdfj;asdfakjsdfhalksdjhflakjsdhflakjsdhflkajshdl asdfasdfasdfadf adf asdf asd fasdf asdfasdfasdfasdfasd adf asdf asdf</div>

        <div id="" style="margin-top: 25px">
            Saya Telah memenuhi semua persyaratan akademis serta administratif yang ditetapkan (Nilai, SPP, pinjaman buku, lab, dan lain-lain) untuk
            mengikuti wisuda. Terlampir adalah berkas persyaratan yang diperlukan:
        </div>

        <div id="">
            <ol>
                @foreach ($syaratAdm as $syarat)
                <li>{{$syarat->syarat}}</li>
                @endforeach
            </ol>
        </div>
        <div class="">Demikian, atas perhatiannya diucapkan terima kasih</div>
        <div class="" style="margin-top:10px">
             <table style="width: 100%">
                <tr>
                    <td>
                        Mengetahui,<br>
                        Ketua Jurusan/Bagian <br>
                        Program Studi
                        <br><br><br><br><br>
                        ............................................
                    </td>
                    <td style="text-align: center; padding-right: 20px">
                        <img src="{{public_path($data->pas_foto)}}" alt="foto" style="height: 120px">
                    </td>
                    <td style="">
                        Indralaya, {{$now}}<br>
                        Mahasiswa ybs, <br>

                        <br><br><br><br><br><br>
                        ............................................
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center; padding-top: 35px">
                        Mengetahui/Menyetujui<br>
                        Dekan/Direktur/Wakil Dekan <br>
                        <br><br><br><br><br><br>
                        .......................................................
                    </td>
                </tr>

             </table>
        </div>
    </div>

</div>
@endsection
