@extends('layouts.prodi')
@section('title')
Mahasiswa Prodi
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Master Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Mahasiswa</li>
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
                <div class="box-header with-border d-flex justify-content-between">
                    <div class="d-flex justify-content-start">
                        <!-- Modal trigger button -->
                        <button type="button" class="btn btn-secondary waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#filter-button">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                        @include('prodi.data-master.mahasiswa.filter')
                    </div>
                    <div class="d-flex justify-content-end">
                        <span class="divider-line mx-1"></span>
                        <button class="btn btn-success waves-effect waves-light" href="#" data-bs-toggle="modal" data-bs-target="#setAngkatanModal"><i class="fa fa-plus"></i>
                            Set Kurikulum Angkatan</button>
                        @include('prodi.data-master.mahasiswa.set-angkatan')
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100 table-bordered" style="font-size: 11px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">FOTO</th>
                                    <th class="text-center align-middle">AKT</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">NAMA</th>
                                    <th class="text-center align-middle">KURIKULUM</th>
                                    <th class="text-center align-middle">DOSEN P.A.</th>
                                    <th class="text-center align-middle">STATUS</th>
                                    <th class="text-center align-middle">STATUS<br>PEMBAYARAN</th>
                                    <th class="text-center align-middle">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center align-middle"></td>
                                    <td class="text-center align-middle">
                                        {{$d->angkatan}}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{$d->nim}}
                                    </td>
                                    <td class="text-start align-middle">
                                        {{$d->nama_mahasiswa}}
                                    </td>
                                    <td class="text-start align-middle" style="width: 15%">
                                        @if ($d->kurikulum)
                                            {{$d->kurikulum->nama_kurikulum}}
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">

                                    </td>
                                    <td class="text-center align-middle">
                                        {{$d->keterangan_keluar ?? 'Aktif'}}
                                    </td>
                                    <td class="text-center align-middle">

                                    </td>
                                    <td class="text-center align-middle">

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
