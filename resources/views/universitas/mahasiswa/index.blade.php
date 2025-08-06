@extends('layouts.universitas')
@section('title')
Mahasiswa
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Daftar Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Mahasiswa</li>
                        <li class="breadcrumb-item active" aria-current="page">Daftar</li>
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
                        <form action="{{route('univ.mahasiswa.sync')}}" method="get" id="sync-form">
                            <button class="btn btn-primary waves-effect waves-light" type="submit"><i
                                    class="fa fa-refresh"></i> Sinkronisasi</button>
                        </form>
                        <span class="divider-line mx-1"></span>
                        <form action="{{route('univ.mahasiswa.sync-prestasi')}}" method="get" id="sync-form-2">
                            <button class="btn btn-success waves-effect waves-light" type="submit"><i
                                    class="fa fa-refresh"></i> Sinkronisasi Prestasi</button>
                        </form>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table  table-hover margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">Status</th>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Nama</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">Jenis Kelamin</th>
                                    <th class="text-center align-middle">Agama</th>
                                    <th class="text-center align-middle">Total SKS Diambil</th>
                                    <th class="text-center align-middle">Tanggal Lahir</th>
                                    <th class="text-center align-middle">Program Studi</th>
                                    <th class="text-center align-middle">Status</th>
                                    <th class="text-center align-middle">Angkatan</th>
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
            ajax: {
                url: '{{route('univ.mahasiswa.data')}}',
                type: 'GET',
                data: function (d) {
                    d.prodi = $('#prodi').val();
                },
                error: function (xhr, error, thrown) {
                    alert('An error occurred. ' + thrown);
                }
            },
            columns: [
                {data: 'status_sync', name: 'status_sync', class: "text-center align-middle", searchable: false, sortable:false,  render: function(data, type, row) {
                    if (data == 'sudah sync') {
                        return '<span class="badge badge-success">' + data + '</span>';
                    } else {
                        return '<span class="badge badge-warning">' + data + '</span>';
                    }
                }},
                {
                    data: null,
                    searchable: false,
                    class: "text-center align-middle",
                    sortable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                }},
                {data: 'nama_mahasiswa', name: 'nama_mahasiswa', class: 'text-start', searchable: true, orderData: [0]},
                {data: 'nim', name: 'nim', class: 'text-center', searchable: true, orderData: [1]},
                {data: 'biodata.jenis_kelamin', name: 'biodata.jenis_kelamin', class: 'text-center', searchable: false, sortable:false},
                {data: 'biodata.nama_agama', name: 'biodata.nama_agama', class: 'text-center', searchable: false, sortable:false},
                {data: null, searchable: false, sortable:false, class:"text-center align-middle", render: function(data, type, row, meta) {
                        return 1;
                }},
                {data: 'biodata.tanggal_lahir', name: 'biodata.tanggal_lahir', class: 'text-center', sortable:false, searchable: false},
                {data: 'nama_program_studi', name: 'nama_program_studi', searchable: true, orderData: [2]},
                {
                    data: null,
                    name: 'keterangan_keluar',
                    searchable: true,
                    sortable: false,
                    class: "text-center align-middle",
                    render: function(data, type, row, meta) {
                        return row.lulus_do?.nama_jenis_keluar || row.keterangan_keluar;
                    }
                },
                {data: 'angkatan', name: 'angkatan', class: "text-center align-middle", searchable: true, orderData: [3]},
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

        $('#sync-form-2').submit(function(e){
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
                    $('#sync-form-2').unbind('submit').submit();
                }
            });
        });

    });
</script>
@endpush
