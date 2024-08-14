@extends('layouts.doc-nologo')
@section('title')
Kartu Rencana Studi
@endsection
@section('content')
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
    {{-- border="1" rules="all"  --}}
        style="width: 100%">
        <tr>
            <td height="20"></td>
        </tr>
        <tr>
            <td class="text-12 text-center text-upper" height="30" colspan="7">
                <strong>KARTU RENCANA STUDI (KRS)</strong>
            </td>
        </tr>
        <tr>
            <td class="text-pdf text-8 text-upper" style="width: 12%">NIM</td>
            <td width="3"> : </td>
            <td class="text-pdf text-8 text-upper" style="width: 45%">{{$nim}}</td>
            <td width="15"></td>
            <td class="text-pdf text-8 text-upper" style="width: 18%">FAKULTAS</td>
            <td width="3"> : </td>
            <td class="text-pdf text-8 text-upper" style="width: 30%">{{$fakultas_pdf}}</td>
        </tr>
        <tr>
            <td class="text-pdf text-8 text-upper" style="width: 12%">NAMA </td>
            <td width="3"> : </td>
            <td class="text-pdf text-8 text-upper" style="width: 45%">{{$nama_mhs}}</td>
            <td width="15"></td>
            <td class="text-pdf text-8 text-upper" style="width: 18%">JURUSAN</td>
            <td width="3"> : </td>
            <td class="text-pdf text-8 text-upper" style="width: 30%">{{$prodi->jurusan->nama_jurusan_id}}</td>
        </tr>
        <tr>
            <td class="text-pdf text-8 text-upper" style="width: 10%">NIP PA</td>
            <td width="3"> : </td>
            <td class="text-pdf text-8 text-upper" style="width: 45%">
                {{ $dosen_pa === NULL ? 'Tidak Diisi' : $dosen_pa->nip }}
            </td>
            <td width="15"></td>
            <td class="text-pdf text-8 text-upper" style="width: 18%">PROGRAM STUDI</td>
            <td width="3"> : </td>
            <td class="text-pdf text-8 text-upper" style="width: 30%">{{$prodi->nama_jenjang_pendidikan}} - {{$prodi->nama_program_studi}}</td>
        </tr>
        <tr>
            <td class="text-pdf text-8 text-upper" style="width: 10%">DOSEN PA</td>
            <td width="3"> : </td>
            <td class="text-pdf text-8 text-upper" style="width: 45%">
                {{ $dosen_pa === NULL ? 'Tidak Diisi' : $dosen_pa->nama_dosen }}
            </td>
            <td width="15"></td>
            <td class="text-pdf text-8 text-upper" style="width: 18%">SEMESTER</td>
            <td width="3"> : </td>
            <td class="text-pdf text-8 text-upper" style="width: 30%">{{$nama_smt}}</td>
        </tr>
        <tr>
            <td height="10"></td>
        </tr>
    </table>
    @if($krs_regular->isNotEmpty())
        <table style="width: 100%">
            <tr>
                <td class="text-upper text-center text-12" height="30" colspan="4">
                    <strong>Rencana Studi Reguler</strong> 
                </td>
            </tr>
        </table>
        <div class="row">
            <div class="table-responsive">
                <table id="krs-regular" class="text-10" border="1" rules="all" style="width: 100%">
                    <thead>
                        
                        <tr>
                            <th width="30" class="text-thead">NO.</th>
                            <th width="150" class="text-thead">KODE MK</th>
                            <th width="250" class="text-thead">NAMA MATA KULIAH</th>
                            <th width="50" class="text-thead">SKS (K)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no=1;
                        @endphp

                        @foreach ($krs_regular as $data)
                            <tr>
                                <td class="text-td text-center">{{ $no++ }}.</td>
                                <td class="text-td text-center">{{$data->kode_mata_kuliah}}</td>
                                <td class="text-td text-left ">{{$data->nama_mata_kuliah}}</td>
                                <td class="text-td text-center">{{$data->sks_mata_kuliah}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-thead" colspan="3"><strong>JUMLAH</strong></td>
                            <td class="text-thead"><strong>{{$total_sks_regular}}</strong></td>
                        </tr> 
                    </tfoot>
                </table>
            </div>
        </div>
    @endif
    @if($krs_merdeka->isNotEmpty())
        <br>    
        <table style="width: 100%">
            <tr>
                <td class="text-upper text-center text-12" height="30" colspan="4">
                    <strong>Rencana Studi Kampus Merdeka</strong> 
                </td>
            </tr>
        </table>
        <div class="row">
            <div class="table-responsive">
                <table id="krs-merdeka" class="text-10" border="1" rules="all" style="width: 100%;" >
                    <thead>
                        
                        <tr>
                            <th width="30" class="text-thead">NO.</th>
                            <th width="150" class="text-thead">KODE MK</th>
                            <th width="250" class="text-thead">NAMA MATA KULIAH</th>
                            <th width="50" class="text-thead">SKS (K)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no=1;
                        @endphp

                        @foreach ($krs_merdeka as $data)
                            <tr>
                                <td class="text-td text-center">{{ $no++ }}.</td>
                                <td class="text-td text-center">{{$data->kode_mata_kuliah}}</td>
                                <td class="text-td text-left">{{$data->nama_mata_kuliah}}</td>
                                <td class="text-td text-center">{{$data->sks_mata_kuliah}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-thead" colspan="3"><strong>JUMLAH</strong></td>
                            <td class="text-thead"><strong>{{$total_sks_regular}}</strong></td>
                        </tr> 
                    </tfoot>
                </table>
            </div>
        </div>
    @endif
    @if($krs_akt->isNotEmpty())
        <br>
        <table style="width: 100%">
            <tr>
                <td class="text-upper text-center text-12" height="30" colspan="4">
                    <strong>Rencana Aktivitas Reguler Mahasiswa</strong> 
                </td>
            </tr>
        </table>
        <div class="row" >
            <div class="table-responsive">
                <table id="krs-akt" class="text-10" border="1" rules="all" style="width: 100%">
                    <thead>
                        
                        <tr>
                            <th width="30" class="text-thead">No.</th>
                            <th width="150" class="text-thead">Jenis Aktivitas</th>
                            <th width="250" class="text-thead">Dosen Pembimbing</th>
                            <th width="50" class="text-thead">SKS (K)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no=1;
                        @endphp

                        @foreach ($krs_akt as $data)
                            <tr>
                                <td  class="text-td text-center">{{ $no++ }}.</td>
                                <td  class="text-td text-center">{{ $data->nama_jenis_aktivitas }}</td>
                                <td  class="text-td">
                                    @foreach($data->bimbing_mahasiswa as $dosen_bimbing)
                                        <ul class="my-0">
                                            <li class="my-0">
                                                {{$dosen_bimbing->nama_dosen}}
                                            </li>
                                        </ul> 
                                    @endforeach
                                </td>
                                <td class="text-center align-middle" style="width:10%">
                                    <div>
                                        {{ $data->konversi= NULL ? '-' :  $data->konversi->sks_mata_kuliah }}
                                    </div>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-thead" colspan="3"><strong>JUMLAH</strong></td>
                            <td class="text-thead"><strong>{{$total_sks_akt}}</strong></td>
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
                        Inderalaya, {{ $tanggal_approve}}
                </td>
            </tr>
            <tr>
                <td class="text-left text-10" width="60%">
                    {{-- Catatan: --}}
                </td>
                <td width="50%" class="text-right text-10" >Pembimbing Akademik,</td>
            </tr>
            <tr>
                <td class="text-left text-10" width="60%" style="vertical-align: text-top">
                    {{-- KSM harus dibawa pada saat mengikuti ujian akhir semester --}}
                </td>
                <td height="60" width="50%" class="text-right text-10 mx-50"><strong>dto<strong></td>
            </tr>
            <tr>
                <td width="50%"></td>
                <td width="50%" class="text-right text-10" >
                    {{ $dosen_pa === NULL ? 'Tidak Diisi' : $dosen_pa->nama_dosen}}
                </td>
            </tr>
            <tr>
                <td class="text-left text-10" width="60%" style="font-style: italic; ">
                    {{-- Lembar untuk mahasiswa --}}
                </td>
                <td width="50%" class="text-right text-10" >
                    NIP. {{ $dosen_pa === NULL ? 'Tidak Diisi' : $dosen_pa->nip}}
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection