@extends('layouts.mahasiswa')
@section('title')
Ambil Aktivitas Mahasiswa
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
                            <h2 class="mb-10">Halaman Kartu Rencana Studi Mahasiswa</h2>
                            <p class="text-dark align-middle mb-0 fs-16">
                                SIMAK Universitas Sriwijaya
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
        <div class="col-xl-4 col-md-6 col-12">
            <div class="box bs-5 border-primary rounded mb-10 pull-up"
                style="background-image: url({{asset('images/images/svg-icon/color-svg/st-1.svg')}}); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">IPS | IPK</p>
                        <h4 class="mt-5 mb-0" style="color:#0052cc">{{$akm->ips}} | {{$akm->ipk}}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-12">
            <div class="box bs-5 border-warning rounded mb-10 pull-up"
                style="background-image: url({{asset('images/images/svg-icon/color-svg/st-3.svg')}}); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">SKS Maksimum</p>
                        <h4 class="mt-5 mb-0" style="color:#0052cc">{{$sks_max}}</h4>
                        {{-- <p class="text-fade mb-0 fs-12 text-white">Sisa SKS : ({{$sks_max}}-{{$sks_mk}})</p> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-12">
            <div class="box bs-5 border-success rounded mb-10 pull-up"
                style="background-image: url({{asset('images/images/svg-icon/color-svg/st-4.svg')}}); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">Dosen PA</p>
                        @if (!empty($riwayat_pendidikan->nama_dosen))
                            <h4 class="mt-5 mb-0" style="color:#0052cc">{{ $riwayat_pendidikan->nama_dosen }}</h4>
                        @else
                            <h4 class="mt-5 mb-0" style="color:#0052cc">Tidak Diisi</h4>
                        @endif
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
                    <li class="nav-item bg-secondary-light rounded10"> <a class="nav-link" href="{{route('mahasiswa.krs')}}"><span><i class="fa-solid fa-file-invoice"></i></span> <span class="hidden-xs-down ms-15">KRS</span></a> </li>
                    <li class="nav-item bg-secondary-light rounded10"> <a class="nav-link active" data-bs-toggle="tab" href="#data-kelas-kuliah" role="tab"><span><i class="fa-solid fa-graduation-cap"></i></span> <span class="hidden-xs-down ms-15">Data Kelas Kuliah</span></a> </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content tabcontent">
                    <div class="tab-pane active" id="data-kelas-kuliah" role="tabpanel">
                        <div class="col-xl-12 col-lg-12 col-12">
                            <div class="bg-primary-light rounded20 big-side-section mb-20 shadow-lg">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                                            <div class="row mb-20">
                                                <div class="col-xxl-12">
                                                    <div class="box box-body mb-0 bg-white">
                                                        <div class="row mb-3">
                                                            <div class="col-12">
                                                                <div class="box no-shadow mb-0 bg-transparent">
                                                                    <div class="box-header no-border px-0">
                                                                        <a type="button" href="{{route('mahasiswa.krs')}}" class="btn btn-warning btn-rounded waves-effect waves-light">
                                                                        <i class="fa-solid fa-arrow-left"></i>
                                                                        </a>
                                                                        <h3 class="box-title px-3">Ambil Aktivitas Mahasiswa</h3>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <div>
                                                        <div class="row">
                                                            <div class="">
                                                                {{-- COPY DISINI --}}
                                                                <div class="container">

                                                                    <table id="data-matkul-regular" class="table table-bordered table-striped text-left">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="text-center align-middle">No</th>
                                                                                <th class="text-center align-middle">Kode Mata Kuliah</th>
                                                                                <th class="text-center align-middle" style="width: 100%">Nama Mata Kuliah</th>
                                                                                <th class="text-center align-middle">RPS</th>
                                                                                <th class="text-center align-middle">Semester Ke</th>
                                                                                <th class="text-center align-middle">SKS Mata Kuliah</th>
                                                                                <th class="text-center align-middle">Jumlah Kelas Kuliah</th>
                                                                                <th class="text-center align-middle">Lihat Kelas</th>
                                                                                {{-- <th class="text-center align-middle">Action</th> --}}
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @php
                                                                                $no_a=1;
                                                                                $isEnrolled = array_column($krs_regular->toArray(), 'id_matkul');
                                                                                $kelas_Enrolled = array_column($krs_regular->toArray(), 'id_kelas_kuliah');

                                                                            @endphp

                                                                            {{-- Tampilkan mata kuliah yang ada di $isEnrolled --}}
                                                                            @foreach ($rps as $data)
                                                                                @php
                                                                                    $isDisabled = $data->jumlah_kelas_kuliah == 0;
                                                                                    $isEnrolledMatkul = in_array($data->id_matkul, $isEnrolled);
                                                                                    $noRPS = empty($data->rencana_pembelajaran); // Assume $data->rps is a boolean or check if RPS is available
                                                                                @endphp
                                                                                <tr class="bg-success-light {{ $isDisabled ? 'disabled-row' : '' }}">
                                                                                    <td class="text-center align-middle">{{ $no_a++ }}</td>
                                                                                    <td class="text-start align-middle" style="white-space: nowrap;">{{ $data->kode_mata_kuliah }}</td>
                                                                                    <td class="text-start align-middle" style="white-space: nowrap;">{{ $data->nama_mata_kuliah }}</td>
                                                                                    <td class="text-center align-middle" style="white-space: nowrap;">
                                                                                        <button class="btn btn-warning-light lihat-rps" title="Lihat RPS" data-id-matkul="{{ $data->id_matkul }}"
                                                                                            {{ $isDisabled || $isEnrolledMatkul ? 'disabled' : '' }}>
                                                                                            <i class="fa fa-newspaper-o"></i> Lihat RPS
                                                                                        </button>
                                                                                    </td>
                                                                                    <td class="text-center align-middle">{{ $data->semester }}</td>
                                                                                    <td class="text-center align-middle">{{ $data->sks_mata_kuliah }}</td>
                                                                                    <td class="text-center align-middle">{{ $data->jumlah_kelas_kuliah }}</td>
                                                                                    <td class="text-center align-middle">
                                                                                        <button class="btn btn-success-light lihat-kelas-kuliah" title="Lihat kelas kuliah" data-id-matkul="{{ $data->id_matkul }}"
                                                                                            {{ $isDisabled || $isEnrolledMatkul || $noRPS ? 'disabled' : '' }}>
                                                                                            <i class="fa fa-eye"></i>
                                                                                        </button>
                                                                                        <div class="result-container" id="result-container_{{ $data->id_matkul }}" style="margin-top: 20px"></div>
                                                                                    </td>
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
@push('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('js')
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> --}}
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>

</script>
@endpush
