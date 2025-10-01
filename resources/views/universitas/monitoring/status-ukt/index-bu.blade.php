@extends('layouts.universitas')
@section('title')
UKT Mahasiswa
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Monitoring UKT Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Monitoring</li>
                        <li class="breadcrumb-item active" aria-current="page">Status UKT</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
@include('swal')

<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
                    <form id="form-filter" class="row align-items-center g-2">
                        {{-- Filter Prodi --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Program Studi</label>
                            <div class="col-sm-10">
                                <select multiple class="form-select" name="prodi[]" id="prodi">
                                    @foreach ($prodi as $p)
                                    <option value="{{$p->id_prodi}}" {{ in_array($p->id_prodi, old('prodi',
                                        request()->get('prodi', []))) ? 'selected' : '' }}>
                                        ({{$p->kode_program_studi}}) {{$p->nama_jenjang_pendidikan}} - {{$p->nama_program_studi}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Filter Angkatan --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Angkatan</label>
                            <div class="col-sm-10">
                                <select multiple class="form-select" name="angkatan[]" id="angkatan">
                                    @foreach ($angkatan as $p)
                                        <option value="{{$p->angkatan_raw}}" 
                                            {{ 
                                                in_array($p->angkatan_raw, old('angkatan', request()->get('angkatan', []))) 
                                                || (empty(request()->get('angkatan')) && $loop->first) 
                                                ? 'selected' 
                                                : '' 
                                            }}>
                                            {{$p->angkatan_raw}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Tombol Filter + Reset --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">&nbsp;</label>
                            <div class="col-sm-4">
                                <button type="button" id="btnFilter" class="btn btn-secondary btn-sm form-control">
                                    <i class="fa fa-filter me-2"></i> Filter
                                </button>
                            </div>
                            <div class="col-sm-4">
                                <a href="{{ route('univ.monitoring.status-ukt') }}" class="btn btn-warning btn-sm form-control">
                                    <i class="fa fa-rotate"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100 table-bordered" style="font-size: 11px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle" style="width: 5%">No</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">NAMA</th>
                                    <th class="text-center align-middle">AKT</th>
                                    <th class="text-center align-middle">PROGRAM STUDI</th>
                                    <th class="text-center align-middle">STATUS</th>
                                    <th class="text-center align-middle">JANJI BAYAR</th>
                                    <th class="text-center align-middle">STATUS BAYAR</th>
                                    <th class="text-center align-middle">TANGGAL BAYAR</th>
                                    <th class="text-center align-middle">NOMINAL BAYAR</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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

    // Init Select2
    $('#prodi').select2({ placeholder: 'Pilih Program Studi', allowClear: true, width: '100%' });
    $('#angkatan').select2({ placeholder: 'Pilih Angkatan', allowClear: true, width: '100%' });

    // Custom sorting untuk status bayar
    $.fn.dataTable.ext.order['statusBayar-pre'] = function(settings, col) {
        return this.api().column(col, {order: 'index'}).nodes().map(function(td, i) {
            let text = $(td).text().trim().toLowerCase();
            if (text.includes("belum bayar")) return 1;
            if (text.includes("penundaan")) return 2;
            if (text.includes("terlambat")) return 3;
            if (text.includes("lunas")) return 4;
            if (text.includes("beasiswa")) return 5;
            return 99;
        });
    };

    // Inisialisasi DataTable
    let table = $('#data').DataTable({
        processing: true,
        serverSide: false, 
        searching: false,
        paging: true,
        ajax: {
            url: '{{ route('univ.monitoring.status-ukt.data') }}',
            type: 'GET',
            data: function(d) {
                d.prodi = $('#prodi').val();
                d.angkatan = $('#angkatan').val();
            },
            dataSrc: '' // ganti 'data' kalau server return { data: [...] }
        },
        deferLoading: 0,
        order: [[7, 'asc']],
        columns: [
            {
                data: null,
                searchable: false,
                class: "text-center align-middle",
                sortable: false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {data: 'nim', class: 'text-center'},
            {data: 'nama_mahasiswa', class: 'text-start'},
            {data: 'angkatan', class: "text-center align-middle"},
            {data: 'nama_program_studi', class: "text-center align-middle"},
            {
                data: 'keterangan_keluar',
                class: "text-center align-middle",
                render: function(data) { return data ? data : 'Aktif'; }
            },
            {
                data: 'batas_bayar',
                class: 'text-center align-middle',
                render: function(data) {
                    if (data) {
                        let date = new Date(data);
                        return date.toLocaleDateString('id-ID');
                    }
                    return '-';
                }
            },
            {
                data: null,
                class: "text-center align-middle",
                render: function(data, type, row) {
                    let result = "";
                    if (row.beasiswa) {
                        result = `<h5><span class="badge bg-primary">Beasiswa<br>(${row.beasiswa.jenis_beasiswa.nama_jenis_beasiswa})</span></h5>`;
                    } else if (row.tagihan) {
                        if (row.tagihan.pembayaran) {
                            if (row.penundaan_bayar == 1 && row.batas_bayar && row.tagihan.pembayaran.waktu_transaksi > row.batas_bayar) {
                                result = `<h5 class="mb-0"><span class="badge bg-danger">Lunas</br>(Terlambat)</span></h5>`;
                            } else {
                                result = `<h5 class="mb-0"><span class="badge bg-success">Lunas</span></h5>`;
                            }
                        } else {
                            result = row.penundaan_bayar == 1
                                ? `<h5 class="mb-0"><span class="badge bg-warning">Penundaan Bayar</span></h5>`
                                : `<h5 class="mb-0"><span class="badge bg-danger">Belum Bayar</span></h5>`;
                        }
                    }
                    return result;
                }
            },
            {
                data: "tagihan.pembayaran.waktu_transaksi",
                class: "text-center align-middle",
                sortable: true,
                render: function(data) {
                    if (data) {
                        let tgl = new Date(data);
                        return tgl.toLocaleDateString("id-ID");
                    }
                    return "-";
                }
            },
            {
                data: 'tagihan.pembayaran.total_nilai_pembayaran',
                class: 'text-start',
                render: function(data) {
                    if (data) {
                        return parseInt(data).toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).replace('IDR', '').trim();
                    }
                    return '-';
                }
            }
        ],
        columnDefs: [
            { targets: 7, orderDataType: 'statusBayar-pre' }
        ]
    });

    // Tombol Filter
    $('#btnFilter').on('click', function() {
        table.ajax.reload();
    });
});
</script>
@endpush
