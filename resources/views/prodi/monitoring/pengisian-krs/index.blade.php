@extends('layouts.prodi')
@section('title')
Monitoring Pengisian KRS
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Monitoring Pengisian KRS</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Monitoring</li>
                        <li class="breadcrumb-item active" aria-current="page">Pengisian KRS</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">Jumlah Mahasiswa Aktif</th>
                                    <th class="text-center align-middle">Jumlah Mahasiswa Aktif {{date('Y') - 7}} -
                                        {{date('Y')}}</th>
                                    <th class="text-center align-middle">Jumlah Mahasiswa (Yang melakukan pengisian KRS)
                                    </th>
                                    <th class="text-center align-middle">Jumlah Mahasiswa (Tidak isi KRS)
                                    </th>
                                    <th class="text-center align-middle">Jumlah Mahasiswa Sudah di Setujui</th>
                                    <th class="text-center align-middle">Jumlah Mahasiswa Belum di Setujui</th>
                                    <th class="text-center align-middle">Persentase Approval</th>
                                </tr>
                            </thead>
                            <tbody id="data-table">
                                <tr>
                                    <td class="text-center align-middle">
                                        <a href="{{route('prodi.monitoring.pengisian-krs.mahasiswa-aktif')}}" id="jumlah_mahasiswa_link">
                                            <span id="jumlah_mahasiswa">Loading...</span>
                                        </a>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{route('prodi.monitoring.pengisian-krs.mahasiswa-aktif-min-tujuh')}}" id="jumlah_mahasiswa_now_link">
                                            <span id="jumlah_mahasiswa_now">Loading...</span>
                                        </a>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{route('prodi.monitoring.pengisian-krs.detail-isi-krs')}}" id="isi_krs_link">
                                            <span id="isi_krs">Loading...</span>
                                        </a>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{route('prodi.monitoring.pengisian-krs.tidak-isi-krs')}}" id="tidak_isi_krs_link">
                                            <span id="tidak_isi_krs">Loading...</span>
                                        </a>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{route('prodi.monitoring.pengisian-krs.approve-krs')}}" id="approve_link">
                                            <span id="approve">Loading...</span>
                                        </a>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{route('prodi.monitoring.pengisian-krs.non-approve-krs')}}" id="non_approve_link">
                                            <span id="non_approve">Loading...</span>
                                        </a>
                                    </td>
                                    <td class="text-center align-middle" id="approve_percentage">
                                        Loading...
                                    </td>
                                </tr>
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
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>
      $(document).ready(function() {
        $.ajax({
            url: '{{ route("prodi.monitoring.pengisian-krs.data") }}',
            method: 'GET',
            success: function(data) {
                $('#jumlah_mahasiswa').text(data.jumlah_mahasiswa);
                $('#jumlah_mahasiswa_now').text(data.jumlah_mahasiswa_now);
                $('#isi_krs').text(data.isi_krs);
                var tidak_isi_krs = data.jumlah_mahasiswa - data.isi_krs;
                $('#tidak_isi_krs').text(tidak_isi_krs);
                $('#approve').text(data.approve);
                $('#non_approve').text(data.non_approve);

                if (data.isi_krs == 0) {
                    $('#approve_percentage').text('0%');
                } else {
                    $('#approve_percentage').text((data.approve / data.isi_krs * 100).toFixed(2) + '%');
                }
            },
            error: function() {
                alert('Failed to fetch data.');
            }
        });
    });
</script>
@endpush
