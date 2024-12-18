@extends('layouts.bak')
@section('title')
Monitoring Pengisian Nilai
@endsection
@section('content')
@push('header')
<div class="mx-4">
    <a href="{{route('bak.monitoring.pengisian-nilai')}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">{{Str::upper($title)}}</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('bak')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Monitoring</li>
                        <li class="breadcrumb-item" aria-current="page">Pengisian Nilai</li>
                        <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
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
                    <table>
                        <tr>
                            <th>NIDN</th>
                            <th class="px-3">:</th>
                            <th>{{$dosen->nidn}}</th>
                        </tr>
                        <tr>
                            <th>Nama Dosen</th>
                            <th class="px-3">:</th>
                            <th>{{$dosen->nama_dosen}}</th>
                        </tr>
                    </table>
                    <hr>
                    <div class="table-responsive mt-5">
                            <table class="table table-bordered table-hover" id="data">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">No</th>
                                        <th class="text-center align-middle">Kode MK</th>
                                        <th class="text-center align-middle">Nama MK</th>
                                        <th class="text-center align-middle">Nama Kelas</th>
                                        <th class="text-center align-middle">Dosen Pengajar</th>
                                        <th class="text-center align-middle">Peserta Kelas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                    <tr>

                                        <td class="text-center align-middle">{{$loop->iteration}}</td>

                                        <td class="text-center align-middle">{{$d->matkul->kode_mata_kuliah}}</td>
                                        <td class="text-start align-middle">{{$d->matkul->nama_mata_kuliah}}</td>
                                        <td class="text-center align-middle">
                                            {{$d->nama_kelas_kuliah}}

                                        </td>
                                        <td class="text-start align-middle">
                                            @if ($d->dosen_pengajar)
                                                <ul>
                                                    @foreach ($d->dosen_pengajar as $p)
                                                        <li>{{$p->dosen->nama_dosen}}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            {{count($d->peserta_kelas_approved)}}
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
    $(document).ready(function(){
        $('#data').DataTable();
    });

</script>
@endpush
