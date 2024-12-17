@extends('layouts.prodi')
@section('title')
Nilai Transfer Pendidikan
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">
                Nilai Transfer Pendidikan
            </h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Akademik</li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Nilai Transfer Pendidikan
                        </li>
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
                        <table id="data" class="table table-bordered table-hover margin-top-10 w-p100"
                            style="font-size: 11px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">NO</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">NAMA</th>
                                    <th class="text-center align-middle">NAMA PROGRAM STUDI</th>
                                    <th class="text-center align-middle">STATUS MAHASISWA</th>
                                    <th class="text-center align-middle">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $row=0;
                                @endphp
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$row = $row + 1}}</td>
                                    <td class="text-center align-middle">
                                        {{$d->nim}}
                                    </td>
                                    <td class="text-start align-middle" style="width: 15%">
                                        {{$d->nama_mahasiswa}}
                                    </td>
                                    <td class="text-center align-middle">{{$d->nama_program_studi}}</td>
                                    <td class="text-center align-middle">
                                        {{!$d->id_jenis_keluar ? 'Aktif' : '-'}}
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="row d-flex justify-content-center">
                                            <a href="{{ route('prodi.data-akademik.nilai-transfer-rpl.input', ['id_reg' => $d->id_registrasi_mahasiswa]) }}" class="btn btn-success btn-sm my-2" title="Nilai Transfer">
                                                <i class="fa fa-pencil-square-o"></i> Nilai Transfer
                                            </a>
                                        </div>
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
<script>
    $(function() {
        "use strict";

        $('#data').DataTable({
            // default sort by column 6 desc
            "stateSave": true,
            "order": [[ 5, "desc" ]],
            "dom": '<"top"lf<"dt-center"B>>rt<"bottom"ip><"clear">', // Place buttons (B) at the top center
            "buttons": [
                {
                    "extend": 'excelHtml5',
                    "text": 'Download Excel',
                    "className": 'btn btn-primary mt-10'
                }
            ],
            "lengthMenu": [10, 25, 50, 75, 100], // Include the length changing control
            "pageLength": 10, // Set the default number of rows to display
            "columnDefs": [{
                "targets": 0,
                "searchable": false,
                "orderable": false,
                "render": function (data, type, full, meta) {
                    return meta.settings._iDisplayStart + meta.row + 1;
                }
            }],
            "drawCallback": function (settings) {
                var api = this.api();
                var startIndex = api.context[0]._iDisplayStart;
                api.column(0, {page: 'current'}).nodes().each(function (cell, i) {
                    cell.innerHTML = startIndex + i + 1;
                });
            }
        });
    });
</script>
@endpush
