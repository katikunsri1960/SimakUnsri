@extends('layouts.doc-nologo')
@section('content')
@push('header')
@if (file_exists(public_path('images/unsri.png')))
<img src="{{ public_path('images/unsri.png') }}" alt="Logo" style="width: 130px">
@endif
<div class="header-div" style="text-align: center; margin-top: 10px; font-size: 16pt">
    <span style="margin-top: 10px; margin-bottom:0; padding: 0; ">KEMENTERIAN PENDIDIKAN TINGGI,<br>SAINS, DAN TEKNOLOGI<br><strong>UNIVERSITAS SRIWIJAYA</strong></span>
    <p style="font-size:12px">Jl. Palembang Prabumulih Km. 32 Inderalaya (OI) Kode Pos 30662</p>
    <p style="font-size:12px">Tlp. (0711) 5800645, 580069, 580169, 580275 Fax. (0711) 580664</p>
    <p style="font-size:12px">Laman www.unsri.ac.id</p>
</div>
<hr style="margin-bottom: 0; margin-top: 10px">
@endpush
<div class="container-fluid">
    <center>
        <strong><u>DAFTAR PRESTASI AKADEMIK</u></strong><br>
        <div style="font-size: 10pt">Nomor : <span style="color: transparent">0073/UN9/SB3.BAK.Ak/{{date('Y')}}</span></div>
    </center>
</div>
<br>
<div class="container-fluid table-responsive ml-3">
    <div class="row mt-3" style="align-content: center">
        <table class="table" style="width:100%" class="mb-3" style="font-size: 11pt">
            <tr>
                <td class="text-start align-middle" style="width: 12%">Nama</td>
                <td style="padding-left: 16px">:</td>
                <td class="text-start" id="namaKrs" style="width: 45%; padding-left: 10px; font-wight: bold">{{Str::title($riwayat->nama_mahasiswa)}}</td>
            </tr>
            <tr>
                <td class="text-start align-middle" style="width: 12%">Tempat, Tanggal Lahir</td>
                <td style="padding-left: 16px">:</td>
                <td class="text-start" id="nimKrs" style="width: 45%; padding-left: 10px">
                    {{$riwayat->biodata ? Str::title($riwayat->biodata->tempat_lahir).', '.\Carbon\Carbon::createFromFormat('d-m-Y', $riwayat->biodata->tanggal_lahir)->translatedFormat('d F Y') : ''}}
                </td>
            </tr>
            <tr>
                <td class="text-start align-middle" style="width: 12%">NIM</td>
                <td style="padding-left: 16px">:</td>
                <td class="text-start" id="nimKrs" style="width: 45%; padding-left: 10px">{{$riwayat->nim}}</td>
            </tr>
            <tr>
                <td class="text-start align-middle" style="width: 12%">Program Studi</td>
                <td style="padding-left: 16px">:</td>
                <td class="text-start" id="namaKrs" style="width: 45%; padding-left: 10px; font-wight: bold">{{Str::title($riwayat->prodi->nama_program_studi)}}</td>
            </tr>
            <tr>
                <td class="text-start align-middle" style="width: 12%">Tahun Masuk</td>
                <td style="padding-left: 16px">:</td>
                <td class="text-start" id="namaKrs" style="width: 45%; padding-left: 10px; font-wight: bold">{{\Carbon\Carbon::parse($riwayat->tanggal_daftar)->format('Y')}}</td>
            </tr>
        </table>
    </div>
    <div class="row" style="margin-top: 15px">
        <table class="table-pdf" id="krs-regular" border="1" rules="all" style="font-size: 8pt; width: 100%; font-family: 'Times New Roman', Times, serif">
            <thead>
                <tr>
                    <th class="text-center align-middle table-pdf">NO.</th>
                    <th class="text-center align-middle table-pdf">KODE</th>
                    <th class="text-center align-middle table-pdf">MATA KULIAH</th>
                    <th class="text-center align-middle table-pdf">HURUF<br>MUTU</th>
                    <th class="text-center align-middle table-pdf">ANGKA<br>MUTU</th>
                    <th class="text-center align-middle table-pdf">KREDIT</th>
                    <th class="text-center align-middle table-pdf">MUTU</th>
                </tr>
            </thead>
            @php
                $mutu = 0;
            @endphp
            <tbody>
                @foreach ($transkrip as $d)
                <tr>
                    <td class="text-center align-middle table-pdf">{{$loop->iteration}}</td>
                    <td class="text-start align-middle table-pdf">{{$d->kode_mata_kuliah}}</td>
                    <td class="text-start align-middle table-pdf">{{$d->nama_mata_kuliah}} {{$d->nama_mata_kuliah_english ? '('.$d->nama_mata_kuliah_english.')' : ''}}</td>
                    <td class="text-center align-middle table-pdf">{{$d->nilai_huruf}}</td>
                    <td class="text-center align-middle table-pdf">{{$d->nilai_indeks}}</td>
                    <td class="text-center align-middle table-pdf">{{$d->sks_mata_kuliah}}</td>
                    <td class="text-center align-middle table-pdf">
                        {{$d->sks_mata_kuliah*$d->nilai_indeks}}
                        @php
                            $mutu += $d->sks_mata_kuliah*$d->nilai_indeks;
                        @endphp
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-center align-middle table-pdf">TOTAL</th>
                    <th class="text-center align-middle table-pdf"></th>
                    <th class="text-center align-middle table-pdf"></th>
                    <th class="text-center align-middle table-pdf">
                        {{$transkrip->sum('sks_mata_kuliah')}}
                    </th>
                    <th class="text-center align-middle table-pdf">{{$mutu}}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="">
        <table style="font-size: 10pt; margin-top: 15px">
            <tr>
                <td>INDEKS PRESTASI KUMULATIF (IPK)</td>
                <td style="padding-left: 20px; padding-right:20px">:</td>
                <td>{{$mutu}}/{{$transkrip->sum('sks_mata_kuliah')}} = {{number_format($mutu/$transkrip->sum('sks_mata_kuliah'), 2, ',','.')}} ({{$ipk_terbilang}})</td>
            </tr>
        </table>
    </div>
    <div style="page-break-inside: avoid;">
        <table style="width: 100%" class="no-break">
            <tbody>
                <tr>
                    <td height="20"></td>
                </tr>
                <tr width="100%">
                    <td width="60%"></td>
                    <td width="40%" class="text-start text-10">
                        Inderalaya, {{ $today->locale('id')->translatedFormat('d F Y')}}
                    </td>
                </tr>
                <tr>
                    <td class="text-left text-10" width="60%">
                        {{-- Catatan: --}}
                    </td>
                    <td width="40%" class="text-start text-10">
                        a.n Rektor,
                    </td>
                </tr>
                <tr>
                    <td class="text-left text-10" width="60%">
                        {{-- Catatan: --}}
                    </td>
                    <td width="40%" class="text-start text-10">
                        Wakil Rektor Bidang Akademik
                    </td>
                </tr>
                <tr>
                    <td class="text-left text-10" width="60%" style="vertical-align: text-top">
                        {{-- KSM harus dibawa pada saat mengikuti ujian akhir semester --}}
                    </td>
                    <td height="60" width="40%" class="text-right text-10 mx-50"><strong><strong></td>
                </tr>
                <tr>
                    <td width="60%"></td>
                    <td width="40%" class="text-start text-10">
                        {{ $pejabat === NULL ? 'Belum Diisi' : $pejabat->gelar_depan . ' ' . ucwords(strtolower($pejabat->nama_dosen)) . ', ' . $pejabat->gelar_belakang }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left text-10" width="60%" style="font-style: italic;">
                        {{-- Lembar untuk mahasiswa --}}
                    </td>
                    <td width="40%" class="text-start text-10">
                        NIP. {{ $pejabat === NULL ? 'Belum Diisi' : $pejabat->nip}}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
