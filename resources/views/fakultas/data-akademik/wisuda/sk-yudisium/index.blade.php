@extends('layouts.fakultas')
@section('title')
Daftar SK Yudisium Fakultas
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Daftar SK Yudisium Fakultas</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('fakultas')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">SK Yudisum Fakultas</li>
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
                @include('fakultas.data-akademik.wisuda.sk-yudisium.create')
                {{-- @include('fakultas.data-master.pejabat-fakultas.edit') --}}
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">NAMA FILE</th>
                                <th class="text-center align-middle">SK YUDISUM FILE</th>
                                <th class="text-center align-middle">TGL SK YUDISUM</th>
                                <th class="text-center align-middle">AKSI</th>
                             </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $d)
                            <tr>
                                <td class="text-center align-middle">{{$loop->iteration}}</td>
                                <td class="text-center align-middle">{{$d->nama_file}}</td>
                                <td class="text-center align-middle">
                                    @if($d->dir_file)
                                        <a href="{{ asset($d->dir_file) }}" target="_blank" class="btn btn-success btn-sm">
                                            <i class="fa fa-file-pdf-o"></i> Lihat File
                                        </a>
                                    @else
                                        <span class="badge badge-lg bg-danger rounded">Tidak ada file</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    {{date('d M Y', strtotime($d->tgl_mulai_jabatan))}} - {{date('d M Y', strtotime($d->tgl_selesai_jabatan))}}
                                </td>
                                <td class="text-center align-middle">
                                    <a href="#" class="btn btn-warning btn-sm my-2" data-bs-toggle="modal" data-bs-target="#editModal{{$d->id}}">
                                        <i class="fa fa-edit"></i> Ubah
                                    </a>

                                    <button type="button" class="btn btn-danger btn-sm my-2 btn-hapus-sk" data-id="{{ $d->id }}">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                    <form id="form-hapus-sk-{{ $d->id }}" action="{{ route('fakultas.wisuda.hapus-sk-yudisium', $d->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
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
    $(function(){
        $('#data').DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });

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
