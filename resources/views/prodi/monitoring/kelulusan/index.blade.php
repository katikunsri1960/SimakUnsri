@extends('layouts.prodi')
@section('title')
Mahasiswa Lulus DO
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Daftar Mahasiswa Lulus DO</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Monitoring</li>
                        <li class="breadcrumb-item active" aria-current="page">Lulus Do</li>
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
                    <div class="d-flex justify-content-start">
                        <!-- Modal trigger button -->
                        <button type="button" class="btn btn-success waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#filter-button">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                        @include('prodi.monitoring.kelulusan.filter')
                        <span class="divider-line mx-1"></span>
                        <a href="{{route('prodi.monitoring.lulus-do')}}" class="btn btn-warning waves-effect waves-light" >
                            <i class="fa fa-refresh"></i> Reset Filter
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-hover margin-top-10 w-p100" style="font-size: 11px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">Nama</th>
                                    <th class="text-center align-middle">ANGKATAN</th>
                                    <th class="text-center align-middle">Jenis Keluar</th>
                                    <th class="text-center align-middle">Tgl Keluar</th>
                                    <th class="text-center align-middle">Periode Keluar</th>
                                    <th class="text-center align-middle">No SK</th>
                                    <th class="text-center align-middle">Tgl SK</th>
                                    <th class="text-center align-middle">PIN</th>
                                    <th class="text-center align-middle">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <hr>
                    @if ($jenis_keluar_counts)
                    <div class="row mt-5">
                        <center>
                            <h3>Total Berdasarkan Jenis Keluar</h3>
                        </center>

                        @foreach ($jenis_keluar_counts as $i)
                        <div class="col-md-4">
                            <a class="box box-link-shadow text-center pull-up" href="javascript:void(0)">
                                <div class="box-body py-25 bg-info-light px-5">
                                    <p class="fw-600 text-dark">{{$i->nama_jenis_keluar}}</p>
                                </div>
                                <div class="box-body">
                                    <h1 class="countnm fs-50 m-0">{{number_format($i->total, 0, ',','.')}}</h1>
                                </div>
                            </a>
                        </div>
                        @endforeach

                    </div>
                    @endif

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

        $('#angkatan').select2({
            placeholder: 'Pilih Angkatan',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#filter-button')
        });

        $('#jenis_keluar').select2({
            placeholder: 'Pilih Angkatan',
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
                url: '{{route('prodi.monitoring.lulus-do.data')}}',
                type: 'GET',
                data: function (d) {
                    d.angkatan = $('#angkatan').val();
                    d.jenis_keluar = $('#jenis_keluar').val();
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
                {data: 'nim', name: 'nim', class: 'text-center', searchable: true, orderData: [0]},
                {data: 'nama_mahasiswa', name: 'nama_mahasiswa', class: 'text-start', searchable: true, orderData: [1]},
                {data: 'angkatan', name: 'angkatan', class: "text-center align-middle", searchable: true, orderData: [2]},
                {data: 'nama_jenis_keluar', name: 'nama_jenis_keluar', class: "text-center align-middle", searchable: true, orderData: [3]},
                {data: 'tanggal_keluar', name: 'tanggal_keluar', class: "text-center align-middle", searchable: true, orderData: [4]},
                {
                    data: null,
                    name: 'nama_semester',
                    class: "text-center align-middle",
                    searchable: true,
                    orderData: [5],
                    render: function (data, type, row) {
                        return data.periode_keluar && data.periode_keluar.nama_semester
                            ? data.periode_keluar.nama_semester
                            : '-';
                    }
                },
                {data: 'sk_yudisium', name: 'sk_yudisium', class: "text-start align-middle", searchable: true, orderData: [6]},
                {data: 'tgl_sk_yudisium', name: 'tgl_sk_yudisium', class: "text-start align-middle", searchable: true, orderData: [7]},
                {data: 'no_seri_ijazah', name: 'no_seri_ijazah', class: "text-start align-middle", searchable: true, orderData: [8]},
                {data: 'keterangan', name: 'keterangan', class: "text-start align-middle", searchable: true, orderData: [9]},
            ],
        });



    });
</script>
@endpush
