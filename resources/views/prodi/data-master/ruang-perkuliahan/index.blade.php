@extends('layouts.prodi')
@section('title')
Ruang Perkuliahan
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Master Ruang Perkuliahan</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Ruang Perkuliahan</li>
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
                        {{-- <form action="{{route('univ.mata-kuliah.sync')}}" method="get" id="sync-form">
                            <button class="btn btn-primary waves-effect waves-light" type="submit"><i class="fa fa-refresh"></i> Sinkronisasi</button>
                        </form> --}}
                        <span class="divider-line mx-1"></span>
                        <button class="btn btn-success waves-effect waves-light" data-bs-toggle="modal"
                        data-bs-target="#tambahRuangKuliah"><i class="fa fa-plus"></i> Tambah Ruang Kuliah</button>
                    </div>
                </div>
                @include('prodi.data-master.ruang-perkuliahan.create')

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">NAMA RUANGAN</th>
                                <th class="text-center align-middle">LOKASI</th>
                                <th class="text-center align-middle">AKSI</th>
                             </tr>
                          </thead>
                          <tbody>
                            <tr>
                                <td class="text-center align-middle">1</td>
                                <td class="text-center align-middle">Customer Support</td>
                                <td class="text-center align-middle">New York</td>
                                <td class="text-center align-middle">
                                    <a class="btn btn-rounded bg-warning" href="{{route('dosen.perkuliahan.kesediaan-waktu-bimbingan')}}" title="Edit Data"><i class="fa fa-pencil-square-o"><span class="path1"></span><span class="path2"></span></i></a>
                                    <a class="btn btn-rounded bg-danger" href="{{route('dosen.perkuliahan.kesediaan-waktu-bimbingan')}}" title="Delete Data"><i class="fa fa-trash"><span class="path1"></span><span class="path2"></span></i></a>
                                </td>
                            </tr>
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
