@extends('layouts.mahasiswa')
@section('title')
Dashboard
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
                            <h2>Halo {{$riwayat_pendidikan->nama_mahasiswa}}, Selamat datang!</h2>
                            <p class="text-dark mb-0 fs-16">
                                SIMAK Universitas Sriwijaya
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-20">
        <div class="col-12">
            <div class="box no-shadow mb-0 bg-transparent">
                <div class="box-header no-border px-0">
                    <h4 class="box-title">Dashboard</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-12">
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
                            <h2 class="mt-5 mb-0">{{$akm->ipk}}</h2>
                        @else
                            <h2 class="mt-5 mb-0" style="color:#0052cc">Tidak Diisi</h2>
                        @endif
                        <p class="text-fade mb-0 fs-12 text-white">Indeks Prestasi Kumulatif</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-12">
            <div class="box text-white bg-danger pull-up">
                <div class="box-header with-border">
                    <div class="d-flex align-items-center">
                        <span class="rounded bg-primary p-2"><i class="fa fa-graduation-cap"></i></span>
                        <h4 class="box-title text-white mx-10">Semester</h4>
                    </div>
                </div>
                <div class="box-body">
                    <div class="flex-grow-1">
                        <h2 class="mt-5 mb-0 text-center">{{$semester_ke}}</h2>
                        <p class="text-fade mb-0 fs-12 text-white">Semester yang telah ditempuh</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-12">
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
                            <h2 class="mt-5 mb-0">{{$akm->sks_total}} SKS</h2>
                        @else
                            <h2 class="mt-5 mb-0" style="color:#0052cc">Tidak Diisi</h2>
                        @endif
                        
                        <p class="text-fade mb-0 fs-12 text-white">Total SKS yang telah ditempuh</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="box bg-transparent no-shadow mb-30">
            
    </div> --}}
    <div class="row mb-20">
        <div class="col-12-xl">
            <div class="box no-shadow mb-0 bg-transparent pb-10">
                <h4 class="box-title">Status Mahasiswa</h4>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="box mb-15 pull-up">
                <div class="box-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="mr-15 bg-primary-light h-50 w-50 l-h-60 rounded text-center">
                                <i class="fa fa-graduation-cap"></i>
                            </div>
                            <div class="d-flex flex-column font-weight-500" style="margin-left: 20px">
                                <a class="text-dark hover-primary mb-1 font-size-16"><strong>Status Aktif</strong></a>
                                @if ($status_aktif == 'Aktif')
                                    <a class="badge bg-primary badge-lg">{{$status_aktif}}</a>
                                @elseif($status_aktif == 'Lulus')
                                    <a class="badge bg-success badge-lg">{{$status_aktif}}</a>
                                @else
                                    <a class="badge bg-warning">{{$status_aktif}}</a>                               
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="box mb-15 pull-up">
                <div class="box-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="mr-15 bg-primary-light h-50 w-50 l-h-60 rounded text-center">
                                <i class="fa fa-pencil"></i>
                            </div>
                            <div class="d-flex flex-column font-weight-500" style="margin-left: 20px">
                                <a class="text-dark hover-primary mb-1 font-size-16"><strong>Nilai USEPT</strong></a>
                                <a class="badge bg-{{ $usept_data['class'] }} badge-lg">{{ $usept_data['score'] }} ({{ $usept_data['status'] }})</a>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="box mb-15 pull-up">
                <div class="box-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="mr-15 bg-primary-light h-50 w-50 l-h-60 rounded text-center">
                                <i class="fa fa-book-open"></i>
                            </div>
                            <div class="d-flex flex-column font-weight-500" style="margin-left: 20px">
                                <a class="text-dark hover-primary mb-1 font-size-16"><strong>Bebas Pustaka</strong></a>
                                @if (!$bebas_pustaka || empty($bebas_pustaka->file_bebas_pustaka))
                                    <a class="badge bg-danger badge-lg">Belum Bebas Pustaka</a>
                                @else
                                    <a class="btn btn-success btn-sm" href="{{asset('storage/'. $bebas_pustaka->file_bebas_pustaka) }}" title="Lihat Surat Bebas Pustaka" target="_blank">Sudah Bebas Pustaka</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="box mb-15 pull-up">
                <div class="box-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="mr-15 bg-primary-light h-50 w-50 l-h-60 rounded text-center">
                                <i class="fa fa-bookmark"></i>
                            </div>
                            <div class="d-flex flex-column font-weight-500" style="margin-left: 20px">
                                <a class="text-dark hover-primary mb-1 font-size-16"><strong>Upload Repository</strong></a>
                                @if (!$bebas_pustaka || empty($bebas_pustaka->link_repo))
                                    <a class="badge bg-danger badge-lg">Belum Upload Repository</a>
                                @else
                                    <a class="btn btn-success btn-sm" href="{{$bebas_pustaka->link_repo}}" title="Lihat Link Repository" target="_blank">{{$bebas_pustaka->link_repo}}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="box">
                <div class="box-header">
                    <h4 class="box-title">Grafik Satuan Kredit Semester</h4>
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
                    <h4 class="box-title">Grafik Indeks Prestasi Semester </h4>
                </div>
                <div class="box-body">
                    <div id="ips-diambil"></div>
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
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/chartist-js-develop/chartist.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- <script src="{{asset('assets/js/pages/widget-chartist.js')}}"></script> --}}
{{-- <script src="{{asset('assets/js/pages/echart-bar.js')}}"></script> --}}
<script src="{{asset('assets/js/pages/chartjs-int.js')}}"></script>
<script>
    $(document).ready(function() {
        // Data Grafik SKS
        var data = {!! json_encode($ips_sks_ipk->map(function($item) {
            return $item->sks_semester;
        })) !!};

        var smt_ke = {!! json_encode($semester_ke) !!};

        // Tambahkan angka 0 sebanyak selisih smt_ke - jumlah data
        while (data.length < smt_ke) {
            data.push(0);
        }

        let categories = [];
        for (let i = 0; i < smt_ke; i++) {
            categories.push("Semester " + (i + 1));
        }

        var options = {
            series: [{
                name: 'SKS',
                data: data,
            }],
            chart: {
                foreColor: "#bac0c7",
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
            colors: ['#f64e60', '#f64e60'],
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    dataLabels: {
                        position: 'top', // top, center, bottom
                    },
                    columnWidth: '40%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: true,
                formatter: function (data) {
                    return data + " SKS";
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#6c757d"]
                }
            },
            yaxis: {
                max: 24,
                tickAmount: 6,
                title: {
                    text: 'Satuan Kredit Semester (SKS)',
                },
                labels: {
                    formatter: function (data) {
                        return data.toFixed(0);
                    }
                },
            },
            xaxis: {
                type: 'data',
                categories: categories,
                axisBorder: {
                    show: false
                },
            },
        };

        var chart = new ApexCharts(document.querySelector("#sks-diambil"), options);
        chart.render();
    });

    $(document).ready(function() {
        // Data Grafik IPS
        var data = {!! json_encode($ips_sks_ipk->map(function($item) {
            return $item->ips;
        })) !!};

        var smt_ke = {!! json_encode($semester_ke) !!};

        // Tambahkan angka 0 sebanyak selisih smt_ke - jumlah data
        while (data.length < smt_ke) {
            data.push(0);
        }

        let categories = [];
        for (let i = 0; i < smt_ke; i++) {
            categories.push("Semester " + (i + 1));
        }

        var options = {
            series: [{
                name: 'IPS',
                data: data
            }],
            chart: {
                foreColor: "#bac0c7",
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
            colors: ['#04a08b', '#f64e60'],
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    dataLabels: {
                        position: 'top', // top, center, bottom
                    },
                    columnWidth: '40%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: true,
                formatter: function (data) {
                    return data;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#6c757d"]
                }
            },
            yaxis: {
                max: 4,
                tickAmount: 4,
                title: {
                    text: 'Indeks Prestasi Semester (IPS)',
                },
                labels: {
                    formatter: function (data) {
                        return data.toFixed(2);
                    }
                },
            },
            xaxis: {
                type: 'data',
                categories: categories,
                axisBorder: {
                    show: false
                },
            },
            fill: {
                opacity: 1
            }
        };

        var chart = new ApexCharts(document.querySelector("#ips-diambil"), options);
        chart.render();
    });

</script>
@endpush