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
                            <h2>Kartu Studi Mahasiswa</h2>
                            <p class="text-dark align-middle mb-0 fs-16">
                                Universitas Sriwijaya
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="content" style="text-align-last: center; padding-bottom:20px">
    <div class="row px-20">
      <div class="col-12 col-lg-6 px-50">
        <div class="box ribbon-box">
          <div class="ribbon-two ribbon-two-primary"><span>KSM</span></div>
          <div class="box-header no-border p-0">				
            <a href="{{route('mahasiswa.krs.index')}}">
              <img class="img-fluid" src="{{asset('images/images/avatar/ksm_regular.png')}}" alt="">
            </a>
          </div>
          <div class="box-body">
              <div class="text-center">
                <h3 class="my-10"><a href="{{route('mahasiswa.krs.index')}}">Kartu Studi Mahasiswa</a></h3>
                {{-- <h6 class="user-info mt-0 mb-10 text-fade">Designer</h6> --}}
                <p class="text-fade w-p85 mx-auto">KSM Regular, KSM Kampus Merdeka, Aktivitas Regular </p>
              </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6 px-50">
        <div class="box ribbon-box">
          <div class="ribbon-two ribbon-two-danger"><span>Magang</span></div>
          <div class="box-header no-border p-0">				
            <a href="{{route('mahasiswa.perkuliahan.aktivitas-magang.index')}}">
              <img class="img-fluid" src="{{asset('images/images/avatar/magang.png')}}" alt="">
            </a>
          </div>
          <div class="box-body">
              <div class="text-center">
                <h3 class="my-10"><a href="{{route('mahasiswa.perkuliahan.aktivitas-magang.index')}}">Aktivitas Magang</a></h3>
                {{-- <h6 class="user-info mt-0 mb-10 text-fade">Full Stack Developer</h6> --}}
                <p class="text-fade w-p85 mx-auto">Aktivitas Magang Kampus Merdeka</p>
              </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection