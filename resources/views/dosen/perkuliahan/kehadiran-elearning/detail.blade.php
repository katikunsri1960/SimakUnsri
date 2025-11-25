@extends('layouts.dosen')
@section('title')
    Detail Mahasiswa
@endsection
@section('content')
    <section class="content bg-white">
        <div class="row align-items-end">
            <div class="col-12">
                <div class="box pull-up">
                    <div class="box-body bg-img bg-primary-light">
                        <div class="d-lg-flex align-items-center justify-content-between">
                            <div class="d-lg-flex align-items-center mb-30 mb-xl-0 w-p100">
                                <img src="{{ asset('images/images/svg-icon/color-svg/custom-14.svg') }}"
                                    class="img-fluid max-w-250" alt="" />
                                <div class="ms-30">
                                    <h2 class="mb-15">Daftar Mahasiswa</h2>
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
                                        <li class="breadcrumb-item"><a href="{{ route('dosen') }}"><i
                                                    class="mdi mdi-home-outline"></i></a></li>
                                        <li class="breadcrumb-item" aria-current="page"><a
                                                href="{{ route('dosen.kehadiran.kehadiran-elearning.kehadiran-elearning') }}">Kehadiran
                                                Mahasiswa</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Detail Mahasiswa</li>
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
                                <div class="col-xl-4 col-lg-12">
                                    <a class="btn btn-rounded bg-warning-light" href="{{ url()->previous() }}">
                                        <i class="fa fa-chevron-left"></i> Kembali
                                    </a>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="data" class="table table-bordered table-striped text-center"
                                        style="font-size: 12px">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">No</th>
                                                <th rowspan="2">NIM</th>
                                                <th rowspan="2">Nama Mahasiswa</th>
                                                <th rowspan="2">Nama Kelas Kuliah</th>
                                                <th rowspan="2">Status Kehadiran</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kehadiran as $detail_item)
                                                <tr>
                                                    <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                    <td class="text-center align-middle">{{ $detail_item->username }}</td>
                                                    <td class="text-start align-middle">{{ $detail_item->nama_mahasiswa }}
                                                    </td>
                                                    <td class="text-center align-middle">{{ $detail_item->nama_kelas }}</td>
                                                    <td class="text-center align-middle">
                                                        {{ $detail_item->status_mahasiswa }}</td>
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
                "fixedColumns": {
                    "rightColumns": 6
                },
                columnDefs: []
            });

        });
    </script>
@endpush
