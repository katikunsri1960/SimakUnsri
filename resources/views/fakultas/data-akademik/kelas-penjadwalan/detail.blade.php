@extends('layouts.fakultas')
@section('title')
Kelas Penjadwalan
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Detail Kelas dan Penjadwalan</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('fakultas')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Akademik</li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('fakultas.data-akademik.kelas-penjadwalan')}}">Kelas dan Penjadwalan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Kelas dan Penjadwalan</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
                    <div class="row mb-5">
                        <div class="col-lg-6 mb-10">
                            <div class="d-flex">
                                <a type="button" class="btn btn-warning waves-effect waves-light" href="{{route('fakultas.data-akademik.kelas-penjadwalan')}}"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                        {{-- <div class="col-lg-6 mb-10">
                            <div class="d-flex justify-content-end">
                                <a type="button" class="btn btn-success waves-effect waves-light" href="{{route('fakultas.data-akademik.kelas-penjadwalan.tambah',['id_matkul' => $id_matkul])}}"><i class="fa fa-plus"></i> Tambah Kelas Kuliah</a>
                            </div>
                        </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex">
                                <h4>{{$matkul->kode_mata_kuliah}} - {{$matkul->nama_mata_kuliah}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100" style="font-size: 12px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">NAMA KELAS</th>
                                    <th class="text-center align-middle">NAMA RUANG</th>
                                    <th class="text-center align-middle">PERIODE</th>
                                    <th class="text-center align-middle">JADWAL HARI</th>
                                    <th class="text-center align-middle">JAM KULIAH</th>
                                    <th class="text-center align-middle">DOSEN</th>
                                    <th class="text-center align-middle">KAPASITAS</th>
                                    <th class="text-center align-middle">JUMLAH PESERTA</th>
                                    <th class="text-center align-middle">TANGGAL UJIAN</th>
                                    <th class="text-center align-middle">WAKTU UJIAN</th>
                                    <th class="text-center align-middle">LOKASI UJIAN</th>
                                    <th class="text-center align-middle">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $row=0;
                                @endphp
                                @foreach($data as $index => $d)
                                    <tr>
                                        <td class="text-center align-middle">{{$row = $row + 1}}</td>
                                        <td class="text-center align-middle">
                                            <a class="btn btn-sm btn-rounded btn-success-light" href="{{route('fakultas.data-akademik.kelas-penjadwalan.peserta', ['id_maktul' =>  $matkul->id, 'id_kelas' => $d->id])}}"> 
                                                <i class="fa fa-search"></i>{{$d->nama_kelas_kuliah}}
                                            </a>
                                        </td>
                                        <td class="text-center align-middle">
                                            @if ($d->ruang_perkuliahan)
                                            {{$d->ruang_perkuliahan->nama_ruang}}<br>({{$d->ruang_perkuliahan->lokasi}})
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">{{$d->semester->nama_semester}}</td>
                                        <td class="text-center align-middle">{{$d->jadwal_hari}}</td>
                                        <td class="text-center align-middle">{{$d->jadwal_jam_mulai}} - {{$d->jadwal_jam_selesai}}</td>
                                        <td class="text-start align-middle">
                                            <ul>
                                            @if ($d->dosen_pengajar)
                                                @foreach ($d->dosen_pengajar as $i)
                                                    <li>{{$i->dosen->nama_dosen}}</li>
                                                @endforeach
                                            @endif
                                            </ul>
                                        </td>
                                        <td class="text-center align-middle">{{$d->kapasitas}}</td>
                                        <td class="text-center align-middle">
                                            @php
                                                echo count($d->peserta_kelas);
                                            @endphp
                                        </td>
                                        <td class="text-center align-middle">{{$hari_ujian[$index]}}, {{$tgl_ujian[$index]}}</td>
                                        <td class="text-center align-middle">{{$jam_mulai_ujian[$index]}} - {{$jam_selesai_ujian[$index]}} WIB</td>
                                        <td class="text-center align-middle">{{$d->ruang_ujian->nama_ruang}} ({{$d->ruang_ujian->lokasi}})</td>
                                        <td class="text-center align-middle">
                                            <div class="row my-3 px-3">
                                                <a href="{{route('fakultas.data-akademik.kelas-penjadwalan.edit',['id_matkul' => $d->id_matkul, 'id_kelas' => $d->id_kelas_kuliah])}}" type="button" class="btn btn-sm btn-rounded btn-primary waves-effect waves-light"><i class="fa fa-pencil"></i> Atur Jadwal Ujian</a>
                                            </div>
                                            <form action="{{route('fakultas.data-akademik.kelas-penjadwalan.delete', ['id_matkul' => $d->id_matkul, 'id_kelas'=> $d->id_kelas_kuliah])}}" method="post" class="delete-form my-3 px-3" data-id="{{$d->id_kelas_kuliah}}" id="deleteForm{{$d->id_kelas_kuliah}}">
                                                @csrf
                                                @method('delete')
                                                <div class="row">
                                                <button type="submit" class="btn btn-sm btn-rounded btn-danger waves-effect waves-light"><i class="fa fa-trash"></i> Hapus Jadwal Ujian</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                      </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script>
    $(function() {
        "use strict";

        $('#data').DataTable();

        $('.delete-form').submit(function(e){
            e.preventDefault();
            var formId = $(this).data('id');
            swal({
                title: 'Apakah Anda Yakin??',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $(`#deleteForm${formId}`).unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });
</script>
@endpush
