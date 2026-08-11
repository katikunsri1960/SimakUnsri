@extends('layouts.bak')
@section('title')
Kartu Hasil Studi
@endsection
@section('content')
<section class="content">
    <div class="row align-items-end">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3"><img
                                src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h2>Kartu Hasil Studi</h2>
                            <p class="text-dark mb-0 fs-16">
                                {{$riwayat->nama_mahasiswa}} ({{$riwayat->nim}})
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('swal')
    <div class="row mt-10">
        <div class="col-lg-12 col-xl-12 mt-0">

            <div class="box">
                <!-- Tab panes -->
                <div class="tab-content tabcontent">
                    <div class="tab-pane active" id="khs" role="tabpanel">
                        <div class="col-xl-12 col-lg-12 col-12">
                            <div class="bg-primary-light big-side-section mb-20 shadow-lg">
                                <div class="box box-body mb-0 bg-white">
                                    <div class="row mb-3 p-3">
                                        <div class="col-12">
                                            {{-- <div class="box no-shadow mb-0 bg-transparent">
                                                <div class="box-header no-border px-0">
                                                    <a type="button"
                                                        href="{{route('mahasiswa.perkuliahan.nilai-perkuliahan')}}"
                                                        class="btn btn-warning btn-rounded waves-effect waves-light">
                                                        <i class="fa-solid fa-arrow-left"></i>
                                                    </a>
                                                    <h3 class="box-title px-3">
                                                        {{empty($data_aktivitas[0]['nama_semester']) ? 'Data Tidak Ada'
                                                        : $data_aktivitas[0]['nama_semester']}}</h3>
                                                </div>
                                            </div> --}}
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="box bs-5 border-primary rounded mb-10 pull-up">
                                                <div class="box-body">
                                                    <div class="flex-grow-1">
                                                        <p class="mt-5 mb-5 text-fade fs-12">SKS Semester AKM</p>
                                                        <h4 class="mt-5 mb-0" style="color:#0052cc">
                                                            {{$akm->sks_semester}}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="box bs-5 border-danger rounded mb-10 pull-up">
                                                <div class="box-body">
                                                    <div class="flex-grow-1">
                                                        <p class="mt-5 mb-5 text-fade fs-12">IP Semester AKM</p>
                                                        <h4 class="mt-5 mb-0" style="color:#0052cc">
                                                            {{$akm->ips}}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="box bs-5 border-warning rounded mb-10 pull-up">
                                                <div class="box-body">
                                                    <div class="flex-grow-1">
                                                        <p class="mt-5 mb-5 text-fade fs-12">IP Komulatif AKM</p>
                                                        <h4 class="mt-5 mb-0" style="color:#0052cc">
                                                            {{$akm->ipk}}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-3">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped text-left">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center align-middle">Kode Mata Kuliah</th>
                                                        <th class="text-center align-middle">Nama Mata Kuliah </th>
                                                        <th class="text-center align-middle">SKS MK</th>

                                                        <th class="text-center align-middle">Nilai Indeks</th>
                                                        <th class="text-center align-middle">Nilai Huruf</th>
                                                    </tr>
                                                </thead>
                                                @php
                                                    $total_sks = 0;
                                                @endphp
                                                <tbody>
                                                    @foreach($nilai_mahasiswa as $d)
                                                    <tr>
                                                        <td class="text-center align-middle">{{$d->kode_mata_kuliah}} {{$d->kuisoner_count}}
                                                        </td>
                                                        <td class="text-start align-middle">{{$d->nama_mata_kuliah}}
                                                        </td>
                                                        <td class="text-center align-middle">{{$d->sks_mata_kuliah}}
                                                        </td>
                                                        <td class="text-center align-middle">{{$d->nilai_indeks=NULL ?
                                                            '-' : $d->nilai_indeks}}</td>
                                                        <td class="text-center align-middle">
                                                            {{empty($d->nilai_huruf) ? '-' : $d->nilai_huruf}}</td>

                                                    </tr>
                                                    @if ($d->nilai_huruf != NULL)
                                                        @php
                                                            $total_sks += $d->sks_mata_kuliah;
                                                        @endphp

                                                    @endif
                                                    @endforeach
                                                    @if($nilai_konversi->isNotEmpty())
                                                    <tr>
                                                        <td class="text-center align-middle bg-dark" colspan="9">Nilai Konversi Aktivitas</td>
                                                    </tr>
                                                        @foreach($nilai_konversi as $n)
                                                            <tr>
                                                                {{-- <td class="text-center align-middle">{{$no++}}</td> --}}
                                                                <td class="text-center align-middle">{{$n->kode_mata_kuliah}}</td>
                                                                <td class="text-start align-middle">{{$n->nama_mata_kuliah}}</td>
                                                                <td class="text-center align-middle">{{$n->sks_mata_kuliah}}</td>
                                                                <td class="text-center align-middle">{{$n->nilai_indeks=NULL ? 'Nilai Belum Diisi' : $n->nilai_indeks}}</td>
                                                                <td class="text-center align-middle">{{empty($n->nilai_huruf) ? 'Nilai Belum Diisi' : $n->nilai_huruf}}</td>
                                                            </tr>
                                                            @if ($n->nilai_huruf != NULL)
                                                                @php
                                                                    $total_sks += $n->sks_mata_kuliah;
                                                                @endphp

                                                            @endif
                                                        @endforeach
                                                    @endif

                                                    @if($nilai_transfer->isNotEmpty() && $riwayat->id_periode_masuk != $akm->id_semester)
                                                    <tr>
                                                        <td class="text-center align-middle bg-dark" colspan="9">Nilai Transfer Pendidikan</td>
                                                    </tr>
                                                        @foreach($nilai_transfer as $nt)
                                                            <tr>
                                                                <td class="text-center align-middle">{{$nt->kode_matkul_diakui}}</td>
                                                                <td>{{$nt->nama_mata_kuliah_diakui}}</td>
                                                                <td class="text-center align-middle">{{$nt->sks_mata_kuliah_diakui}}</td>
                                                                <td class="text-center align-middle">{{$nt->nilai_angka_diakui=NULL ? 'Nilai Belum Diisi' : $nt->nilai_angka_diakui}}</td>
                                                                <td class="text-center align-middle">{{empty($nt->nilai_huruf_diakui) ? 'Nilai Belum Diisi' : $nt->nilai_huruf_diakui}}</td>
                                                            </tr>
                                                            @if ($nt->nilai_huruf_diakui != NULL)
                                                                @php
                                                                    $total_sks += $nt->sks_mata_kuliah_diakui;
                                                                @endphp

                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                                <tbody>
                                                    <tr>
                                                        <th class="text-center align-middle" colspan="2">Total SKS KHS</th>
                                                        <th class="text-center align-middle" >{{$total_sks}}</th>
                                                        <th class="text-center align-middle" ></th>
                                                        <th class="text-center align-middle" ></th>
                                                      
                                                    </tr>
                                                </tbody>
                                            </table>
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
