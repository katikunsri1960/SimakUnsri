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
                            <img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}"
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
                                    <li class="breadcrumb-item"><a href="{{route('dosen')}}"><i class="mdi mdi-home-outline"></i></a></li>
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
                       <div class="col-xl-4 col-lg-12">
                                <a class="btn btn-rounded bg-warning-light" href="{{route('dosen.perkuliahan.kehadiran-elearning.detail-dosen')}}"><i class="fa fa-chevron-left"><span class="path1"></span><span class="path2"></span></i> Kembali</a>
                            </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table id="data" class="table table-bordered table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Mata Kuliah</th>
                                            <th>Nama Mata Kuliah</th>
                                            <th>Kelas</th>
                                            <th>Dosen Pengajar</th>
                                            <th>Tanggal</th>
                                            <th>Jumlah Peserta Kelas</th>
                                            <th>Status Kehadiran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data akan diisi oleh Ajax -->
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
const pesertaDetailBaseUrl = @json(route('dosen.perkuliahan.kehadiran-elearning.detail', ['session_id' => 'SESSION_ID']));
$(document).ready(function() {
    $('#data').DataTable({
        "ajax": "{{ route('dosen.kehadiran.ajax') }}",
        "paging": true,
        "pageLength": 10,
        "lengthMenu": [[10, 50, 100], [10, 50, 100]],
        "ordering": true,
        "searching": true,
        "scrollCollapse": true,
        "scrollY": "550px",
        "stateSave": true,
        "columns": [
            { 
                "data": null,
                "render": function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { "data": "kode_mata_kuliah" },
            { "data": "nama_mk" },
            { "data": "nama_kelas" },
            { "data": "nama_dosen" },
            { "data": "session_date" },
            { 
                "data": "jumlah_peserta",
                "render": function(data, type, row) {
                    let url = pesertaDetailBaseUrl.replace('SESSION_ID', row.session_id);
                    return `
                        <a href="${url}" class="d-inline-block text-decoration-none" title="Lihat peserta sesi">
                            <span style="
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
                                transition: box-shadow 0.2s;
                            ">${data}</span>
                        </a>
                    `;
                }
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    return `Present: ${row.jumlah_hadir} <br>
                            Late: ${row.jumlah_terlambat} <br>
                            Excused: ${row.jumlah_izin} <br>
                            Absent: ${row.jumlah_absen}`;
                }
            }
        ]
    });
});
</script>
@endpush