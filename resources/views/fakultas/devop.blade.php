@extends('layouts.mahasiswa')
@section('title')
Underdevelopment
@endsection
@section('content')
<div class="row align-items-end">
    <div class="col-xl-12 col-12">
        <div class="box bg-primary-light pull-up">
            <div class="box-body p-xl-0">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-3"><img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" alt="">
                    </div>
                    <div class="col-12 col-lg-9">
                        <h2>Halaman Under Development</h2>
                        <p class="text-dark mb-0 fs-16">
                            Universitas Sriwijaya
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
                    {{-- <div class="d-flex justify-content-end">
                        <form action="{{route('univ.mata-kuliah.sync')}}" method="get" id="sync-form">
                            <button class="btn btn-primary waves-effect waves-light" type="submit"><i class="fa fa-refresh"></i> Sinkronisasi</button>
                        </form>
                        <span class="divider-line mx-1"></span>
                        <button class="btn btn-success waves-effect waves-light" href="#"><i class="fa fa-plus"></i> Tambah Kurikulum</button>
                    </div> --}}
                </div>
                <div class="box-body text-center">
                    <h1>UNDER DEVELOPMENT</h1>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
