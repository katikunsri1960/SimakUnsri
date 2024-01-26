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
                    <div class="table-responsive">
                        <table id="data" class="table  table-hover margin-top-10 w-p100">

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

       // $('#data').DataTable({
            // dom: 'Bfrtip',
            // buttons: [
            //     'copy', 'csv', 'excel', 'pdf', 'print'
            // ],
            // processing: true,
            // serverSide: true,
            // ajax: {
            //     url: '{{route('univ.mata-kuliah.data')}}',
            //     type: 'GET',
            //     data: function (d) {
            //         d.prodi = $('#prodi').val();
            //     },
            //     error: function (xhr, error, thrown) {
            //         alert('An error occurred. ' + thrown);
            //     }
            // },
            // columns: [
            //     {data: 'kode_mata_kuliah', name: 'kode_mata_kuliah', searchable: true},
            //     {data: 'nama_mata_kuliah', name: 'nama_mata_kuliah', searchable: true},
            //     {data: 'sks_mata_kuliah', name: 'sks_mata_kuliah', class: 'text-center'},
            //     {
            //         data: null,
            //         name: 'prodi',
            //         searchable: true,
            //         render: function (data, type, row, meta) {
            //             return data.prodi.nama_jenjang_pendidikan + ' ' + data.prodi.nama_program_studi ;
            //         }
            //     }
            // ],
        //});

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
