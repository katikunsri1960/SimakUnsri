@extends('layouts.prodi')
@section('title')
Aktivitas Mahasiswa
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Detail Program Studi</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Program Studi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<section class="content">
    @include('swal')
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-body">
                    <div class="d-flex justify-content-between">
                        <a href="{{route('prodi')}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
                        <button class="btn btn-primary btn-rounded waves-effect waves-light" data-bs-toggle="modal"
                        data-bs-target="#ProdiInggris"><i class="fa fa-edit"><span class="path1"></span><span class="path2"></span></i> Update Nama Bahasa Inggris Prodi</button> 
                    </div>
                    @include('prodi.data-master.detail-prodi.create-prodi-inggris')
                    <hr class="my-15">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table id="data" class="table table-hover margin-top-10 w-p100">
                                    <tbody>
                                        <tr>
                                            <td class="text-start align-middle" style="width:30%">Nama Program Studi (Indonesia)</td>
                                            <td class="text-center align-middle" style="width:3%">:</td>
                                            <td class="text-start align-middle">{{$data->nama_program_studi}}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start align-middle" style="width:30%">Nama Program Studi (Inggris)</td>
                                            <td class="text-center align-middle" style="width:3%">:</td>
                                            <td class="text-start align-middle">{{$data->nama_program_studi_en}}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start align-middle" style="width:30%">Jenjang Pendidikan</td>
                                            <td class="text-center align-middle" style="width:3%">:</td>
                                            <td class="text-start align-middle">{{$data->nama_jenjang_pendidikan}}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start align-middle" style="width:30%">Jurusan</td>
                                            <td class="text-center align-middle" style="width:3%">:</td>
                                            <td class="text-start align-middle">{{$data->jurusan->nama_jurusan_id}}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start align-middle" style="width:30%">Fakultas</td>
                                            <td class="text-center align-middle" style="width:3%">:</td>
                                            <td class="text-start align-middle">{{$data->fakultas->nama_fakultas}}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start align-middle" style="width:30%">BKU pada Ijazah</td>
                                            <td class="text-center align-middle" style="width:3%">:</td>
                                            <td class="text-start align-middle">{{$data->bku_pada_ijazah == 1 ? 'Iya' : 'Tidak'}}</td>
                                        </tr>
                                    </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
                    <div class="d-flex justify-content-end">
                       <div class="btn-group">
                            <button class="btn btn-warning btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#BkuIjazah"><i class="fa fa-edit"><span class="path1"></span><span class="path2"></span></i> Setting BKU pada Ijazah</button> 
                            <a class="btn btn-success btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#TambahBKU"><i class="fa fa-plus"><span class="path1"></span><span class="path2"></span></i> Tambah BKU Prodi</a>
                        </div>   
                    </div>
                    @include('prodi.data-master.detail-prodi.setting-bku')
                    @include('prodi.data-master.detail-prodi.create-bku')
                    <h3 class="text-info mb-0 mt-10"><i class="fa fa-list"></i> Bidang Kajian Utama</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">BKU Bahasa Indonesia</th>
                                    <th class="text-center align-middle">BKU Bahasa Inggris</th>
                                    <th class="text-center align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no_a=1;
                                @endphp

                                @foreach ($data_bku as $db)
                                    <tr>
                                        <td class="text-center align-middle">{{ $no_a++ }}</td>
                                        <td class="text-center align-middle">{{ $db->bku_prodi_id }}</td>
                                        <td class="text-center align-middle">{{ $db->bku_prodi_en }}</td>
                                        <td class="text-center align-middle" style="width:3%">
                                            <form action="{{ route('prodi.data-master.detail-prodi.delete-bku', $db->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger delete-button">
                                                    <i class="fa fa-trash"></i>
                                                </button>
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
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>
    $(function() {
        "use strict";
        
        $('#data').DataTable();
    });

    $(document).ready(function(){
        $('.delete-button').click(function(e){
            e.preventDefault();
            var form = $(this).closest('form');
            swal({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }, function(isConfirmed){
                if (isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
