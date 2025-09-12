@extends('layouts.prodi')
@section('title')
Peserta Kelas Kuliah
@endsection
@section('content')
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
                                <h2 class="mb-10">{{$matkul->kode_mata_kuliah}} - {{$matkul->nama_mata_kuliah}}</h2>
                                <p class="mb-0 text-fade fs-18">{{$matkul->nama_program_studi}}</p>
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
                                    <li class="breadcrumb-item"><a href="{{route('dosen')}}"><i
                                                class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item" aria-current="page"><a
                                            href="{{route('dosen.penilaian.penilaian-perkuliahan')}}">Penilaian
                                            Perkuliahan</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-12">
                    <div class="box box-body mb-0 ">
                        <div class="row mb-5">
                            <div class="col-xl-6 col-lg-6">
                                <a class="btn btn-rounded bg-warning-light"
                                    href="{{route('prodi.data-akademik.kelas-penjadwalan.detail', ['id_matkul' => $matkul->id_matkul, 'semester' => $kelas->id_semester])}}"><i
                                        class="fa fa-chevron-left"><span class="path1"></span><span
                                            class="path2"></span></i> Kembali</a>
                            </div>
                            <div class="col-xl-6 col-lg-6 text-end">
                                <div class="btn-group">
                                    {{-- <button class="btn btn-rounded bg-success-light" disabled>
                                        <i class="fa fa-plus"></i> Tambah RPS
                                    </button> --}}
                                    <a class="btn btn-rounded bg-primary-light" href="{{route('prodi.data-akademik.kelas-penjadwalan.absensi', ['id_kelas' => $kelas->id])}}" target="_blank">
                                        <i class="fa fa-file-lines"></i> Download Presensi
                                    </a>
                                </div>
                            </div>
                        </div><br>
                        <div class="rowo mt-3">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-bordered table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle">No</th>
                                            <th class="text-center align-middle">NIM</th>
                                            <th class="text-center align-middle">Nama Mahasiswa</th>
                                            <th class="text-center align-middle">Angkatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($peserta as $p)
                                        <tr>
                                            <td class="text-center align-middle">{{$loop->iteration}}</td>
                                            <td class="text-center align-middle">{{$p->mahasiswa->nim}}</td>
                                            <td class="text-start align-middle">{{$p->mahasiswa->nama_mahasiswa}}</td>
                                            <td class="text-center align-middle">{{$p->mahasiswa->angkatan}}</td>
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
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#data-table').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
            // "scrollCollapse": true,
            // "scrollY": "550px",
            columnDefs:[]
        });

    });
</script>

@endpush
