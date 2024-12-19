@extends('layouts.prodi')
@section('title')
Ruang Perkuliahan
@endsection
@section('content')
@include('swal')
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
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">LOKASI RUANG</th>
                                <th class="text-center align-middle">NAMA RUANG</th>
                                <th class="text-center align-middle">KAPASITAS RUANG</th>
                             </tr>
                          </thead>
                          <tbody>
                            @php
                                $row = 0;
                            @endphp
                            @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$row = $row + 1}}</td>
                                    <td class="text-center align-middle">{{$d->lokasi}}</td>
                                    <td class="text-center align-middle">{{$d->nama_ruang}}</td>
                                    <td class="text-center align-middle">{{$d->kapasitas_ruang}}</td>
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
<script>
     $(function() {
        "use strict";

        $('#data').DataTable();
    });
</script>
@endpush
