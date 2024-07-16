@extends('layouts.universitas')
@section('title')
Pengaturan Akun
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Pengaturan Akun</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Pengaturan</li>
                        <li class="breadcrumb-item active" aria-current="page">Akun</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
@include('swal')
@include('universitas.pengaturan.akun.create-fakultas')
@include('universitas.pengaturan.akun.create-prodi')
@include('universitas.pengaturan.akun.create-dosen')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-warning waves-effect waves-light" type="button" data-bs-toggle="modal"
                        data-bs-target="#createFakultas"><i class="fa fa-plus"></i> Tambah Akun Fakultas</button>
                        <span class="divider-line mx-1"></span>
                        <button class="btn btn-primary waves-effect waves-light" type="button" data-bs-toggle="modal"
                            data-bs-target="#createModal"><i class="fa fa-plus"></i> Tambah Akun Prodi</button>
                        <span class="divider-line mx-1"></span>
                        <button class="btn btn-success waves-effect waves-light" type="button" data-bs-toggle="modal"
                        data-bs-target="#createDosen"><i class="fa fa-plus"></i> Tambah Akun Dosen</button>
                        {{-- <button class="btn btn-success waves-effect waves-light" href="#"><i
                                class="fa fa-plus"></i> Tambah Kurikulum</button> --}}
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-hover margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Role</th>
                                    <th class="text-center align-middle">NAMA</th>
                                    <th class="text-center align-middle">ACT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $user)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center align-middle">{{$user->role}}</td>
                                    <td class="text-start align-middle">{{$user->name}}</td>
                                    <td class="text-center align-middle">
                                        {{-- <a href="{{route('univ.pengaturan.akun.edit', $user->id)}}"
                                            class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                        --}}
                                        <form action="{{route('univ.pengaturan.akun.delete', $user->id)}}" method="post"
                                            class="delete-form" id="deleteForm{{$user->id}}" data-id="{{$user->id}}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger"><i
                                                    class="fa fa-trash"></i></button>
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
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/js/confirmSubmit.js')}}"></script>
<script>
    $(function () {
        // "use strict";
        $('#data').DataTable();

        $('#fk_id').select2({
            placeholder: 'Pilih Salah Satu',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#createModal')
        });

        $("#fakultas_fk_id").select2({
            placeholder : '-- Pilih Fakultas --',
            dropdownParent: $('#createFakultas'),
            width: '100%',
            minimumInputLength: 3,
            ajax: {
                url: "{{route('univ.pengaturan.akun.get-fakultas')}}",
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
                                text: item.nama_fakultas,
                                id: item.id
                            }
                        })
                    };
                },
            }
        });

        $("#id_dosen_create").select2({
            placeholder : '-- Pilih Nama Dosen --',
            dropdownParent: $('#createDosen'),
            width: '100%',
            minimumInputLength: 3,
            ajax: {
                url: "{{route('univ.pengaturan.akun.get-dosen')}}",
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama_dosen + " - (" + item.nidn + ")" ,
                                id: item.id_dosen
                            }
                        })
                    };
                },
            }
        });

        confirmSubmit('dosen-refresh');
        confirmSubmit('createFakultasForm');
        // sweet alert createProdiForm
        $('#createProdiForm').submit(function(e){
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
                    $('#createProdiForm').unbind('submit').submit();
                }
            });
        });


        $('.delete-form').submit(function(e){
            e.preventDefault();
            var formId = $(this).data('id');
            swal({
                title: 'Apakah Anda Yakin?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, simpan!'
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
