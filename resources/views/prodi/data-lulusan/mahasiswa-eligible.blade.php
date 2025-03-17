@extends('layouts.prodi')
@section('title')
Ajuan Wisuda Mahasiswa
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Data Ajuan Wisuda Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Lulusan</li>
                        <li class="breadcrumb-item active" aria-current="page">Ajuan Wisuda Mahasiswa</li>
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
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">NAMA MAHASISWA</th>
                                    <th class="text-center align-middle">ANGKATAN</th>
                                    <th class="text-center align-middle">JUDUL SKRIPSI</th>
                                    <th class="text-center align-middle">TANGGAL SIDANG</th>
                                    <th class="text-center align-middle">SKS DIAKUI</th>
                                    <th class="text-center align-middle">STATUS</th>
                                    <th class="text-center align-middle">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no_a=1;
                                @endphp

                                @foreach ($data as $d)
                                @include('prodi.data-lulusan.pembatalan-ajuan')
                                    <tr>
                                        <td class="text-center align-middle">{{ $no_a++ }}</td>
                                        <td class="text-start align-middle">{{ $d->nim }}</td>
                                        <td class="text-start align-middle" style="white-space: nowrap;">{{ $d->nama_mahasiswa }}</td>
                                        <td class="text-center align-middle">{{ $d->angkatan }}</td>
                                        <td class="text-start align-middle">{{ $d->aktivitas_mahasiswa->judul }}</td>
                                        <td class="text-center align-middle">{{ $d->aktivitas_mahasiswa->tanggal_selesai }}</td>
                                        <td class="text-center align-middle">{{ $d->transkrip_mahasiswa_sum_sks_mata_kuliah }}</td>
                                        <td class="text-center align-middle">
                                            <div class="row">
                                                @if($d->approved == 0)
                                                    @if ($d->jumlah_sks != 1 || $d->status_masa_studi != 1 || $d->status_ipk != 1 || $d->status_semester_pendek != 1)
                                                        <span class="badge badge-lg badge-danger">Belum Eligible</span>
                                                    @else
                                                        <span class="badge badge-lg badge-success">Eligible</span>
                                                    @endif
                                                @elseif($d->approved == 1)
                                                    <span class="badge badge-lg badge-primary mb-5">Disetujui Koor. Prodi</span>
                                                @elseif($d->approved == 2)
                                                    <span class="badge badge-lg badge-primary mb-5">Disetujui Fakultas</span>
                                                @elseif($d->approved == 3)
                                                    <span class="badge badge-lg badge-success mb-5">Disetujui BAK</span>
                                                @elseif($d->approved == 97)
                                                    <span class="badge badge-lg badge-danger mb-5">Ditolak Koor. Prodi</span>
                                                @elseif($d->approved == 98)
                                                    <span class="badge badge-lg badge-danger mb-5">Ditolak Fakultas</span>
                                                @elseif($d->approved == 99)
                                                    <span class="badge badge-lg badge-danger mb-5">Ditolak BAK</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="row d-flex justify-content-center">
                                                <a href="{{route('prodi.data-lulusan.detail', ['id' => $d->id])}}" class="btn btn-secondary btn-sm my-2" title="Detail Mahasiswa" style="white-space: nowrap;"><i class="fa fa-edit"></i> Detail</a>
                                                <a href="#" class="btn btn-danger btn-sm my-2" title="Tolak Ajuan Wisuda" data-bs-toggle="modal" data-bs-target="#PembatalanAjuanModal{{$d->id}}" style="white-space: nowrap;"><i class="fa fa-ban"></i> Decline</a>
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
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>
    $(function() {
        "use strict";
        
        $('#data').DataTable();
    });
</script>
@endpush
