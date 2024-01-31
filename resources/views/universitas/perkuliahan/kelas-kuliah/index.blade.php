@extends('layouts.universitas')
@section('title')
Kelas Kuliah
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Kelas Kuliah</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Perkuliahan</li>
                        <li class="breadcrumb-item active" aria-current="page">Kelas Kuliah</li>
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
                        @include('universitas.perkuliahan.kelas-kuliah.filter')
                    </div>
                    <div class="d-flex justify-content-end">
                        <form action="{{route('univ.perkuliahan.kelas-kuliah.sync')}}" method="get" id="sync-form">
                            <button class="btn btn-primary waves-effect waves-light" type="submit"><i class="fa fa-refresh"></i> Sinkronisasi</button>
                        </form>
                        <span class="divider-line mx-1"></span>
                        <form action="{{route('univ.perkuliahan.kelas-kuliah.sync-pengajar-kelas')}}" method="get" id="sync-pengajar">
                            <button class="btn btn-success waves-effect waves-light" type="submit"><i class="fa fa-refresh"></i> Sinkronisasi Pengajar</button>
                        </form>
                        <span class="divider-line mx-1"></span>
                        <form action="{{route('univ.perkuliahan.kelas-kuliah.sync-peserta-kelas')}}" method="get" id="sync-peserta">
                            <button class="btn btn-secondary waves-effect waves-light" type="submit"><i class="fa fa-refresh"></i> Sinkronisasi Peserta</button>
                        </form>
                        {{-- <button class="btn btn-success waves-effect waves-light" href="#"><i class="fa fa-plus"></i> Tambah Kurikulum</button> --}}
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table  table-hover margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">Semester</th>
                                    <th class="text-center align-middle">Kode MK</th>
                                    <th class="text-center align-middle">Nama Mata Kuliah</th>
                                    <th class="text-center align-middle">Nama Kelas</th>
                                    <th class="text-center align-middle">Dosen Pengajar</th>
                                    <th class="text-center align-middle">Peserta Kelas</th>
                                </tr>
                            </thead>
                          <tbody>

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
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>
    $(function () {
        // "use strict";

        $('#id_prodi').select2({
            placeholder: 'Pilih Program Studi',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#filter-button')
        });

        $('#id_semester').select2({
            placeholder: 'Pilih Semester',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#filter-button')
        });

        $('#data').DataTable({
            // dom: 'Bfrtip',
            // buttons: [
            //     'copy', 'csv', 'excel', 'pdf', 'print'
            // ],
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('univ.perkuliahan.kelas-kuliah.data')}}',
                type: 'GET',
                data: function (d) {
                    d.id_prodi = $('#id_prodi').val();
                    d.id_semester = $('#id_semester').val();
                },
                error: function (xhr, error, thrown) {
                    alert('An error occurred. ' + thrown);
                }
            },
            columns: [
                {data: 'nama_semester', name: 'nama_semester', class: 'text-center', searchable: false},
                {data: 'kode_mata_kuliah', name: 'kode_mata_kuliah', class: 'text-center', searchable: true},
                {data: 'nama_mata_kuliah', name: 'nama_mata_kuliah', class: 'text-start'},
                {data: 'nama_kelas_kuliah', name: 'nama_kelas_kuliah', class: 'text-center'},
                {data: 'nama_dosen', name: 'nama_dosen', class: 'text-start', searchable: false, orderable: false},
                {data: 'peserta_kelas_count', name: 'peserta_kelas_count', class: 'text-center', searchable: false, orderable: false},
            ],
        });

        $('#apply-filter').click(function(e) {
            e.preventDefault();
            $('#data').DataTable().ajax.reload();
            $('#filter-button').modal('hide');
        });

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

        $('#sync-pengajar').submit(function(e){
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
                    $('#sync-pengajar').unbind('submit').submit();
                }
            });
        });

        $('#sync-peserta').submit(function(e){
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
                    $('#sync-peserta').unbind('submit').submit();
                }
            });
        });

    });
</script>
@endpush
