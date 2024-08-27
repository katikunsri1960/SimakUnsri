@extends('layouts.mahasiswa')
@section('title')
Kartu Rencana Studi
@endsection
@section('content')
@include('swal')
<section class="content">
    <div class="row align-items-end">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3"><img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h2 class="mb-10">Halaman Kartu Rencana Studi</h2>
                            <p class="text-dark mb-0 fs-16">
                                SIMAK Universitas Sriwijaya
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="content" style="text-align-last: center; padding-bottom:20px">
  <div class="row mx-20" >
    <div class="col-12 px-25">
        <div class="box no-shadow mb-20 bg-transparent">
            <div class="box-header no-border px-0" style="text-align-last: left; padding-bottom:20px">
            <a type="button" href="{{route('mahasiswa.krs')}}" class="btn btn-warning btn-rounded waves-effect waves-light">
            <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h3 class="box-title px-3">Daftar Aktivitas MBKM</h3>
            </div>
        </div>
    </div>
  <div>
  <div class="container">
    <div class="row px-20">
      <div class="col-12 col-lg-6 px-50">
        <div class="box ribbon-box">
          <div class="ribbon-two ribbon-two-primary"><span>MBKM</span></div>
          <div class="box-header no-border p-0">				
            <a href="{{route('mahasiswa.perkuliahan.mbkm.pertukaran')}}">
              <img class="img-fluid" src="{{asset('images/images/avatar/icon_kampus_merdeka.png')}}" alt="">
            </a>
          </div>
          <div class="box-body">
              <div class="text-center">
                <h3 class="my-10"><a href="{{route('mahasiswa.perkuliahan.mbkm.pertukaran')}}">Aktivitas MBKM Pertukaran</a></h3>
                {{-- <h6 class="user-info mt-0 mb-10 text-fade">Designer</h6> --}}
                {{-- <p class="text-fade w-p85 mx-auto"></p> --}}
              </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6 px-50">
        <div class="box ribbon-box">
          <div class="ribbon-two ribbon-two-danger"><span>MBKM</span></div>
          <div class="box-header no-border p-0">				
            <a href="{{route('mahasiswa.perkuliahan.mbkm.non-pertukaran')}}">
              <img class="img-fluid" src="{{asset('images/images/avatar/icon_kampus_merdeka.png')}}" alt="">
            </a>
          </div>
          <div class="box-body">
              <div class="text-center">
                <h3 class="my-10"><a href="{{route('mahasiswa.perkuliahan.mbkm.non-pertukaran')}}">Aktivitas MBKM Non Pertukaran</a></h3>
                {{-- <h6 class="user-info mt-0 mb-10 text-fade">Full Stack Developer</h6> --}}
                {{-- <p class="text-fade w-p85 mx-auto my-15">Non Pertukaran Pelajar</p> --}}
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection