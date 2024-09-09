@extends('layouts.universitas')
@section('title')
Batas Isi KRS Manual
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Master Batas Isi KRS Manual</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Batas Isi KRS Manual</li>
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
                        <button class="btn btn-success waves-effect waves-light" data-bs-toggle="modal"
                        data-bs-target="#createModal"><i class="fa fa-plus"></i> Tambah Data</button>
                    </div>
                </div>
                @include('universitas.batas-isi-krs-manual.create')
                @include('universitas.batas-isi-krs-manual.edit')
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th class="text-center align-middle">No</th>
                                {{-- <th class="text-center align-middle">Semester</th> --}}
                                <th class="text-center align-middle">NIM</th>
                                <th class="text-center align-middle">Nama Mahasiswa</th>
                                <th class="text-center align-middle">Batas Isi KRS</th>
                                <th class="text-center align-middle">Status Bayar</th>
                                <th class="text-center align-middle">Terakhir Update</th>
                                <th class="text-center align-middle">Action</th>
                             </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $d)
                            <tr>
                                <td class="text-center align-middle">{{$loop->iteration}}</td>
                                {{-- <td class="text-center align-middle">{{$d->semester->nama_semester}}</td> --}}
                                <td class="text-center align-middle">{{$d->nim}}</td>
                                <td class="text-start align-middle">{{$d->riwayat->nama_mahasiswa}}</td>
                                <td class="text-center align-middle">{{date('d-m-Y', strtotime($d->batas_isi_krs))}}</td>
                                <td class="text-center align-middle">
                                    @php
                                        switch ($d->status_bayar) {
                                            case 0:
                                                $text = 'warning';
                                                break;
                                            case 1:
                                                $text = 'success';
                                                break;
                                            case 2:
                                                $text = 'primary';
                                                break;
                                            default:
                                                $text = 'danger';
                                                break;
                                        }
                                    @endphp
                                    <span class="badge bg-{{$text}}">
                                        {{$d->status_text}}
                                    </span>
                                </td>
                                <td class="text-center align-middle">{{$d->terakhir_update}}</td>
                                <td class="text-center align-middle">
                                    {{-- <button class="btn btn-rounded bg-warning" title="Edit Data" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editBatas({{$d}}, {{$d->id}})">
                                        <i class="fa fa-pencil-square-o"><span class="path1"></span><span class="path2"></span></i>
                                    </button> --}}
                                    <form action="{{route('univ.batas-isi-krs-manual.delete', $d->id)}}" method="POST" id="delete-form-{{$d->id}}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-rounded bg-danger" title="Delete Data">
                                            <i class="fa fa-trash"><span class="path1"></span><span class="path2"></span></i>
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
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>
     $(function() {
        "use strict";

        $('#data').DataTable();

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
    });

    function editBatas(data, id) {
        document.getElementById('edit_status').value = data.status;
        // Populate other fields...
        document.getElementById('editForm').action = '/universitas/krs-manual/update/' + id;
    }

    function deleteKRSManual(id) {
        swal({
            title: 'Delete Data',
            text: "Apakah anda yakin ingin menghapus data?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                document.getElementById('delete-form-' + id).submit();
                $('#spinner').show();
            }
        });
    }

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

    $('#editForm').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Edit Data',
            text: "Apakah anda yakin ingin merubah data?",
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


</script>
@endpush
