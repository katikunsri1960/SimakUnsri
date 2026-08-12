@extends('layouts.bak')
@section('title')
Bidang Kegiatan SKPI
@endsection
@section('content')
@include('swal')
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
                            <h2>Daftar SKPI Bidang Kegiatan</h2>

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
                        @include('bak.skpi.bidang.create')
                        <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                        data-bs-target="#modalCreateBidang">
                            <i class="fa fa-plus"></i> Tambah Data
                        </button>
                        <span class="divider-line mx-1"></span>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">NO</th>
                                    <th class="text-center align-middle">Nama Bidang</th>
                                    <th class="text-center align-middle">Nama Kegiatan</th>
                                    <th class="text-center align-middle">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $d)

                                @include('bak.skpi.bidang.edit')
                                
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center align-middle">{{$d->nama_bidang}}</td>
                                    <td class="text-center align-middle">{{$d->nama_kegiatan}}</td>
                                    <td class="text-center align-middle">
                                        <button class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $d->id }}">
                                             <i class="fa fa-edit mr-1"></i>
                                        </button>

                                        <form action="{{route('bak.skpi.bidang.destroy',$d->id)}}" method="POST" style="display:inline" class="form-delete">
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
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>

<script>
$(document).ready(function(){

    // ===============================
    // DATATABLE
    // ===============================
    $('#data').DataTable();

    // ===============================
    // DELETE (SUDAH ADA)
    // ===============================
    $('.btn-delete').on('click', function(e){
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
            if (isConfirm) {
                form.submit();
            }
        });
    });

    // ===============================
    // CREATE
    // ===============================
    $('.form-create').on('submit', function(e){
        e.preventDefault();

        var form = this;

        swal({
            title: "Simpan Data?",
            text: "Data bidang akan ditambahkan",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            confirmButtonText: "Ya, Simpan!",
            cancelButtonText: "Batal"
        }, function(isConfirm){
            if (isConfirm) {
                form.submit();
            }
        });
    });

    // ===============================
    // EDIT
    // ===============================
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
            if (isConfirm) {
                form.submit();
            }
        });
    });

});
</script>
@endpush