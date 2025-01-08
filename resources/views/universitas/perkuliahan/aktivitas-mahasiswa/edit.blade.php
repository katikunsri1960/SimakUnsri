@extends('layouts.universitas')
@section('title')
Aktivitas Mahasiswa Edit
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Aktivitas Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Perkuliahan</li>
                        <li class="breadcrumb-item active" aria-current="page">List Aktivitas</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
@include('swal')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                {{-- <div class="box-header with-border">

                </div> --}}
                <div class="box-body">
                    <form action="{{route('univ.perkuliahan.aktivitas-mahasiswa.update', ['id' => $data->id])}}" method="post" id="updateForm">
                    @csrf
                    @method('patch')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul</label>
                                <textarea class="form-control" name="judul" id="judul" rows="3">{{$data->judul}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nim" class="form-label">NIM</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nim"
                                    id="nim"
                                    value="{{$data->anggota_aktivitas_personal ? $data->anggota_aktivitas_personal->nim : ''}}"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_mahasiswa" class="form-label">Nama</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mahasiswa"
                                    id="nama_mahasiswa"
                                    value="{{$data->anggota_aktivitas_personal ? $data->anggota_aktivitas_personal->nama_mahasiswa : ''}}"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="tanggal_mulai"
                                    id="tanggal_mulai"
                                    value="{{$data->id_tanggal_mulai}}"
                                />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="tanggal_selesai"
                                    id="tanggal_selesai"
                                    value="{{$data->id_tanggal_selesai}}"
                                />
                            </div>
                        </div>
                        <div class="col-md-6">

                            <button class="btn btn-primary waves-effect waves-light" type="submit">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                            <a class="btn btn-secondary waves-effect waves-light" href="{{route('univ.perkuliahan.aktivitas-mahasiswa')}}">
                                Batalkan
                            </a>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('assets/js/flatpickr/flatpickr.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/flatpickr/flatpickr.js')}}"></script>
<script>

    $(function () {
        // "use strict";
        flatpickr("#tanggal_mulai", {
            dateFormat: "d-m-Y",
        });

        flatpickr("#tanggal_selesai", {
            dateFormat: "d-m-Y",
        });


        // sweet alert sync-form
        $('#updateForm').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Update Data',
                text: "Apakah anda yakin?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $('#spinner').show();
                    $('#updateForm').unbind('submit').submit();
                }
            });
        });



    });
</script>
@endpush
