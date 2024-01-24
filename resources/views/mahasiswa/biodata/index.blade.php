@extends('layouts.mahasiswa')
@section('title')
Biodata
@endsection
@section('content')
<section class="content">
    <div class="row align-items-end">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3"><img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <!-- <h2>Halaman Biodata {{auth()->user()->name}}</h2> -->
                            <h2>Biodata Mahasiswa</h2>
                            <p class="text-dark mb-0 fs-16">
                                Universitas Sriwijaya
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box">
				<!-- Nav tabs -->
                <ul class="nav nav-tabs rounded10 justify-content-center" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#data-diri" role="tab"><span><i class="fas fa-user-cog"></i></span> <span class="hidden-xs-down ms-15">Data Diri</span></a> </li>
                    <li class="nav-item"> <a class="nav-link " data-bs-toggle="tab" href="#akademik" role="tab"><span><i class="fa fa-graduation-cap"></i></span> <span class="hidden-xs-down ms-15">Akademik</span></a> </li>
                    <li class="nav-item"> <a class="nav-link " data-bs-toggle="tab" href="#alamat" role="tab"><span><i class="fas fa-address-card"></i></span> <span class="hidden-xs-down ms-15">Alamat</span></a> </li>
                    <li class="nav-item"> <a class="nav-link " data-bs-toggle="tab" href="#orang-tua" role="tab"><span><i class="fas fa-users"></i></span> <span class="hidden-xs-down ms-15">Orang Tua</span></a> </li>
                    <li class="nav-item"> <a class="nav-link " data-bs-toggle="tab" href="#wali" role="tab"><span><i class="fas fa-user-friends"></i></span> <span class="hidden-xs-down ms-15">Wali</span></a> </li>
                    <li class="nav-item"> <a class="nav-link " data-bs-toggle="tab" href="#data-sekolah" role="tab"><span><i class="fas fa-school"></i></span> <span class="hidden-xs-down ms-15">Data Sekolah</span></a> </li>
                    <li class="nav-item"> <a class="nav-link " data-bs-toggle="tab" href="#pt-asal" role="tab"><span><i class="fas fa-university"></i></span> <span class="hidden-xs-down ms-15">Perguruan Tinggi Asal</span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#riwayat-pendidikan" role="tab"><span><i class="fas fa-tasks"></i></span> <span class="hidden-xs-down ms-15">Riwayat Pendidikan</span></a> </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content tabcontent">
                    @include('mahasiswa.biodata.include.data-diri')
                    @include('mahasiswa.biodata.include.akademik')
                    @include('mahasiswa.biodata.include.alamat')
                    @include('mahasiswa.biodata.include.orang-tua')
                    @include('mahasiswa.biodata.include.wali')
                    @include('mahasiswa.biodata.include.data-sekolah')
                    @include('mahasiswa.biodata.include.pt-asal')
                    @include('mahasiswa.biodata.include.riwayat-pendidikan')
                </div>
				<!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>		
</section>
@endsection
