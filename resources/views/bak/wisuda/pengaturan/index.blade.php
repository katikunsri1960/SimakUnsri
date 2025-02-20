@extends('layouts.bak')
@section('title')
Pembukaan Wisuda
@endsection
@section('content')
<section class="content">
    <div class="row align-items-end">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3"><img
                                src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h2>Daftar Periode Wisuda</h2>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
                    <div class="d-flex justify-content-end">
                        @include('bak.wisuda.pengaturan.create')
                        @include('bak.wisuda.pengaturan.edit')
                        <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                        data-bs-target="#createModal">
                            <i class="fa fa-plus"></i> Tambah Periode
                        </button>
                        <span class="divider-line mx-1"></span>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-hover margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Periode</th>
                                    <th class="text-center align-middle">Tanggal Wisuda</th>
                                    <th class="text-center align-middle">Tanggal Mulai Pendaftaran</th>
                                    <th class="text-center align-middle">Tanggal Akhir Pendaftaran</th>
                                    <th class="text-center align-middle">Apa Aktif</th>
                                    <th class="text-center align-middle">ACT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                    <tr>
                                        <td class="text-center align-middle">{{$loop->iteration}}</td>
                                        <td class="text-center align-middle">{{$d->periode}}</td>
                                        <td class="text-center align-middle">{{$d->id_tanggal_wisuda}}</td>
                                        <td class="text-center align-middle">{{$d->id_tanggal_mulai_daftar}}</td>
                                        <td class="text-center align-middle">{{$d->id_tanggal_akhir_daftar}}</td>
                                        <td class="text-center align-middle">
                                            @if ($d->is_active == 1)
                                                <i class="fa fa-check text-success" style="font-size: 16pt"></i>
                                            @else

                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <button type="button" data-bs-toggle="modal"
                                                data-bs-target="#editModal" onclick="edit({{$d}})"
                                                class="btn btn-warning btn-sm waves-effect waves-light">
                                                <i class="fa fa-edit mr-1"></i>
                                            </button>

                                            <form action="{{ route('bak.wisuda.pengaturan.delete', $d->id) }}" method="post" class="d-inline delete-form" id="deleteForm{{ $d->id }}" data-id="{{ $d->id }}">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                            </form>
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
<link rel="stylesheet" href="{{asset('assets/js/flatpickr/flatpickr.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/js/flatpickr/flatpickr.js')}}"></script>
<script>

    function edit(data) {
        $('#edit_periode').val(data.periode);
        $('#edit_tanggal_wisuda').val(flatpickr.formatDate(new Date(data.tanggal_wisuda), "d-m-Y"));
        $('#edit_tanggal_mulai_daftar').val(flatpickr.formatDate(new Date(data.tanggal_mulai_daftar), "d-m-Y"));
        $('#edit_tanggal_akhir_daftar').val(flatpickr.formatDate(new Date(data.tanggal_akhir_daftar), "d-m-Y"));
        $('#edit_is_active').val(data.is_active);
        $('#editForm').attr('action', `{{url('bak/wisuda/pengaturan/update')}}/${data.id}`);
    }


    $(function () {
        // "use strict";
        $('#data').DataTable();

        flatpickr("#tanggal_wisuda", {
            dateFormat: "d-m-Y",
        });

        flatpickr("#tanggal_mulai_daftar", {
            dateFormat: "d-m-Y",
        });

        flatpickr("#tanggal_akhir_daftar", {
            dateFormat: "d-m-Y",
        });

        flatpickr("#edit_tanggal_wisuda", {
            dateFormat: "d-m-Y",
        });

        flatpickr("#edit_tanggal_mulai_daftar", {
            dateFormat: "d-m-Y",
        });

        flatpickr("#edit_tanggal_akhir_daftar", {
            dateFormat: "d-m-Y",
        });

        $('#createForm').submit(function(e){
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
                    $('#createForm').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });

        $('#editForm').submit(function(e){
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
                    $('#editForm').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });

        $('.delete-form').submit(function(e){
            e.preventDefault();
            var formId = $(this).data('id');
            swal({
                title: 'Hapus Data',
                text: "Apakah anda yakin ingin menghapus data?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $(`#deleteForm${formId}`).unbind('submit').submit();
                    $('#spinner').show();
                }
            });

        });
    });


</script>
@endpush
