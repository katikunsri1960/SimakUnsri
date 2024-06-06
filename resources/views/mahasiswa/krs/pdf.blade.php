@extends('layouts.doc-nologo')
@section('title')
Kartu Rencana Studi
@endsection
@section('content')
<div style="text-transform: uppercase; font-family: Arial, Helvetica, sans-serif">
    <div class="container-fluid table-pdf" >
        <left>
            <label class="text-judul3">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET DAN TEKNOLOGI</label><br>
            <label class="text-judul3"><strong>UNIVERSITAS SRIWIJAYA</strong></label>
        </left>
    </div>
    <div>
        <table>
            <tr>
                <td height="20"></td>
            </tr>
        </table>
        <center>
            <p class="text-judul1">KARTU STUDI MAHASISWA (KSM)</p>
        </center>
    </div>
    <table class="text-10">
        <tr>
            <td class="text-pdf" style="width: 20%">NIM</td>
            <td class="text-pdf" style="width: 30%"> : {{$nim}}</td>
            <td width="70"></td>
            <td class="text-pdf" style="width: 20%">FAKULTAS</td>
            <td class="text-pdf" style="width: 30%"> : {{$prodi->fakultas->nama_fakultas}}</td>
        </tr>
        <tr>
            <td class="text-pdf" style="width: 20%">NAMA MAHASISWA</td>
            <td class="text-pdf" style="width: 30%"> : {{$nama_mhs}}</td>
            <td width="70"></td>
            <td class="text-pdf" style="width: 20%">JURUSAN</td>
            <td class="text-pdf" style="width: 30%"> : {{$prodi->jurusan->nama_jurusan_id}}</td>
        </tr>
        <tr>
            <td class="text-pdf" style="width: 20%">NIP PA</td>
            <td class="text-pdf" style="width: 30%"> : {{$dosen_pa->nip}}</td>
            <td width="70"></td>
            <td class="text-pdf" style="width: 20%">PROGRAM STUDI</td>
            <td class="text-pdf" style="width: 30%"> : {{$prodi->nama_jenjang_pendidikan}} - {{$prodi->nama_program_studi}}</td>
        </tr>
        <tr>
            <td class="text-pdf" style="width: 20%">DOSEN PA</td>
            <td class="text-pdf" style="width: 30%"> : {{$dosen_pa->nama_dosen}}</td>
            <td width="70"></td>
            <td class="text-pdf" style="width: 20%">SEMESTER</td>
            <td class="text-pdf" style="width: 30%"> : {{$nama_smt}}</td>
        </tr>
        <tr>
            <td height="20"></td>
        </tr>
    </table>
    @php
        $today = \Carbon\Carbon::now();
        $deadline = \Carbon\Carbon::parse($semester_aktif->krs_selesai);
    @endphp
    @if($data_status_mahasiswa == "A" || $data_status_mahasiswa == "M" )
        <div class="row">
            <center>
                <p class="text-judul2 mb-20">Kartu Rencana Studi Reguler</p>

            </center>
        </div>
        <div class="row">
            <div class="table-responsive">
                <table id="krs-regular" class="text-10" border="1" rules="all" style="width: 95%">
                    <thead>
                        <tr>
                            <th width="30" class="text-center align-middle">No.</th>
                            <th width="150" class="text-center align-middle">Kode MK</th>
                            <th width="250" class="text-center align-middle">Nama Mata Kuliah</th>
                            <th width="50" class="text-center align-middle">SKS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no=1;
                        @endphp

                        @foreach ($krs_regular as $data)
                            <tr>
                                <td><center>{{ $no++ }}.</center></td>
                                <td><center>{{$data->kode_mata_kuliah}}</center></td>
                                <td>{{$data->nama_mata_kuliah}}</td>
                                <td><center>{{$data->sks_mata_kuliah}}</center></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><center><strong>JUMLAH</strong></center></td>
                            <td><center><strong>{{$total_sks_regular}}</strong></center></td>
                        </tr> 
                    </tfoot>
                </table>
            </div>
        </div>
        @if ($data_status_mahasiswa == "M" )
            <div class="row">
                <center>
                    <p class="text-judul2 mb-20">Kartu Rencana Studi Kampus Merdeka</p>

                </center>
            </div>
            <div class="row">
                <div class="table-responsive">
                    <table id="krs-merdeka" class="text-10" border="1" rules="all" style="width: 95%">
                        <thead>
                            <tr>
                                <th width="30" class="text-center align-middle">No.</th>
                                <th width="150" class="text-center align-middle">Kode MK</th>
                                <th width="250" class="text-center align-middle">Nama Mata Kuliah</th>
                                <th width="50" class="text-center align-middle">SKS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp

                            @foreach ($krs_merdeka as $data)
                                <tr>
                                    <td><center>{{ $no++ }}.</center></td>
                                    <td><center>{{$data->kode_mata_kuliah}}</center></td>
                                    <td>{{$data->nama_mata_kuliah}}</td>
                                    <td><center>{{$data->sks_mata_kuliah}}</center></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><center><strong>JUMLAH</strong></center></td>
                                <td><center><strong>{{$total_sks_merdeka}}</strong></center></td>
                            </tr> 
                        </tfoot>
                    </table>
                </div>
            </div>
        @endif
        <br>
        <div class="row">
            <center>
                <p class="text-judul2 mb-20">Kartu Rencana Aktivitas Mahasiswa</p>

            </center>
        </div>
        <div class="row">
            <div class="table-responsive">
                <table id="krs-akt" class="text-10" border="1" rules="all" style="width: 95%">
                    <thead>
                        <tr>
                            <th width="30" class="text-center align-middle">No.</th>
                            <th width="150" class="text-center align-middle">Jenis Aktivitas</th>
                            <th width="250" class="text-center align-middle">Dosen Pembimbing</th>
                            <th width="50" class="text-center align-middle">SKS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no=1;
                        @endphp

                        @foreach ($krs_akt as $data)
                            <tr>
                                <td><center>{{ $no++ }}.</center></td>
                                <td><center>{{ $data->nama_jenis_aktivitas }}</center></td>
                                <td>
                                    <center>
                                        @foreach($data->aktivitas_mahasiswa->bimbing_mahasiswa as $dosen_bimbing)
                                            <ul class="my-0">
                                                <li class="my-0">
                                                    {{$dosen_bimbing->nama_dosen}}
                                                </li>
                                            </ul> 
                                        @endforeach
                                    </center>
                                </td>
                                <td><center>{{ $data->aktivitas_mahasiswa->konversi->sks_mata_kuliah }}</center></td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><center><strong>JUMLAH</strong></center></td>
                            <td><center><strong>{{$total_sks_akt}}</strong></center></td>
                        </tr> 
                    </tfoot>
                </table>
            </div>
        </div>
    @endif
</div>
<table border="1" rules="all">
    <tbody>
        <tr>
            <td height="20"></td>
        </tr>
        <tr>
            <td width="500" style="text-align: end; float:right;" >
                Inderalaya, {{$today->isoFormat('DD MMMM Y')}}
            </td>
        </tr>
        <tr class="text-end">
            <td>
                <right>
                    Pembimbing Akademik,
                </right>                
            </td>
        </tr>
        <tr>
            <td height="60"></td>
        </tr>
        <tr class="text-end">
            <td>
                {{$dosen_pa->nama_dosen}}
            </td>
        </tr>
        <tr>
            <td>
                NIP. {{$dosen_pa->nip}}
            </td>
        </tr>
    </tbody>
</table>
@endsection