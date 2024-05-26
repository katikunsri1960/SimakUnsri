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
                            <div class="col-xl-4 col-lg-12">
                                <h3 class="fw-500 text-dark mt-0">{{$data[0]->kelas_kuliah->nama_semester}}</h3>
                                <p class="mb-0 text-fade fs-18">Batas Pengisian Nilai, {{$semester_aktif ? $semester_aktif['batas_isi_nilai'] : 'Data Belum Terisi'}}</p>
                            </div> 
                        </div><hr>
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
                                            <td class="text-center align-middle">{{$d->kelas_kuliah->matkul->kode_mata_kuliah}}</td>
                                            <td class="text-start align-middle">{{$d->kelas_kuliah->matkul->nama_mata_kuliah}}</td>
                                            <td class="text-center align-middle">
                                                <a class="btn btn-sm btn-rounded btn-info-light" href="{{route('dosen.penilaian.penilaian-perkuliahan.detail', ['kelas' => $d->kelas_kuliah->id_kelas_kuliah])}}" title="Detail Peserta Kelas"><i class="fa fa-search"></i> {{$d->kelas_kuliah->nama_kelas_kuliah}}</a>
                                            </td>
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
                                            <td class="text-center align-middle">
                                                <div class="row" style="white-space:nowrap;">
                                                    <div class="col-md-12 mb-2">
                                                        <a class="btn btn-sm btn-rounded bg-warning-light"
                                                href="{{route('dosen.penilaian.komponen-evaluasi', ['kelas' => $d->kelas_kuliah->id_kelas_kuliah])}}"
                                                title="Komponen Evaluasi" {{ $batas_pengisian >= 0 ? '' : 'disabled' }}><i class="fa fa-clipboard-list"></i> Komponen Evaluasi</a>
                                                    </div>
                                                </div>
                                                <div class="row" style="white-space:nowrap;">
                                                    <div class="col-md-6 mb-2">
                                                        <a class="btn btn-sm btn-rounded bg-success-light" href="{{route('dosen.penilaian.penilaian-perkuliahan.download-dpna', ['kelas' => $d->kelas_kuliah->id_kelas_kuliah])}}"
                                                    title="Download DPNA" {{ $batas_pengisian >= 0 ? '' : 'disabled' }}><i class="fa fa-download"></i> Download</a>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <a class="btn btn-sm btn-rounded bg-primary-light" href="#"
                                                    title="Upload DPNA" {{ $batas_pengisian >= 0 ? '' : 'disabled' }}><i class="fa fa-upload"></i> Upload</a>
                                                    </div>
                                                </div>
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
            "paging": false,
            "ordering": true,
            "searching": true,
            "scrollCollapse": true,
            "scrollY": "550px",
        });

    });
</script>

@endpush
