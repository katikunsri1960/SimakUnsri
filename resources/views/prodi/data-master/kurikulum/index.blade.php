@extends('layouts.prodi')
@section('title')
Kurikulum
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Master Kurikulum</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Kurikulum</li>
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
                        <span class="badge badge-info">Silahkan menghubungi Admin Tingkat Universitas untuk mengaktifkan kurikulum!!</span>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th rowspan="2" class="text-center align-middle">No</th>
                                <th rowspan="2" class="text-center align-middle">NAMA</th>
                                <th rowspan="2" class="text-center align-middle">MULAI BERLAKU</th>
                                <th colspan="3" class="text-center align-middle">ATURAN JUMLAH SKS</th>
                                <th colspan="2" class="text-center align-middle">JUMLAH SKS MK</th>
                             </tr>
                             <tr>
                                <th class="text-center align-middle">LULUS</th>
                                <th class="text-center align-middle">WAJIB</th>
                                <th class="text-center align-middle">PILIHAN</th>
                                <th class="text-center align-middle">WAJIB</th>
                                <th class="text-center align-middle">PILIHAN</th>
                             </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $d)
                            <tr>
                                <td class="text-center align-middle">
                                    <span class="badge badge-success">{{$d->status_sync}}</span>
                                </td>
                                <td class="text-start align-middle">
                                    <a href="{{route('prodi.data-master.kurikulum.detail', $d)}}">{{$d->nama_kurikulum}}</a>
                                </td>
                                <td class="text-center align-middle">{{$d->semester_mulai_berlaku}}</td>
                                <td class="text-center align-middle">{{$d->jumlah_sks_lulus}}</td>
                                <td class="text-center align-middle">{{$d->jumlah_sks_wajib}}</td>
                                <td class="text-center align-middle">{{$d->jumlah_sks_pilihan}}</td>
                                <td class="text-center align-middle">{{$d->jumlah_sks_mata_kuliah_wajib}}</td>
                                <td class="text-center align-middle">{{$d->jumlah_sks_mata_kuliah_pilihan}}</td>
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
