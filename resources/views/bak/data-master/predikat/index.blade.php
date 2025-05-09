@extends('layouts.bak')
@section('title')
Predikat Lulusan
@endsection
@section('content')
@include('swal')
@include('bak.data-master.predikat.edit')
@include('bak.data-master.predikat.create')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Predikat Lulusan</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a>
                        </li>
                        <li class="breadcrumb-item">Data Master</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Predikat Lulusan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-body">
                    <button type="button" class="btn btn-primary mx-1"
                    data-bs-toggle="modal" data-bs-target="#createModal"
                >
                    <i class="fa fa-plus me-2"></i> Tambah Data
                </button>
                    <div class="table-responsive">
                        <table id="data" class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Bahasa Indonesia</th>
                                    <th class="text-center align-middle">Bahasa Inggris</th>
                                    <th class="text-center align-middle">ACT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-start align-middle">{{$d->indonesia}}</td>
                                    <td class="text-start align-middle">{{$d->inggris}}</td>
                                    <td class="text-center align-middle">
                                        <div class="px-3">

                                            <button type="button" class="btn btn-primary btn-sm mx-1"
                                                data-bs-toggle="modal" data-bs-target="#editModal"
                                                onclick="edit({{$d}})">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <form action="{{ route('bak.data-master.predikat.delete', $d->id) }}"
                                                method="post" class="d-inline delete-form" id="deleteForm{{ $d->id }}"
                                                data-id="{{ $d->id }}">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger btn-sm mx-1">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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
<script src="{{asset('assets/js/confirmSwal.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#data').DataTable({
            paging: false,
        });

        confirmSubmit('editForm');
        confirmSubmit('createForm');
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
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $(`#deleteForm${formId}`).unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });

    function edit(data) {
        document.getElementById('editForm').reset();

        document.getElementById('edit_indonesia').value = data.indonesia;
        document.getElementById('edit_inggris').value = data.inggris;

        document.getElementById('editForm').action = '/bak/data-master/predikat/' + data.id

    }




</script>
@endpush
