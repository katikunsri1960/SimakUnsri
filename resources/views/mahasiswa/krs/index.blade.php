@extends('layouts.mahasiswa')
@section('title')
Kartu Rencana Studi
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
                            <h2>Kartu Rencana Studi Mahasiswa</h2>
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
            <div class="box no-shadow mb-0 bg-transparent">
                <div class="box-header no-border px-0">
                    <h4 class="box-title">KRS</h4>
                    <ul class="box-controls pull-right d-md-flex d-none">
                        <li>
                            <button class="btn btn-primary-light px-10">View All</button>
                        </li>
                        <li class="dropdown">
                            <button class="dropdown-toggle btn btn-primary-light px-10" data-bs-toggle="dropdown"
                                href="#" aria-expanded="false">Most
                                Popular</button>
                            <div class="dropdown-menu dropdown-menu-end" style="">
                                <a class="dropdown-item active" href="#">Today</a>
                                <a class="dropdown-item" href="#">Yesterday</a>
                                <a class="dropdown-item" href="#">Last week</a>
                                <a class="dropdown-item" href="#">Last month</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="box bs-5 border-primary rounded mb-10 pull-up"
                style="background-image: url(../images/svg-icon/color-svg/st-1.svg); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">IPS | IPK</p>
                        <h4 class="mt-5 mb-0" style="color:#0052cc">3.3 | 3.06</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="box bs-5 border-danger rounded mb-10 pull-up"
                style="background-image: url(../images/svg-icon/color-svg/st-2.svg); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">Jenis Kelas</p>
                        <h4 class="mt-5 mb-0" style="color:#0052cc">Regular</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="box bs-5 border-warning rounded mb-10 pull-up"
                style="background-image: url(../images/svg-icon/color-svg/st-3.svg); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">SKS Maksimum</p>
                        <h4 class="mt-5 mb-0" style="color:#0052cc">21</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="box bs-5 border-success rounded mb-10 pull-up"
                style="background-image: url(../images/svg-icon/color-svg/st-4.svg); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">Dosen PA/Wali</p>
                        <h4 class="mt-5 mb-0" style="color:#0052cc">Prof. Dr. Erwin, S.Si,. M.Si</h4>
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
                        <h3 class="fw-500 text-dark mt-0">Daftar Mata Kuliah</h3>
                    </div>                             
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Mata Kuliah</th>
                                    <th>Nama Mata Kuliah</th>                                    
                                    <th>Kode Kelas</th>
                                    <th>Nama Kelas</th>
                                    <th>SKS</th>
                                    <th>Nama Dosen</th>
                                    <th>Waktu Kuliah</th>
                                    <!-- <td>Action</td> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>FSK11711</td>
                                    <td>KALKULUS II</td>
                                    <td>IDL01</td>
                                    <td>Inderalaya A</td>
                                    <td>3</td>
                                    <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                    <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                    <!-- <td>
                                        <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                    </td> -->
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
