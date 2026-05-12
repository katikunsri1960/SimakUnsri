@extends('layouts.mahasiswa')
@section('title')
Pendaftaran Wisuda Mahasiswa
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Wisuda Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('mahasiswa.dashboard')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Wisuda</li>
                        <li class="breadcrumb-item active" aria-current="page">Pendaftaran</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<section class="content bg-white text-uppercase">
    <div class="row">
        <div class="col-xxl-12">
            {{-- DATA WISUDA --}}
            {{--@if($wisuda && !$wisuda -> approved == 3)
            <div class="box box-outline-danger bs-3 border-danger">
                <div class="box-header with-border d-flex justify-content-between mx-20">
                    <div class="d-flex justify-content-start">
                        <h4 class="text-primary mb-0"><i class="fa fa-graduation-cap"></i> Pendaftaran Wisuda</h4>
                    </div>
                </div>
                <div class="box-body">
                    <p class="text-danger">
                        <i class="fa fa-info-circle"></i>
                        Saat ini pendaftaran Yudisium Anda belum disetujui Direktorat Akademik. 
                    </p>
                </div>
            </div>
            @else--}}
            @if($wisuda)
                @if($wisuda->approved == 3)
                <div class="box box-outline-success bs-3 border-success">
                    <div class="box-header with-border d-flex justify-content-between mx-20">
                        <div class="d-flex justify-content-start">
                            <h4 class="text-primary mb-0"><i class="fa-solid fa-list-check"></i> Status Persyaratan Pendaftaran</h4>
                        </div>                  
                    </div>
                    <div class="box box-body mb-0">
                        <div class="col-lg-12 mb-3">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover text-center align-middle">
                                    <thead class="table-success">
                                        <tr>
                                            <th>Nama di Ijazah</th>
                                            <th style="width:80px">Foto</th>
                                            <th style="width:80px">SKPI</th>
                                            <th style="width:80px">Ver Mahasiswa</th>
                                            <!-- <th style="width:80px">Ver Pembimbing TA</th> -->
                                            <!-- <th style="width:80px">Ver Koor. Prodi</th> -->
                                            <!-- <th style="width:80px">Ver Fakultas</th> -->
                                            <th style="width:80px">Ver Dir. Akad</th>
                                            <!-- <th style="width:80px">PISN</th> -->
                                            <th style="width:80px">Persyaratan Lengkap</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-start text-nowrap">
                                                {{ $riwayat_pendidikan->biodata->nama_mahasiswa }}
                                            </td>
                                        
                                            <td class="p-0"><!--Foto-->
                                                <a href="{{ route('mahasiswa.kelulusan.wisuda.data-wisuda') }}"
                                                    class="btn btn-sm {{ $wisuda->verified_wisuda == 1 ? 'btn-success' : 'btn-danger' }}">
                                                    <i class="fas {{ $wisuda->verified_wisuda == 1 ? 'fa-check' : 'fa-times' }}"></i>
                                                </a>
                                            </td>

                                            <td class="p-0"><!--SKPI-->
                                                <a href="{{ route('mahasiswa.kelulusan.wisuda.data-skpi') }}"
                                                    @if($wisuda->verified_skpi == 1 && $skpi_data->isNotEmpty())
                                                        class="btn btn-sm btn-success">
                                                        <i class="fas fa-check"></i>
                                                    @elseif($wisuda->verified_skpi == 1 && $skpi_data->isEmpty())
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fas fa-minus"></i>
                                                    @else
                                                        class="btn btn-sm btn-danger">
                                                        <i class="fas fa-times"></i>
                                                    @endif
                                                </a>
                                            </td>

                                            <td class="p-0"><!--Verifikasi Mahasiswa-->
                                                <a href="{{ route('mahasiswa.kelulusan.wisuda.resume.index') }}"
                                                    class="btn btn-sm {{ $wisuda->finalisasi_wisuda == 1 ? 'btn-success' : 'btn-danger' }}">
                                                    <i class="fas {{ $wisuda->finalisasi_wisuda == 1 ? 'fa-check' : 'fa-times' }}"></i>
                                                </a>
                                            </td>

                                            <td class="p-0"><!--Verifikasi Dir. Akad-->
                                                <button class="btn btn-sm {{ $wisuda->approved_wisuda == 3 ? 'btn-success' : 'btn-danger' }}">
                                                    <i class="fas {{ $wisuda->approved_wisuda == 3 ? 'fa-check' : 'fa-times' }}"></i>
                                                </button>
                                            </td>

                                            <td class="p-0"><!--Persyaratan Lengkap-->
                                                <button class="btn btn-sm {{ 
                                                        $wisuda->verified_induk == 1 && $wisuda->verified_akademik == 1 && $wisuda->verified_ta == 1 &&
                                                        $wisuda->verified_wisuda == 1 && $wisuda->verified_skpi == 1 && $wisuda->finalisasi_data ==1 &&
                                                        $wisuda->pisn && $wisuda->approved == 3 
                                                        ? 'btn-success' : 'btn-danger' }}">
                                                    <i class="fas {{ 
                                                            $wisuda->verified_induk == 1 && $wisuda->verified_akademik == 1 && $wisuda->verified_ta == 1 &&
                                                            $wisuda->verified_wisuda == 1 && $wisuda->verified_skpi == 1 && $wisuda->finalisasi_data ==1 &&
                                                            $wisuda->pisn && $wisuda->approved == 3 
                                                            ? 'fa-check' : 'fa-times' }}"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            <div class="box box-outline-success bs-3 border-success">    
                <div class="box-header with-border d-flex justify-content-between mx-20">
                    <div class="d-flex justify-content-start">
                        <h4 class="text-primary mb-0"><i class="fa fa-graduation-cap"></i> Pendaftaran Wisuda</h4>
                    </div>                  
                </div>
                <div class="box box-body mb-0">
                    <div class="row mx-20">
                        <div class="col-12">
                            <div class="box">
                                <div class="box-body">
                                    @if($wisuda->pas_foto)
                                    <div class="row text-center mb-20">
                                        <div class="widget-user-image">
                                            @php
                                                $imagePath = public_path('storage/' . $wisuda->pas_foto);
                                            @endphp

                                            <img class="rounded bg-success"
                                                src="{{ (!empty($wisuda->pas_foto) && file_exists($imagePath))
                                                        ? asset('storage/' . $wisuda->pas_foto)
                                                        : asset('images/images/avatar/avatar-15.png') }}"
                                                alt="User Avatar"
                                                style="width: 250px;">
                                        </div>
                                    </div>
                                    @endif
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <tr>
                                                <td class="text-left" style="width: 30%;">Judul {{$wisuda->aktivitas_mahasiswa ? $wisuda->aktivitas_mahasiswa->nama_jenis_aktivitas : 'Tugas Akhir'}}</td>
                                                <td class="text-center" style="width: 5%;">:</td>
                                                <td class="text-left" style="width: 65%; text-align: justify;">{{$wisuda->aktivitas_mahasiswa ? $wisuda->aktivitas_mahasiswa->judul : 'Belum diisi'}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left" style="width: 30%;">Wisuda Ke-</td>
                                                <td class="text-center" style="width: 5%;">:</td>
                                                <td class="text-left" style="width: 65%; text-align: justify;">{{$wisuda->wisuda_ke ? $wisuda->wisuda_ke : 'Belum diisi'}}</td>
                                            </tr>
                                            @if($wisuda->prodi->bku_pada_ijazah == 1)                        
                                                <tr>
                                                    <td class="text-left" style="width: 30%;">Bidang Kajian Utama (BKU) / Kosentrasi</td>
                                                    <td class="text-center" style="width: 5%;">:</td>
                                                    <td class="text-left" style="width: 65%; text-align: justify;">{{$wisuda->bku_prodi ? $wisuda->bku_prodi->bku_prodi_id : '-'}}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td class="text-left" style="width: 30%;">Abstrak {{$wisuda->aktivitas_mahasiswa ? $wisuda->aktivitas_mahasiswa->nama_jenis_aktivitas : 'Tugas Akhir'}}</td>
                                                <td class="text-center" style="width: 5%;">:</td>
                                                <td class="text-left" style="width: 65%; text-align: justify;">{{$wisuda->abstrak_ta ? $wisuda->abstrak_ta : 'Belum diisi'}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left" style="width: 30%;">File Abstrak {{$wisuda->aktivitas_mahasiswa ? $wisuda->aktivitas_mahasiswa->nama_jenis_aktivitas : 'Tugas Akhir'}}</td>
                                                <td class="text-center" style="width: 5%;">:</td>
                                                {{-- <td class="text-left" style="text-align: justify">{{$wisuda->abstrak_file}}</td> --}}
                                                <td class="text-left" style="width: 65%; text-align: justify;">
                                                    @if($wisuda->abstrak_file)
                                                        <a class="btn btn-sm btn-success my-5" href="{{ asset($wisuda->abstrak_file) }}" type="button" target="_blank">Lihat Abstrak Indonesia</a>
                                                    @else
                                                        <span class="badge badge-lg bg-danger mb-5">Abstrak Indonesia Tidak Diupload</span>
                                                    @endif
                                                    <!-- @if($wisuda->abstrak_file_eng)
                                                        <a class="btn btn-sm btn-success  my-5" href="{{ asset($wisuda->abstrak_file_eng) }}" type="button" target="_blank">Lihat Abstrak Inggris</a>
                                                    @else
                                                        <span class="badge badge-lg bg-danger mb-5">Abstrak Inggris Tidak Diupload</span>
                                                    @endif -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-left" style="width: 30%;">File Ijazah Terakhir</td>
                                                <td class="text-center" style="width: 5%;">:</td>
                                                {{-- <td class="text-left" style="text-align: justify">{{$wisuda->abstrak_file}}</td> --}}
                                                <td class="text-left" style="width: 65%; text-align: justify;">
                                                    @if($wisuda->abstrak_file)
                                                        <a class="btn btn-sm btn-success" href="{{ asset($wisuda->ijazah_terakhir_file) }}" type="button" target="_blank">Lihat Ijazah Terakhir</a>
                                                    @else
                                                        <span class="badge badge-lg bg-danger">Ijazah Terakhir Tidak Diupload</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-left" style="width: 30%;">Status Pendaftaran Yudisium</td>
                                                <td class="text-center" style="width: 5%;">:</td>
                                                <td class="text-left align-middle" style="width:10%">
                                                    {{-- KONDISI PERSYARATAN--}}
                                                    @if($wisuda->finalisasi_data == 1)
                                                        
                                                        {{-- KONDISI BEBAS PUSTAKA--}}
                                                        @if($bebas_pustaka && $bebas_pustaka->file_bebas_pustaka && $bebas_pustaka->link_repo)
                                                            
                                                            {{-- KONDISI APPROVED--}}
                                                            @if($wisuda->approved == 0)
                                                                <span class="badge badge-lg badge-warning mb-5 rounded">Belum Disetujui Koor. Prodi</span>
                                                            @elseif($wisuda->approved == 11)
                                                                <span class="badge badge-lg badge-primary mb-5 rounded">Disetujui Dosen Pembimbing TA</span>
                                                            @elseif($wisuda->approved == 1)
                                                                <span class="badge badge-lg badge-primary mb-5 rounded">Disetujui Koor. Prodi</span>
                                                            @elseif($wisuda->approved == 2)
                                                                <span class="badge badge-lg badge-primary mb-5 rounded">Disetujui Fakultas</span>
                                                            @elseif($wisuda->approved == 3)
                                                                <span class="badge badge-lg badge-success mb-5 rounded">Disetujui Dir. Akademik</span>
                                                            @elseif($wisuda->approved == 9)
                                                                <span class="badge badge-lg badge-danger mb-5 rounded">Ditolak Dosen Pembimbing TA</span>
                                                            @elseif($wisuda->approved == 97)
                                                                <span class="badge badge-lg badge-danger mb-5 rounded">Ditolak Koor. Prodi</span>
                                                            @elseif($wisuda->approved == 98)
                                                                <span class="badge badge-lg badge-danger mb-5 rounded">Ditolak Fakultas</span>
                                                            @elseif($wisuda->approved == 99)
                                                                <span class="badge badge-lg badge-danger mb-5 rounded">Ditolak Dir. Akademik</span>
                                                            @endif
                                                        @elseif($bebas_pustaka && !$bebas_pustaka->file_bebas_pustaka)
                                                            <span class="badge badge-lg bg-danger mb-5 rounded">
                                                                Ditangguhkan
                                                            </span>
                                                            <p class="text-danger">
                                                                <strong>
                                                                    Anda belum Mengumpulkan Bundle Skripsi/Tesis/Disertasi ke UPT Perpustakaan!
                                                                </strong>
                                                            </p>
                                                        @elseif($bebas_pustaka && !$bebas_pustaka->link_repo)
                                                            <span class="badge badge-lg bg-danger mb-5 rounded">
                                                                Ditangguhkan
                                                            </span>
                                                            <p class="text-danger">
                                                                <strong>
                                                                    Anda belum Upload Repository!
                                                                </strong>
                                                            </p>
                                                        @else
                                                            <span class="badge badge-lg bg-danger mb-5 rounded">
                                                                Persyaratan Wisuda Belum Lengkap
                                                            </span>
                                                        @endif
                                                    @else
                                                        <a type="button" href="{{route('mahasiswa.kelulusan.wisuda.data-wisuda')}}" class="btn btn-sm btn-danger waves-effect waves-light"
                                                            title="Anda harus melakukan finalisasi data terlebih dahulu">
                                                            Belum Finalisasi Data
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>     
                                            <tr>
                                                <td class="text-left" style="width: 30%;">Status Pendaftaran Wisuda</td>
                                                <td class="text-center" style="width: 5%;">:</td>
                                                <td class="text-left align-middle" style="width:10%">
                                                    {{-- KONDISI PERSYARATAN--}}
                                                    @if($wisuda->finalisasi_wisuda == 1)
                                                        
                                                        {{-- KONDISI APPROVED--}}
                                                        @if($wisuda->approved_wisuda == 0)
                                                            <span class="badge badge-lg badge-warning mb-5 rounded">Belum Diapproved</span>
                                                        @elseif($wisuda->approved_wisuda == 3)
                                                            <span class="badge badge-lg badge-success mb-5 rounded">Disetujui Dir. Akademik</span>
                                                        @elseif($wisuda->approved_wisuda == 99)
                                                            <span class="badge badge-lg badge-danger mb-5 rounded">Ditolak Dir. Akademik</span>
                                                        @endif
                                                    
                                                    @else
                                                        <a type="button" href="{{route('mahasiswa.kelulusan.wisuda.data-wisuda')}}" class="btn btn-sm btn-danger waves-effect waves-light"
                                                            title="Anda harus melakukan finalisasi data terlebih dahulu">
                                                            Belum Finalisasi Data
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>  
                                            @if($wisuda->approved_wisuda > 3 && $wisuda->alasan_pembatalan)
                                            <tr>
                                                <td class="text-left" style="width: 30%;">Alasan Pembatalan</td>
                                                <td class="text-center" style="width: 5%;">:</td>
                                                <td class="text-left align-middle" style="width:10%">
                                                    {{-- KONDISI PERSYARATAN--}}
                                                    <span class="badge badge-lg bg-danger mb-5 rounded">
                                                        {{ $wisuda->alasan_pembatalan }}
                                                    </span>
                                                </td>
                                            </tr>     
                                            @endif                                  
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5 mt-30">
                        <div class="col-xl-12 col-lg-12 text-center">
                            <div class="d-flex justify-content-center">
                                @if($wisuda && $wisuda->finalisasi_data == 1 && $wisuda->approved == 3)
                                <a class="btn bg-primary" 
                                    href="{{ route('mahasiswa.kelulusan.wisuda.data-wisuda') }}"
                                    id="lanjut-wisuda-btn" title="Pastikan semua syarat sudah terpenuhi sebelum mendaftar!">
                                    <i class="fa fa-graduation-cap"><span class="path1"></span><span class="path2"></span></i> 
                                    DAFTAR WISUDA
                                </a>
                                @endif
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
            @endif    
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
    $(document).ready(function() {        
        $('.select2').select2();

        $('#data').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
        });
    });
    
    $('#daftar-wisuda-btn').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        swal({
            title: 'Daftar Wisuda',
            text: "Apakah anda yakin ingin mendaftar Wisuda?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                window.location.href = url;
            }
        });
    });

</script>
@endpush