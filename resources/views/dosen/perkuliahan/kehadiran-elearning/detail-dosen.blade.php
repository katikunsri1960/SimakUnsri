@extends('layouts.dosen')
@section('title')
    Kehadiran Mahasiswa di Elearning
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
                                <img src="{{ asset('images/images/svg-icon/color-svg/custom-14.svg') }}"
                                    class="img-fluid max-w-250" alt="" />
                                <div class="ms-30">
                                    <h2 class="mb-10">Daftar Hadir Dosen</h2>
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
                                        <li class="breadcrumb-item"><a href="{{ route('dosen') }}"><i
                                                    class="mdi mdi-home-outline"></i></a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Daftar Hadir Elearning</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="box box-body mb-0 ">
                            <div class="row"></div>
                            <hr>
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="data" class="table table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Mata Kuliah</th>
                                                <th>Nama Mata Kuliah</th>
                                                <th>Nama Kelas</th>
                                                <th>Dosen Pengajar</th>
                                                <th>Total Pertemuan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rekap as $row)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $row['kode_mata_kuliah'] }}</td>
                                                    <td class="text-start">{{ $row['nama_mk'] }}</td>
                                                    <td>{{ $row['nama_kelas'] }}</td>
                                                    <td>{{ $row['dosen_pengajar'] }}</td>
                                                    <td>
                                                        <a href="{{ route('dosen.perkuliahan.kehadiran-elearning.detail-pertemuan', [
                                                            'kode_mk' => $row['kode_mata_kuliah'],
                                                            'nama_kelas' => $row['nama_kelas'],
                                                        ]) }}"
                                                            title="Lihat Detail Pertemuan"
                                                            style="
                                                                display: inline-block;
                                                                width: 36px;
                                                                height: 36px;
                                                                line-height: 36px;
                                                                border-radius: 50%;
                                                                background: #b6f3ff;
                                                                color: #2196f3;
                                                                font-weight: bold;
                                                                text-align: center;
                                                                font-size: 16px;
                                                                box-shadow: 0 1px 2px rgba(0,0,0,0.07);
                                                                text-decoration: none;
                                                                transition: 0.2s ease-in-out;
                                                            "
                                                            onmouseover="this.style.boxShadow='0 0 8px rgba(0,123,255,0.3)'"
                                                            onmouseout="this.style.boxShadow='0 1px 2px rgba(0,0,0,0.07)'">
                                                            {{ $row['total_kehadiran'] }}
                                                        </a>
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
                "pageLength": 5,
                "lengthMenu": [
                    [5, 10, 20],
                    [5, 10, 20]
                ],
                "ordering": true,
                "searching": true,
                "scrollCollapse": true,
                "scrollY": "550px",
                "stateSave": true
            });
        });
    </script>
@endpush
