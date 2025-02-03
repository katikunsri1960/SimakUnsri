@extends('layouts.universitas')
@section('title')
Aktivitas Mahasiswa
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
                <div class="box-header with-border">
                    <div class="d-flex justify-content-end">
                        <form action="{{route('univ.perkuliahan.aktivitas-mahasiswa.sync')}}" method="get" id="sync-form">
                            <button class="btn btn-primary waves-effect waves-light" type="submit"><i class="fa fa-refresh"></i> Sinkronisasi</button>
                        </form>
                        <span class="divider-line mx-1"></span>
                        <form action="{{route('univ.perkuliahan.aktivitas-mahasiswa.sync-anggota')}}" method="get" id="sync-anggota">
                            <button class="btn btn-secondary waves-effect waves-light" type="submit"><i class="fa fa-refresh"></i> Sinkronisasi Anggota</button>
                        </form>
                        {{-- <button class="btn btn-success waves-effect waves-light" href="#"><i class="fa fa-plus"></i> Tambah Kurikulum</button> --}}
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6 text-start">
                            <button type="button" class="btn btn-success waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#filter-button">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                        @include('universitas.perkuliahan.aktivitas-mahasiswa.filter')
                        <span class="divider-line mx-1"></span>
                        <a href="{{route('univ.perkuliahan.aktivitas-kuliah')}}"
                            class="btn btn-warning waves-effect waves-light">
                            <i class="fa fa-refresh"></i> Reset Filter
                        </a>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Prodi</th>
                                    <th class="text-center align-middle">Judul</th>
                                    <th class="text-center align-middle">Jenis</th>
                                    <th class="text-center align-middle">Tanggal Selesai</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">Nama</th>
                                    <th class="text-center align-middle">ACT</th>
                                    {{-- <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">Program Studi</th>
                                    <th class="text-center align-middle">Angkatan</th>
                                    <th class="text-center align-middle">Status</th> --}}
                                    {{-- <th class="text-center align-middle">Aksi</th> --}}
                                </tr>
                            </thead>

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

        $('#data').DataTable({
            // dom: 'Bfrtip',
            // buttons: [
            //     'copy', 'csv', 'excel', 'pdf', 'print'
            // ],
            processing: true,
            serverSide: true,
            ordering:false,
            stateSave: true,
            ajax: {
                url: '{{route('univ.perkuliahan.aktivitas-mahasiswa.data')}}',
                type: 'GET',
                data: function (d) {
                    d.id_prodi = $('#id_prodi').val();
                    d.semester = $('#semester').val();
                    d.jenis = $('#jenis').val();
                },
                error: function (xhr, error, thrown) {
                    alert('An error occurred. ' + thrown);
                }
            },
            columns: [
                {
                    data: null,
                    searchable: false,
                    class: "text-center align-middle",
                    sortable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                }},
                {
                    data: null,
                    name: 'prodi',
                    searchable: false,
                    render: function (data, type, row, meta) {
                        return data.nama_jenjang_pendidikan + ' ' + data.nama_prodi ;
                    }
                },
                {data: 'judul', name: 'judul', class: 'text-start', searchable: true},
                {data: 'nama_jenis_aktivitas', name: 'nama_jenis_aktivitas', class: 'text-start', searchable: true},
                {
                    data: null,
                    name: 'tanggal_selesai',
                    class: "text-center align-middle",
                    searchable: true,
                    render: function (data, type, row, meta) {
                        if (data.tanggal_selesai == null) {
                            return 'Tidak ada data' ;
                        } else {
                            return data.tanggal_selesai ;
                        }
                    }
                },
                {
                    data: null,
                    name: 'anggota',
                    class: "text-center align-middle",
                    searchable: true,
                    render: function (data, type, row, meta) {
                        if (data.anggota_aktivitas_personal == null) {
                            return 'Tidak ada data' ;
                        } else {
                            return data.anggota_aktivitas_personal.nim ;
                        }
                    }
                },
                {
                    data: null,
                    name: 'anggota',
                    searchable: true,
                    render: function (data, type, row, meta) {
                        if (data.anggota_aktivitas_personal == null) {
                            return 'Tidak ada data' ;
                        } else {
                            return data.anggota_aktivitas_personal.nama_mahasiswa ;
                        }
                    }
                },
                {
                    data: null,
                    searchable: false,
                    class: "text-center align-middle",
                    sortable: false,
                    render: function(data, type, row, meta) {
                        var url = '{{ route("univ.perkuliahan.aktivitas-mahasiswa.edit", ":id") }}';
                        url = url.replace(':id', data.id);
                        var button = '<a href="' + url + '" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>';
                        return button;
                }},
            ],
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

        $('#sync-anggota').submit(function(e){
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
                    $('#sync-anggota').unbind('submit').submit();
                }
            });
        });

    });
</script>
@endpush
