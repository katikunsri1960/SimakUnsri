@extends('layouts.prodi')
@section('title')
Monitoring Entry Nilai Dosen
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Monitoring Entry Nilai Dosen</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Monitoring</li>
                        <li class="breadcrumb-item active" aria-current="page">Monitoring Entry Nilai</li>
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
                    {{-- <div class="d-flex justify-content-end">
                        <form action="{{route('univ.mata-kuliah.sync')}}" method="get" id="sync-form">
                            <button class="btn btn-primary waves-effect waves-light" type="submit"><i class="fa fa-refresh"></i> Sinkronisasi</button>
                        </form>
                        <span class="divider-line mx-1"></span>
                        <button class="btn btn-success waves-effect waves-light" href="#"><i class="fa fa-plus"></i> Tambah Kurikulum</button>
                    </div> --}}
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="data-monitoring">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">NIDN</th>
                                    <th class="text-center align-middle">NAMA DOSEN</th>
                                    <th class="text-center align-middle">TOTAL KELAS AJAR</th>
                                    <th class="text-center align-middle">SUDAH DINILAI</th>
                                    <th class="text-center align-middle">BELUM DINILAI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center align-middle">{{$d->nidn}}</td>
                                    <td class="text-start align-middle">{{$d->nama_dosen}}</td>
                                    <td class="text-center align-middle">
                                        <a href="{{route('prodi.monitoring.entry-nilai.detail', ['mode' => 1, 'dosen' => $d->id_dosen])}}">{{$d->total_kelas}}</a>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{route('prodi.monitoring.entry-nilai.detail', ['mode' => 2, 'dosen' => $d->id_dosen])}}">{{$d->total_kelas_dinilai}}</a>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{route('prodi.monitoring.entry-nilai.detail', ['mode' => 3, 'dosen' => $d->id_dosen])}}">{{$d->total_kelas_belum_dinilai}}</a>
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

        $('#data-monitoring').DataTable();
    });
</script>
@endpush
