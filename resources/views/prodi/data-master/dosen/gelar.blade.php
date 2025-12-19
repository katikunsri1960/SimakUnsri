@extends('layouts.prodi')
@section('title')
Gelar Dosen
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Gelar Dosen</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Master</li>
                        <li class="breadcrumb-item" aria-current="page">Dosen</li>
                        <li class="breadcrumb-item active" aria-current="page">Gelar Dosen</li>
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
                                <th class="text-center align-middle">NAMA</th>
                                <th class="text-center align-middle">NIDK/NIDN</th>
                                <th class="text-center align-middle">NUPTK</th>
                                <th class="text-center align-middle">E-MAIL</th>
                                <th class="text-center align-middle">HOMEBASE</th>
                             </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-start align-middle">{{$d->nama_dosen}}</td>
                                    <td class="text-center align-middle">{{$d->nidn ?? '-'}}</td>
                                    <td class="text-center align-middle">{{$d->nuptk ?? '-'}}</td>
                                    <td class="text-start align-middle">{{$d->email ?? '-'}}</td>
                                    <td class="text-center align-middle">
                                        @if($d->homebase == 1)
                                            <i class="fa-regular fa-circle-check "></i>
                                        @endif
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

        $('#data').DataTable({
            "paging": false,
            "ordering": true,
            "searching": true,
            "scrollCollapse": false,
            "scrollX": true,
            "scrollY": window.innerHeight * 0.6 + "px",
        });
    });
</script>
@endpush
