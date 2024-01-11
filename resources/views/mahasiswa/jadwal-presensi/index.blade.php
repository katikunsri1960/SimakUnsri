@extends('layouts.mahasiswa')
@section('title')
Jadwal dan Presensi
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
                            <!-- <h2>Halaman KRS {{auth()->user()->name}}</h2> -->
                            <h2>Jadwal dan Presensi</h2>
                            <p class="text-dark mb-0 fs-16">
                                Universitas Sriwijaya
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-20">
        <div class="col-lg-12 col-xl-12 mt-5">
            <div class="box">
				<!-- Nav tabs -->
                <ul class="nav nav-pills justify-content-left" role="tablist">
                    <li class="nav-item bg-secondary-light rounded10"> <a class="nav-link active" data-bs-toggle="tab" href="#kuliah" role="tab"><span><i class="fa fa-book"></i></span> <span class="hidden-xs-down ms-15">Kuliah</span></a> </li>
                    <li class="nav-item bg-secondary-light rounded10"> <a class="nav-link " data-bs-toggle="tab" href="#uts" role="tab"><span><i class="fa-solid fa-tasks"></i></span> <span class="hidden-xs-down ms-15">UTS</span></a> </li>
                    <li class="nav-item bg-secondary-light rounded10"> <a class="nav-link " data-bs-toggle="tab" href="#uas" role="tab"><span><i class="fa-solid fa-tasks"></i></span> <span class="hidden-xs-down ms-15">UAS</span></a> </li>
                </ul>
                
                <!-- Tab panes -->
                <div class="tab-content tabcontent">
                    @include('mahasiswa.jadwal-presensi.include.kuliah')
                    @include('mahasiswa.jadwal-presensi.include.uts')
                    @include('mahasiswa.jadwal-presensi.include.uas')
                </div>
				<!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>	
</section>
@endsection
