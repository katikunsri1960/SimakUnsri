@extends('layouts.fakultas')
@section('title')
BEASISWA
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Daftar Beasiswa Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('bak')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Beasiswa Mahasiswa</li>
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
                        <div class="d-flex justify-content-start">
                            <!-- Modal trigger button -->
                            <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                                data-bs-target="#filter-button">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                            <span class="divider-line mx-1"></span>
                            <a href="{{route('fakultas.beasiswa')}}" class="btn btn-warning waves-effect waves-light" >
                                <i class="fa fa-rotate"></i> Reset Filter
                            </a>
                            @include('fakultas.beasiswa.filter')
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-hover margin-top-10 w-p100" style="font-size: 10pt">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Program Studi</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">Nama</th>
                                    <th class="text-center align-middle">Angkatan</th>
                                    <th class="text-center align-middle">Jenis Beasiswa</th>
                                    <th class="text-center align-middle">Pembiayaan</th>
                                    <th class="text-center align-middle">Tanggal Mulai</th>
                                    <th class="text-center align-middle">Tanggal Selesai</th>
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

     $(document).ready(function() {
        // "use strict";

        $('#data').DataTable({
            // dom: 'Bfrtip',
            // buttons: [
            //     'copy', 'csv', 'excel', 'pdf', 'print'
            // ],
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('fakultas.beasiswa.data')}}',
                type: 'GET',
                data: function (d) {
                    d.prodi = $('#prodi').val();
                    d.jenis_beasiswa = $('#jenis_beasiswa').val();
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
                {data: 'nama_program_studi', name: 'nama_program_studi', class: 'text-start', searchable: true, orderData: [0]},
                {data: 'nim', name: 'nim', class: 'text-center', searchable: true, orderData: [1]},
                {data: 'nama_mahasiswa', name: 'nama_mahasiswa', class: 'text-start', searchable: true, orderData: [2]},
                {data: 'mahasiswa.angkatan', name: 'mahasiswa.angkatan', class: "text-center align-middle", searchable: true, orderData: [3]},
                {data: 'jenis_beasiswa.nama_jenis_beasiswa', name: 'jenis_beasiswa.nama_jenis_beasiswa', class: 'text-center', searchable: false, sortable:false},
                {data: 'nama_pembiayaan', name: 'nama_pembiayaan', class: 'text-center', searchable: false, sortable:false},
                {data: 'id_tanggal_mulai_beasiswa', name: 'id_tanggal_mulai_beasiswa', searchable: true, orderData: [4]},
                {data: 'id_tanggal_akhir_beasiswa', name: 'id_tanggal_akhir_beasiswa', searchable: true, orderData: [5]},

            ],
        });


        $("#id_registrasi_mahasiswa").select2({
            placeholder : '-- Masukan NIM / Nama Mahasiswa --',
            dropdownParent: $('#createModal'),
            width: '100%',
            minimumInputLength: 3,
            ajax: {
                url: "{{route('univ.pengaturan.akun.get-mahasiswa')}}",
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function (data) {
                    // console.log(data);
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: "("+item.nim+") "+item.nama_mahasiswa,
                                id: item.id_registrasi_mahasiswa
                            }
                        })
                    };
                },
            }
        });

        $('#storeForm').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Simpan Data',
                text: "Apakah anda yakin ingin menyimpan data?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $('#storeForm').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });



    });
</script>
@endpush
