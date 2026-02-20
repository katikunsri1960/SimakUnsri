@extends('layouts.universitas')
@section('title')
Perbaikan Data Pokok
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Daftar Perbaikan Data Pokok</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a>
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
                        @include('universitas.monitoring.perbaikan-data-pokok.filter')
                        <span class="divider-line mx-1"></span>
                        <a href="{{route('univ.monitoring.lulus-do')}}" class="btn btn-warning waves-effect waves-light" >
                            <i class="fa fa-refresh"></i> Reset Filter
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table  table-hover margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle" rowspan="2">Nama</th>
                                    <th class="text-center align-middle" rowspan="2">Tempat</th>
                                    <th class="text-center align-middle" rowspan="2">Tanggal</th>                                   
                                    <th class="text-center align-middle">Program Studi</th>
                                    <th class="text-center align-middle">Angkatan</th>
                                    <th class="text-center align-middle">Keterangan</th>
                                </tr>
                                <tr>
                                    <th class="text-center align-middle">Awal</th>
                                    <th class="text-center align-middle">Perbaikan</th>
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

        $('#angkatan').select2({
            placeholder: 'Pilih Angkatan',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#filter-button')
        });

        $('#data').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('univ.monitoring.perbaikan-data.get') }}',
                type: 'GET',
                data: function (d) {
                    d.id_prodi = $('#id_prodi').val();
                    d.angkatan = $('#angkatan').val();
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
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'nim', name: 'nim', class: 'text-center', searchable: true, orderData: [0] },
                {
                    data: null, name: 'nama_mahasiswa', class: "text-start align-middle", searchable: true, orderData: [1],
                    render: function (data, type, row) {
                        return data.riwayat_pendidikan.biodata
                            ? data.riwayat_pendidikan.biodata.nama_mahasiswa
                            : '-';
                    }
                },
                { data: 'nama_perbaikan', name: 'nama_perbaikan', class: 'text-center', searchable: true, orderData: [1] },
                {
                    data: null, name: 'nama_program_studi', class: "text-start align-middle", searchable: true, orderData: [2],
                    render: function (data, type, row) {
                        return data.riwayat_pendidikan.prodi
                            ? data.riwayat_pendidikan.prodi.nama_jenjang_pendidikan + ' ' + data.riwayat_pendidikan.prodi.nama_program_studi
                            : '-';
                    }
                },
                {
                    data: null, name: 'angkatan', class: "text-start align-middle", searchable: true, orderData: [3],
                    render: function (data, type, row) {
                        return data.riwayat_pendidikan.id_periode_masuk
                            ? data.riwayat_pendidikan.id_periode_masuk
                            : '-';
                    }
                },
                {
                    data: null, name: 'keterangan', class: "text-start align-middle", searchable: true, orderData: [4],
                },
            ],
        });
    });
</script>
@endpush
