@extends('layouts.doc-formulir')
@section('content')
<div class="container-fluid">
    <table style="width: 100%">
        <tr>
            <td style="text-align:center; padding-left:30px">
                <img src="{{public_path('images/unsri.png')}}" alt="unsri" style="width: 80px">
            </td>
            <td style="text-align:center">
                <h3>FORMULIR PERMOHONAN MENGIKUTI WISUDA<br>{{Str::upper($pt->nama_perguruan_tinggi)}}</h3>
            </td>
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
                <td>{{$riwayat->biodata ? $riwayat->biodata->nik : '-'}}</td>
            </tr>
            <tr>
                <td>Tempat Lahir</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? $riwayat->biodata->tempat_lahir : '-'}}</td>
            </tr>
            <tr>
                <td>Tanggal Lahir</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? Str::upper($riwayat->biodata->id_tanggal_lahir) : '-'}}</td>
            </tr>
            <tr>
                <td>IPK</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$data->ipk ? $data->ipk : '-'}}</td>
            </tr>
            <tr>
                <td>Alamat Alumni</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? Str::upper($riwayat->biodata->jalan.', '. $riwayat->biodata->dusun.', RT.'.$riwayat->biodata->rt.'/RW.'.$riwayat->biodata->rw.', '.$riwayat->biodata->kelurahan.', '.$riwayat->biodata->nama_wilayah) : '-'}}</td>
            </tr>
            <tr>
                <td>No. Telepon</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? $riwayat->biodata->handphone : '-'}}</td>
            </tr>
            <tr>
                <td>E-Mail</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? $riwayat->biodata->email : '-'}}</td>
            </tr>
            <tr>
                <td>Nama Orang Tua</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? $riwayat->biodata->nama_ayah." DAN ".$riwayat->biodata->nama_ibu_kandung :
                    '-'}}</td>
            </tr>
            <tr>
                <td>Alamat Orang Tua</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$biodata->alamat_orang_tua ? Str::upper($biodata->alamat_orang_tua) : '-'}}</td>
            </tr>
            <tr>
                <td>Terdaftar di Unsri</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>Bulan: {{date('m', strtotime($riwayat->tanggal_daftar))}} Tahun: {{date('Y',
                    strtotime($riwayat->tanggal_daftar))}}</td>
            </tr>
            <tr>
                <td>Tanggal Yudisium</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>
                    {{ \Carbon\Carbon::parse($data->tgl_sk_yudisium)->translatedFormat('d F Y') }}
                </td>
            </tr>

            <tr>
                <td>Masa Studi</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$data->masa_studi}}</td>
            </tr>
        </table>
        <div id="" style="margin-top: 20px">
            Judul {{Str::title($data->aktivitas_mahasiswa->nama_jenis_aktivitas)}}:
        </div>
        <div class="">{{$data->aktivitas_mahasiswa ? $data->aktivitas_mahasiswa->judul : '-'}}</div>

        <div id="" style="margin-top: 25px">
            Saya Telah memenuhi semua persyaratan akademis serta administratif yang ditetapkan (Nilai, SPP, pinjaman
            buku, lab, dan lain-lain) untuk
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
                        @php
                            $fotoPath = public_path('storage/'.$data->pas_foto);
                        @endphp

                        @if(!empty($data->pas_foto) && file_exists($fotoPath))
                            <img src="{{ $fotoPath }}"
                                alt="foto"
                                style="height: 120px">
                        @else
                            <span style="font-size:11px;">Foto tidak tersedia</span>
                        @endif
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
<div class="page-break"></div>
<div class="container-fluid">
    <table style="width: 100%">
        <tr>
            <td style="text-align:right; padding-left:20px; padding-right:-50px">
                <img src="{{public_path('images/unsri.png')}}" alt="unsri" style="width: 80px">
            </td>
            <td style="text-align:center; margin-left: -50px; padding-left:-50px">
                <h3 style="margin-left: -100px">
                    BIODATA AKADEMIK ALUMNI<br>
                    {{Str::upper($pt->nama_perguruan_tinggi)}}<br>
                    WISUDA KE - {{$data->wisuda_ke}}
                </h3>
            </td>
        </tr>
    </table>
    <hr>
</div>
<div class="container-fluid table-responsive ml-3">
    <center>No. Registrasi: <strong></strong></center>


    <div id="table-nama" style="font-size: 12px; margin-top: 30px">
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
                <td>{{$riwayat->biodata ? $riwayat->biodata->nik : '-'}}</td>
            </tr>
            <tr>
                <td>Tempat Lahir</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? $riwayat->biodata->tempat_lahir : '-'}}</td>
            </tr>
            <tr>
                <td>Tanggal Lahir</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? Str::upper($riwayat->biodata->id_tanggal_lahir) : '-'}}</td>
            </tr>
            <tr>
                <td>IPK</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$data->ipk ? $data->ipk : '-'}}</td>
            </tr>
            <tr>
                <td>Alamat Alumni</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? Str::upper($riwayat->biodata->jalan.', '. $riwayat->biodata->dusun.', RT.'.$riwayat->biodata->rt.'/RW.'.$riwayat->biodata->rw.', '.$riwayat->biodata->kelurahan.', '.$riwayat->biodata->nama_wilayah) : '-'}}</td>
            </tr>
            <tr>
                <td>No. Telepon</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? $riwayat->biodata->handphone : '-'}}</td>
            </tr>
            <tr>
                <td>E-Mail</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? $riwayat->biodata->email : '-'}}</td>
            </tr>
            <tr>
                <td>Nama Orang Tua</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? $riwayat->biodata->nama_ayah." DAN ".$riwayat->biodata->nama_ibu_kandung :
                    '-'}}</td>
            </tr>
            <tr>
                <td>Alamat Orang Tua</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$riwayat->biodata ? $riwayat->biodata->alamat_orang_tua : '-'}}</td>
            </tr>
            <tr>
                <td>Terdaftar di Unsri</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>Bulan: {{date('m', strtotime($riwayat->tanggal_daftar))}} Tahun: {{date('Y',
                    strtotime($riwayat->tanggal_daftar))}}</td>
            </tr>
            <tr>
                <td>Tanggal Yudisium</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>
                    {{ \Carbon\Carbon::parse($data->tgl_sk_yudisium)->translatedFormat('d F Y') }}
                </td>
            </tr>

            <tr>
                <td>Masa Studi</td>
                <td style="padding-left:40px; padding-right:20px">:</td>
                <td>{{$data->masa_studi}}</td>
            </tr>
        </table>
        <div id="" style="margin-top: 20px">
            Judul {{Str::title($data->aktivitas_mahasiswa->nama_jenis_aktivitas)}}:
        </div>
        <div class="">{{$data->aktivitas_mahasiswa ? $data->aktivitas_mahasiswa->judul : '-'}}</div>
        <div class="" style="margin-top:40px">
            <table style="width: 50%; margin-left: 45%">
                <tr>

                    <td style="text-align: center; padding-right: 20px">
                        @php
                            $fotoPath = public_path('storage/'.$data->pas_foto);
                        @endphp

                        @if(!empty($data->pas_foto) && file_exists($fotoPath))
                            <img src="{{ $fotoPath }}"
                                alt="foto"
                                style="height: 120px">
                        @else
                            <span style="font-size:11px;">Foto tidak tersedia</span>
                        @endif
                    </td>
                    <td style="">
                        Indralaya, {{$now}}<br>
                        Mahasiswa ybs, <br>

                        <br><br><br><br><br><br>
                        ............................................
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 20px">
            Catatan:<br>
            <ol>
                <li>Agar diisi dengan benar dan jelas (untuk dimuat dalam BUKU ALUMNI)</li>
                <li>Formulir ini dapat diperbanyak sesuai dengan keperluan</li>
            </ol>
        </div>
    </div>
</div>
<div class="page-break"></div>
<div class="container-fluid" style="padding-left: 40px; padding-right: 40px">
    <div class="text-center">
        <h3 style="border: 3px solid; padding: 10px">
            DATA ALUMNI<br>
            UNTUK TAYANGAN WISUDA UNSRI KE - {{$data->wisuda_ke}}
        </h3>
    </div>
    <div class="text-center">
        {{-- buat border dengan ukurang 4 x 6 cm --}}
        <div style="border: 3px solid; width: 4cm; height: 6cm; margin: auto; margin-top: 100px; margin-bottom: 100px ">
            <div style="margin-top: 40px; text-wight: bold"><strong>FOTO</strong></div>
            <div style="margin-top: 40px; text-wight: bold"><strong>4 X 6</strong></div>
            <div style="margin-top: 40px; text-wight: bold"><strong>BERWARNA</strong></div>
        </div>
    </div>
    <div>
        ( DITULIS DENGAN HURUF CETAK / BALOK )
    </div>
    <div style="margin-top: 40px">
        <table style="width: 100%; font-weight: bold">
            <tr>
                <td style="padding-top: 10px; padding-bottom: 10px">NAMA</td>
                <td style="padding-left: 10px;padding-right:10px">:</td>
                <td>....................................................................................................................
                </td>
            </tr>
            <tr>
                <td style="padding-top: 10px; padding-bottom: 10px">NIM</td>
                <td style="padding-left: 10px;padding-right:10px">:</td>
                <td>....................................................................................................................
                </td>
            </tr>
            <tr>
                <td style="padding-top: 10px; padding-bottom: 10px">FAKULTAS</td>
                <td style="padding-left: 10px;padding-right:10px">:</td>
                <td>....................................................................................................................
                </td>
            </tr>
            <tr>
                <td style="padding-top: 10px; padding-bottom: 10px">JURUSAN/PRODI</td>
                <td style="padding-left: 10px;padding-right:10px">:</td>
                <td>....................................................................................................................
                </td>
            </tr>
            <tr>
                <td style="padding-top: 10px; padding-bottom: 10px">IPK</td>
                <td style="padding-left: 10px;padding-right:10px">:</td>
                <td>....................................................................................................................
                </td>
            </tr>
            <tr>
                <td style="padding-top: 10px; padding-bottom: 10px">PREDIKAT</td>
                <td style="padding-left: 10px;padding-right:10px">:</td>
                <td>DENGAN PUJIAN / SM / M</td>
            </tr>
        </table>
    </div>
</div>
<div class="page-break"></div>
<div class="container-fluid" style="font-size: 12px">
    <div class="text-center">
        <h2>
            CHECK LIST<br>
            BERKAS PERSYARATAN WISUDA KE - {{$data->wisuda_ke}}
        </h2>
    </div>
    <div style="margin-top: 20px">
        <table style="width: 100%; border-collapse:collapse">
            <tr>
                <td rowspan="4" style="width: 10%; text-align:center; padding-left: 10px; padding-right: 10px">
                    <img src="{{public_path('images/unsri.png')}}" alt="unsri" style="width: 80px">
                </td>
                <td style="width:20%">NAMA</td>
                <td style="width:60%; border: 1px solid;"></td>
            </tr>
            <tr>
                <td>NIM</td>
                <td style="width:60%; border: 1px solid;"></td>
            </tr>
            <tr>
                <td>JURUSAN/PRODI</td>
                <td style="width:60%; border: 1px solid;"></td>
            </tr>
            <tr>
                <td>FAKULTAS/PROGRAM</td>
                <td style="width:60%; border: 1px solid;"></td>
            </tr>
        </table>
    </div>
    <div style="margin-top: 20px">
        <table style="border-collapse: collapse; width: 100%; border-bottom: 1px solid;">
            <thead>
                <tr>
                    <th class="text-center align-middle table-pdf">Petugas Pendaftaran</th>
                    <th class="text-center align-middle table-pdf" style="white-space: nowrap; padding-left:15px; padding-right:15px">PA BAK</th>
                    <th class="text-center align-middle table-pdf" colspan="2">Persyaratan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($checklist as $index => $c)
                <tr style="">
                    <td class="text-center align-middle table-pdf" style="padding-top: 10px; padding-bottom: 10px; padding-left: 45%">
                        <div style="width: 10px; height: 10px; border: 1px solid;"></div>
                    </td>
                    <td class="text-center align-middle table-pdf" style="padding-top: 10px; padding-bottom: 10px; padding-left: 40%">
                        <div style="width: 10px; height: 10px; border: 1px solid;"></div>
                    </td>
                    <td class="text-left" style="vertical-align: top; padding-top: 10px; padding-bottom: 10px; padding-left:15px">{{ chr(97 + $index) }}.</td>
                    <td class="text-start" style="vertical-align: top;border-right: 1px solid; padding-top: 10px; padding-bottom: 10px">
                        {{$c->checklist}}
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td class="text-center align-middle table-pdf" colspan="2">
                        Tandatangan
                    </td>
                    <td class="text-center align-middle" colspan="2" style="border-right: 1px solid;"></td>
                </tr>
                <tr>
                    <td class="text-center align-middle table-pdf">Petugas<br>Pendaftar</td>
                    <td class="text-center align-middle table-pdf"><strong>PA BAK</strong></td>
                    <td class="text-center align-middle" colspan="2" style="border-right: 1px solid;"></td>
                </tr>
                <tr>
                    <td class="text-center align-middle table-pdf" style="padding-top:30px">            (........................)
                    </td>
                    <td class="text-center align-middle table-pdf" style="padding-top:30px">(........................)</td>
                    <td class="text-center align-middle" colspan="2" style="border-right: 1px solid;"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
