@extends('layouts.mahasiswa')
@section('title')
Dashboard
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
                            <h2>Nilai Perkuliahan,  {{auth()->user()->name}}</h2>
                            <p class="text-dark mb-0 fs-16">
                                SIAKAD Universitas Sriwijaya
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <div class="row mt-10">
        <div class="col-lg-12 col-xl-12 mt-0">
            <!-- Nav tabs -->
            <ul class="nav nav-pills justify-content-left" role="tablist">
                <li class="nav-item bg-secondary-light"> <a class="nav-link" href="{{route('mahasiswa.perkuliahan.nilai-perkuliahan')}}"><span><i class="fa-solid fa-file-invoice"></i></span> <span class="hidden-xs-down ms-15">Kartu Hasil Studi</span></a> </li>
                <li class="nav-item bg-secondary-light"> <a class="nav-link active" data-bs-toggle="tab" href="#transkrip-mahasiswa" role="tab"><span><i class="fa-solid fa-graduation-cap"></i></span> <span class="hidden-xs-down ms-15">Transkrip Mahasiswa</span></a> </li>
            </ul>
            <div class="box">
                <!-- Tab panes -->
                <div class="tab-content tabcontent">
                    <div class="tab-pane active" id="transkrip-mahasiswa" role="tabpanel">
                        <div class="col-xl-12 col-lg-12 col-12">
                            <div class="bg-primary-light big-side-section mb-20 shadow-lg">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                                            <div class="box box-body">
                                                <div class="row mb-10">
                                                    <div class="col-xxl-12">
                                                        <div class="box box-body mb-0 bg-white">
                                                            <div class="row mb-3">
                                                                <div class="col-12">
                                                                    <div class="box no-shadow mb-0 bg-transparent">
                                                                        <div class="box-header no-border px-0">
                                                                            <a type="button" href="{{route('mahasiswa.perkuliahan.nilai-perkuliahan')}}" class="btn btn-warning btn-rounded waves-effect waves-light">
                                                                            <i class="fa-solid fa-arrow-left"></i>
                                                                            </a>
                                                                            <h3 class="box-title px-3">Histori Nilai</h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <div>
                                                            <div class="row">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-striped text-left">
                                                                        <thead>
                                                                            <tr>
                                                                            <th class="text-center align-middle">No</th>
                                                                            <th class="text-center align-middle">Kode Mata Kuliah</th>
                                                                            <th class="text-center align-middle">Nama Mata Kuliah</th>
                                                                            <th class="text-center align-middle">SKS</th>
                                                                            <th class="text-center align-middle">Semester</th>
                                                                            <th class="text-center align-middle">Nilai Angka</th>
                                                                            <th class="text-center align-middle">Nilai Huruf</th>
                                                                            <th class="text-center align-middle">Nilai Indeks</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @php

                                                                                $no=1;
                            
                                                                            @endphp

                                                                            @if(!empty($transkrip))
                                                                            <tr>
                                                                                <td class="text-center align-middle bg-dark" colspan="9">Nilai Perkuliahan</td>
                                                                            </tr>
                                                                            @foreach($transkrip as $d)
                                                                                <tr>
                                                                                    <td class="text-center align-middle">{{$no++}}</td>
                                                                                    <td class="text-center align-middle">{{$d->kode_mata_kuliah}}</td>
                                                                                    <td>{{$d->nama_mata_kuliah}}</td>
                                                                                    <td class="text-center align-middle">{{$d->sks_mata_kuliah}}</td>
                                                                                    <td class="text-center align-middle">{{$d->nama_semester}}</td>
                                                                                    <td class="text-center align-middle">{{empty($d->nilai_angka) ? 'Nilai Belum Diisi' : $d->nilai_angka}}</td>
                                                                                    <td class="text-center align-middle">{{empty($d->nilai_huruf) ? 'Nilai Belum Diisi' : $d->nilai_huruf}}</td>
                                                                                    <td class="text-center align-middle">{{empty($d->nilai_indeks) ? 'Nilai Belum Diisi' : $d->nilai_indeks}}</td>
                                                                                </tr>
                                                                            @endforeach 
                                                                            @endif 

                                                                            @if(!empty($nilai_konversi))
                                                                            <tr>
                                                                                <td class="text-center align-middle bg-dark" colspan="9">Nilai Konversi Aktivitas</td>
                                                                            </tr>
                                                                            @foreach($nilai_konversi as $n)
                                                                                <tr>
                                                                                    <td class="text-center align-middle">{{$no++}}</td>
                                                                                    <td class="text-center align-middle">{{$n->kode_mata_kuliah}}</td>
                                                                                    <td>{{$n->nama_mata_kuliah}}</td>
                                                                                    <td class="text-center align-middle">{{$n->sks_mata_kuliah}}</td>
                                                                                    <td class="text-center align-middle">{{$n->nama_semester}}</td>
                                                                                    <td class="text-center align-middle">{{empty($n->nilai_angka) ? 'Nilai Belum Diisi' : $n->nilai_angka}}</td>
                                                                                    <td class="text-center align-middle">{{empty($n->nilai_huruf) ? 'Nilai Belum Diisi' : $n->nilai_huruf}}</td>
                                                                                    <td class="text-center align-middle">{{empty($n->nilai_indeks) ? 'Nilai Belum Diisi' : $n->nilai_indeks}}</td>
                                                                                </tr>
                                                                            @endforeach 
                                                                            @endif  

                                                                            @if(!empty($nilai_transfer))
                                                                            <tr>
                                                                                <td class="text-center align-middle bg-dark" colspan="9">Nilai Transfer Pendidikan</td>
                                                                            </tr>
                                                                            @foreach($nilai_transfer as $nt)
                                                                                <tr>
                                                                                    <td class="text-center align-middle">{{$no++}}</td>
                                                                                    <td class="text-center align-middle">{{$nt->kode_mata_kuliah}}</td>
                                                                                    <td>{{$nt->nama_mata_kuliah}}</td>
                                                                                    <td class="text-center align-middle">{{$nt->sks_mata_kuliah}}</td>
                                                                                    <td class="text-center align-middle">{{$nt->nama_semester}}</td>
                                                                                    <td class="text-center align-middle">{{empty($nt->nilai_angka) ? 'Nilai Belum Diisi' : $nt->nilai_angka}}</td>
                                                                                    <td class="text-center align-middle">{{empty($nt->nilai_huruf) ? 'Nilai Belum Diisi' : $nt->nilai_huruf}}</td>
                                                                                    <td class="text-center align-middle">{{empty($nt->nilai_indeks) ? 'Nilai Belum Diisi' : $nt->nilai_indeks}}</td>
                                                                                </tr>
                                                                            @endforeach 
                                                                            @endif      
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
