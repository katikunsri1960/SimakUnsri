@extends('layouts.dosen')
@section('title')
Penilaian Perkuliahan Mahasiswa
@endsection
@section('content')
@include('swal')
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-12">
            <div class="box pull-up">
                <div class="box-body bg-img bg-primary-light">
                    <div class="d-lg-flex align-items-center justify-content-between">
                        <div class="d-lg-flex align-items-center mb-30 mb-xl-0 w-p100">
                            <img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}"
                                class="img-fluid max-w-250" alt="" />
                            <div class="ms-30">
                                <h2 class="mb-10">Penilaian Perkuliahan Mahasiswa</h2>
                                <p class="mb-0 text-fade fs-18">Universitas Sriwijaya</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header mb-3">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('dosen')}}"><i class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Penilaian Perkuliahan</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-12">
                    <div class="box box-body mb-0 ">
                        <div class="row">
                            <div class="table-responsive">
                                <table id="data" class="table table-bordered table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>PRODI</th>
                                            <th>Kode MK</th>
                                            <th>Nama MK</th>
                                            <th>Nama Kelas</th>
                                            <th>Dosen Pengajar</th>
                                            <th>Peserta Kelas</th>
                                            <th>Batas Pengisian Nilai</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $d)
                                        <tr>
                                            <td class="text-center align-middle">{{$loop->iteration}}</td>
                                            <td class="text-start align-middle">
                                                {{$d->kelas_kuliah->prodi->nama_jenjang_pendidikan}} - {{$d->kelas_kuliah->prodi->nama_program_studi}}
                                            </td>
                                            <td class="text-center align-middle">
                                                <a href="{{route('dosen.penilaian.penilaian-perkuliahan.detail', ['kelas' => $d->kelas_kuliah->id_kelas_kuliah])}}"> {{$d->kelas_kuliah->matkul->kode_mata_kuliah}}</a>
                                            </td>
                                            <td class="text-start align-middle">{{$d->kelas_kuliah->matkul->nama_mata_kuliah}}</td>
                                            <td class="text-center align-middle">{{$d->kelas_kuliah->nama_kelas_kuliah}}</td>
                                            <td class="text-start align-middle">
                                                @if ($d->kelas_kuliah->dosen_pengajar)
                                                <ul>
                                                @foreach ($d->kelas_kuliah->dosen_pengajar as $p)
                                                <li>{{$p->dosen->nama_dosen}}</li>
                                                @endforeach
                                            </ul>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">{{$d->peserta_kelas_count}}</td>
                                            <td class="text-center align-middle"></td>
                                            <td class="text-center align-middle">
                                                <a class="btn btn-rounded bg-warning-light"
                                                href="{{route('dosen.penilaian.presentase-penilaian-perkuliahan')}}"
                                                title="Presentase Nilai"><i class="fa fa-percent"><span
                                                        class="path1"></span><span class="path2"></span></i></a>
                                            <a class="btn btn-rounded bg-success-light" href="#"
                                                title="Download DPNA"><i class="fa fa-download"><span
                                                        class="path1"></span><span class="path2"></span></i></a>
                                            <a class="btn btn-rounded bg-primary-light" href="#"
                                                title="Upload DPNA"><i class="fa fa-upload"><span
                                                        class="path1"></span><span class="path2"></span></i></a>
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
</section>
@endsection
@push('js')

<script>
      $(document).ready(function() {
        $('#data').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
            // "scrollCollapse": true,
            // "scrollY": "550px",
        });

    });
</script>

@endpush
