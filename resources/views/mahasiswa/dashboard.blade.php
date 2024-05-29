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
                            <h2>Hello {{auth()->user()->name}}, Welcome Back!</h2>
                            <p class="text-dark mb-0 fs-16">
                            Selamat Datang di SIAKAD (Sistem Informasi Akademik) Universitas Sriwijaya
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
                    <h4 class="box-title">Dashboard</h4>
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
            <div class="box text-white bg-success pull-up bg-opacity-50">
                <div class="box-header with-border">
                <div class="d-flex align-items-center">
                        <span class="rounded bg-primary p-2"><i class="fa fa-star"></i></span>
                        <h4 class="box-title text-white mx-10">IPK</h4>
                    </div>
                </div>
                <div class="box-body">
                    <div class="flex-grow-1">
                        @if (!empty($akm->ipk))
                            <h2 class="mb-5">{{$akm->ipk}}</h2>
                        @else
                            <h4 class="mt-5 mb-0" style="color:#0052cc">Tidak Diisi</h4>
                        @endif
                        
                        <p class="text-fade mb-0 fs-12 text-white">Tetap dari semester lalu</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="box text-white bg-danger pull-up">
                <div class="box-header with-border">
                    <div class="d-flex align-items-center">
                        <span class="rounded bg-primary p-2"><i class="fa fa-money"></i></span>
                        <h4 class="box-title text-white mx-10">UKT</h4>
                    </div>
                </div>
                <div class="box-body">
                    <div class="flex-grow-1">
                        @if (!empty($akm->biaya_kuliah_smt))
                            <h2 class="mb-5">Rp  {{number_format($akm->biaya_kuliah_smt, 0, ',', '.') }}</h2>
                        @else
                            <h4 class="mt-5 mb-0" style="color:#0052cc">Tidak Diisi</h4>
                        @endif
                        <p class="text-fade mb-0 fs-12 text-white">Nominal UKT</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="box text-white bg-info pull-up">
                <div class="box-header with-border">
                    <div class="d-flex align-items-center">
                        <span class="rounded bg-primary p-2"><i class="fa fa-graduation-cap"></i></span>
                        <h4 class="box-title text-white mx-10">Semester</h4>
                    </div>
                </div>
                <div class="box-body">
                    <div class="flex-grow-1">
                        <h2 class="mb-5 text-center">{{$semester_ke}}</h2>
                        @if((int) substr($riwayat_pendidikan->id_periode_masuk, 0, 4) >= 2014)
                            <p class="text-fade mb-0 fs-12 text-white">Batas Studi : 10 Semester</p>
                        @else
                            <p class="text-fade mb-0 fs-12 text-white">Batas Studi : 14 Semester</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="box text-white bg-warning pull-up">
                <div class="box-header with-border">
                <div class="d-flex align-items-center">
                        <span class="rounded bg-primary p-2"><i class="fa fa-file-text-o"></i></span>
                        <h4 class="box-title text-white mx-10">SKS Total</h4>
                    </div>
                </div>
                <div class="box-body">
                    <div class="flex-grow-1">
                        @if (!empty($akm->sks_total))
                        <h2 class="mb-5">{{$akm->sks_total}} SKS</h2>
                        @else
                            <h4 class="mt-5 mb-0" style="color:#0052cc">Tidak Diisi</h4>
                        @endif
                        
                        {{-- <p class="text-fade mb-0 fs-12 text-white">28 SKS lagi untuk lulus</p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="box">
                <div class="box-header">
                    <h4 class="box-title">Grafik SKS Semester</h4>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12 col-12">
                            <div id="sks-diambil"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-12">
            <div class="box">
                <div class="box-header">
                    <h4 class="box-title">Grafik IPS </h4>
                </div>
                <div class="box-body">
                    <!-- <p class="text-fade">Grafik IPK</p> -->
                    <!-- <h3 class="mt-0 mb-20">21 h 30 min <small class="text-danger"><i class="fa fa-arrow-down ms-25 me-5"></i> 15%</small></h3> -->
                    <div id="charts_widget_1_chart"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('js')
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> --}}
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/chartist-js-develop/chartist.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{asset('assets/js/pages/widget-chartist.js')}}"></script>
<script>
    $(document).ready(function() {
        // Data SKS yang diambil
        // Ambil data dari PHP dan ubah menjadi format JSON
        var sksData = {!! json_encode($smt->map(function($item) {
                return $item->sks_semester;
            })) !!};

        var smt_ke = {!! json_encode($semester_ke) !!};

        // let smt = "Semester "
        // let text = "";
        // for (let i = 0; i < smt_ke; i++) {
        //     text += "'"+(i+1)+"'";
        // }

        // smt_ke = array.from(text)

        // var categories = smt_ke.map(i => 'Semester ' + i);
        

        let categories = [];
        for (let i = 0; i < smt_ke; i++) {
            categories.push("Semester " + (i+1));
        }
        // var sksSeriesData = [];
        
            // Tampilkan data di konsol
        console.log("Data SKS:", sksData);
        console.log(categories);

        var options = {
            series: [{
                name: 'SKS',
                data: sksData
            }],
            chart: {
                    foreColor:"#bac0c7",
                    type: 'bar',
                    height: 350,
                    stacked: true,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: true
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                            position: 'bottom',
                            offsetX: -10,
                            offsetY: 0
                        }
                    }
                }],		
                grid: {
                    show: true,
                    borderColor: '#f7f7f7',      
                },
                colors:['#6993ff', '#f64e60'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '10%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    type: 'data',
                    categories: categories,
                },
                legend: {
                    show: false,
                },
                fill: {
                    opacity: 1
                }
            };

            var chart = new ApexCharts(document.querySelector("#sks-diambil"), options);
            chart.render();
        });

</script>