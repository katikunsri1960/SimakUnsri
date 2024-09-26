@extends('layouts.doc-nologo')
@section('title')
Kartu Hasil Studi
@endsection
@section('content')
@include('swal')
<div style="text-transform: uppercase; font-family: Arial, Helvetica, sans-serif" style="margin-left: 2%">
    <div class="container-fluid" >
        <table style="width: 70%" class="table-pdf">
            <tr>
                <td class="text-judul3">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET DAN TEKNOLOGI</td>
            </tr>
            <tr>
                <td class="text-judul3"><strong>UNIVERSITAS SRIWIJAYA</strong></td>
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
@if($khs->isNotEmpty())
<div class="row">
    <div class="table-responsive">
        <table id="krs-regular" class="text-10" border="1" rules="all" style="width: 100%">
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
            <tbody>
                @foreach ($khs as $d)
                    <tr>
                        <td class="text-td text-center">{{$loop->iteration}}</td>
                        <td class="text-td text-center">{{$d->kode_mata_kuliah}}</td>
                        <td class="text-td text-left ">{{$d->nama_mata_kuliah}}</td>
                        <td class="text-td text-center">{{$d->sks_mata_kuliah}}</td>
                        <td class="text-td text-center">{{$d->nilai_indeks}}</td>
                        <td class="text-td text-center">{{$d->nilai_huruf}}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-thead" colspan="3"><strong>TOTAL SKS</strong></td>
                    <td class="text-thead"><strong>{{$total_sks}}</strong></td>
                    <td class="text-thead" colspan="2"><strong></strong></td>
                </tr> 
            </tfoot>
        </table>
    </div>
</div>
@endif
<table style="width: 100%">
    <tbody>
        <tr>
            <td height="20"></td>
        </tr>
        <tr width="100%">
            <td width="60%"></td>
            <td width="50%" class="text-right text-10" >
                    Inderalaya, {{ $today->locale('id')->translatedFormat('d F Y')}}
            </td>
        </tr>
        <tr>
            <td class="text-left text-10" width="60%">
                {{-- Catatan: --}}
            </td>
            <td width="50%" class="text-right text-10" >a.n Dekan</td>
        </tr>
        <tr>
            <td class="text-left text-10" width="60%">
                {{-- Catatan: --}}
            </td>
            <td width="50%" class="text-right text-10" >Wakil Dekan Bidang Akademik,</td>
        </tr>
        <tr>
            <td class="text-left text-10" width="60%" style="vertical-align: text-top">
                {{-- KSM harus dibawa pada saat mengikuti ujian akhir semester --}}
            </td>
            <td height="60" width="50%" class="text-right text-10 mx-50"><strong><strong></td>
        </tr>
        <tr>
            <td width="50%"></td>
            <td width="60%" class="text-right text-10">
                {{ $wd1 === NULL ? 'Tidak Diisi' : $wd1->gelar_depan . ' ' . ucwords(strtolower($wd1->nama_dosen)) . ', ' . $wd1->gelar_belakang }}
            </td>                
        </tr>
        <tr>
            <td class="text-left text-10" width="60%" style="font-style: italic; ">
                {{-- Lembar untuk mahasiswa --}}
            </td>
            <td width="60%" class="text-right text-10" >
                NIP. {{ $wd1 === NULL ? 'Tidak Diisi' : $wd1->dosen->nip}}
            </td>
        </tr>
    </tbody>
</table>
{{-- </div> --}}
@endsection
