@extends('layouts.universitas')
@section('title')
List Mahasiswa Aktif
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">List Mahasiswa Aktif</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Monitoring</li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('univ.monitoring.pengisian-krs')}}">Pengisian KRS</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List Mahasiswa Aktif</li>
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
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <td>Fakultas</td>
                                    <td>:</td>
                                    <td>{{$prodi->fakultas->nama_fakultas}}</td>
                                </tr>
                                <tr>
                                    <td>Program Studi</td>
                                    <td>:</td>
                                    <td>{{$prodi->nama_jenjang_pendidikan}} - {{$prodi->nama_program_studi}} ({{$prodi->kode_program_studi}})</td>
                                </tr>
                            </table>
                        </div>

                    </div>
                    <div class="table-responsive">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">Angkatan</th>
                                <th class="text-center align-middle">NIM</th>
                                <th class="text-center align-middle">Nama</th>
                             </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center align-middle">{{$d->angkatan}}</td>
                                    <td class="text-center align-middle">{{$d->nim}}</td>
                                    <td class="text-start align-middle">{{$d->nama_mahasiswa}}</td>
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
