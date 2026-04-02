@extends('layouts.prodi')
@section('title')
Capaian Pembelajaran Lulusan Kurikulum
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
                        <button class="btn btn-success waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#kampusMerdeka"><i class="fa fa-plus"></i> Tambah CPL</button>
                    </div>
                </div>
                @include('prodi.data-master.capaian-pembelajaran.create')
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th width="2%" class="text-center align-middle">No</th>
                                <th width="25%" class="text-center align-middle">NAMA KURIKULUM</th>
                                <th width="65%" class="text-center align-middle">NAMA CPL</th>
                                <th width="8%" class="text-center align-middle">AKSI</th>
                             </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $d)
                            <tr>
                                <td class="text-center align-middle">{{$loop->iteration}}</td>
                                <td class="text-center align-middle">{{$d->kurikulum->nama_kurikulum}}</td>
                                <td class="text-center align-middle">{{$d->nama_cpl}}</td>
                                <td class="text-center align-middle">
                                    <form action="{{route('prodi.data-master.cpl.delete', $d->id)}}" method="post" class="delete-form" data-id="{{$d->id}}" id="deleteForm{{$d->id}}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger" title="Hapus Data"><i class="fa fa-trash"></i></button>
                                    </form>
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
