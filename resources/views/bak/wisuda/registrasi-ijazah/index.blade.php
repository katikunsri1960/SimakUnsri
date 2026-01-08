@extends('layouts.bak')
@section('title')
Daftar Registrasi Ijazah
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
                            <h2>Daftar Registrasi Ijazah</h2>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border d-flex justify-content-between">
                    <div class="d-flex justify-content-start">
                        <form action="{{route('bak.wisuda.registrasi-ijazah.index')}}" method="get" id="semesterForm">
                            <select name="id_semester" id="id_semester" class="form-select"
                                onchange="document.getElementById('semesterForm').submit();">
                                <option value="">-- Pilih Semester --</option>
                                @foreach ($semester as $s)
                                <option value="{{$s->id_semester}}" {{ request('id_semester') == $s->id_semester ? 'selected' : '' }}>{{$s->nama_semester}}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-success waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#createModal"><i class="fa fa-plus"></i> Tambah Data</button>
                        <span class="divider-line mx-1"></span>
                        <!-- Modal trigger button -->
                        <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#uploadModal">
                            <i class="fa fa-upload me-2"></i>Upload Data
                        </button>
                    </div>
                </div>
                @include('bak.wisuda.registrasi-ijazah.create')
                @include('bak.wisuda.registrasi-ijazah.edit')
                @include('bak.wisuda.registrasi-ijazah.upload')
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Semester</th>
                                    <th class="text-center align-middle">Periode Wisuda</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">Nama Mahasiswa</th>
                                    <th class="text-center align-middle">Status Mahasiswa</th>
                                    <th class="text-center align-middle">PISN Mahasiswa</th>
                                    <th class="text-center align-middle">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center align-middle">{{$d->semester->nama_semester}}</td>
                                    <td class="text-center align-middle">{{$d->periode_wisuda}}</td>
                                    <td class="text-center align-middle">{{$d->nim}}</td>
                                    <td class="text-start align-middle">{{$d->lulus_do ? $d->lulus_do->nama_mahasiswa :
                                        '-'}}</td>
                                    <td class="text-center align-middle">{{$d->lulus_do ? $d->lulus_do->nama_jenis_keluar :
                                        'Aktif'}}</td>
                                    <td class="text-center align-middle">{{$d->penomoran_ijazah_nasional ? $d->penomoran_ijazah_nasional : '-'}}</td>
                                    <td class="text-center align-middle">
                                        {{-- <button class="btn btn-rounded bg-warning" title="Edit Data"
                                            data-bs-toggle="modal" data-bs-target="#editModal"
                                            onclick="editPISN({{$d}}, {{$d->id}})">
                                            <i class="fa fa-pencil-square-o"><span class="path1"></span><span
                                                    class="path2"></span></i>
                                        </button> --}}
                                        <button type="button" class="btn btn-rounded bg-danger my-2" title="Delete Data"
                                            onclick="deletePISN({{$d->id}})">
                                            <i class="fa fa-trash"><span class="path1"></span><span
                                                    class="path2"></span></i>
                                        </button>
                                        <form action="{{route('bak.wisuda.registrasi-ijazah.delete', $d->id)}}" method="POST"
                                            id="delete-form-{{$d->id}}">
                                            @csrf
                                            @method('delete')
                                            {{-- <button type="submit" class="btn btn-rounded bg-danger"
                                                title="Delete Data">
                                                <i class="fa fa-trash"><span class="path1"></span><span
                                                        class="path2"></span></i>
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
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/cleave.min.js')}}"></script>
<script>
    $(function() {
        // "use strict";
        $('#data').DataTable();

        $("#id_registrasi_mahasiswa").select2({
            placeholder : '-- Masukan NIM / Nama Mahasiswa --',
            dropdownParent: $('#createModal'),
            width: '100%',
            minimumInputLength: 3,
            ajax: {
                url: "{{route('bak.wisuda.registrasi-ijazah.get-mahasiswa')}}",
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

    function editPISN(data, id) {
        document.getElementById('edit_status').value = data.status;
        // Populate other fields...
        document.getElementById('editForm').action = '/bak/wisuda/registrasi-ijazah/update/' + id;
    }

    function deletePISN(id) {
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

    $('#uploadForm').submit(function(e){
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
                $('#uploadForm').unbind('submit').submit();
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