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
                            <h2>Aktivitas MBKM-Non Perkuliahan</h2>
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
  <div class="row mt-20">
    <div class="col-lg-12 col-xl-12 mt-5">
      <div class="box">
        <div class="row mx-20 " >
          <div class="col-12 px-25">
            <div class="box no-shadow mb-0 bg-transparent">
              <div class="box-header no-border px-0" style="text-align-last: left; padding-bottom:20px">
                <a type="button" href="{{route('mahasiswa.krs')}}" class="btn btn-warning btn-rounded waves-effect waves-light">
                <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h3 class="box-title px-3">Aktivitas MBKM-Non Perkuliahan</h3>
              </div>
            </div>
          </div>
        <div>
        <div class="row mx-20 mt-50">
          <div class="col-lg-3 col-md-6 col-12">
            <div class="box ribbon-box">
              <div class="ribbon-two ribbon-two-primary"><span>Magang</span></div>
              <div class="box-header no-border p-0">				
                <a href="">
                  <img class="img-fluid" src="{{asset('images/images/icon/icon_tombol_magang.png')}}" alt="">
                </a>
              </div>
              <div class="box-body">
                  <div class="text-center">
                    <h4 class="my-10"><a href="">Magang/Praktik Kerja</a></h4>
                    {{-- <h6 class="user-info mt-0 mb-10 text-fade">Designer</h6> --}}
                    <p class="text-fade w-p85 mx-auto">Aktivitas Magang/Praktik Kerja</p>
                  </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-12">
            <div class="box ribbon-box">
              <div class="ribbon-two ribbon-two-danger"><span>   Asistensi</span></div>
              <div class="box-header no-border p-0">				
                <a href="">
                  <img class="img-fluid" src="{{asset('images/images/icon/icon_tombol_asistensi.png')}}" alt="">
                </a>
              </div>
              <div class="box-body">
                  <div class="text-center">
                    <h4 class="my-10"><a href="">Asistensi Mengajar</a></h4>
                    {{-- <h6 class="user-info mt-0 mb-10 text-fade">Full Stack Developer</h6> --}}
                    <p class="text-fade w-p85 mx-auto">Aktivitas Asistensi Mengajar di Satuan Pendidikan</p>
                  </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-12">
            <div class="box ribbon-box">
              <div class="ribbon-two ribbon-two-success"><span>Penelitian</span></div>
              <div class="box-header no-border p-0">				
                <a href="">
                  <img class="img-fluid" src="{{asset('images/images/icon/icon_tombol_penelitian.png')}}" alt="">
                </a>
              </div>
              <div class="box-body">
                  <div class="text-center">
                    <h4 class="my-10"><a href="">Penelitian/Riset</a></h4>
                    {{-- <h6 class="user-info mt-0 mb-10 text-fade">Designer</h6> --}}
                    <p class="text-fade w-p85 mx-auto">Aktivitas Penelitian/Riset</p>
                  </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-12">
            <div class="box ribbon-box">
              <div class="ribbon-two ribbon-two-warning"><span>Kemanusiaan</span></div>
              <div class="box-header no-border p-0">				
                <a href="">
                  <img class="img-fluid" src="{{asset('images/images/icon/icon_tombol_kemanusiaan.png')}}" alt="">
                </a>
              </div>
              <div class="box-body">
                  <div class="text-center">
                    <h4 class="my-10"><a href="">Proyek Kemanusiaan</a></h4>
                    {{-- <h6 class="user-info mt-0 mb-10 text-fade">Full Stack Developer</h6> --}}
                    <p class="text-fade w-p85 mx-auto">Aktivitas Proyek Kemanusiaan</p>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row mx-20">
          <div class="col-lg-3 col-md-6 col-12">
            <div class="box ribbon-box">
              <div class="ribbon-two ribbon-two-primary"><span>Wirausaha</span></div>
              <div class="box-header no-border p-0">				
                <a href="">
                  <img class="img-fluid" src="{{asset('images/images/icon/icon_tombol_kegiatan_wirausaha.png')}}" alt="">
                </a>
              </div>
              <div class="box-body">
                  <div class="text-center">
                    <h4 class="my-10"><a href="">Kegiatan Wirausaha</a></h4>
                    {{-- <h6 class="user-info mt-0 mb-10 text-fade">Designer</h6> --}}
                    <p class="text-fade w-p85 mx-auto">Aktivitas Kegiatan Wirausaha</p>
                  </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-12">
            <div class="box ribbon-box">
              <div class="ribbon-two ribbon-two-danger"><span>Studi</span></div>
              <div class="box-header no-border p-0">				
                <a href="">
                  <img class="img-fluid" src="{{asset('images/images/icon/icon_tombol_studi_independen.png')}}" alt="">
                </a>
              </div>
              <div class="box-body">
                  <div class="text-center">
                    <h4 class="my-10"><a href="">Studi/Proyek Independen</a></h4>
                    {{-- <h6 class="user-info mt-0 mb-10 text-fade">Full Stack Developer</h6> --}}
                    <p class="text-fade w-p85 mx-auto">Aktivitas Studi/Proyek Independen</p>
                  </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-12">
            <div class="box ribbon-box">
              <div class="ribbon-two ribbon-two-success"><span>Membangun Desa</span></div>
              <div class="box-header no-border p-0">				
                <a href="">
                  <img class="img-fluid" src="{{asset('images/images/icon/icon_tombol_membangun_desa.png')}}" alt="">
                </a>
              </div>
              <div class="box-body">
                  <div class="text-center">
                    <h4 class="my-10"><a href="">Membangun Desa</a></h4>
                    {{-- <h6 class="user-info mt-0 mb-10 text-fade">Designer</h6> --}}
                    <p class="text-fade w-p85 mx-auto">Aktivitas Membangun Desa/Kuliah Kerja Nyata Tematik</p>
                  </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-12">
            <div class="box ribbon-box">
              <div class="ribbon-two ribbon-two-warning"><span>Bela Negara</span></div>
              <div class="box-header no-border p-0">				
                <a href="">
                  <img class="img-fluid" src="{{asset('images/images/icon/icon_tombol_bela_negara.png')}}" alt="">
                </a>
              </div>
              <div class="box-body">
                  <div class="text-center">
                    <h4 class="my-10"><a href="">Bela Negara</a></h4>
                    {{-- <h6 class="user-info mt-0 mb-10 text-fade">Full Stack Developer</h6> --}}
                    <p class="text-fade w-p85 mx-auto">Aktivitas Bela Negara</p>
                  </div>
              </div>
            </div>
          </div>
        </div>
      <div>
    </div>
  </div>
</div>
</section>
@endsection