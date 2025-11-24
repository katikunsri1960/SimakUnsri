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
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('dosen') }}">
                                                <i class="mdi mdi-home-outline"></i>
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            Daftar Hadir Elearning
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-12">
                        <div class="box box-body mb-0">
                            <div class="col-xl-4 col-lg-12 mb-3">
                                <a class="btn btn-rounded bg-warning-light"
                                    href="{{ route('dosen.kehadiran.kehadiran-elearning.detail-dosen') }}">
                                    <i class="fa fa-chevron-left"></i> Kembali
                                </a>
                            </div>

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
                                                <th>Tanggal</th>
                                                <th>Jumlah Peserta Kelas</th>
                                                <th>Status Kehadiran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kehadirandosen as $i => $row)
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td>{{ $row->kode_mata_kuliah }}</td>
                                                    <td>{{ $row->nama_mk }}</td>
                                                    <td>{{ $row->nama_kelas }}</td>
                                                    <td>{{ $row->nama_dosen }}</td>
                                                    <td>{{ $row->tanggal }}</td>
                                                    <td>
                                                        <a href="{{ route('dosen.kehadiran.kehadiran-elearning.detail', ['session_id' => $row->session_id]) }}"
                                                            title="Lihat peserta sesi"
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
                                                            ">
                                                            {{ $row->jumlah_peserta }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        Present: {{ $row->jumlah_hadir }}<br>
                                                        Late: {{ $row->jumlah_terlambat }}<br>
                                                        Excused: {{ $row->jumlah_izin }}<br>
                                                        Absent: {{ $row->jumlah_absen }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div> <!-- table-responsive -->
                            </div> <!-- row -->
                        </div> <!-- box -->
                    </div> <!-- col -->
                </div> <!-- row -->
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#data').DataTable({
                paging: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                ordering: true,
                searching: true,
                scrollCollapse: true,
                scrollY: "550px",
                stateSave: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    },
                    zeroRecords: "Tidak ada data ditemukan",
                    infoEmpty: "Tidak ada data yang ditampilkan",
                    infoFiltered: "(disaring dari _MAX_ total data)"
                }
            });
        });
    </script>
@endpush
