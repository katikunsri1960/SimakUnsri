<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
        <title>Transkrip Akademik</title>
    <style>
        @page { margin: 10mm 14mm 10mm 12mm; }

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
            border: 0.5pt solid #000;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        /* Hilangkan garis antar baris isi */
        tbody td {
            border-left: 0.5pt solid #000;
            border-right: 0.5pt solid #000;
            padding: 2px 0px 4px 0px;
        }

        /* Tambahkan border bawah tiap halaman */
        tfoot {
            display: table-row-group;
        }

        tfoot td.footer-border {
            border-bottom: 0.5pt solid #000;
        }

        .no-border td {
            border: none !important;
            padding: 0px 0;
        }

        .dataDiri-table tr td {
            vertical-align: top;
            font-size: 10px;
            padding: 0.60px 0px 0.60px 0px;
        }

        .transkrip-table th {
            padding: 7.5px 2px 7.5px 2px;
            font-size:7.5px;
            border: 0.5pt solid #000 !important; 
            vertical-align: middle;
        }

        .transkrip-table tr {
            border-left: 0.5pt solid #000 !important; 
            border-right: 0.5pt solid #000 !important; 
            vertical-align: top;
            margin-bottom: 0px;
        }

        .transkrip-table tr.mk-small td {
            padding: 5px 2px 5px 2px;
            font-size: 7.5px;
        }
        .transkrip-table tr.mk-medium td {
            padding: 1.5px 2px 1.5px 2px;
            font-size: 7px;
        }
        .transkrip-table tr.mk-mlarge td {
            padding: 0.5px 2px 0.5px 2px;
            font-size: 7px;
        }
        .transkrip-table tr.mk-large td {
            padding: 0.5px 2px 0.5px 2px;
            font-size: 6.5px;
        }
        .transkrip-table tr.mk-xlarge td {
            padding: 0px 2px 0px 2px;
            font-size: 5.5px;
        }
        .transkrip-table tr.mk-xxl td {
            padding: 0px 2px 0px 2px;
            font-size: 4.5px;
        }

        .transkrip-table td {
            border-left: 0.5pt solid #000 !important; 
            border-right: 0.5pt solid #000 !important; 
            vertical-align: top;
            padding: 0px 2px 0px 2px;
            margin-bottom: 0px;
        }

        .ipk-table tr td {
            vertical-align: top;
            padding: 5px 3px;
        }

        .ttd-table tr td {
            vertical-align: top;
        }

        .header-title {
            text-align:center;
            font-weight:bold;
        }

        .text-upper{ text-transform: uppercase; }

        .header-title .title1 { font-size: 12px; margin-bottom: -2px; }
        .header-title .title2 { font-size: 11px; font-style: italic; margin-bottom: -2px; }
        .header-title .title3 { font-size: 11px;}
        .header-title .title4 { font-size: 10px; font-style: italic; margin-bottom: -2px; }

        .font-small td, 
        .font-small th {
            font-size: 7px !important;
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
    $totalSks = 0;
    $totalBobot = 0;
    $jumlahMK = count($d->transkrip_mahasiswa);
    //$mkLeft = $d->transkrip_mahasiswa->slice(0, $splitIndex);
    //$mkRight = $d->transkrip_mahasiswa->slice($splitIndex);

    // margin dinamis tabel
    //dd($jumlahMK, $mkLeft->count(), $mkRight->count());
    if ($jumlahMK <= 33) {
        $rowStyle = 'mk-small';
    } elseif ($jumlahMK > 33 && $jumlahMK <= 50) {
        $rowStyle = 'mk-medium';
    } elseif ($jumlahMK > 50 && $jumlahMK <= 76) {
        $rowStyle = 'mk-large';
    } else {
        $rowStyle = 'mk-xlarge';
    }

    if($rowStyle == 'mk-small'){
        $MAX_ROWS_LEFT = 15;
    } elseif($rowStyle == 'mk-medium'){
        $MAX_ROWS_LEFT = 28;
    } elseif($d->id_prodi == '132e62cc-dfdc-437d-9df3-e5317f80a6ff' || // Sp-1 Ilmu Kesehatan Anak
    ){ 
        $MAX_ROWS_LEFT = 42;
    } elseif($d->id_prodi == 'e2f2ac47-8844-456b-b525-482db9da0abf' || // Sp-1 Ilmu Penyakit Kulit dan Kelamin
    ){ 
        $MAX_ROWS_LEFT = 57;
    } elseif($rowStyle == 'mk-large'){
        $MAX_ROWS_LEFT = 37;
    } else{
        $MAX_ROWS_LEFT = 53;
    }
    
    $totalRows = 0;

    $mkLeft = collect();
    $mkRight = collect();

    //dd($totalRows, $mkLeft->count(), $mkRight->count(), $jumlahMK);

    foreach ($d->transkrip_mahasiswa as $mk) {

        $namaId = strtoupper($mk->nama_mata_kuliah);
        $namaEn = $mk->mk_english->nama_mata_kuliah_english ?? '';

        // hitung jumlah karakter
        $totalChar = mb_strlen($namaId) + mb_strlen($namaEn);

        // 1 baris per 80 karakter
        $rowsNeeded = ceil($totalChar / 83);

        // minimal 1 baris
        $rowsNeeded = max(1, $rowsNeeded);

        if ($totalRows + $rowsNeeded <= $MAX_ROWS_LEFT) {
            $mkLeft->push($mk);
            $totalRows += $rowsNeeded;
        } else {
            $mkRight->push($mk);
        }
    }
    //dd($mkLeft->first()->jumlah_baris, $totalRows, $mkLeft->count(), $mkRight->count(), $mkLeft->first()->jumlah_baris, $jumlahMK, $rowsNeeded);

    if ($totalRows <= 37 && $mkLeft->count() < 25 && $jumlahMK > 50 && $jumlahMK < 70){
        $rowStyle = 'mk-xxl';
    }

    //dd($totalRows, $rowStyle, $mkLeft->count(), $mkRight->count() );
@endphp

@if($d->id_prodi == 'e2f2ac47-8844-456b-b525-482db9da0abf') {{-- Sp-1 Ilmu Penyakit Kulit dan Kelamin --}}
    <style>
        @page { margin: 1mm 6mm 1mm 4mm; }
    </style>
@endif

<div class="page-landscape">
    <table class="row-table">
        <tr>
            {{-- KOLM KIRI --}}
            <td class="col-left">
                {{-- ========================================================= --}}
                {{--                         HEADER                           --}}
                {{-- ========================================================= --}}
                <table class="no-border" style="@if($d->id_prodi == 'e2f2ac47-8844-456b-b525-482db9da0abf') font-size: 8px; @endif">
                    <tr>
                        <td width="30" align="center" style="padding:0px 0px 0px 18px;">
                            <img src="{{ public_path('images/unsri.png') }}" width="60">
                        </td>
                        <td align="center" width="300" style="padding:0px 0px 0px 0px;">
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
                            <div class="header-title" style="margin: 12px 50px 0px 0px;">
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
                <table class="no-border dataDiri-table" style="margin: -10px 0px 15px 0px; @if($d->id_prodi == 'e2f2ac47-8844-456b-b525-482db9da0abf') font-size: 7px; @endif">
                    <tr class="{{$rowStyle}}">
                        <td style="margin: -20px 0px -20px 0px;" width="165">NAMA <em>(NAME)</em></td> 
                        <td width="1%">:  </td>
                        @if($d->nama_perbaikan)
                            <td> {{ strtoupper($d->nama_perbaikan) }}</td>
                        @else
                            <td> {{ strtoupper($d->nama_mahasiswa) }}</td>
                        @endif
                    </tr>
                    <tr class="{{$rowStyle}}">
                        <td>TEMPAT LAHIR <em>(PLACE OF BIRTH)</em></td> 
                        <td width="1%">:  </td>
                        @if($d->tmpt_perbaikan)
                            <td> {{ strtoupper($d->tmpt_perbaikan) }}</td>
                        @else
                            <td> {{ strtoupper($d->tempat_lahir) }}</td>
                        @endif
                    </tr>
                    <tr class="{{$rowStyle}}">
                        <td>TANGGAL LAHIR <em>(DATE OF BIRTH)</em></td>
                        <td width="1%">:</td>
                        @if($d->tgl_perbaikan)
                            <td>{{ idDate($d->tgl_perbaikan)}} (<em>{!! enDate($d->tgl_perbaikan)!!}</em>)</td>
                        @else
                            <td>{{ idDate($d->tanggal_lahir)}} (<em>{!! enDate($d->tanggal_lahir)!!}</em>)</td>
                        @endif
                    </tr>
                    <tr class="{{$rowStyle}}">
                        <td>NIM <em>(STUDENT REGISTRATION NUMBER)</em></td> 
                        <td width="1%">:  </td>
                        <td> {{ $d->nim }}</td>
                    </tr>
                    <tr class="text-upper {{$rowStyle}}">
                        <td>FAKULTAS <em>(FACULTY)</em></td> 
                        <td width="1%">:  </td>
                        <td> 
                            {{str_replace('Fakultas ', '', $fakultas->nama_fakultas) }}
                            @if($fakultas->nama_fakultas_eng)
                                <em>({{str_replace('Faculty of ', '', $fakultas->nama_fakultas_eng) }})</em>
                            @endif
                        </td>
                    </tr>
                    <tr class="{{$rowStyle}}">
                        <td>STRATA PENDIDIKAN <em>(EDUCATION PROGRAM)</em></td> 
                        <td width="1%">:  </td>
                        <td> {{ strtoupper($d->jenjang) }}
                            @if($d->jenjang == 'D3')
                                <em>(DIPLOMA)</em>
                            @elseif($d->jenjang == 'S1')
                                <em>(UNDERGRADUATE)</em>
                            @elseif($d->jenjang == 'Profesi')
                                <em>(UNDERGRADUATE)</em>
                            @elseif($d->jenjang == 'S2')
                                <em>(GRADUATE)</em>
                            @elseif($d->jenjang == 'S3')
                                <em>(POST GRADUATE)</em>
                            @elseif($d->jenjang == 'Sp-1')
                                <em>(MEDICAL SPECIALIST)</em>
                            @elseif($d->jenjang == 'Sp-2')
                                <em>(MEDICAL SUBSPECIALIST)</em>
                            @endif
                        </td>
                    </tr>
                    <tr class="text-upper {{$rowStyle}}">
                        <td>JURUSAN/PROGRAM STUDI <br><em>(DEPARTMENT/STUDY PROGRAM)</em></td> 
                        <td width="1%">:  </td>
                        <td> 
                            {{ $d->nama_prodi }}
                            @if(!empty($d->nama_prodi_en))
                                <em>({{ $d->nama_prodi_en }})</em>
                            @endif
                        </td>
                    </tr>
                    
                    @if($d->is_peminatan == 1)
                        <tr class="text-upper {{$rowStyle}}">
                            <td>PEMINATAN <br><em>(MAJOR)</em></td> 
                            <td width="1%">:  </td>
                            <td> 
                                {{ $d->bku_prodi_id }}
                                @if(!empty($d->bku_prodi_en))
                                    <em>({{ $d->bku_prodi_en }})</em>
                                @endif
                            </td>
                        </tr>
                    @endif

                    <tr class="{{$rowStyle}}">
                        <td>TANGGAL LULUS <em>(DATE OF COMPLETION)</em></td>
                        <td width="1%">:</td>
                        <td>{{ idDate($d->tgl_keluar)}} (<em>{!! enDate($d->tgl_keluar)!!}</em>)</td>
                    </tr>
                    <tr class="{{$rowStyle}}">
                        <td>TANGGAL WISUDA <em>(CONVOCATION DATE)</em></td>
                        <td width="1%">:</td>
                        <td>{{ idDate($d->periode_wisuda->first()->tanggal_wisuda)}} (<em>{!! enDate($d->periode_wisuda->first()->tanggal_wisuda)!!}</em>)</td>
                    </tr>
                    <tr class="text-upper {{$rowStyle}}">
                        <td>MASA STUDI <em>(LENGTH OF STUDY)</em></td> 
                        <td width="1%">:  </td>
                        <td> 
                            {{ $d->masa_studi }}
                            <em>({{$d->masa_studi_en}})</em>
                        </td>
                    </tr>
                    <tr class="text-upper {{$rowStyle}}">
                        <td>NOMOR IJAZAH NASIONAL <em>(NATIONAL CERTIFICATE NUMBER)</em></td> 
                        <td width="1%">:  </td>
                        <td> {{ $d->no_ijazah }}</td>
                    </tr>
                    <tr class="{{$rowStyle}}">
                        <td>KODE UNIVERSITAS <em>(UNIVERSITY CODE)</em></td> 
                        <td width="1%">:  </td>
                        <td> {{ $kode_univ }}</td>
                    </tr>
                </table>

                {{-- ========================================================= --}}
                {{--                TABEL MATA KULIAH TRANSKRIP                --}}
                {{-- ========================================================= --}}


                <table class="transkrip-table">
                    <thead>
                        <tr>
                            <th rowspan="2" width="7">NO</th>
                            <th rowspan="2" width="20">KODE<br>(CODE)</th>
                            <th rowspan="2">MATA KULIAH (SUBJECT)</th>
                            <th width="35">SKS (K)</th>
                            <th width="10">NILAI</th>
                            <th width="35">BOBOT (B)</th>
                            <th width="20">K × B</th>
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
                            <tr class="{{$rowStyle}}">
                                <td align="center">{{ $i+1 }}</td>
                                <td class="no-wrap">{{ $mk->kode_mata_kuliah }}</td>
                                <td>
                                    {{ strtoupper($mk->nama_mata_kuliah) }}
                                    {!! $mk->mk_english->nama_mata_kuliah_english ? '<em>(' . strtoupper($mk->mk_english->nama_mata_kuliah_english) . ')</em>' : '' !!}
                                </td> 
                                <td align="center">{{ $sks }}</td>
                                <td align="center">{{ $mk->nilai_huruf }}</td>
                                <td align="center">{{ $mk->nilai_indeks }}</td>
                                <td align="center">{{ $bobot }}</td>
                            </tr>
                        @endforeach
                        @if($mkRight->count() == 0)
                            {{-- TOTAL SKS & BOBOT kolom kanan --}}
                            <tr style="border: 0.5pt solid #000; text-align:center; font-size:7.5px;">
                                <td style="padding:2px 0px 2px 0px;" colspan="3">TOTAL</td>
                                <td style="padding:2px 0px 2px 0px;">{{ $totalSks }}</td>
                                <td style="padding:2px 0px 2px 0px;" colspan="2"></td>
                                <td style="padding:2px 0px 2px 0px;">{{$totalBobot}}</td>
                            </tr>
                        @else
                            <tr style="border-bottom: 0.5pt solid #000;">
                                <td colspan="7"></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </td>

            {{-- KOLM KANAN --}}
            <td class="col-right">
                
                @if(count($mkRight) > 0)
                    {{-- TABEL MATA KULIAH KOLON KANAN --}}
                    <div style="margin: 35px 0px 5px 0px; padding:0px 0px 0px 0px; @if($d->id_prodi == 'e2f2ac47-8844-456b-b525-482db9da0abf') margin: 5px 0px 5px 0px; @endif" >
                        <table class="transkrip-table" >
                            <thead>
                                <tr>
                                    <th rowspan="2" width="7">NO</th>
                                    <th rowspan="2" width="20">KODE<br>(CODE)</th>
                                    <th rowspan="2">MATA KULIAH (SUBJECT)</th>
                                    <th width="35">SKS (K)</th>
                                    <th width="10">NILAI</th>
                                    <th width="35">BOBOT (B)</th>
                                    <th width="20">K × B</th>
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
                                    <tr class="{{$rowStyle}}">
                                        <td align="center">{{ $mkLeft->count() + $i + 1 }}</td>
                                        <td class="no-wrap">{{ $mk->kode_mata_kuliah }}</td>
                                        <td>
                                            {{ strtoupper($mk->nama_mata_kuliah) }}
                                            {!! $mk->mk_english->nama_mata_kuliah_english ? '<em>(' . strtoupper($mk->mk_english->nama_mata_kuliah_english) . ')</em>' : '' !!}
                                        </td> 
                                        <td align="center">{{ $sks }}</td>
                                        <td align="center">{{ $mk->nilai_huruf }}</td>
                                        <td align="center">{{ $mk->nilai_indeks }}</td>
                                        <td align="center">{{ $bobot }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{-- TOTAL SKS & BOBOT kolom kanan --}}
                            <tr style="border: 0.5pt solid #000; text-align:center; font-size:7.5px;">
                                <td style="padding:2px 0px 2px 0px;" colspan="3">TOTAL</td>
                                <td style="padding:2px 0px 2px 0px;">{{ $totalSks }}</td>
                                <td style="padding:2px 0px 2px 0px;" colspan="2"></td>
                                <td style="padding:2px 0px 2px 0px;">{{$totalBobot}}</td>
                            </tr>
                        </table>
                    </div>
                @else
                    {{-- jika tidak ada kolom kanan, tambahkan jarak --}}
                    <div style="margin:70px 0px 5px 0px; padding:0px 0px 0px 0px;" ></div>
                @endif                         

                {{-- ========================================================= --}}
                {{--                   IPK + SKRIPSI + PEMBIMBING              --}}
                {{-- ========================================================= --}}
                <!-- @if($jumlahMK < 37)
                    <div class="page-break"></div>
                @endif -->
                <table class="no-border ipk-table" style="font-size: 7.5px; padding-top:-20px;">
                    <tr class="text-upper {{$rowStyle}}">
                        <td class="no-wrap">INDEKS PRESTASI KUMULATIF / <em>GRADE POINT AVERAGE</em></td>
                        <td width="1">: </td>
                        @php
                            $ipk = $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;
                            $ipkFormatted = number_format($ipk, 2, ',', '');
                            $ipkFormattedEn = number_format($ipk, 2, '.', '');
                        @endphp

                        <td colspan="3">
                            {{ $totalBobot }}/{{ $totalSks }} =
                            {{ $ipkFormatted }}
                            ({{ terbilang_ipk($ipk) }}) /
                            {{ $ipkFormattedEn }}
                            ({{ terbilang_ipk_en($ipk) }})
                        </td>
                    </tr>

                    @if(!in_array($d->jenjang, ['Profesi', 'Sp-1', 'Sp-2']))
                        <tr class="text-upper {{$rowStyle}}">
                            <td class="no-wrap">PREDIKAT KELULUSAN <em>(OVERALL RATING)</em></td>
                            <td width="1">: </td>
                            <td width="400" colspan="3">
                                {{ $d->predikat_kelulusan->indonesia }}
                                <em>({{ $d->predikat_kelulusan->inggris }})</em>
                            </td>
                        </tr>
                    @endif

                    @if($d->jenjang != 'Profesi')
                        <tr class="{{$rowStyle}}">
                            @if($d->jenjang == 'D3')
                                <td width="50">JUDUL TUGAS AKHIR<br><em>(FINAL PROJECT TITLE)</em></td>
                            @elseif($d->jenjang == 'S1')
                                <td width="50">JUDUL SKRIPSI<br><em>(FINAL PROJECT TITLE)</em></td>
                            @elseif($d->jenjang == 'S2' || $d->jenjang == 'Sp-1')
                                <td width="50">JUDUL TESIS<br><em>(THESIS TITLE)</em></td>
                            @elseif($d->jenjang == 'S3' || $d->jenjang == 'Sp-2')
                                <td width="50">JUDUL DISERTASI<br><em>(DISERTATION TITLE)</em></td>
                            @endif
                            <td width="1">: </td>
                            <!-- <td width="1">: </td> -->
                            <td colspan="3">
                                {{ strtoupper($d->aktivitas_mahasiswa->judul) }}<br>
                                @if(!empty($d->judul_eng))
                                    <em>({{ strtoupper($d->judul_eng) }})</em>
                                @endif     
                            </td>
                        </tr>
                    @endif

                    <tr class="{{$rowStyle}}">
                        @if($d->jenjang == 'D3' || $d->jenjang == 'Profesi')
                            <td width="50">PEMBIMBING TUGAS AKHIR<br><em>(FINAL PROJECT ADVISORS)</em></td>
                        @elseif($d->jenjang == 'S1')
                            <td width="50">PEMBIMBING SKRIPSI<br><em>(FINAL PROJECT ADVISORS)</em></td>
                        @elseif($d->jenjang == 'S2' || $d->jenjang == 'Sp-1')
                            <td width="50">PEMBIMBING TESIS<br><em>(THESIS ADVISORS)</em></td>
                        @elseif($d->jenjang == 'S3' || $d->jenjang == 'Sp-2')
                            <td width="50">PEMBIMBING DISERTASI<br><em>(DISERTATION ADVISORS)</em></td>
                        @endif
                        <td width="1">: </td>
                        <!-- <td width="1">: </td> -->
                        <td colspan="4">
                            <span style="display:block;">
                                @foreach($d->aktivitas_mahasiswa->bimbing_mahasiswa as $pembimbing)
                                    @php
                                        $gelar = $pembimbing->gelar;

                                        $gelarDepan = $gelar
                                            ? collect([
                                                $gelar->gelar_depan_s3,
                                                $gelar->gelar_depan_s2,
                                                $gelar->gelar_depan_s1,
                                            ])->filter()->implode(' ')
                                            : null;

                                        $gelarBelakang = $gelar
                                            ? collect([
                                                $gelar->gelar_belakang_s1,
                                                $gelar->gelar_belakang_s2,
                                                $gelar->gelar_belakang_s3,
                                            ])->filter()->implode(' ')
                                            : null;
                                    @endphp

                                    <div>
                                        {{ $pembimbing->pembimbing_ke }}.
                                        {{ trim(($gelarDepan ? $gelarDepan.' ' : '').$pembimbing->nama_dosen) }}@if($gelarBelakang), {{ $gelarBelakang }}@endif
                                    </div>
                                @endforeach
                            </span>
                        </td>

                    </tr>
                </table>

                {{-- ========================================================= --}}
                {{--                        TANDA TANGAN                       --}}
                {{-- ========================================================= --}}
                <table class="no-border ttd-table" width="100%" style="font-size: 7.5px; margin : 10px 0px 0px 0px">

                    {{-- Baris 1 --}}
                    <tr class="{{$rowStyle}}">
                        <td width="38%"></td>
                        <td width="24%"></td>
                        <td width="38%" style="padding-bottom:5px">
                            INDRALAYA, {{ idDate($d->periode_wisuda->first()->tanggal_wisuda)}} 
                            (<em>{!! enDate($d->periode_wisuda->first()->tanggal_wisuda) !!}</em>)
                        </td>
                    </tr>

                    {{-- Baris 2 --}}
                    <tr class="{{$rowStyle}}" style="padding:0px; margin:0px">
                        <td width="35%">
                            {{ strtoupper($wd1->jabatan) }}
                        </td width="35%">
                        <td></td>
                        
                        <td width="35%">
                            {{ strtoupper($wr1->jabatan) }}
                        </td>
                    </tr>

                    {{-- Baris 3 --}}
                    <tr class="{{$rowStyle}}">
                        <td width="35%">
                            @if($d->nama_fakultas == 'Sekolah Pascasarjana')
                                VICE DIRECTOR FOR ACADEMIC, RESEARCH, INNOVATION, DOWNSTREAM, COMMUNITY
                            @else
                                VICE DEAN OF ACADEMIC, STUDENT AND QUALITY ASSURANCE AFFAIRS
                            @endif
                        </td>
                        <td width="35%" rowspan="10" style="text-align:center; vertical-align:top;
                        /* padding-top:5px; */
                        ">
                            <img src="storage/{{ $d->pas_foto }}" alt="Foto" width="70">
                        </td>
                        <td width="35%">
                            VICE RECTOR FOR ACADEMIC, STUDENT AND QUALITY ASSURANCE AFFAIRS
                        </td>
                    </tr>

                    {{-- Baris 4 (kosong, karena foto merge di kolom tengah) --}}
                    <tr>
                        <td width="40%"></td>
                        <td width="40%"></td>
                    </tr>

                    <tr><td><br><br><br><br><br></td></tr>

                    {{-- Baris 5 --}}
                    <tr class="{{$rowStyle}}" style="margin:0px;">
                        <td width="40%">
                            {{ $wd1->gelar_depan }} {{ strtoupper($wd1->nama) }}{{ $wd1->gelar_belakang ? ', '.$wd1->gelar_belakang : '' }}
                        </td>
                        <td width="40%">
                            {{ $wr1->gelar_depan }} {{ strtoupper($wr1->nama) }}{{ $wr1->gelar_belakang ? ', '.$wr1->gelar_belakang : '' }}
                        </td>
                    </tr>

                    {{-- Baris 6 --}}
                    <tr class="{{$rowStyle}}" style="margin:0px;">
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
