@extends('layouts.prodi')
@section('title')
Anggota Pembimbingan Akademik
@endsection
@section('content')
@push('header')
<div class="mx-4">
    <a href="{{route('prodi.data-aktivitas.aktivitas-pa')}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Aktivitas Pembimbingan Akademik</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Aktivitas</li>
                        <li class="breadcrumb-item" aria-current="page">Aktivitas PA</li>
                        <li class="breadcrumb-item active" aria-current="page">Anggota PA</li>
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
                    <table>
                        <tr>
                            <td>NIDN</td>
                            <td style="width: 30px" class="text-center">:</td>
                            <td>{{$bimbingan->nidn}}</td>
                        </tr>
                        <tr>
                            <td>Nama Dosen</td>
                            <td class="text-center">:</td>
                            <td>{{$bimbingan->nama_dosen}}</td>
                        </tr>
                        <tr>
                            <td>No SK</td>
                            <td class="text-center">:</td>
                            <td>{{$aktivitas->sk_tugas}}</td>
                        </tr>
                        <tr>
                            <td>Tanggal SK Tugas</td>
                            <td class="text-center">:</td>
                            <td>{{$aktivitas->tanggal_sk_tugas}}</td>
                        </tr>
                    </table>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">NIM</th>
                                <th class="text-center align-middle">Nama</th>
                             </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $d)
                            <tr>
                                <td class="text-center align-middle">{{$loop->iteration}}</td>
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

        $('#data').DataTable({
            "stateSave": true,
        });
    });
</script>
@endpush
