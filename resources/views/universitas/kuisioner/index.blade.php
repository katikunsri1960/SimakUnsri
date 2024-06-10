@extends('layouts.universitas')
@section('title')
Questionnaire
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Master Kuisioner</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Kuisioner</li>
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
                        data-bs-target="#createModal"><i class="fa fa-plus"></i> Tambah Pertanyaan</button>
                    </div>
                </div>
                @include('universitas.kuisioner.create')
                @include('universitas.kuisioner.edit')
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">Indonesia</th>
                                <th class="text-center align-middle">Inggris</th>
                                <th class="text-center align-middle">AKSI</th>
                             </tr>
                          </thead>
                          <tbody>
                            @php
                                $row = 0;
                            @endphp
                            @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$row = $row + 1}}</td>
                                    <td class="text-start align-middle">{{$d->question_indonesia}}</td>
                                    <td class="text-start align-middle">{{$d->question_english}}</td>
                                    <td class="text-center align-middle">
                                        <button class="btn btn-rounded bg-warning" title="Edit Data" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editRuang({{$d}}, {{$d->id}})">
                                            <i class="fa fa-pencil-square-o"><span class="path1"></span><span class="path2"></span></i>
                                        </button>
                                        <button type="button" class="btn btn-rounded bg-danger my-2" title="Delete Data" onclick="deleteRuang({{$d->id}})">
                                            <i class="fa fa-trash"><span class="path1"></span><span class="path2"></span></i>
                                        </button>
                                        <form action="{{route('univ.kuisioner.delete', $d->id)}}" method="POST" id="delete-form-{{$d->id}}">
                                            @csrf
                                            @method('delete')
                                            {{-- <button type="submit" class="btn btn-rounded bg-danger" title="Delete Data">
                                                <i class="fa fa-trash"><span class="path1"></span><span class="path2"></span></i>
                                            </button> --}}
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
     $(function() {
        "use strict";

        $('#data').DataTable();
    });

    function editRuang(data, id) {
        document.getElementById('edit_question_indonesia').value = data.question_indonesia;
        document.getElementById('edit_question_english').value = data.question_english;
        // Populate other fields...
        document.getElementById('editForm').action = '/universitas/kuisioner/update/' + id;
    }

    function deleteRuang(id) {
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
