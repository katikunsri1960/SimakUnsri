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
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Mahasiswa</li>
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
                        <table id="data" class="table table-hover margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">FOTO</th>
                                <th class="text-center align-middle">NIM</th>
                                <th class="text-center align-middle">NAMA</th>
                                <th class="text-center align-middle">DOSEN P.A.</th>
                                <th class="text-center align-middle">STATUS</th>
                                <th class="text-center align-middle">STATUS PEMBAYARAN</th>
                                <th class="text-center align-middle">AKSI</th>
                             </tr>
                          </thead>
                          <tbody>

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