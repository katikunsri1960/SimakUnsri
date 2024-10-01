@extends('layouts.fakultas')
@section('title')
Pejabat Fakultas
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Daftar Pejabat Fakultas</h3>
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
                @include('fakultas.data-master.pejabat-fakultas.create')
                {{-- @include('fakultas.data-master.pejabat-fakultas.edit') --}}
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">JABATAN</th>
                                <th class="text-center align-middle">NAMA</th>
                                <th class="text-center align-middle">NIDN</th>
                                <th class="text-center align-middle">GELAR DEPAN</th>
                                <th class="text-center align-middle">GELAR BELAKANG</th>
                                <th class="text-center align-middle">PERIODE MENJABAT</th>
                                <th class="text-center align-middle">ACTION</th>
                             </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $d)
                            <tr>
                                <td class="text-center align-middle">{{$loop->iteration}}</td>
                                <td class="text-center align-middle">{{$d->nama_jabatan}}</td>
                                <td class="text-start align-middle">{{$d->nama_dosen}}</td>
                                <td class="text-start align-middle">{{$d->nidn}}</td>
                                <td class="text-center align-middle">{{$d->gelar_depan}}</td>
                                <td class="text-center align-middle">{{$d->gelar_belakang}}</td>
                                <td class="text-center align-middle">
                                    {{date('d M Y', strtotime($d->tgl_mulai_jabatan))}} - {{date('d M Y', strtotime($d->tgl_selesai_jabatan))}}
                                </td>
                                <td class="text-center align-middle">
                                    {{-- <a href="{{ route('fakultas.data-master.pejabat-fakultas.edit', $d->id) }}" class="btn btn-rounded bg-warning" title="Edit Data">
                                        <i class="fa fa-pencil"></i>
                                    </a>--}}
                                    <form action="{{route('fakultas.data-master.pejabat-fakultas.delete', $d->id)}}" method="POST" id="delete-form-{{$d->id}}">
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
    $(document).ready(function(){
        // Initialize Select2 for the first select element
        var initialSelect = initializeSelect2($('#id_dosen'));

        function initializeSelect2(selectElement) {
            return selectElement.select2({
                placeholder : '-- Pilih Dosen --',
                minimumInputLength: 3,
                width: 'resolve', // Auto width
                ajax: {
                    url: "{{route('fakultas.data-master.pejabat-fakultas.get-dosen')}}",
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
                                    text: item.nama_dosen + " ( " + item.nama_program_studi + " )",
                                    id: item.id_registrasi_dosen
                                }
                            })
                        };
                    },
                }
            });
        }
    });
     $(function() {
        "use strict";

        $('#data').DataTable();

        $("#id_dosen").select2({
            placeholder : '-- Masukan NIDN / Nama Dosen --',
            dropdownParent: $('#createModal'),
            width: '100%',
            minimumInputLength: 3,
            ajax: {
                url: "{{route('fakultas.data-master.pejabat-fakultas.get-dosen')}}",
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
                                text: "("+item.nidn+") "+item.nama_dosen,
                                id: item.id_dosen
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
        document.getElementById('editForm').action = '/fakultas/krs-manual/update/' + id;
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
