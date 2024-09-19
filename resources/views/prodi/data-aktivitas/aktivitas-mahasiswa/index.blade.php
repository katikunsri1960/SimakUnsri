@extends('layouts.prodi')
@section('title')
Aktivitas Mahasiswa
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Konversi Aktivitas Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Konversi Aktivitas Mahasiswa</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<section class="content">
    @include('swal')
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
                    <div class="d-flex justify-content-end">
                       <div class="btn-group">
                            <a class="btn btn-success waves-effect waves-light" href="{{route('prodi.data-aktivitas.aktivitas-mahasiswa.create')}}"><i class="fa fa-plus"><span class="path1"></span><span class="path2"></span></i> Tambah Konversi Aktivitas</a>
                        </div>   
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    {{-- <th class="text-center align-middle">Program Studi</th> --}}
                                    <th class="text-center align-middle">Kurikulum</th>
                                    <th class="text-center align-middle">Aktivitas</th>
                                    <th class="text-center align-middle">Kode Mata Kuliah</th>
                                    <th class="text-center align-middle">Nama Mata Kuliah</th>
                                    <th class="text-center align-middle">SKS</th>
                                    <th class="text-center align-middle">Semester</th>
                                    <th class="text-center align-middle">Penilaian Langsung</th>
                                    <th class="text-center align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no_a=1;
                                @endphp

                                @foreach ($data as $d)
                                    <tr>
                                        <td class="text-center align-middle">{{ $no_a++ }}</td>
                                        {{-- <td class="text-start align-middle" style="white-space: nowrap;">{{ $d->nama_program_studi}}</td> --}}
                                        <td class="text-start align-middle">{{ $d->nama_kurikulum }}</td>
                                        <td class="text-start align-middle">{{ $d->nama_jenis_aktivitas }}</td>
                                        <td class="text-start align-middle" style="white-space: nowrap;">{{ $d->kode_mata_kuliah }}</td>
                                        <td class="text-start align-middle" style="white-space: nowrap;">{{ $d->nama_mata_kuliah }}</td>
                                        <td class="text-center align-middle" style="width:3%">{{ $d->sks_mata_kuliah }}</td>
                                        <td class="text-center align-middle" style="width:3%">{{ $d->semester }}</td>
                                        <td class="text-center align-middle" style="width:3%">{{ $d->penilaian_langsung == 0 ? 'Tidak' : 'Ya' }}</td>
                                        <td class="text-center align-middle" style="width:3%">
                                            <form action="{{ route('prodi.data-aktivitas.aktivitas-mahasiswa.ubah', ['rencana_ajar' => $d->id]) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('GET')
                                                <button type="submit" class="btn btn-warning edit-button mb-5">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('prodi.data-aktivitas.aktivitas-mahasiswa.delete', $d->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger delete-button">
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
    $(function() {
        "use strict";
        
        $('#data').DataTable();
    });

    $(document).ready(function(){
        $('.delete-button').click(function(e){
            e.preventDefault();
            var form = $(this).closest('form');
            swal({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }, function(isConfirmed){
                if (isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
