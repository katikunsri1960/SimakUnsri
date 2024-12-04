@extends('layouts.doc-nologo')
@section('title')
Kartu Hasil Studi
@endsection
@section('content')
@include('swal')
<div style="text-transform: uppercase; font-family: Arial, Helvetica, sans-serif" style="margin-left: 2%">
    <div class="container-fluid" >
        <table style="width: 100%" class="table-pdf">
            <tr>
                <td class="text-judul3 text-center">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</td>
            </tr>
            <tr>
                <td class="text-judul3 text-center"><strong>UNIVERSITAS SRIWIJAYA</strong></td>
            </tr>
        </table>
    </div>
    <table class="text-10"
        style="width: 100%">
        <tr>
            <td height="20"></td>
        </tr>
        <tr>
            <td class="text-12 text-center text-upper" height="30" colspan="7">
                <strong>KARTU HASIL STUDI (KHS)</strong>
            </td>
        </tr>
        <tr>
            <td class="text-pdf text-8 text-upper" style="width: 12%">NIM</td>
            <td width="3"> : </td>
            <td class="text-pdf text-8 text-upper" style="width: 45%">{{$riwayat->nim}}</td>
            <td width="15"></td>
            <td class="text-pdf text-8 text-upper" style="width: 18%">FAKULTAS</td>
            <td width="3"> : </td>
            <td class="text-pdf text-8 text-upper" style="width: 30%">{{$riwayat->prodi->fakultas->nama_fakultas}}</td>
        </tr>
        <tr>
            <td class="text-pdf text-8 text-upper" style="width: 12%">NAMA </td>
            <td width="3"> : </td>
            <td class="text-pdf text-8 text-upper" style="width: 45%">{{$riwayat->nama_mahasiswa}}</td>
            <td width="15"></td>
            <td class="text-pdf text-8 text-upper" style="width: 18%">JURUSAN</td>
            <td width="3"> : </td>
            <td class="text-pdf text-8 text-upper" style="width: 30%">{{$riwayat->prodi->jurusan->nama_jurusan_id}}</td>
        </tr>
        <tr>
            <td class="text-pdf text-8 text-upper" style="width: 10%">NIP PA</td>
            <td width="3"> : </td>
            <td class="text-pdf text-8 text-upper" style="width: 45%">
                {{$riwayat->pembimbing_akademik ? $riwayat->pembimbing_akademik->nip : '-'}}
            </td>
            <td width="15"></td>
            <td class="text-pdf text-8 text-upper" style="width: 18%">PROGRAM STUDI</td>
            <td width="3"> : </td>
            <td class="text-pdf text-8 text-upper" style="width: 30%">{{$riwayat->prodi->nama_program_studi}}</td>
        </tr>
        <tr>
            <td class="text-pdf text-8 text-upper" style="width: 10%">DOSEN PA</td>
            <td width="3"> : </td>
            <td class="text-pdf text-8 text-upper" style="width: 45%">
                {{$riwayat->pembimbing_akademik ? $riwayat->pembimbing_akademik->nama_dosen : '-'}}
            </td>
            <td width="15"></td>
            <td class="text-pdf text-8 text-upper" style="width: 18%">SEMESTER</td>
            <td width="3"> : </td>
            <td class="text-pdf text-8 text-upper" style="width: 30%">{{$semester->nama_semester}}</td>
        </tr>
        <tr>
            <td height="10"></td>
        </tr>
    </table>
</div>
<div class="row">
    <div class="table-responsive">
        <table id="header" class="text-10" border="1" rules="all" style="width: 100%">
            <thead>
                <tr>
                    <th width="30" class="text-thead">NO.</th>
                    <th width="80" class="text-thead">KODE MK</th>
                    <th width="230" class="text-thead">NAMA MATA KULIAH</th>
                    <th width="40" class="text-thead">SKS</th>
                    <th width="40" class="text-thead">NILAI INDEKS</th>
                    <th width="40" class="text-thead">NILAI HURUF</th>
                </tr>
            </thead>

            @php
                $collections = [
                    ['data' => $khs, 'title' => null],
                    ['data' => $khs_transfer, 'title' => 'NILAI TRANSFER'],
                    ['data' => $khs_konversi, 'title' => 'NILAI KONVERSI']
                ];
            @endphp

            @foreach ($collections as $collection)
                @if($collection['data']->isNotEmpty())
                    @if($collection['title'])
                        <thead>
                            <tr style="background-color: #d7d7d7;"> <!-- Warna abu-abu muda -->
                                <th colspan="6" class="text-thead" style="border-top: none;">{{ $collection['title'] }}</th>
                            </tr>
                        </thead>
                    @endif
                    <tbody>
                        @foreach ($collection['data'] as $index => $d)
                            <tr>
                                <td width="30" class="text-td text-center">{{ $loop->iteration }}</td>
                                <td width="80" class="text-td text-center">
                                    @if(isset($d->kode_mata_kuliah))
                                        {{ $d->kode_mata_kuliah }}
                                    @elseif(isset($d->kode_matkul_diakui))
                                        {{ $d->kode_matkul_diakui }}
                                    @elseif($d->matkul)
                                        {{ $d->matkul->kode_mata_kuliah }}
                                    @endif
                                </td>
                                <td width="230" class="text-td text-left">
                                    {{ $d->nama_mata_kuliah ?? $d->nama_mata_kuliah_diakui ?? '' }}
                                </td>
                                <td width="40" class="text-td text-center">
                                    {{ $d->sks_mata_kuliah ?? $d->sks_mata_kuliah_diakui ?? '' }}
                                </td>
                                <td width="40" class="text-td text-center">
                                    {{ $d->nilai_indeks ?? $d->nilai_angka_diakui ?? '' }}
                                </td>
                                <td width="40" class="text-td text-center">
                                    {{ $d->nilai_huruf ?? $d->nilai_huruf_diakui ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            @endforeach

            <tfoot style="border-top: none;">
                <tr>
                    <td width="327" class="text-start" colspan="3" style="padding: 0.2rem 0.3rem; font-size:8pt;">
                        <strong>SKS Yang Ditempuh</strong>
                    </td>
                    <td width="120" class="text-thead" colspan="3"><strong>{{ $total_sks }}</strong></td>
                </tr>
                <tr>
                    <td width="327" class="text-start" colspan="3" style="padding: 0.2rem 0.3rem; font-size:8pt;">
                        <strong>Total Kredit Yang Telah Ditempuh</strong>
                    </td>
                    <td width="120" class="text-thead" colspan="3"><strong>{{ $akm->sks_total }}</strong></td>
                </tr>
                <tr>
                    <td width="327" class="text-start" colspan="3" style="padding: 0.2rem 0.3rem; font-size:8pt;">
                        <strong>Indeks Prestasi Semester</strong>
                    </td>
                    <td width="120" class="text-thead" colspan="3"><strong>{{ $ips }}</strong></td>
                </tr>
                <tr>
                    <td width="327" class="text-start" colspan="3" style="padding: 0.2rem 0.3rem; font-size:8pt;">
                        <strong>Indeks Prestasi Kumulatif</strong>
                    </td>
                    <td width="120" class="text-thead" colspan="3"><strong>{{ $akm->ipk }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
{{-- <div class="row">
    <div class="table-responsive">
        <table id="header" class="text-10" border="1" rules="all" style="width: 100%">
            <thead>
                <tr>
                    <th width="30" class="text-thead">NO.</th>
                    <th width="80" class="text-thead">KODE MK</th>
                    <th width="230" class="text-thead">NAMA MATA KULIAH</th>
                    <th width="40" class="text-thead">SKS</th>
                    <th width="40" class="text-thead">NILAI INDEKS</th>
                    <th width="40" class="text-thead">NILAI HURUF</th>
                </tr>
            </thead>
        
            @if($khs->isNotEmpty())
                <tbody>
                    @foreach ($khs as $d)
                        <tr>
                            <td width="30" class="text-td text-center">{{$loop->iteration}}</td>
                            <td width="80" class="text-td text-center">{{$d->kode_mata_kuliah}}</td>
                            <td width="230" class="text-td text-left ">{{$d->nama_mata_kuliah}}</td>
                            <td width="40" class="text-td text-center">{{$d->sks_mata_kuliah}}</td>
                            <td width="40" class="text-td text-center">{{$d->nilai_indeks}}</td>
                            <td width="40" class="text-td text-center">{{$d->nilai_huruf}}</td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
            @if($khs_transfer->isNotEmpty())
            <thead>
                <tr>
                    <th colspan="6" class="text-thead" style="border-top: none;" >NILAI TRANSFER</th>
                </tr>
            </thead>            
            <tbody>
                @foreach ($khs_transfer as $d)
                    <tr>
                        <td width="30" class="text-td text-center">{{$loop->iteration}}</td>
                        <td width="80" class="text-td text-center">{{$d->kode_matkul_diakui}}</td>
                        <td width="230" class="text-td text-left">{{$d->nama_mata_kuliah_diakui}}</td>
                        <td width="40" class="text-td text-center">{{$d->sks_mata_kuliah_diakui}}</td>
                        <td width="40" class="text-td text-center">{{$d->nilai_angka_diakui}}</td>
                        <td width="40" class="text-td text-center">{{$d->nilai_huruf_diakui}}</td>
                    </tr>
                @endforeach
            </tbody>
            @endif
            @if($khs_konversi->isNotEmpty())
            <thead>
                <tr>
                    <th colspan="6" class="text-thead" style="border-top: none;" >NILAI KONVERSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($khs_konversi as $d)
                    <tr>
                        <td width="30" class="text-td text-center">{{$loop->iteration}}</td>
                        <td width="80" class="text-td text-center">
                            @if($d->matkul)
                                {{$d->matkul->kode_mata_kuliah}}
                            @endif
                        </td>
                        <td width="230" class="text-td text-left ">{{$d->nama_mata_kuliah}}</td>
                        <td width="40" class="text-td text-center">{{$d->sks_mata_kuliah}}</td>
                        <td width="40" class="text-td text-center">{{$d->nilai_indeks}}</td>
                        <td width="40" class="text-td text-center">{{$d->nilai_huruf}}</td>
                    </tr>
                @endforeach
            </tbody>
            @endif
            <tfoot style="border-top: none;">
                <tr>
                    <td width="327" class="text-start" colspan="3" style="padding: 0.2rem 0.3rem 0.3rem 0.3rem; font-size:8pt"><strong>SKS Yang Ditempuh</strong></td>
                    <td width="120" class="text-thead" colspan="3"><strong>{{$total_sks}}</strong></td>
                </tr>
                <tr>
                    <td width="327" class="text-start" colspan="3" style="padding: 0.2rem 0.3rem 0.3rem 0.3rem; font-size:8pt"><strong>Total Kredit Yang Telah Ditempuh</strong></td>
                    <td width="120" class="text-thead" colspan="3"><strong>{{$akm->sks_total}}</strong></td>
                </tr>
                <tr>
                    <td width="327" class="text-start" colspan="3" style="padding: 0.2rem 0.3rem 0.3rem 0.3rem; font-size:8pt"><strong>Indeks Prestasi Semester</strong></td>
                    <td width="120" class="text-thead" colspan="3"><strong>{{$ips}}</strong></td>
                </tr>
                <tr>
                    <td width="327" class="text-start" colspan="3" style="padding: 0.2rem 0.3rem 0.3rem 0.3rem; font-size:8pt"><strong>Indeks Prestasi Kumulatif</strong></td>
                    <td width="120" class="text-thead" colspan="3"><strong>{{$akm->ipk}}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div> --}}
<table style="width: 100%">
    <tbody>
        <tr>
            <td height="20"></td>
        </tr>
        <tr width="100%">
            <td width="60%"></td>
            <td width="40%" class="text-start text-10" >
                    Inderalaya, {{ $today->locale('id')->translatedFormat('d F Y')}}
            </td>
        </tr>
        <tr>
            <td class="text-left text-10" width="60%">
                {{-- Catatan: --}}
            </td>
            <td width="40%" class="text-start text-10" >a.n Dekan</td>
        </tr>
        <tr>
            <td class="text-left text-10" width="60%">
                {{-- Catatan: --}}
            </td>
            <td width="40%" class="text-start text-10" >Wakil Dekan Bidang Akademik,</td>
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
                {{ $wd1 === NULL ? 'Tidak Diisi' : $wd1->gelar_depan . ' ' . ucwords(strtolower($wd1->nama_dosen)) . ', ' . $wd1->gelar_belakang }}
            </td>
        </tr>
        <tr>
            <td class="text-left text-10" width="60%" style="font-style: italic; ">
                {{-- Lembar untuk mahasiswa --}}
            </td>
            <td width="40%" class="text-start text-10" >
                NIP. {{ $wd1 === NULL ? 'Tidak Diisi' : $wd1->dosen->nip}}
            </td>
        </tr>
    </tbody>
</table>
{{-- </div> --}}
@endsection
