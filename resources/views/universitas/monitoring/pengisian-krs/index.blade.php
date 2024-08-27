@extends('layouts.universitas')
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
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a></li>
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
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">Nama Fakultas</th>
                                <th class="text-center align-middle">Nama Program Studi</th>
                                <th class="text-center align-middle">Jumlah Mahasiswa Aktif</th>
                                <th class="text-center align-middle">Jumlah Mahasiswa Aktif {{date('Y') - 7}} - {{date('Y')}}</th>
                                <th class="text-center align-middle">Jumlah Mahasiswa (Yang melakukan pengisian KRS)</th>
                                <th class="text-center align-middle">Jumlah Mahasiswa Sudah di Setujui</th>
                                <th class="text-center align-middle">Jumlah Mahasiswa Belum di Setujui</th>
                                <th class="text-center align-middle">Persentase Approval</th>
                             </tr>
                          </thead>
                          <tbody>
                            {{-- @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-start align-middle">{{$d->id}} - {{$d->nama_fakultas}}</td>
                                    <td class="text-start align-middle">{{$d->nama_prodi}}</td>
                                    <td class="text-center align-middle">{{$d->jumlah_mahasiswa}}</td>
                                    <td class="text-center align-middle">{{$d->jumlah_mahasiswa_isi_krs}}</td>
                                    <td class="text-center align-middle">{{$d->jumlah_mahasiswa_approved}}</td>
                                    <td class="text-center align-middle">{{$d->jumlah_mahasiswa_not_approved}}</td>
                                </tr>
                            @endforeach --}}
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
     $(function() {
        "use strict";

        $('#data').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('univ.monitoring.pengisian-krs.data')}}',
                type: 'GET',
                data: function (d) {
                    d.prodi = $('#prodi').val();
                },
                error: function (xhr, error, thrown) {
                    alert('An error occurred. ' + thrown);
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', class: 'text-center', searchable: false, orderable: false},
                {data: 'fakultas.nama_fakultas', name: 'fakultas.nama_fakultas', class: 'text-start', searchable: true, orderData: [0]},
                {data: 'nama_prodi', name: 'nama_prodi', class: 'text-start', searchable: true},
                {data: 'jumlah_mahasiswa', name: 'jumlah_mahasiswa', class: 'text-center', searchable: false, sortable:false},
                {data: 'jumlah_mahasiswa_now', name: 'jumlah_mahasiswa_now', class: 'text-center', searchable: false, sortable:false},
                {data: 'jumlah_mahasiswa_isi_krs', name: 'jumlah_mahasiswa_isi_krs', class: 'text-center', searchable: false, sortable:false},
                {data: 'jumlah_mahasiswa_approved', name: 'jumlah_mahasiswa_approved', class: "text-center align-middle", searchable: true},
                {data: 'jumlah_mahasiswa_not_approved', name: 'jumlah_mahasiswa_not_approved', class: "text-center align-middle", searchable: true},
                {
                    data: null,
                    name: 'persentase_approval',
                    class: 'text-center',
                    searchable: false,
                    sortable: false,
                    render: function(data, type, row) {
                        var jumlah_mahasiswa_approved = row.jumlah_mahasiswa_approved || 0;
                        var jumlah_mahasiswa_isi_krs = row.jumlah_mahasiswa_isi_krs || 1; // Avoid division by zero
                        var percentage = (jumlah_mahasiswa_approved / jumlah_mahasiswa_isi_krs) * 100;
                        return percentage.toFixed(2) + '%';
                    },
                }
            ],
            rowCallback: function(row, data, index) {
                var table = $('#data').DataTable();
                var pageInfo = table.page.info();
                $('td:eq(0)', row).html(pageInfo.start + index + 1);
                var jumlah_mahasiswa_approved = data.jumlah_mahasiswa_approved || 0;
                var jumlah_mahasiswa_isi_krs = data.jumlah_mahasiswa_isi_krs || 1; // Avoid division by zero
                var percentage = (jumlah_mahasiswa_approved / jumlah_mahasiswa_isi_krs) * 100;
                if (percentage < 50) {
                    $('td:eq(8)', row).addClass('table-danger'); // Assuming this is the 8th column (0-indexed)
                }
            }
        });
    });
</script>
@endpush
