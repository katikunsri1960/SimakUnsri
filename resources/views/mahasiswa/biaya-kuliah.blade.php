@extends('layouts.mahasiswa')
@section('title')
Biaya Kuliah
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
                            <h2>Biaya Kuliah Mahasiswa</h2>
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
        <div class="col-xl-4 col-md-6 col-12">
            <div class="box bs-5 border-primary rounded mb-10 pull-up"
                style="background-image: url(../images/svg-icon/color-svg/st-1.svg); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">UKT</p>
                        <h4 class="mt-5 mb-0" style="color:#0052cc">Rp. 3.375.000</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-12">
            <div class="box bs-5 border-warning rounded mb-10 pull-up"
                style="background-image: url(../images/svg-icon/color-svg/st-2.svg); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">UKT yang Belum Dibayar</p>
                        <h4 class="mt-5 mb-0" style="color:#0052cc">Rp. 3.375.000</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-12">
            <div class="box bs-5 border-danger rounded mb-10 pull-up"
                style="background-image: url(../images/svg-icon/color-svg/st-3.svg); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">Batas Akhir Pembayaran</p>
                        <h4 class="mt-5 mb-0" style="color:#0052cc">20 Januari 2024</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-12">
            <div class="box box-body mb-0 bg-light">
                <div class="row">
                    <div class="col-xl-4 col-lg-12">
                        <h3 class="fw-500 text-dark mt-0">Riwayat Pembayaran Uang Kuliah Tunggal</h3>
                    </div>                             
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Semester</th>
                                    <th>Tahun Ajaran</th>                                    
                                    <th>Nominal Bayar</th>
                                    <th>Tanggal Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Ganjil</td>
                                    <td>2023/2024</td>
                                    <td>Rp. 3.375.000</td>
                                    <td>1 Agustus 2023</td>
                                </tr>
                            </tbody>
					  </table>
                    </div>
                </div>
            </div>
        </div>
    </div>			
</section>
@endsection
