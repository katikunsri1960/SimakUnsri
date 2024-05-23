@extends('layouts.prodi')
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
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Kurikulum</li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('prodi.data-master.kurikulum')}}">List Kurikulum</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Kurikulum</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                {{-- <div class="box-header with-border">
                    <div class="d-flex justify-content-start">
                        <h4>Kurikulum Kuliah</h4>
                    </div>
                </div> --}}
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="nama_kurikulum" class="form-label">Nama Kurikulum <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_kurikulum"
                                    id="nama_kurikulum"
                                    aria-describedby="helpId"
                                    value="{{$data->nama_kurikulum}}" disabled
                                />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="jumlah_sks_pilihan" class="form-label">Jumlah Bobot Mata Kuliah Pilihan <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="jumlah_sks_pilihan"
                                    id="jumlah_sks_pilihan"
                                    aria-describedby="helpId"
                                    value="{{$data->jumlah_sks_pilihan}}" disabled
                                />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="nama_program_studi" class="form-label">Program Studi <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_program_studi"
                                    id="nama_program_studi"
                                    aria-describedby="helpId"
                                    value="{{$data->nama_program_studi}}" disabled
                                />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="semester_mulai_berlaku" class="form-label">Mulai Berlaku <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="semester_mulai_berlaku"
                                    id="semester_mulai_berlaku"
                                    aria-describedby="helpId"
                                    value="{{$data->semester_mulai_berlaku}}" disabled
                                />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="jumlah_sks_lulus" class="form-label">Jumlah SKS <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="jumlah_sks_lulus"
                                    id="jumlah_sks_lulus"
                                    aria-describedby="helpId"
                                    value="{{$data->jumlah_sks_lulus}}" disabled
                                />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="jumlah_sks_wajib" class="form-label">Jumlah Bobot Mata Kuliah Wajib <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="jumlah_sks_wajib"
                                    id="jumlah_sks_wajib"
                                    aria-describedby="helpId"
                                    value="{{$data->jumlah_sks_wajib}}" disabled
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('prodi.data-master.kurikulum.matkul-kurikulum')
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>
    $(function () {
        "use strict";

        $('#data').DataTable( {
             dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            paging: false,
            scrollCollapse: true,
            scrollY: "550px",
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

    });
</script>
@endpush
