@extends('layouts.perpus')
@section('title')
Dashboard Perpustakaan
@endsection
@section('content')
<section class="content">
    <div class="row align-items-end">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3"><img src="images/images/svg-icon/color-svg/custom-14.svg" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h2>Hello {{auth()->user()->name}}, Welcome Back!</h2>
                            <p class="text-dark mb-0 fs-16">
                                Selamat Datang di SIMAK (Sistem Informasi Akademik) Universitas Sriwijaya
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
