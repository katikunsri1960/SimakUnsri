@extends('layouts.mahasiswa')

@section('title')
Bimbingan Tugas Akhir
@endsection

@section('content')
@push('header')
<div class="mx-4">
    <a href="{{ route('mahasiswa.bimbingan.bimbingan-tugas-akhir') }}"
        class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush

@include('swal')

@if ($aktivitas == NULL)
    <!-- Bagian ini ditampilkan jika $aktivitas == NULL -->
    <section class="content">
        <div class="row align-items-end">
            <div class="col-xl-12 col-12">
                <div class="box bg-primary-light pull-up">
                    <div class="box-body p-xl-0">
                        <div class="row align-items-center">
                            <div class="col-12 col-lg-3"><img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" alt="">
                            </div>
                            <div class="col-12 col-lg-9">
                                <h2>Bimbingan Tugas Akhir</h2>
                                <p class="text-dark align-middle mb-0 fs-16">
                                    Universitas Sriwijaya
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-20">
            <div class="col-xxl-12">
                <div class="box box-body mb-0 bg-white">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <h3 class="fw-500 text-dark mb-20">Bimbingan Tugas Akhir</h3>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-lg-12 col-lg-12 col-lg-12 p-20 m-0">
                            <div class="box box-body bg-warning-light">
                                <div class="row" style="align-items: center;">
                                    <div class="col-lg-1 text-right" style="text-align-last: end;">
                                        <i class="fa-solid fa-2xl fa-circle-exclamation fa-danger" style="color: #d10000;"></i></i>
                                    </div>
                                    <div class="col-lg-10 text-left text-danger">
                                        <label>
                                            Anda tidak memiliki Aktivitas!
                                        </label><br>
                                        <label>
                                            Silahkan Ambil Aktivitas di Menu Kartu Rencana Studi!
                                        </label><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@else
    @include('mahasiswa.bimbingan.tugas-akhir.asistensi-tambah')
    <section class="content bg-white">
        <div class="row align-items-end">
            <div class="col-md-12">
                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-gradient-secondary">
                        <div class="widget-user-image">
                            @php
                            $imagePath = public_path('storage/'.$aktivitas->anggota_aktivitas_personal->mahasiswa->angkatan.'/'.$aktivitas->anggota_aktivitas_personal->mahasiswa->nim.'.jpg');
                            @endphp
                            <img class="rounded bg-success-light"
                                src="{{ file_exists($imagePath) ? asset('storage/'.$aktivitas->anggota_aktivitas_personal->mahasiswa->angkatan.'/'.$aktivitas->anggota_aktivitas_personal->mahasiswa->nim.'.jpg') : asset('images/images/avatar/avatar-15.png') }}"
                                alt="User Avatar">
                        </div>
                        <h3 class="widget-user-username">{{$aktivitas->anggota_aktivitas_personal->nama_mahasiswa}} </h3>
                        <h4 class="widget-user-desc">NIM: {{$aktivitas->anggota_aktivitas_personal->nim}}<br
                                class="mb-1">ANGKATAN: {{$aktivitas->anggota_aktivitas_personal->mahasiswa->angkatan}}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-12">
                <div class="box box-body mb-0">
                    <div class="row mb-2">
                        <div class="col-xl-12 col-lg-12 d-flex justify-content-between">
                            <div class="d-flex justify-content-start">
                                <table class="table">
                                    <tr>
                                        <td class="text-left">Judul</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">{{$aktivitas->judul}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left text-nowrap">No. SK</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">{{$aktivitas->sk_tugas}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left text-nowrap">Tanggal Mulai</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">{{$aktivitas->id_tanggal_mulai}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left text-nowrap align-middle">Pembimbing</td>
                                        <td class="text-center align-middle">:</td>
                                        <td class="text-left align-middle">
                                            <ul style="padding: 0; padding-left:0.8rem">
                                                @foreach ($aktivitas->bimbing_mahasiswa as $bimbingan)
                                                <li>Pembimbing {{$bimbingan->pembimbing_ke}} : {{$bimbingan->nama_dosen}}  
                                                    @if ($bimbingan->approved == 0)
                                                        <span class="badge bg-warning mx-5">Menunggu Persetujuan</span>
                                                    @elseif ($bimbingan->approved == 1)
                                                        <span class="badge bg-success mx-5">Disetujui</span>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-xl-12 col-lg-12 text-end">
                            <div class="btn-group">
                                <a class="btn btn-rounded bg-success-light" href="#" data-bs-toggle="modal"
                                    data-bs-target="#tambahAsistensiModal" id="btnTambahAsistensi"><i class="fa fa-plus"><span
                                    class="path1"></span><span class="path2"></span></i> Tambah Asistensi</a>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="table-responsive">
                            <table id="dt" class="table table-bordered table-striped" style="font-size: 12px">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" style="width: 5%">No</th>
                                        <th class="text-center align-middle">Tanggal</th>
                                        <th class="text-center align-middle">Keterangan</th>
                                        <th class="text-center align-middle">Pembimbing</th>
                                        <th class="text-center align-middle">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                    <tr>
                                        <td class="text-center align-middle">{{$loop->iteration}}</td>
                                        <td class="text-center align-middle">{{$d->id_tanggal}}</td>
                                        <td class="text-left align-middle" style="text-align: justify">{{$d->uraian}}</td>
                                        <td class="text-center align-middle">{{$d->dosen ? $d->dosen->nama_dosen : '-'}}</td>
                                        <td class="text-center align-middle">
                                            @if ($d->approved == 0)
                                            <span class="badge bg-warning">Menunggu Persetujuan</span>
                                            @elseif ($d->approved == 1)
                                            <span class="badge bg-success">Disetujui</span>
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
@endif
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/vendor_components/select2/dist/css/select2.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/vendor_components/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/vendor_components/select2/dist/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();

        // Check payment status and show SweetAlert if not paid
        @if ($statusPembayaran == NULL)
        //console.log('masook')
            swal({
                title: 'Pembayaran Belum Lunas',
                text: 'Anda belum menyelesaikan pembayaran untuk semester ini.',
                type: 'warning',
                confirmButtonText: 'OK'
            }, function() {
                window.location.href = "{{ route('mahasiswa.dashboard') }}"; // Ganti dengan rute yang sesuai, jika ada
            });
        // Check if $aktivitas is NULL
        @elseif ($aktivitas == NULL)
        // console.log('masook juga')
            swal({
                title: 'Aktivitas Tidak Ditemukan',
                text: 'Anda belum mengambil aktivitas. Silakan ambil aktivitas terlebih dahulu.',
                type: 'warning',
                confirmButtonText: 'OK'
            // }, function() {
            //     window.location.href = "{{ route('mahasiswa.dashboard') }}"; // Ganti dengan rute yang sesuai, jika ada
            });
        @else
            $('#btnTambahAsistensi').click(function(e) {
                e.preventDefault();  // Prevent the default action

                let bimbinganApproved = true;

                @foreach ($aktivitas->bimbing_mahasiswa as $bimbingan)
                    if ({{ $bimbingan->approved }} === 0) {
                        bimbinganApproved = false;
                        break;  // Exit the loop early if any approval is pending
                    }
                @endforeach

                if (!bimbinganApproved) {
                    swal({
                        title: 'Dosen Pembimbing Belum Disetujui!',
                        text: 'Dosen pembimbing Anda belum disetujui oleh Koordinator Program Studi.',
                        type: 'warning',
                        confirmButtonText: 'OK'
                    });
                } else {
                    $('#tambahAsistensiModal').modal('show');
                }
            });
        @endif   

        $('#dt').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
        });
    });
</script>
@endpush
