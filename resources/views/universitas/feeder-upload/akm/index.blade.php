@extends('layouts.universitas')
@section('title')
FEEDER UPLOAD - AKM
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">FEEDER UPLOAD - AKM</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Feeder Upload</li>
                        <li class="breadcrumb-item active" aria-current="page">Aktivitas Kuliah Mahasiswa</li>
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
                        Total Data Belum Upload: <span class="ms-3 badge bg-danger">{{$count}}</span>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{-- <button class="btn btn-success waves-effect waves-light" data-bs-toggle="modal"
                        data-bs-target="#createModal"><i class="fa fa-plus"></i> Tambah Data</button> --}}
                        <span class="divider-line mx-1"></span>
                        {{-- <form action="{{route('univ.mahasiswa.sync-prestasi')}}" method="get" id="sync-form-2">
                            <button class="btn btn-success waves-effect waves-light" type="submit"><i class="fa fa-refresh"></i> Sinkronisasi Prestasi</button>
                        </form> --}}
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-hover margin-top-10 w-p100" style="font-size: 10pt">
                            <thead>
                                <tr>
                                    <th class="text-center-align-middle">No</th>
                                    <th class="text-center-align-middle">NIM</th>
                                    <th class="text-center-align-middle">Nama</th>
                                    <th class="text-center-align-middle">Prodi</th>
                                    <th class="text-center-align-middle">Angkatan</th>
                                    <th class="text-center-align-middle">Semester</th>
                                    <th class="text-center-align-middle">Status</th>
                                    <th class="text-center-align-middle">IPS</th>
                                    <th class="text-center-align-middle">IPK</th>
                                    <th class="text-center-align-middle">SKS Semester</th>
                                    <th class="text-center-align-middle">SKS Total</th>
                                    <th class="text-center-align-middle">Jenis Pembiayaan</th>
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
@push('css')
<link rel="stylesheet" href="{{asset('assets/js/flatpickr/flatpickr.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/js/flatpickr/flatpickr.js')}}"></script>
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

        $('#semester').select2({
            placeholder: 'Pilih Semester',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#filter-button')
        });
        $('#status_mahasiswa').select2({
            placeholder: 'Pilih Status',
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
                url: '{{route('univ.feeder-upload.akm.data')}}',
                type: 'GET',
                data: function (d) {
                    d.id_prodi = $('#id_prodi').val();
                    d.semester = $('#semester').val();
                    d.angkatan = $('#angkatan').val();
                    d.status_mahasiswa = $('#status_mahasiswa').val();
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
                {data: 'nim', name: 'nim', class: 'text-center', searchable: true},
                {data: 'nama_mahasiswa', name: 'nama_mahasiswa', class: 'text-start', searchable: true},
                {data: 'nama_program_studi', name: 'nama_program_studi', searchable: true},
                {data: 'angkatan', name: 'angkatan', class: "text-center align-middle", searchable: true},
                {data: 'nama_semester', name: 'nama_semester', class: "text-center align-middle", searchable: true},
                {data: 'nama_status_mahasiswa', name: 'nama_status_mahasiswa', class: "text-center align-middle", searchable: true},
                {data: 'ips', name: 'ips', class: "text-center align-middle", searchable: true},
                {data: 'ipk', name: 'ipk', class: "text-center align-middle", searchable: true},
                {data: 'sks_semester', name: 'sks_semester', class: "text-center align-middle", searchable: true},
                {data: 'sks_total', name: 'sks_total', class: "text-center align-middle", searchable: true},
                {data: 'nama_pembiayaan', name: 'nama_pembiayaan', class: "text-center align-middle", searchable: false},
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

    });
</script>
@endpush
