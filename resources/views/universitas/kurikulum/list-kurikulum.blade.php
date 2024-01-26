@extends('layouts.universitas')
@section('title')
List Kurikulum
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Kurikulum</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Kurikulum</li>
                        <li class="breadcrumb-item active" aria-current="page">List Kurikulum</li>
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
                <div class="box-header with-border d-flex justify-content-between">
                    <div class="d-flex justify-content-start">
                        <!-- Modal trigger button -->
                        <button type="button" class="btn btn-secondary waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#filter-button">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                        @include('universitas.kurikulum.filter')
                    </div>
                    <div class="d-flex justify-content-end">
                        <form action="{{route('univ.kurikulum.sync')}}" method="get" id="sync-form">
                            <button class="btn btn-primary waves-effect waves-light" type="submit"><i
                                    class="fa fa-refresh"></i> Sinkronisasi</button>
                        </form>
                        <span class="divider-line mx-1"></span>
                        <button class="btn btn-success waves-effect waves-light" href="#"><i class="fa fa-plus"></i>
                            Tambah Kurikulum</button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table  table-hover margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle" rowspan="2">Status</th>
                                    <th class="text-center align-middle" rowspan="2">No</th>
                                    <th class="text-center align-middle" rowspan="2">Nama Kurikulum</th>
                                    <th class="text-center align-middle" rowspan="2">Program Studi</th>
                                    <th class="text-center align-middle" rowspan="2">Mulai Berlaku</th>
                                    <th class="text-center align-middle" colspan="3">Aturan Jumlah sks</th>
                                    <th class="text-center align-middle" colspan="2">Jumlah sks Matakuliah</th>
                                    <th class="text-center align-middle" rowspan="2">SK Kurikulum</th>
                                </tr>
                                <tr>
                                    <th class="text-center align-middle">Lulus</th>
                                    <th class="text-center align-middle">Wajib</th>
                                    <th class="text-center align-middle">Pilihan</th>
                                    <th class="text-center align-middle">Wajib</th>
                                    <th class="text-center align-middle">Pilihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-success">{{$d->status_sync}}</span>

                                    </td>
                                    <td class="text-center align-middle"></td>
                                    <td>
                                        <a href="{{route('univ.kurikulum.detail', $d)}}">{{$d->nama_kurikulum}}</a>
                                    </td>
                                    <td>{{$d->nama_program_studi}}</td>
                                    <td class="text-center align-middle">{{$d->semester_mulai_berlaku}}</td>
                                    <td class="text-center align-middle">{{$d->jumlah_sks_lulus}}</td>
                                    <td class="text-center align-middle">{{$d->jumlah_sks_wajib}}</td>
                                    <td class="text-center align-middle">{{$d->jumlah_sks_pilihan}}</td>
                                    <td class="text-center align-middle">{{$d->jumlah_sks_mata_kuliah_wajib}}</td>
                                    <td class="text-center align-middle">{{$d->jumlah_sks_mata_kuliah_pilihan}}</td>
                                    <td class="text-center align-middle">
                                        {{$d->sk_kurikulum}}
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
@push('css')
    <link rel="stylesheet" href="{{asset('assets/vendor_components/select2/dist/css/select2.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>
    $(function () {
        "use strict";

        $('#data').DataTable( {
            columnDefs: [{
                targets: 1,
                searchable: false,
                orderable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },},
                {
                    targets: 0,
                    searchable: false,
                    orderable: false,
                 },
            ]
        } );

        // sweet alert sync-form
        $('#sync-form').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Sinkronisasi Data',
                text: "Apakah anda yakin ingin melakukan sinkronisasi data kurikulum?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sinkronkan!',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $('#sync-form').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });

        $('#id_prodi').select2({
            placeholder: 'Pilih Program Studi',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#filter-button')
        });

    });
</script>
@endpush
