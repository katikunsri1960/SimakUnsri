@extends('layouts.prodi')
@section('title')
Kelas Penjadwalan
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Kelas dan Penjadwalan</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Akademik</li>
                        <li class="breadcrumb-item active" aria-current="page">Kelas dan Penjadwalan</li>
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
                <div class="box-header with-border">
                    <div class="d-flex">
                        <h3>Daftar Kelas Mata Kuliah</h3>
                    </div>
                    <div class="pull-right">
                        <p class="mb-0 text-fade fs-18">Semester - {{$semester_aktif->semester->nama_semester}}</p>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100" style="text-size: 11px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">KURIKULUM</th>
                                    <th class="text-center align-middle">KODE MATA KULIAH</th>
                                    <th class="text-center align-middle">NAMA MATA KULIAH</th>
                                    <th class="text-center align-middle">JUMLAH KELAS</th>
                                    <th class="text-center align-middle">SEMESTER MATA KULIAH</th>
                                    <th class="text-center align-middle">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $row=0;
                                @endphp
                                @foreach ($data as $k)
                                    @if ($k->mata_kuliah)
                                        @foreach($k->mata_kuliah as $d)
                                            @if(!$d->matkul_konversi)
                                            <tr>
                                                <td class="text-center align-middle">{{$row = $row + 1}}</td>
                                                <td class="text-start align-middle">{{$k->nama_kurikulum}}</td>
                                                <td class="text-center align-middle">{{$d->kode_mata_kuliah}}</td>
                                                <td class="text-start align-middle">{{$d->nama_mata_kuliah}}</td>
                                                <td class="text-center align-middle">
                                                    @php
                                                        echo count($d->kelas_kuliah);
                                                    @endphp
                                                </td>
                                                <td class="text-center align-middle">{{$k->semester_mulai_berlaku}}</td>
                                                <td class="text-center align-middle">
                                                    <a type="button" class="btn btn-success btn-rounded waves-effect waves-light" href="{{route('prodi.data-akademik.kelas-penjadwalan.detail', ['id_matkul' => $d->id_matkul])}}" title="Lihat Detail"><i class="fa fa-search"></i></a>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    @endif
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

        var table = $('#data').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
            "scrollCollapse": true,
            "scrollY": "450px",
            "order": [[ 4, "desc" ]]
        });

        table.on( 'order.dt search.dt', function () {
            table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });
</script>
@endpush
