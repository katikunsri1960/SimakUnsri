@extends('layouts.prodi')
@section('title')
Kelas Penjadwalan
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Kelas dan Penjadwalan</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Akademik</li>
                        <li class="breadcrumb-item active" aria-current="page">Kelas dan Penjadwalan</li>
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
                    <div class="d-flex">
                        <h3>Daftar Kelas Mata Kuliah</h3>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">KODE MATA KULIAH</th>
                                    <th class="text-center align-middle">NAMA MATA KULIAH</th>
                                    <th class="text-center align-middle">JUMLAH KELAS</th>
                                    <th class="text-center align-middle">PERIODE PERKULIAHAN</th>
                                    <th class="text-center align-middle">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $row=0;
                                @endphp
                                @foreach($data as $d)
                                    <tr>
                                        <td class="text-center align-middle">{{$row = $row + 1}}</td>
                                        <td class="text-center align-middle">{{$d->kode_mata_kuliah}}</td>
                                        <td class="text-center align-middle">{{$d->nama_mata_kuliah}}</td>
                                        <td class="text-center align-middle">{{$d->jumlah_kelas_kuliah}}</td>
                                        <td class="text-center align-middle">{{$d->nama_semester}}</td>
                                        <td class="text-center align-middle">
                                            <a type="button" class="btn btn-success btn-rounded waves-effect waves-light" href="{{route('prodi.data-akademik.kelas-penjadwalan.detail', ['id_matkul' => $d->id_matkul])}}" title="Lihat Detail"><i class="fa fa-search"></i></a>
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
<script>
    $(function() {
        "use strict";
        
        $('#data').DataTable();
    });    
</script>
@endpush
