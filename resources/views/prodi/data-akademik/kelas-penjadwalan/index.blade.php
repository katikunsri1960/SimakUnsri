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
                        <form action="{{ route('prodi.data-akademik.kelas-penjadwalan') }}" method="get" id="semesterForm">

                        {{-- <p class="mb-0 text-fade fs-18">Semester - </p> --}}
                        <div class="mb-3">
                            <label for="semester_view" class="form-label">Semester</label>
                            <select
                                class="form-select"
                                name="semester_view"
                                id="semester_view"
                                onchange="document.getElementById('semesterForm').submit();"
                            >
                                <option value="" selected disabled>-- Pilih Semester --</option>
                                @foreach ($pilihan_semester as $p)
                                    <option value="{{$p->id_semester}}"
                                        @if ($semester_view != null)
                                        {{$semester_view == $p->id_semester ? 'selected' : ''}}
                                        @else
                                        {{$semester_aktif->id_semester == $p->id_semester ? 'selected' : ''}}
                                        @endif
                                        >{{$p->nama_semester}}</option>
                                @endforeach
                            </select>
                        </div>
                        </form>
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
                                    <th class="text-center align-middle">QUISIONER MATKUL</th>
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
                                                <td class="text-center align-middle">
                                                    <a href="{{route('prodi.data-akademik.kelas-penjadwalan.kuisioner-matkul', ['id_matkul' => $d->id_matkul, 'semester'=>$semester_pilih])}}" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"> Lihat <i class="fa fa-circle-question"></i></a>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <a type="button" class="btn btn-success btn-rounded waves-effect waves-light" href="{{route('prodi.data-akademik.kelas-penjadwalan.detail', ['id_matkul' => $d->id_matkul, 'semester' => $semester_view ?? $semester_aktif->id_semester])}}" title="Lihat Detail"><i class="fa fa-search"></i></a>
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
            "stateSave": true,
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
