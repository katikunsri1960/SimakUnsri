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
                            <p class="text-dark align-middle mb-0 fs-16">
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
                    <h4 class="box-title"><i class="fa fa-file-invoice"></i> KRS</h4>
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
    <div class="row mt-20">
        <div class="col-lg-12 col-xl-12 mt-5">
            <div class="box">
				<!-- Nav tabs -->
                <ul class="nav nav-pills justify-content-left" role="tablist">
                    <li class="nav-item bg-secondary-light rounded10"> <a class="nav-link " data-bs-toggle="tab" href="#krs" role="tab"><span><i class="fa-solid fa-file-invoice"></i></span> <span class="hidden-xs-down ms-15">KRS</span></a> </li>
                    <li class="nav-item bg-secondary-light rounded10"> <a class="nav-link active" data-bs-toggle="tab" href="#data-kelas-kuliah" role="tab"><span><i class="fa-solid fa-graduation-cap"></i></span> <span class="hidden-xs-down ms-15">Data Kelas Kuliah</span></a> </li>
                </ul>
                <div class="row">
                    <div class="col-12">
                        <div class="box no-shadow">
                            <div class="box-header no-border px-0 mb-40">
                                <ul class="box-controls pull-right d-md-flex d-none">
                                    <!-- <li> -->
                                    <div class="clearfix">
                                        <!-- <a class="waves-effect waves-light btn btn-app btn-info" href="#">
                                            <i class="fa fa-print"></i> Print
                                        </a> -->
                                        <a class="waves-effect waves-light btn btn-app btn-success mb-20" href="#">
                                            <i class="fa fa-print"></i> Print
                                        </a>
                                    </div>
                                    <div class="form-group m-10">
                                        <!-- <label class="form-label">Interested in</label> -->
                                        <select class="form-select">
                                            <option>2023/2024 Genap</option>
                                            <option>2023/2024 Ganjil</option>
                                            <option>2022/2023 Genap</option>
                                            <option>2022/2023 Ganjil</option>
                                            <option>2021/2022 Genap</option>
                                            <option>2021/2022 Ganjil</option>
                                        </select>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tab panes -->
                <div class="tab-content tabcontent">
                    @include('mahasiswa.krs.include.krs')
                    {{-- @include('mahasiswa.krs.include.data-kelas-kuliah') --}}

                    <div class="tab-pane active" id="data-kelas-kuliah" role="tabpanel">
                        <!-- <div class="tab-pane active" id="krs" role="tabpanel"> -->
                            <div class="col-xl-12 col-lg-12 col-12">
                                <div class="bg-primary-light rounded20 big-side-section">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                                                <div class="box box-body">
                                                    <div class="row">
                                                        <div class="col-xl-12 col-lg-12">
                                                            <h3 class="fw-500 text-dark mt-0 mb-20">Data Kelas Kuliah</h3>
                                                        </div>                             
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xxl-12">
                                                            <div class="box box-body mb-0 bg-light">
                                                                <div class="row">
                                                                    <div class="col-xl-4 col-lg-12">
                                                                        <h3 class="fw-500 text-dark mt-0">Semester 6</h3>
                                                                    </div>                             
                                                                </div>
                                                                <div class="row">
                                                                    <div class="table-responsive">
                                                                        <table id="data-matkul" class="table table-bordered table-striped text-center">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="text-center align-middle">No</th>
                                                                                    <th class="text-center align-middle">Kode Mata Kuliah</th>
                                                                                    <th class="text-center align-middle">Nama Mata Kuliah</th>                                    
                                                                                    <th class="text-center align-middle">SKS Mata Kuliah</th>
                                                                                    <th class="text-center align-middle">Jadwal Kuliah</th>
                                                                                    <th class="text-center align-middle">Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @php
                                                                                    $no_a=1;
                                                                                @endphp
                        
                                                                                @foreach ($matakuliah_kurikulum as $data)
                                                                                    <tr>
                                                                                        <td class="text-center align-middle">{{ $no_a++ }}</td>
                                                                                        <td class="text-center align-middle">{{$data->kode_mata_kuliah}}</td>
                                                                                        <td class="text-start align-middle">{{$data->nama_mata_kuliah}}</td>
                                                                                        <td class="text-center align-middle">{{$data->sks_mata_kuliah}}</td>
                                                                                        <td>
                                                                                            {{-- <button type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"
                                                                                                        class="waves-effect waves-light btn btn-success-light mb-5">
                                                                                                    Lihat Kelas Kuliah
                                                                                            </button> --}}
                                                                                            {{-- <div class="btn-group">
                                                                                                <button class="waves-effect rounded waves-light btn btn-success-light mb-5 no-caret" type="button" data-bs-toggle="dropdown">Lihat Kelas Kuliah</button>
                                                                                                <div class="dropdown-menu">
                                                                                                    <a class="dropdown-item" href="#">Action</a>
                                                                                                    <a class="dropdown-item" href="#">Another action</a>
                                                                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="collapse" id="collapseExample">
                                                                                                <div class="card card-body">
                                                                                                    
                                                                                                </div>
                                                                                            </div> --}}

                                                                                            <p>
                                                                                                <button class="waves-effect rounded waves-light btn btn-success-light mb-5 no-caret" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample{{$data->id_matkul}}" aria-expanded="false" aria-controls="collapseExample">
                                                                                                    Lihat Kelas Kuliah
                                                                                                </button>
                                                                                            </p>
                                                                                            <div class="collapse" id="collapseExample{{$data->id_matkul}}">
                                                                                                <div class="card card-body">
                                                                                                    <table id="data-kelas" class="table table-bordered table-striped text-center">
                                                                                                        <thead>
                                                                                                            <tr>
                                                                                                                <th class="text-center align-middle">No</th>
                                                                                                                <th class="text-center align-middle">Mata Kuliah</th>
                                                                                                                <th class="text-center align-middle">Kelas Kuliah</th>
                                                                                                                <th class="text-center align-middle">Jadwal Kuliah</th>
                                                                                                                <th class="text-center align-middle">Peserta</th>
                                                                                                            </tr>
                                                                                                        </thead>
                                                                                                        <tbody>
                                                                                                            @php
                                                                                                                $no=1;
                                                                                                            @endphp
                                                    
                                                                                                            @foreach ($kelas_kuliah as $d)
                                                                                                                @if($d->id_matkul == $data->id_matkul)
                                                                                                                    <tr>
                                                                                                                        <td class="text-center align-middle">{{ $no++ }}</td>
                                                                                                                        <td class="text-center align-middle">{{$d->nama_mata_kuliah}}</td>
                                                                                                                        <td class="text-center align-middle">{{$d->nama_kelas_kuliah}}</td>
                                                                                                                        <td class="text-start align-middle">{{$d->tanggal_mulai_efektif}}</td>
                                                                                                                        <td class="text-center align-middle">-</td>
                                                                                                                        <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                                                                                    </tr>
                                                                                                                @endif
                                                                                                            @endforeach
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>		
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                </div>
				<!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>		
    
    	
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script>
    $(function () {
        "use strict";

        $('#data-matkul').DataTable({
            
        });

    });
</script>
@endpush