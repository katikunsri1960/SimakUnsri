@extends('layouts.fakultas')
@section('title')
LIST {{ strtoupper(str_replace('_', ' ', $status)) }}
@endsection
@section('content')
@push('header')
<div class="mx-4">
    <a href="{{route('fakultas.monitoring.status-mahasiswa')}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">LIST {{ strtoupper(str_replace('_', ' ', $status)) }}</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('fakultas')}}"><i class="mdi mdi-home-outline"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Monitoring</li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('fakultas.monitoring.status-mahasiswa')}}">Status Mahasiswa</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> {{ ucwords(str_replace('_', ' ', $status)) }}</li>
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
                    <div class="mb-5">

                    </div>
                    {{-- <div class="progress mt-3">
                        <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;"
                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div> --}}
                    <div class="table-responsive mt-5">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100 table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">Prodi</th>
                                    <th class="text-center align-middle">Kode Prodi</th>
                                    <th class="text-center align-middle">Angkatan</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">Nama</th>
                                    <th class="text-center align-middle">SKS Terkumpul</th>
                                    <th class="text-center align-middle">IPK</th>
                                    <th class="text-center align-middle">Masa Studi (Bulan)</th>
                                    <th class="text-center align-middle">ACT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                    <td class="text-start align-middle">
                                        {{$item->riwayat->prodi->nama_jenjang_pendidikan}} - {{$item->riwayat->prodi->nama_program_studi}}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{$item->riwayat->prodi->kode_program_studi}}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{$item->riwayat->angkatan}}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{$item->riwayat->nim}}
                                    </td>
                                    <td class="text-start align-middle">
                                        {{$item->riwayat->nama_mahasiswa}}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{$item->total_sks}}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{$item->ipk}}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{$item->masa_studi}}
                                    </td>
                                    <td class="text-start align-middle">

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
<script src="{{asset('assets/js/confirmSwal.js')}}"></script>
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>


    $(document).ready(function(){
        $('#data').DataTable({
            "paging": false,
            "scrollX": true,
            "scrollY": "45vh",
            "dom": 'Bfrtip', // Add buttons for export
            "buttons": [
            {
            extend: 'excelHtml5',
            title: 'Monev Status Mahasiswa',
            text: '<i class="fa fa-file-excel-o"></i> Download Excel', // Add Excel icon
            className: 'btn btn-success' // Optional: Add custom styling
            }
            ],
            "columnDefs": [
            [{
            "targets": 0, // Kolom pertama
            "type": "num" // Menentukan tipe data sebagai numerik
            }],
            [{
            "targets": 5, // Kolom pertama
            "orderable": false // Menentukan tipe data sebagai numerik
            }]
        ]
        });

    });

</script>
@endpush
