@extends('layouts.prodi')
@section('title')
Ajuan SKPI Mahasiswa
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Data Ajuan SKPI Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data SKPI</li>
                        <li class="breadcrumb-item active" aria-current="page">Ajuan SKPI Mahasiswa</li>
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
                                    <th class="text-center align-middle">NO</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">NAMA MAHASISWA</th>
                                    <th class="text-center align-middle">PERIODE WISUDA</th>
                                    <th class="text-center align-middle">SKOR</th>
                                    <th class="text-center align-middle">STATUS</th>
                                    <th class="text-center align-middle">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no_a=1;
                                @endphp

                                @foreach ($data as $d)
                                    <tr>
                                        <td class="text-center align-middle">{{ $no_a++ }}</td>
                                        <td class="text-start align-middle">{{ $d->nim }}</td>
                                        <td class="text-start align-middle" style="white-space: nowrap;">{{ $d->nama_mahasiswa }}</td>
                                        <td class="text-center align-middle">{{ $d->wisuda_ke }}</td>
                                        <td class="text-center align-middle">{{ $d->total_skor }}</td>
                                        <td class="text-center align-middle">
                                            <div class="row">
                                                @if($d->finalisasi_data == 1)
                                                    @if($d->approved == 0)
                                                        <span class="badge badge-lg badge-warning">Belum Disetujui</span>
                                                    @elseif($d->approved == 1)
                                                        <span class="badge badge-lg badge-primary mb-5">Disetujui Koor. Prodi</span>
                                                    @elseif($d->approved == 2)
                                                        <span class="badge badge-lg badge-primary mb-5">Disetujui Fakultas</span>
                                                    @elseif($d->approved == 3)
                                                        <span class="badge badge-lg badge-success mb-5">Disetujui Dir. Akademik</span>
                                                    @elseif($d->approved == 97)
                                                        <span class="badge badge-lg badge-danger mb-5">Ditolak Koor. Prodi</span>
                                                    @elseif($d->approved == 98)
                                                        <span class="badge badge-lg badge-danger mb-5">Ditolak Fakultas</span>
                                                    @elseif($d->approved == 99)
                                                        <span class="badge badge-lg badge-danger mb-5">Ditolak Dir. Akademik</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-lg badge-danger">Belum Finalisasi Data</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($d->finalisasi_data == 1 && $d->bebas_pustaka->file_bebas_pustaka && $d->bebas_pustaka->link_repo)
                                                <div class="row d-flex justify-content-center">
                                                    <a href="{{route('prodi.data-skpi.detail', ['id' => $d->id])}}" class="btn btn-primary btn-sm my-2" title="Detail Mahasiswa" style="white-space: nowrap;"><i class="fa fa-eye"></i> Detail</a>
                                                </div>
                                            @elseif($d->finalisasi_data == 1 && optional($d->bebas_pustaka)->file_bebas_pustaka)
                                                <span class="badge badge-lg bg-danger mb-5 rounded">
                                                    Ditangguhkan
                                                </span>
                                                <p class="text-danger">
                                                    <strong>
                                                        Mahasiswa belum Mengumpulkan Bundle Skripsi/Tesis/Disertasi ke UPT Perpustakaan!
                                                    </strong>
                                                </p>
                                            @elseif($d->finalisasi_data == 1 && optional($d->bebas_pustaka)->link_repo)
                                                <span class="badge badge-lg bg-danger mb-5 rounded">
                                                    Ditangguhkan
                                                </span>
                                                <p class="text-danger">
                                                    <strong>
                                                        Mahasiswa belum Upload Repository!
                                                    </strong>
                                                </p>
                                            @else
                                                <span class="badge badge-lg bg-warning mb-5 rounded">
                                                    Belum Finalisasi
                                                </span>
                                                <p class="text-warning">
                                                    <strong>
                                                        Mahasiswa belum Finalisasi Data!
                                                    </strong>
                                                </p>
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
        
        $('#data').DataTable();
    });
</script>
@endpush
