@extends('layouts.dosen')
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
                        <div class="col-12 col-lg-3"><img
                                src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h2>Nilai Perkuliahan, {{$mahasiswa->nama_mahasiswa}}</h2>
                            <p class="text-dark mb-0 fs-16">
                                SIMAK Universitas Sriwijaya
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
            <!-- Nav tabs -->
            <ul class="nav nav-pills justify-content-left" role="tablist">
                <li class="nav-item bg-secondary-light"> <a class="nav-link active" data-bs-toggle="tab" href="#khs"
                        role="tab"><span><i class="fa-solid fa-file-invoice"></i></span> <span
                            class="hidden-xs-down ms-15">Kartu Hasil Studi</span></a> </li>
            </ul>
            <div class="box">
                <!-- Tab panes -->
                <div class="tab-content tabcontent">
                    <div class="tab-pane active" id="khs" role="tabpanel">
                        <div class="col-xl-12 col-lg-12 col-12">
                            <div class="bg-primary-light big-side-section mb-20 shadow-lg">
                                <div class="box box-body mb-0 bg-white">
                                    <div class="row mb-3 p-3">
                                        <div class="col-12">
                                            <div class="box no-shadow mb-0 bg-transparent">
                                                <div class="box-header no-border px-0">
                                                    <a type="button"
                                                        href="{{ route('dosen.pembimbing.bimbingan-akademik.lihat-khs', ['riwayat' => $mahasiswa->id_registrasi_mahasiswa]) }}"
                                                        class="btn btn-warning btn-rounded waves-effect waves-light">
                                                        <i class="fa-solid fa-arrow-left"></i>
                                                    </a>
                                                    <h3 class="box-title px-3">
                                                        {{empty($data_aktivitas[0]['nama_semester']) ? 'Data Tidak Ada'
                                                        : $data_aktivitas[0]['nama_semester']}}</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="box bs-5 border-primary rounded mb-10 pull-up">
                                                <div class="box-body">
                                                    <div class="flex-grow-1">
                                                        <p class="mt-5 mb-5 text-fade fs-12">SKS Semester</p>
                                                        <h4 class="mt-5 mb-0" style="color:#0052cc">
                                                            {{empty($data_aktivitas[0]['sks_semester']) ? 'Data Tidak
                                                            Ada' : $data_aktivitas[0]['sks_semester']}}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="box bs-5 border-danger rounded mb-10 pull-up">
                                                <div class="box-body">
                                                    <div class="flex-grow-1">
                                                        <p class="mt-5 mb-5 text-fade fs-12">IP Semester</p>
                                                        <h4 class="mt-5 mb-0" style="color:#0052cc">
                                                            {{empty($data_aktivitas[0]['ips']) ? 'Data Tidak Ada' :
                                                            $data_aktivitas[0]['ips']}}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="box bs-5 border-warning rounded mb-10 pull-up">
                                                <div class="box-body">
                                                    <div class="flex-grow-1">
                                                        <p class="mt-5 mb-5 text-fade fs-12">IP Komulatif</p>
                                                        <h4 class="mt-5 mb-0" style="color:#0052cc">
                                                            {{empty($data_aktivitas[0]['ipk']) ? 'Data Tidak Ada' :
                                                            $data_aktivitas[0]['ipk']}}</h4>
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
                                                        <th class="text-center align-middle">Nilai Angka</th>
                                                        <th class="text-center align-middle">Nilai Indeks</th>
                                                        <th class="text-center align-middle">Nilai Huruf</th>
                                                        <th class="text-center align-middle">Dosen Pengampu</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($data_nilai as $d)
                                                    <tr>
                                                        <td class="text-center align-middle">{{$d->kelas_kuliah->matkul->kode_mata_kuliah}}</td>
                                                        <td class="text-start align-middle">{{$d->kelas_kuliah->matkul->nama_mata_kuliah}}</td>
                                                        <td class="text-center align-middle">{{empty($d->nilai_angka) ? '-' : $d->nilai_angka}}</td>
                                                        <td class="text-center align-middle">{{empty($d->nilai_indeks) ? '-' : $d->nilai_indeks}}</td>
                                                        <td class="text-center align-middle">{{empty($d->nilai_huruf) ? '-' : $d->nilai_huruf}}</td>
                                                        <td class="text-start align-middle w-300">
                                                            <ul>
                                                                @foreach($d->dosen_pengajar as $dd)
                                                                <li>{{$dd->dosen->nama_dosen}}</li>
                                                                @endforeach
                                                            </ul>
                                                        </td>
                                                    </tr>
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
                                                                <td class="text-center align-middle">{{empty($n->nilai_angka) ? 'Nilai Belum Diisi' : $n->nilai_angka}}</td>
                                                                <td class="text-center align-middle">{{empty($n->nilai_indeks) ? 'Nilai Belum Diisi' : $n->nilai_indeks}}</td>
                                                                <td class="text-center align-middle">{{empty($n->nilai_huruf) ? 'Nilai Belum Diisi' : $n->nilai_huruf}}</td>
                                                                {{-- <td class="text-center align-middle">
                                                                    {{ !empty($n->nilai_indeks) ? $n->sks_mata_kuliah * $d->nilai_indeks : 'Nilai Belum Diisi' }}
                                                                </td> --}}
                                                                <td class="text-start align-middle w-300">
                                                                    <ul>
                                                                        @foreach($n->aktivitas_mahasiswa->bimbing_mahasiswa as $dd)
                                                                        <li>{{$dd->nama_dosen}}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </td>
                                                            </tr>
                                                        @endforeach 
                                                    @endif  

                                                    @if($nilai_transfer->isNotEmpty())
                                                    <tr>
                                                        <td class="text-center align-middle bg-dark" colspan="9">Nilai Transfer Pendidikan</td>
                                                    </tr>
                                                        @foreach($nilai_transfer as $nt)
                                                            <tr>
                                                                <td class="text-center align-middle">{{$nt->kode_matkul_diakui}}</td>
                                                                <td>{{$nt->nama_mata_kuliah_diakui}}</td>
                                                                <td class="text-center align-middle">{{empty($nt->nilai_angka) ? '-' : $nt->nilai_angka}}</td>
                                                                <td class="text-center align-middle">{{empty($nt->nilai_huruf_diakui) ? 'Nilai Belum Diisi' : $nt->nilai_huruf_diakui}}</td>
                                                                <td class="text-center align-middle">{{empty($nt->nilai_angka_diakui) ? 'Nilai Belum Diisi' : $nt->nilai_angka_diakui}}</td>
                                                                {{-- <td class="text-center align-middle">
                                                                    {{ !empty($nt->nilai_indeks) ? $nt->sks_mata_kuliah_diakui * $nt->nilai_indeks_diakui : 'Nilai Belum Diisi' }}
                                                                </td> --}}
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
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
</section>
@endsection
