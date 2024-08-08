@extends('layouts.prodi')
@section('title')
Aktivitas Mahasiswa
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Aktivitas Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Aktivitas</li>
                        <li class="breadcrumb-item active" aria-current="page">Aktivitas Organisasi</li>
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
                        {{-- <span class="divider-line mx-1"></span>
                        <button class="btn btn-success waves-effect waves-light" href="{{route('prodi.data-aktivitas.aktivitas-mahasiswa.create')}}"><i class="fa fa-plus"></i> Tambah Konversi Aktivitas</button> --}}
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
                                    <th class="text-center align-middle">NAMA PROGRAM STUDI</th>
                                    <th class="text-center align-middle">NAMA KURIKULUM</th>
                                    {{-- <th class="text-center align-middle">SEMESTER</th> --}}
                                    <th class="text-center align-middle">NAMA AKTIVITAS</th>
                                    <th class="text-center align-middle">NAMA MATA KULIAH</th>
                                    <th class="text-center align-middle">KODE MATA KULIAH</th>
                                    <th class="text-center align-middle">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no_a=1;
                                @endphp

                                @foreach ($data as $d)
                                    <tr>
                                        <td class="text-center align-middle">{{ $no_a++ }}</td>
                                        <td class="text-center align-middle">{{ $d->nama_program_studi}}</td>
                                        <td class="text-center align-middle">{{ $d->nama_kurikulum }}</td>
                                        <td class="text-center align-middle">{{ $d->nama_jenis_aktivitas }}</td>
                                        <td class="text-start align-middle" style="white-space: nowrap;">{{ $d->kode_mata_kuliah }}</td>
                                        <td class="text-start align-middle" style="white-space: nowrap;">{{ $d->nama_mata_kuliah }}</td>
                                        <td>
                                            <form action="{{ route('prodi.data-aktivitas.aktivitas-mahasiswa.delete', $d->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger delete-button">Hapus</button>
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
</script>
@endpush
