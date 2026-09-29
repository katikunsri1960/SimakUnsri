@extends('layouts.ditmawa')

@section('title')
Sub Bidang SKPI
@endsection

@section('content')
@include('swal')

<section class="content">
    <div class="row align-items-end">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3">
                            <img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h2>Daftar SKPI Sub Bidang Kegiatan</h2>
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
                        <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="fa fa-plus"></i> Tambah Sub Bidang
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Bidang Utama</th>
                                    <th class="text-center align-middle">Nama Sub Bidang</th>
                                    <th class="text-center align-middle">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                    <td class="text-center align-middle">{{ $d->bidang->nama_bidang ?? '-' }}</td>
                                    <td class="text-start align-middle">{{ $d->nama_sub_bidang }}</td>
                                    <td class="text-center align-middle">
                                        <button 
                                            type="button" 
                                            class="btn btn-warning btn-sm mb-1 btn-edit"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEdit"
                                            data-id="{{ $d->id }}"
                                            data-bidang="{{ $d->bidang_id }}"
                                            data-nama="{{ e($d->nama_sub_bidang) }}"
                                        >
                                            <i class="fa fa-edit mr-1"></i>
                                        </button>

                                        <form action="{{ route('ditmawa.skpi.sub-bidang.destroy', $d->id) }}" method="POST" style="display:inline" class="form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm btn-delete mb-1">
                                                <i class="fa fa-trash"></i>
                                            </button>
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

{{-- INCLUDE MODAL CREATE DAN EDIT --}}
@include('ditmawa.skpi.sub-bidang.create')
@include('ditmawa.skpi.sub-bidang.edit')

@endsection

@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>

<script>
$(document).ready(function(){

    // DataTables
    $('#data').DataTable();

    // Reset Modal Tambah saat ditutup
    $('#createModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });

    // Populate Data di Modal Edit
    $(document).on('click', '.btn-edit', function () {
        let id = $(this).data('id');
        let bidang = $(this).data('bidang');
        let nama = $(this).data('nama');

        $('#edit_bidang_id').val(bidang);
        $('#edit_nama_sub_bidang').val(nama);

        let url = "{{ route('ditmawa.skpi.sub-bidang.update', ':id') }}";
        url = url.replace(':id', id);
        $('#formEdit').attr('action', url);
    });

    // SweetAlert Hapus
    $(document).on('click', '.btn-delete', function(e){
        e.preventDefault();
        var form = $(this).closest('form');

        swal({
            title: "Yakin?",
            text: "Data akan dihapus permanen!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }, function(isConfirm){
            if (isConfirm) form.submit();
        });
    });

    // SweetAlert Create
    $('.form-create').on('submit', function(e){
        e.preventDefault();
        var form = this;

        swal({
            title: "Simpan Data?",
            text: "Sub Bidang akan ditambahkan",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#04a08b",
            confirmButtonText: "Ya, Simpan!",
            cancelButtonText: "Batal"
        }, function(isConfirm){
            if (isConfirm) form.submit();
        });
    });

    // SweetAlert Edit
    $('.form-edit').on('submit', function(e){
        e.preventDefault();
        var form = this;

        swal({
            title: "Update Data?",
            text: "Perubahan akan disimpan",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ffc107",
            confirmButtonText: "Ya, Update!",
            cancelButtonText: "Batal"
        }, function(isConfirm){
            if (isConfirm) form.submit();
        });
    });

});
</script>
@endpush