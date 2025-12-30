<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
        <title>Transkrip Akademik</title>
    <style>
        @page { margin: 40px 20px 40px 20px; }

        .page-landscape {
            width: 100%;
        }

        .row-table {
            width: 100%;
            border: none !important;       /* hapus border tabel utama */
            border-collapse: collapse;   
                }

        .row-table td,
        .row-table th {
            border: none !important;       /* hapus border sel di tabel utama */
        }

        .col-left {
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }

        .col-right {
            width: 50%;
            vertical-align: top;
            padding-left: 10px;
        }


        body {
            font-family: "Times New Roman", serif;
            margin: 5px;
        }

        table { border-collapse: collapse; 
            width: 100%; 
                page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        /* Header tabel tetap bergaris */
        thead th {
            font-size: 10px;
            border: 0.25px solid #000;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        /* Hilangkan garis antar baris isi */
        tbody td {
            border-left: 0.25px solid #000;
            border-right: 0.25px solid #000;
            padding: 2px 4px;
        }

        /* Tambahkan border bawah tiap halaman */
        tfoot {
            display: table-row-group;
        }

        tfoot td.footer-border {
            border-bottom: 0.25px solid #000;
        }

        .no-border td {
            border: none !important;
            padding: 2px 0;
        }

        .dataDiri-table tr td {
            vertical-align: top;
            font-size: 8px;
        }

        .transkrip-table th {
            padding: 10px 2px 10px 2px;
            font-size: 5pt;
            border: 0.25px solid #000 !important; 
        }

        .transkrip-table tr td {
            border-left: 0.25px solid #000 !important; 
            border-right: 0.25px solid #000 !important; 
            /* border-top: none;
            border-bottom: none; */
            vertical-align: top;
            padding: 1px 5px 1px 5px;
            font-size: 5pt;
            margin-bottom: 0px;
        }

        .ipk-table tr td {
            vertical-align: top;
            padding: 5px 3px;
            font-size:5pt;
        }

        .ttd-table tr td {
            vertical-align: top;
            padding: 0px 3px;
            font-size:5pt
        }

        .header-title {
            text-align:center;
            font-weight:bold;
        }

        .text-upper{ text-transform: uppercase; }

        .header-title .title1 { font-size: 10px; }
        .header-title .title2 { font-size: 9px; font-style: italic; }
        .header-title .title3 { font-size: 9px; margin-top: 10px; }
        .header-title .title4 { font-size: 8px; }

        .font-small td, 
        .font-small th {
            font-size: 6px !important;
            padding: 1px 2px !important;
        }

        .no-wrap {
            white-space: nowrap; /* pastikan teks tidak terpecah */
        }

        .page-break { page-break-after: always; }
    </style>
</head>

<body>
@foreach($data as $d)
@php
    $splitIndex = 30; // batas baris kolom kiri
@endphp

<div class="page-landscape">
    <table class="row-table">
        <tr>
            {{-- KOLM KIRI --}}
            <td class="col-left">
                {{-- ========================================================= --}}
                {{--                         HEADER                           --}}
                {{-- ========================================================= --}}
                <table class="no-border">
                    <tr>
                        <td width="30" align="center" style="padding:0px 0px 0px 20px; 
                            {{-- 
                            border:0.25px solid #000 !important;
                            --}}
                        ">
                            <img src="{{ public_path('images/unsri.png') }}" width="50">
                        </td>
                        <td align="center" width="300" style="padding:0px 0px px 0px;  
                            {{--
                            border:0.25px solid #000 !important;
                            --}}
                        ">
                            <div class="header-title" style="margin-right:50px;">
                                <div class="title1">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</div>
                                <div class="title2">MINISTRY OF HIGHER EDUCATION, SCIENCE, AND TECHNOLOGY</div>
                                <div class="title1">UNIVERSITAS SRIWIJAYA</div>
                                <div class="title2">SRIWIJAYA UNIVERSITY</div>
                                <div class="title1">{{ strtoupper($fakultas->nama_fakultas) }}</div>
                                <div class="title2">{{ strtoupper($fakultas->nama_fakultas_eng) }}</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div class="header-title" style="margin-right:50px;">
                                <div class="title3">DAFTAR NILAI AKADEMIK (TRANSKRIP)</div>
                                <div class="title4">ACADEMIC TRANSCRIPT</div>
                            </div>
                        </td>
                    </tr>
                </table>

                <br>

                {{-- ========================================================= --}}
                {{--                        BIODATA                           --}}
                {{-- ========================================================= --}}
                <table class="no-border dataDiri-table">
                    <tr>
                        <td width="140">NAMA <em>(NAME)</em></td> 
                        <td width="1%">:  </td>
                        <td> {{ strtoupper($d->nama_mahasiswa) }}</td>
                    </tr>
                    <tr>
                        <td>TEMPAT LAHIR <em>(PLACE OF BIRTH)</em></td> 
                        <td width="1%">:  </td>
                        <td> {{ $d->tempat_lahir }}</td>
                    </tr>
                    <tr>
                        <td>TANGGAL LAHIR <em>(DATE OF BIRTH)</em></td>
                        <td width="1%">:</td>
                        <td>{{ idDate($d->tanggal_lahir)}} (<em>{!! enDate($d->tanggal_lahir)!!}</em>)</td>
                    </tr>
                    <tr>
                        <td>NIM <em>(STUDENT REGISTRATION NUMBER)</em></td> 
                        <td width="1%">:  </td>
                        <td> {{ $d->nim }}</td>
                    </tr>
                    <tr class="text-upper">
                        <td>FAKULTAS <em>(FACULTY)</em></td> 
                        <td width="1%">:  </td>
                        <td> 
                            {{str_replace('Fakultas ', '', $fakultas->nama_fakultas) }}<br>
                            <em>({{str_replace('Faculty of ', '', $fakultas->nama_fakultas_eng) }})</em>
                        </td>
                    </tr>
                    <tr>
                        <td>STRATA PENDIDIKAN <em>(EDUCATION PROGRAM)</em></td> 
                        <td width="1%">:  </td>
                        <td> {{ $d->jenjang }}</td>
                    </tr>
                    <tr class="text-upper">
                        <td>JURUSAN/PROGRAM STUDI <br><em>(DEPARTMENT/STUDY PROGRAM)</em></td> 
                        <td width="1%">:  </td>
                        <td> 
                            {{ $d->nama_prodi }}
                            @if(!empty($d->nama_prodi_en))
                                <em>({{ $d->nama_prodi_en }})</em>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td>TANGGAL LULUS <em>(DATE OF COMPLETION)</em></td>
                        <td width="1%">:</td>
                        <td>{{ idDate($d->tgl_sk_yudisium)}} (<em>{!! enDate($d->tgl_sk_yudisium)!!}</em>)</td>
                    </tr>
                    <tr>
                    <td>TANGGAL WISUDA <em>(CONVOCATION DATE)</em></td>
                        <td width="1%">:</td>
                        <td>{{ idDate($d->periode_wisuda->first()->tanggal_wisuda)}} (<em>{!! enDate($d->periode_wisuda->first()->tanggal_wisuda)!!}</em>)</td>
                    </tr>
                    <tr class="text-upper">
                        <td>MASA STUDI <em>(LENGTH OF STUDY)</em></td> 
                        <td width="1%">:  </td>
                        <td> 
                            {{ $d->masa_studi }}
                            <em>({{$d->masa_studi_en}})</em>
                        </td>
                    </tr>
                    <tr class="text-upper">
                        <td>NOMOR IJAZAH NASIONAL <em>(NATIONAL CERTIFICATE NUMBER)</em></td> 
                        <td width="1%">:  </td>
                        <td> {{ $d->no_ijazah }}</td>
                    </tr>
                    <tr>
                        <td>KODE UNIVERSITAS <em>(UNIVERSITY CODE)</em></td> 
                        <td width="1%">:  </td>
                        <td> {{ $kode_univ }}</td>
                    </tr>
                </table>

                <br>

                {{-- ========================================================= --}}
                {{--                TABEL MATA KULIAH TRANSKRIP                --}}
                {{-- ========================================================= --}}
                @php
                    $totalSks = 0;
                    $totalBobot = 0;
                    $jumlahMK = count($d->transkrip_mahasiswa);
                    $mkLeft = $d->transkrip_mahasiswa->slice(0, $splitIndex);
                    $mkRight = $d->transkrip_mahasiswa->slice($splitIndex);
                @endphp

                <table class="transkrip-table {{ $jumlahMK > 70 ? 'font-small' : '' }}">
                    <thead>
                        <tr>
                            <th rowspan="2" width="7">NO</th>
                            <th rowspan="2" width="20">KODE<br>(CODE)</th>
                            <th rowspan="2">MATA KULIAH (SUBJECT)</th>
                            <th width="28">SKS (K)</th>
                            <th width="10">NILAI</th>
                            <th width="32">BOBOT (B)</th>
                            <th width="15">K × B</th>
                        </tr>
                        <tr>
                            <th>CREDIT (C)</th>
                            <th>GRADE</th>
                            <th>WEIGHT (W)</th>
                            <th>C × W</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mkLeft as $i => $mk)
                            @php
                                $sks = floor($mk->sks_mata_kuliah);
                                $bobot = $mk->nilai_indeks * $sks;

                                // tambahkan ke total sehingga meliputi kolom kiri dan kanan
                                $totalSks += $sks;
                                $totalBobot += $bobot;
                            @endphp
                            <tr>
                                <td align="center">{{ $i+1 }}</td>
                                <td>{{ $mk->kode_mata_kuliah }}</td>
                                <td>{{ strtoupper($mk->nama_mata_kuliah) }}
                                    <em>({{ strtoupper($mk->nama_mata_kuliah) }})</em>
                                </td>
                                <td align="center">{{ $sks }}</td>
                                <td align="center">{{ $mk->nilai_huruf }}</td>
                                <td align="center">{{ $mk->nilai_indeks }}</td>
                                <td align="center">{{ $bobot }}</td>
                            </tr>
                        @endforeach
                        <tr style="border-bottom: 0.25px solid #000;">
                            <td colspan="7"></td>
                        </tr>
                    </tbody>
                </table>
            </td>

            {{-- KOLM KANAN --}}
            <td class="col-right">
                @if(count($mkRight) > 0)
                    {{-- TABEL MATA KULIAH KOLON KANAN --}}
                    <div style="margin:50px 0px 5px 0px; padding:0px 0px 0px 0px;" >
                        <table class="transkrip-table {{ $jumlahMK > 70 ? 'font-small' : '' }}">
                            <thead>
                                <tr>
                                    <th rowspan="2" width="7">NO</th>
                                    <th rowspan="2" width="20">KODE<br>(CODE)</th>
                                    <th rowspan="2">MATA KULIAH (SUBJECT)</th>
                                    <th width="28">SKS (K)</th>
                                    <th width="10">NILAI</th>
                                    <th width="32">BOBOT (B)</th>
                                    <th width="15">K × B</th>
                                </tr>
                                <tr>
                                    <th>CREDIT (C)</th>
                                    <th>GRADE</th>
                                    <th>WEIGHT (W)</th>
                                    <th>C × W</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mkRight as $i => $mk)
                                    @php
                                        $sks = floor($mk->sks_mata_kuliah);
                                        $bobot = $mk->nilai_indeks * $sks;

                                        $totalSks += $sks;
                                        $totalBobot += $bobot;
                                    @endphp
                                    <tr>
                                        <td align="center">{{ $i+1 }}</td>
                                        <td>{{ $mk->kode_mata_kuliah }}</td>
                                        <td>{{ strtoupper($mk->nama_mata_kuliah) }}
                                            <em>({{ strtoupper($mk->nama_mata_kuliah) }})</em>
                                        </td>
                                        <td align="center">{{ $sks }}</td>
                                        <td align="center">{{ $mk->nilai_huruf }}</td>
                                        <td align="center">{{ $mk->nilai_indeks }}</td>
                                        <td align="center">{{ $bobot }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{-- TOTAL SKS & BOBOT kolom kanan --}}
                            <tr style="border: 0.25px solid #000; text-align:center;">
                                <td colspan="3">TOTAL</td>
                                <td>{{ $totalSks }}</td>
                                <td colspan="2"></td>
                                <td>{{$totalBobot}}</td>
                            </tr>
                        </table>
                    </div>
                @endif                                
                <!-- <br> -->

                {{-- ========================================================= --}}
                {{--                   IPK + SKRIPSI + PEMBIMBING              --}}
                {{-- ========================================================= --}}
                <!-- @if($jumlahMK < 37)
                    <div class="page-break"></div>
                @endif -->
                <table class="no-border ipk-table pt-100">
                    <tr class="text-upper">
                        <td class="no-wrap">INDEKS PRESTASI KUMULATIF / <em>GRADE POINT AVERAGE</em></td>
                        <td width="1">: </td>
                        <td colspan="3">
                            {{$totalBobot}}/{{$totalSks}} = 
                            {{ str_replace('.', ',', $d->ipk) }} ({{terbilang_ipk($d->ipk)}}) / 
                            {{ str_replace('.', ',', $d->ipk) }} ({{terbilang_ipk_en($d->ipk)}})
                        </td>
                    </tr>

                    <tr class="text-upper">
                        <td class="no-wrap">PREDIKAT KELULUSAN <em>(OVERALL RATING)</em></td>
                        <td width="1">: </td>
                        <td width="400" colspan="3">
                            {{ $d->predikat_kelulusan->indonesia }}
                            <em>({{ $d->predikat_kelulusan->inggris }})</em>
                        </td>
                    </tr>

                    <tr>
                        <td width="50">JUDUL SKRIPSI<br><em>(FINAL PROJECT TITLE)</em></td>
                        <td width="1">: </td>
                        <!-- <td width="1">: </td> -->
                        <td colspan="4">
                            {{ $d->aktivitas_mahasiswa->judul }}<br>
                            <em>({{ $d->judul_eng }})</em>     
                        </td>
                    </tr>

                    <tr>
                        <td width="50">PEMBIMBING SKRIPSI<br><em>(FINAL PROJECT ADVISORS)</em></td>
                        <td width="1">: </td>
                        <!-- <td width="1">: </td> -->
                        <td colspan="4">
                            <span style="float:left;"></span>
                            <span style="display:block; margin-left: 0px;">
                                @foreach($d->aktivitas_mahasiswa->bimbing_mahasiswa as $pembimbing)
                                    <div>{{ $pembimbing->pembimbing_ke }}. {{ $pembimbing->nama_dosen }}</div>
                                @endforeach
                            </span>
                        </td>
                    </tr>
                </table>


                <br><br>

                {{-- ========================================================= --}}
                {{--                        TANDA TANGAN                       --}}
                {{-- ========================================================= --}}
                <table class="no-border ttd-table" width="100%" style="font-size: 10px;">

                    {{-- Baris 1 --}}
                    <tr>
                        <td width="38%"></td>
                        <td width="24%"></td>
                        <td width="38%" style="padding-bottom:5px">
                            INDRALAYA, {{ idDate($d->tgl_sk_yudisium)}} 
                            (<em>{!! enDate($d->tgl_sk_yudisium) !!}</em>)
                        </td>
                    </tr>

                    {{-- Baris 2 --}}
                    <tr>
                        <td width="45%">
                            {{ strtoupper($wd1->jabatan) }}
                        </td width="10%">
                        <td></td>
                        
                        <td width="45%">
                            {{ strtoupper($wr1->jabatan) }}
                        </td>
                    </tr>

                    {{-- Baris 3 --}}
                    <tr>
                        <td width="45%">
                            VICE DEAN OF ACADEMIC, STUDENT AND QUALITY ASSURANCE AFFAIRS,
                        </td>
                        <td width="10%" rowspan="10" style="text-align:center; vertical-align:top;
                        /* padding-top:5px; */
                        ">
                            <img src="{{ $d->pas_foto }}" alt="Foto" width="70">
                        </td>
                        <td width="45%">
                            VICE RECTOR FOR ACADEMIC, STUDENT AFFAIRS, AND QUALITY ASSURANCE
                        </td>
                    </tr>

                    {{-- Baris 4 (kosong, karena foto merge di kolom tengah) --}}
                    <tr>
                        <td width="40%"></td>
                        <td width="40%"></td>
                    </tr>

                    <tr><td><br><br><br><br><br><br></td></tr>

                    {{-- Baris 5 --}}
                    <tr class="margin:0px;">
                        <td width="40%">
                            {{ $wd1->gelar_depan }} {{ strtoupper($wd1->nama) }}{{ $wd1->gelar_belakang ? ', '.$wd1->gelar_belakang : '' }}
                        </td>
                        <td width="40%">
                            {{ $wr1->gelar_depan }} {{ strtoupper($wr1->nama) }}{{ $wr1->gelar_belakang ? ', '.$wr1->gelar_belakang : '' }}
                        </td>
                    </tr>

                    {{-- Baris 6 --}}
                    <tr class="margin:0px;">
                        <td width="40%">
                            NIP {{ strtoupper($wd1->nip) }}
                        </td>
                        
                        <td width="40%">
                            NIP {{ strtoupper($wr1->nip) }}
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</div>

@endforeach

</body>
</html>
