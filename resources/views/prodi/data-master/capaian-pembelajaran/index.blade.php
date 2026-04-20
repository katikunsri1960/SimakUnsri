@extends('layouts.prodi')

@section('title')
Capaian Pembelajaran Lulusan Kurikulum
@endsection

@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Master Kurikulum</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{route('prodi')}}">
                                <i class="mdi mdi-home-outline"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">Data Master</li>
                        <li class="breadcrumb-item active">Kurikulum</li>
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

    <div class="box-header with-border">
        <div class="d-flex justify-content-end">
            <button class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#createModal">
                <i class="fa fa-plus"></i> Tambah CPL
            </button>
        </div>
    </div>

    <div class="box-body">
        <div class="table-responsive">
            <table id="data" class="table table-hover w-p100">
                <thead>
                    <tr>
                        <th width="2%" class="text-center">No</th>
                        <th class="text-center">Nama Kurikulum</th>
                        <th class="text-center">Kode CPL</th>
                        <th class="text-center">Nama CPL</th>
                        <th width="8%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                    <tr>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-center">{{$d->kurikulum->nama_kurikulum}}</td>
                        <td class="text-center">{{$d->kode_cpl}}</td>
                        <td>{{$d->nama_cpl}}</td>
                        <td class="text-center">
                            <div class="row" role="group">
                                <div class="col-md-12 mb-2">
                                    <form action="{{route('prodi.data-master.cpl.delete', $d->id)}}"
                                        method="post"
                                        class="delete-form">
                                        @csrf
                                        @method('delete')

                                        <button type="submit"
                                            class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <button class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal{{$d->id}}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>

                    @include('prodi.data-master.capaian-pembelajaran.edit')
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>
</div>
</section>
@include('prodi.data-master.capaian-pembelajaran.create')

@endsection


@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>

<script>
$(function () {

    "use strict";

    // DataTable
    $('#data').DataTable();

    // RESET MODAL CREATE
    $('#createModal').on('shown.bs.modal', function () {
        $(this).find('#matkulTambah').val('');
        $(this).find('#preview_kode_cpl').val('');
    });

    // ================================
    // CREATE - AUTO GENERATE KODE CPL
    // ================================
    $('#createModal').on('change', '#matkulTambah', function () {

        let kurikulumId = $(this).val();

        if (!kurikulumId) {
            $('#createModal #preview_kode_cpl').val('');
            return;
        }

        $('#createModal #preview_kode_cpl').val('Loading...');

        $.get("{{ route('prodi.data-master.cpl.get-last-kode') }}", {
            id_kurikulum: kurikulumId
        }, function (res) {

            console.log(res);

            if (res.status === 'success') {
                $('#createModal #preview_kode_cpl').val(res.kode);
            } else {
                $('#createModal #preview_kode_cpl').val('');
                swal('Warning', res.message, 'warning');
            }

        }).fail(function () {

            $('#createModal #preview_kode_cpl').val('');
            swal('Error', 'Gagal mengambil kode CPL', 'error');

        });

    });

});

// ================================
// KONFIRMASI CREATE DATA
// ================================
$(document).on('submit', '#createModal form', function (e) {

    e.preventDefault(); // tahan submit dulu

    let form = this;

    swal({
        title: "Simpan Data?",
        text: "Pastikan data sudah benar.",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Simpan",
        cancelButtonText: "Batal"
    }, function (isConfirm) {

        if (isConfirm) {
            // optional: disable tombol biar tidak double submit
            $(form).find('button[type="submit"]').prop('disabled', true);

            form.submit(); // lanjut submit
        }

    });

});


// ================================
// EDIT - AUTO GENERATE KODE CPL
// ================================
$(document).on('change', '.edit-kurikulum', function () {

    let kurikulumId = $(this).val();
    let id = $(this).data('id');

    if (!kurikulumId) {
        $('#edit_kode_' + id).val('');
        return;
    }

    $('#edit_kode_' + id).val('Loading...');

    $.get("{{ route('prodi.data-master.cpl.get-last-kode') }}", {
        id_kurikulum: kurikulumId
    }, function (res) {

        if (res.status === 'success') {
            $('#edit_kode_' + id).val(res.kode);
        } else {
            $('#edit_kode_' + id).val('');
            swal('Warning', res.message, 'warning');
        }

    }).fail(function () {

        $('#edit_kode_' + id).val('');
        swal('Error', 'Gagal generate kode', 'error');

    });

});

// ================================
// KONFIRMASI UPDATE (MODAL EDIT)
// ================================
$(document).on('submit', '.form-edit', function (e) {

    e.preventDefault(); // tahan submit dulu

    let form = this;

    swal({
        title: "Update Data?",
        text: "Perubahan akan disimpan.",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Update",
        cancelButtonText: "Batal"
    }, function (isConfirm) {

        if (isConfirm) {
            // optional: disable tombol supaya tidak double klik
            $(form).find('button[type="submit"]').prop('disabled', true);

            form.submit();
        }

    });

});

// SWEET ALERT DELETE
$(document).on('submit', '.delete-form', function (e) {

    e.preventDefault(); // tahan submit

    let form = this;

    swal({
        title: "Yakin ingin menghapus?",
        text: "Data yang dihapus tidak bisa dikembalikan!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal"
    }, function (isConfirm) {

        if (isConfirm) {
            form.submit(); // lanjut submit kalau user klik Ya
        }

    });

});
</script>
@endpush