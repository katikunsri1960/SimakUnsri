@extends('layouts.mahasiswa')
@section('title')
Kartu Rencana Studi
@endsection
@section('content')
@push('header')
<div class="mx-4">
    <a href="{{route('mahasiswa.krs')}}"
        class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush
<section class="content">
    <div class="row align-items-end">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3"><img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h2 class="mb-10">Halaman Kartu Rencana Studi,  {{auth()->user()->name}}</h2>
                            <p class="text-dark mb-0 fs-16">
                                SIMAK Universitas Sriwijaya
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('swal')
    <div class="row">
        <div class="col-xl-4 col-md-6 col-12">
            <div class="box bs-5 border-primary rounded mb-10 pull-up"
                style="background-image: url({{asset('images/images/svg-icon/color-svg/st-1.svg')}}); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">IPK</p>
                        <h4 class="mt-5 mb-0" style="color:#0052cc">{{$transkrip->ipk==NULL ? '0' : $transkrip->ipk }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-12">
            <div class="box bs-5 border-warning rounded mb-10 pull-up"
                style="background-image: url({{asset('images/images/svg-icon/color-svg/st-3.svg')}}); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">SKS Maksimum</p>
                        <h4 class="mt-5 mb-0" style="color:#0052cc">{{$sks_max}}</h4>
                        {{-- <p class="text-fade mb-0 fs-12 text-white">Sisa SKS : ({{$sks_max}}-{{$sks_mk}})</p> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-12">
            <div class="box bs-5 border-success rounded mb-10 pull-up"
                style="background-image: url({{asset('images/images/svg-icon/color-svg/st-4.svg')}}); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">Dosen PA</p>
                        @if (!empty($riwayat_pendidikan->pembimbing_akademik->nama_dosen))
                            <h4 class="mt-5 mb-0" style="color:#0052cc">{{ $riwayat_pendidikan->pembimbing_akademik->nama_dosen }}</h4>
                        @else
                            <h4 class="mt-5 mb-0" style="color:#0052cc">Tidak Diisi</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-20">
        <div class="col-lg-12 col-xl-12 mt-5">
            <div class="box box-outline-success bs-3 border-success">
                <div class="row px-20">
                    <div class="col-md-6 text-start mt-3">
                        <h3 class="text-info mb-0"><i class="fa fa-newspaper-o"></i> Kartu Rencana Studi</h3>
                    </div>
                    <div class="col-md-6 text-end mt-3">
                        @if ($today<=$batas_isi_krs)
                            <span class="badge badge-warning-light my-10">Periode pengisian KRS hingga tanggal <strong style="color: red">{{ date('d M Y', strtotime($batas_isi_krs)) }}</strong></span>
                        @endif
                    </div>
                
                
                    {{-- Form untuk update SKS Maksimal PMM --}}
                    <form action="{{ route('mahasiswa.krs.update', $riwayat_pendidikan->id) }}" method="POST">
                        @csrf
                        {{-- <h4 class="text-info mb-0 mt-40"><i class="fa fa-calendar-o"></i> Lokasi Dan Jadwal Ujian</h4> --}}
                        <hr class="my-15">
                        <div class="form-group">
                            <div class="mb-4">
                                <label for="sks_maks_pmm">SKS Maksimal PMM</label>
                                <select class="form-select" name="sks_maks_pmm" id="sks_maks_pmm" required>
                                    <option value="">-- Pilih SKS Maksimal --</option>
                                    @foreach($sks_aktivitas_mbkm as $sks_aktivitas)
                                        <option value="{{ $sks_aktivitas}}">{{ $sks_aktivitas  }} SKS </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a type="button" href="{{route('mahasiswa.krs')}}" class="btn btn-danger waves-effect waves-light">
                                Batal
                            </a>
                            <button type="submit" id="submit-button" class="btn btn-primary waves-effect waves-light">Simpan</button>
                        </div>
                    </form>
                </div>
				<!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>

</section>
@endsection
@push('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.full.min.js')}}"></script>

@endpush
