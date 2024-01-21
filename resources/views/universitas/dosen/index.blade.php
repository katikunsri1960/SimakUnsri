@extends('layouts.universitas')
@section('title')
List Dosen
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Dosen</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Dosen</li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
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
                <div class="box-header with-border">
                    <div class="d-flex justify-content-end">
                        <form action="{{route('univ.dosen.sync')}}" method="get" id="sync-form">
                            <button class="btn btn-primary waves-effect waves-light" type="submit"><i
                                    class="fa fa-refresh"></i> Sinkronisasi</button>
                        </form>
                        <span class="divider-line mx-1"></span>
                        <form action="{{route('univ.dosen.sync-penugasan')}}" method="get" id="sync-penugasan">
                            <button class="btn btn-success waves-effect waves-light" type="submit"><i
                                    class="fa fa-refresh"></i> Sinkronisasi Penugasan</button>
                        </form>
                        {{-- <button class="btn btn-success waves-effect waves-light" href="#"><i
                                class="fa fa-plus"></i> Tambah Kurikulum</button> --}}
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table  table-hover margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Nama</th>
                                    <th class="text-center align-middle">NIDN</th>
                                    <th class="text-center align-middle">Jenis Kelamin</th>
                                    <th class="text-center align-middle">Agama</th>
                                    <th class="text-center align-middle">Status</th>
                                    <th class="text-center align-middle">Tanggal Lahir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-start align-middle">{{$d->nama_dosen}}</td>
                                    <td class="text-center align-middle">{{$d->nidn}}</td>
                                    <td class="text-center align-middle">{{$d->jenis_kelamin}}</td>
                                    <td class="text-center align-middle">{{$d->nama_agama}}</td>
                                    <td class="text-center align-middle">{{$d->nama_status_aktif}}</td>
                                    <td class="text-center align-middle">{{$d->id_tanggal_lahir}}</td>
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
    $(function () {
        // "use strict";

        $('#data').DataTable();

        // sweet alert sync-form
        $('#sync-form').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Sinkronisasi Data',
                text: "Apakah anda yakin ingin melakukan sinkronisasi?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sinkronkan!',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $('#spinner').show();
                    $('#sync-form').unbind('submit').submit();
                }
            });
        });

        $('#sync-penugasan').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Sinkronisasi Data',
                text: "Apakah anda yakin ingin melakukan sinkronisasi?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sinkronkan!',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $('#spinner').show();
                    $('#sync-penugasan').unbind('submit').submit();
                }
            });
        });

    });
</script>
@endpush
