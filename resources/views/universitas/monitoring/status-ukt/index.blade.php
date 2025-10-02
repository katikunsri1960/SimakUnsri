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
                <div class="box-body">
                    <div class="row">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Fakultas</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="fakultas" id="fakultas" required>
                                    <option value="">-- Pilih Fakultas --</option>
                                    @foreach ($fakultas as $p)
                                        <option value="{{$p->id}}" 
                                            {{ request()->get('fakultas') == $p->id ? 'selected' : '' }}>
                                            {{$p->nama_fakultas}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Program Studi</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="prodi" id="prodi">
                                    <!-- opsi prodi akan di-load otomatis -->
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Status Bayar</label>
                            <div class="col-sm-4">
                                <select class="form-select" name="status_bayar" id="status_bayar" required>
                                    <option value="">-- Semua Status --</option>
                                    <option value="belum_bayar">Belum Bayar</option>
                                    <option value="penundaan">Penundaan Bayar</option>
                                    <option value="lunas_terlambat">Lunas Terlambat</option>
                                    <option value="lunas">Lunas</option>
                                    <option value="beasiswa">Beasiswa</option>
                                </select>

                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="example-search-input" class="col-sm-2 col-form-label">Angkatan</label>
                            <div class="col-sm-4">
                                <select multiple class="form-select" name="angkatan[]" id="angkatan">
                                    @foreach ($angkatan as $p)
                                        <option value="{{$p->angkatan_raw}}" 
                                            {{ 
                                                in_array($p->angkatan_raw, old('angkatan', request()->get('angkatan', []))) 
                                                ? 'selected' 
                                                : '' 
                                            }}>
                                            {{$p->angkatan_raw}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-search-input" class="col-sm-2 col-form-label">&nbsp;</label>
                            <div class="col-sm-4">
                                <div class="row mx-1">
                                    <button type="button" class="btn btn-secondary btn-sm form-control" onclick="getData()"><i class="fa fa-filter me-2"></i> Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-5">
                        <table id="data" class="table table-bordered table-hover margin-top-10 w-p100" style="font-size: 11px">
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
                            <tbody style="display: none"> <!-- sembunyikan dulu -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Spinner -->
                    <div id="loading" class="text-center mt-3" style="display:none;">
                        <i class="fa fa-spinner fa-spin fa-2x"></i>
                        <p>Memproses data...</p>
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
    function formatIDR(number) {
        return new Intl.NumberFormat('id-ID', { 
            style: 'currency', 
            currency: 'IDR', 
            minimumFractionDigits: 0 
        }).format(number);
    }

    function statusBayarOrder(text) {
        text = text ? text.toLowerCase() : '';
        if (text.includes("belum bayar")) return 1;
        if (text.includes("penundaan")) return 2;
        if (text.includes("terlambat")) return 3;
        if (text.includes("lunas")) return 4;
        if (text.includes("beasiswa")) return 5;
        return 99;
    }

    function getData(){
        var prodi = $('#prodi').val();
        var angkatan = $('#angkatan').val();
        var status_bayar = $('#status_bayar').val();

        // kalau semester kosong → ambil angkatan terakhir
        // if(!angkatan || angkatan.length === 0){
        //     angkatan = [$('#angkatan option:first').val()];
        // }

        $('#loading').show();
        $('#data tbody').hide().html('');
        if ($.fn.DataTable.isDataTable('#data')) {
            $('#data').DataTable().clear().destroy();
        }

        $.ajax({
            url: "{{ route('univ.monitoring.status-ukt.data') }}",
            type: 'GET',
            data: { 
                prodi: prodi, 
                angkatan: angkatan, 
                status_bayar: status_bayar 
            },
            success: function(response) {
                var data = response;
                var html = '';
                var no = 1;
                
                $.each(data, function(i, item) {
                    let batasBayar = item.batas_bayar ? new Date(item.batas_bayar).toLocaleDateString("id-ID") : "-";
                    let statusBayar = "-";

                    if (item.beasiswa) {
                        statusBayar = `<h5><span class="badge bg-primary">Beasiswa<br>(${item.beasiswa.jenis_beasiswa.nama_jenis_beasiswa})</span></h5>`;
                    } else if (item.tagihan) {
                        if (item.tagihan.pembayaran) {
                            if (item.penundaan_bayar == 1 && item.batas_bayar && item.tagihan.pembayaran.waktu_transaksi > item.batas_bayar) {
                                statusBayar = `<h5 class="mb-0"><span class="badge bg-danger">Lunas<br>(Terlambat)</span></h5>`;
                            } else {
                                statusBayar = `<h5 class="mb-0"><span class="badge bg-success">Lunas</span></h5>`;
                            }
                        } else {
                            statusBayar = item.penundaan_bayar == 1
                                ? `<h5 class="mb-0"><span class="badge bg-warning">Penundaan Bayar</span></h5>`
                                : `<h5 class="mb-0"><span class="badge bg-danger">Belum Bayar</span></h5>`;
                        }
                    }

                    let waktuTransaksi = "-";
                    if (item.tagihan && item.tagihan.pembayaran && item.tagihan.pembayaran.waktu_transaksi) {
                        waktuTransaksi = new Date(item.tagihan.pembayaran.waktu_transaksi).toLocaleDateString("id-ID");
                    }

                    let totalBayar = "-";
                    if (item.tagihan && item.tagihan.pembayaran && item.tagihan.pembayaran.total_nilai_pembayaran) {
                        totalBayar = parseInt(item.tagihan.pembayaran.total_nilai_pembayaran).toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).replace('IDR', '').trim();
                    }

                    html += `
                        <tr>
                            <td class="text-center">${no}</td>
                            <td>${item.nim}</td>
                            <td>${item.nama_mahasiswa}</td>
                            <td class="text-center">${item.angkatan}</td>
                            <td>${item.nama_program_studi}</td>
                            <td class="text-center">${item.keterangan_keluar ? item.keterangan_keluar : 'Aktif'}</td>
                            <td class="text-center">${batasBayar}</td>
                            <td class="text-center status-bayar" data-order="${statusBayarOrder(statusBayar)}">${statusBayar}</td>
                            <td class="text-center">${waktuTransaksi}</td>
                            <td class="text-start">${totalBayar}</td>
                        </tr>`;
                    no++;
                });

                $('#data tbody').html(html).show();
                $('#data').DataTable({
                    order: [[6, 'asc']]
                });
            },
            complete: function(){
                $('#loading').hide(); // hilangkan spinner
            },
            error: function(xhr, status, error) {
                alert('Terjadi kesalahan: ' + error);
            }
        });
    }

    $(document).ready(function () {
        function checkFilterValidity() {
            let fakultas = $('#fakultas').val();
            let prodi = $('#prodi').val();
            // let statusBayar = $('#status_bayar').val();

            if (fakultas && prodi) {
                $('button[onclick="getData()"]').prop('disabled', false);
            } else {
                $('button[onclick="getData()"]').prop('disabled', true);
            }
        }

        // Cek setiap kali ada perubahan
        $('#fakultas, #prodi').on('change', function () {
            checkFilterValidity();
        });

        // Trigger awal
        checkFilterValidity();

        // // Tambahkan event handler kalau tombol di klik saat disable
        // $('button[onclick="getData()"]').on('click', function (e) {
        //     if ($(this).prop('disabled')) {
        //         e.preventDefault();
        //         swal({
        //             icon: 'warning',
        //             title: 'Filter Belum Lengkap',
        //             text: 'Harap isi filter fakultas, prodi, dan status bayar terlebih dahulu.',
        //             confirmButtonText: 'OK'
        //         });
        //     }
        // });
    });

    $(document).ready(function () {
        $('#fakultas').on('change', function () {
            let fakultasId = $(this).val();

            if (fakultasId) {
                $.ajax({
                    url: "{{ route('univ.monitoring.status-ukt.getProdi', '') }}/" + fakultasId,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#prodi').empty(); // kosongkan dulu
                        // tambahkan opsi default paling atas
                        $('#prodi').append('<option value="">-- Pilih Program Studi --</option>');
                        
                        $.each(data, function (key, value) {
                            $('#prodi').append('<option value="' + value.id_prodi + '">' +
                                '(' + value.kode_program_studi + ') ' + value.nama_jenjang_pendidikan + ' - ' + value.nama_program_studi +
                                '</option>');
                        });
                    }
                });
            } else {
                $('#prodi').empty();
            }
        });
    });

    $(function () {
        // "use strict";
        $('#data').DataTable();

        $('#fakultas').select2({
            placeholder: 'Pilih Fakultas',
            allowClear: true,
            width: '100%',
        });

        $('#prodi').select2({
            placeholder: 'Pilih Program Studi',
            allowClear: true,
            width: '100%',
        });


        $('#angkatan').select2({
            placeholder: 'Pilih Semester',
            allowClear: true,
            width: '100%',
        });
    });
</script>
@endpush
