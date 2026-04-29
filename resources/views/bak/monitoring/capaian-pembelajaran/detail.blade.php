@extends('layouts.bak')
@section('title')
Monitoring CPL Kurikulum
@endsection

@section('content')

@push('header')
<div class="mx-4">
    <a href="{{route('bak.monitoring.cpl-kurikulum')}}" class="btn btn-warning btn-rounded">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>
@endpush

<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">{{ strtoupper($title) }}</h3>
        </div>
    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-12">

            <div class="box box-outline-success">
                <div class="box-body">

                    {{-- INFO PRODI --}}
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <td class="text-start" width="30%">Fakultas</td>
                                    <td class="text-start" width="2%">:</td>
                                    <td class="text-start" width="68%">{{$prodi->fakultas->nama_fakultas}}</td>
                                </tr>
                                <tr>
                                    <td class="text-start"  width="30%">Program Studi</td>
                                    <td class="text-start" width="2%">:</td>
                                    <td class="text-start" width="68%">{{$prodi->nama_jenjang_pendidikan}} - {{$prodi->nama_program_studi}} ({{$prodi->kode_program_studi}})</td>
                                </tr>
                            </table>
                        </div>

                    </div>

                    {{-- TABLE --}}
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-hover" id="data">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Kurikulum</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Jumlah CPL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>

                                    <td>{{ $d->nama_kurikulum }}</td>

                                    <td class="text-center">
                                        @if($d->cpl_count > 0)
                                            <span class="badge bg-success">Sudah Ada CPL</span>
                                        @else
                                            <span class="badge bg-danger">Belum Ada CPL</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        {{ $d->cpl_count }}
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
$(document).ready(function(){
    $('#data').DataTable();
});
</script>
@endpush