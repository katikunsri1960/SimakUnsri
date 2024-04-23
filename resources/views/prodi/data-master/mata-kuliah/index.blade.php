@extends('layouts.prodi')
@section('title')
Mata Kuliah
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Master Mata Kuliah</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Mata Kuliah</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>
@include('swal')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                {{-- <div class="box-header with-border">

                </div> --}}
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">KODE MK</th>
                                    <th class="text-center align-middle">NAMA MK</th>
                                    {{-- <th class="text-center align-middle">NAMA MK (ENGLISH)</th> --}}
                                    <th class="text-center align-middle">SKS</th>
                                    <th class="text-center align-middle">PRASYARAT</th>
                                    <th class="text-center align-middle">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center align-middle">{{$d->kode_mata_kuliah}}</td>
                                    <td class="text-start align-middle">{{$d->nama_mata_kuliah}}</td>
                                    {{-- <td class="text-center align-middle">{{$d->nama_mata_kuliah_english}}</td> --}}
                                    <td class="text-center align-middle">{{$d->sks_mata_kuliah}}</td>
                                    <td class="text-start align-middle">
                                        @if ($d->prasyarat_matkul)
                                        <ul>
                                            @foreach ($d->prasyarat_matkul as $item)
                                            <li>{{$item->matkul_prasyarat->kode_mata_kuliah}} - {{$item->matkul_prasyarat->nama_mata_kuliah}}</li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <a class="btn btn-primary" href="{{route('prodi.data-master.mata-kuliah.tambah-prasyarat', ['matkul' => $d])}}">
                                            <i class="fa fa-plus"></i>Prasyarat
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
