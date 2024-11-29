@extends('layouts.fakultas')
@section('title')
Mahasiswa Prodi
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Master Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Mahasiswa</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
@include('swal')
{{-- @include('fakultas.data-master.mahasiswa.set-pa')
@include('fakultas.data-master.mahasiswa.set-kurikulum') --}}
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
                    <div class="d-flex justify-content-end">
                        <div class="d-flex justify-content-start">
                            <!-- Modal trigger button -->
                            <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                                data-bs-target="#filter-button">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                            <span class="divider-line mx-1"></span>
                            <a href="{{route('fakultas.data-master.mahasiswa')}}" class="btn btn-warning waves-effect waves-light" >
                                <i class="fa fa-rotate"></i> Reset Filter
                            </a>
                            @include('fakultas.data-master.mahasiswa.filter')
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100 table-bordered" style="font-size: 11px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle" style="width: 5%">No</th>
                                    <th class="text-center align-middle">FOTO</th>
                                    <th class="text-center align-middle">AKT</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">NAMA</th>
                                    <th class="text-center align-middle">PROGRAM STUDI</th>
                                    <th class="text-center align-middle">KURIKULUM</th>
                                    <th class="text-center align-middle">DOSEN P.A.</th>
                                    <th class="text-center align-middle">STATUS</th>
                                    <th class="text-center align-middle">STATUS<br>PEMBAYARAN</th>
                                    <th class="text-center align-middle">NOMINAL UKT</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('assets/vendor_components/select2/dist/css/select2.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>



    $(function() {
        "use strict";

        $('#data').DataTable({
            // dom: 'Bfrtip',
            // buttons: [
            //     'copy', 'csv', 'excel', 'pdf', 'print'
            // ],
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('fakultas.data-master.mahasiswa.data')}}',
                type: 'GET',
                data: function (d) {
                    d.prodi = $('#prodi').val();
                    d.angkatan = $('#angkatan').val();
                    d.status_keluar = $('#status_keluar').val();
                },
                // success: function(response) {
                //     console.log(response); // Menampilkan respon data di console
                // },
                error: function (xhr, error, thrown) {
                    alert('An error occurred. ' + thrown);
                }
            },
            order: [[2, 'desc']],
            columns: [
                {
                    data: null,
                    searchable: false,
                    class: "text-center align-middle",
                    sortable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                }},
                {
                    data: null,
                    name: 'image',
                    render: function(data, type, row) {
                        var imagePath = '{{ asset('storage') }}' + '/' + data.angkatan + '/' + data.nim + '.jpg';
                        return '<img class="rounded20 bg-light img-fluid w-80" src="' + imagePath + '" alt="" onerror="this.onerror=null;this.src=\'{{ asset('images/images/avatar/avatar-15.png') }}\';">';
                    },
                    orderable: false,
                    searchable: false
                },
                {data: 'angkatan', name: 'angkatan', class: "text-center align-middle", searchable: true, orderData: [0]},
                {data: 'nim', name: 'nim', class: 'text-center', searchable: true, orderData: [1]},
                {data: 'nama_mahasiswa', name: 'nama_mahasiswa', class: 'text-start', searchable: true, orderData: [2]},
                {data: 'nama_program_studi', name: 'nama_program_studi', class: "text-center align-middle", searchable: true, orderData: [0]},
                {
                    data: 'kurikulum.nama_kurikulum',
                    name: 'kurikulum.nama_kurikulum',
                    class: 'text-start',
                    searchable: false,
                    sortable:false,
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'pembimbing_akademik.nama_dosen',
                    name: 'pembimbing_akademik.nama_dosen',
                    class: 'text-start',
                    searchable: false,
                    sortable:false,
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'keterangan_keluar',
                    name: 'keterangan_keluar',
                    searchable: true,
                    class: "text-center align-middle",
                    sortable: false,
                    render: function(data, type, row) {
                        return data ? data : 'Aktif';
                    }
                },
                {
                    data: null,
                    searchable: false,
                    // sortable: false,
                    class: "text-center align-middle",
                    render: function(data, type, row, meta) {
                        let result = "";

                        // Jika ada beasiswa
                        if (row.beasiswa) {
                            result = `<h5><span class="badge bg-primary">${row.beasiswa.jenis_beasiswa.nama_jenis_beasiswa}</span></h5>`;
                        } else {
                            // Jika ada tagihan
                            if (row.tagihan) {
                                // Jika pembayaran ada
                                if (row.tagihan.pembayaran) {
                                    result = `<h5><span class="badge bg-success">Lunas</span></h5>`;
                                } else {
                                    // Jika penundaan bayar ada
                                    if (row.penundaan_bayar == 1) {
                                        result = `<h5><span class="badge bg-warning">Penundaan Bayar</span></h5>`;
                                    } else {
                                        result = `<h5><span class="badge bg-danger">Belum Bayar</span></h5>`;
                                    }
                                }
                            }
                        }

                        return result;
                    }
                },
                {
                    data: 'tagihan.pembayaran.total_nilai_pembayaran',
                    name: 'tagihan.pembayaran.total_nilai_pembayaran',
                    class: 'text-start',
                    searchable: false,
                    sortable: false,
                    render: function(data, type, row) {
                        if (data) {
                            // Memformat nilai ke dalam Rupiah
                            return parseInt(data).toLocaleString('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            }).replace('IDR', '').trim();
                        } else {
                            return '-';
                        }
                    }
                }
            ],
        });
    });
</script>
@endpush
