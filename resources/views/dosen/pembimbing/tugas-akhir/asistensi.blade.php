@extends('layouts.dosen')
@section('title')
Bimbingan Tugas Akhir Dosen
@endsection
@section('content')
@push('header')
<div class="mx-4">
    <a href="{{route('dosen.pembimbing.bimbingan-tugas-akhir')}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-md-12">
            <div class="box box-widget widget-user-2">
                <div class="widget-user-header bg-gradient-secondary">
                    <div class="widget-user-image">
                        @php
                            $imagePath = public_path('storage/'.$aktivitas->anggota_aktivitas_personal->mahasiswa->angkatan.'/'.$aktivitas->anggota_aktivitas_personal->mahasiswa->nim.'.jpg');
                        @endphp
                        <img class="rounded bg-success-light" src="{{file_exists($imagePath) ? asset('storage/'.$aktivitas->anggota_aktivitas_personal->mahasiswa->angkatan.'/'.$aktivitas->anggota_aktivitas_personal->mahasiswa->nim.'.jpg') : asset('images/images/avatar/avatar-15.png')}}"
                            alt="User Avatar">
                    </div>
                    <h3 class="widget-user-username">{{$aktivitas->anggota_aktivitas_personal->nama_mahasiswa}} </h3>
                    <h4 class="widget-user-desc">NIM: {{$aktivitas->anggota_aktivitas_personal->nim}}<br class="mb-1">ANGKATAN: {{$aktivitas->anggota_aktivitas_personal->mahasiswa->angkatan}}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-12">
            <div class="box box-body mb-0">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 d-flex justify-content-between">
                        <div class="d-flex justify-content-start">
                            <h4 class="fw-500 text-dark mt-0">Daftar Bimbingan Tugas Akhir Dosen</h4>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="dt" class="table table-bordered table-striped" style="font-size: 11px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Tanggal</th>
                                    <th class="text-center align-middle">Keterangan</th>
                                    <th class="text-center align-middle">Status</th>
                                    <th class="text-center align-middle">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center align-middle">{{$d->id_tanggal}}</td>
                                    <td class="text-center align-middle">{{$d->uraian}}</td>
                                    <td class="text-center align-middle">
                                        @if ($d->approved == 0)
                                        <span class="badge bg-warning">Menunggu</span>
                                        @elseif ($d->status == 1)
                                        <span class="badge bg-success">Disetujui</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">

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
@push('css')
<link rel="stylesheet" href="{{asset('assets/vendor_components/select2/dist/css/select2.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>
    $('#dt').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
    });

</script>
@endpush
