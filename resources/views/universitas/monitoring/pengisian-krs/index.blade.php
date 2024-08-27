@extends('layouts.universitas')
@section('title')
Monitoring Pengisian KRS
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Monitoring Pengisian KRS</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Monitoring</li>
                        <li class="breadcrumb-item active" aria-current="page">Pengisian KRS</li>
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
                    <div class="table-responsive">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">Nama Fakultas</th>
                                <th class="text-center align-middle">Nama Program Studi</th>
                                <th class="text-center align-middle">Jumlah Mahasiswa Aktif</th>
                                <th class="text-center align-middle">Jumlah Mahasiswa (Yang melakukan pengisian KRS)</th>
                                <th class="text-center align-middle">Jumlah Mahasiswa Sudah di Setujui</th>
                                <th class="text-center align-middle">Jumlah Mahasiswa Belum di Setujui</th>
                             </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-start align-middle">{{$d->id}} - {{$d->nama_fakultas}}</td>
                                    <td class="text-start align-middle">{{$d->nama_prodi}}</td>
                                    <td class="text-center align-middle">{{$d->jumlah_mahasiswa}}</td>
                                    <td class="text-center align-middle">{{$d->jumlah_mahasiswa_isi_krs}}</td>
                                    <td class="text-center align-middle">{{$d->jumlah_mahasiswa_approved}}</td>
                                    <td class="text-center align-middle">{{$d->jumlah_mahasiswa_not_approved}}</td>
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
