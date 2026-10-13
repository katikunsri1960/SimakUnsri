@extends('layouts.ditmawa')

@section('title')
Data Prestasi Mandiri Mahasiswa (Ditmawa)
@endsection

@section('content')
@include('swal')

<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Data Prestasi Mandiri Mahasiswa</h3>

            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('ditmawa') }}">
                                <i class="mdi mdi-home-outline"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Data Prestasi Mahasiswa
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Mandiri
                        </li>
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
                    
                    {{-- Form Filter Tiga Tingkat dengan Tombol Tampilkan --}}
                    <form action="{{ route('ditmawa.data-prestasi.mandiri.index') }}" method="get" id="filterForm">
                        <div class="row align-items-end mb-3">
                            <div class="col-md-3">
                                <label for="fakultas_view" class="form-label fw-bold">Pilih Fakultas</label>
                                <select class="form-select" name="fakultas_view" id="fakultas_view" onchange="filterProdiByFakultas()">
                                    <option value="all" {{ ($fakultas_view == 'all' || !$fakultas_view) ? 'selected' : '' }}>--- Semua Fakultas ---</option>
                                    @foreach ($fakultas as $f)
                                        <option value="{{ $f->id }}" {{ ($fakultas_view ==$f->id) ? 'selected' : '' }}>
                                            {{ $f->nama_fakultas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="prodi_view" class="form-label fw-bold">Pilih Program Studi</label>
                                <select class="form-select" name="prodi_view" id="prodi_view">
                                    <option value="all" {{ ($prodi_view == 'all' || !$prodi_view) ? 'selected' : '' }}>--- Semua Program Studi ---</option>
                                    @foreach ($prodi as $pr)
                                        <option value="{{ $pr->id_prodi }}" data-fakultas="{{ $pr->fakultas_id }}" {{ ($prodi_view ==$pr->id_prodi) ? 'selected' : '' }}>
                                            {{ $pr->nama_jenjang_pendidikan }} - {{$pr->nama_program_studi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="tahun_view" class="form-label fw-bold">Pilih Tahun Prestasi</label>
                                <select class="form-select" name="tahun_view" id="tahun_view">
                                    <option value="all" {{ ($tahun_view == 'all') ? 'selected' : '' }}>--- Semua Tahun ---</option>
                                    @foreach ($pilihan_tahun as $t)
                                        <option value="{{ $t }}" 
                                            {{ ($tahun_view ? ($tahun_view ==$t) : ($tahun_aktif ==$t)) ? 'selected' : '' }}>
                                            {{ $t }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 mt-2 mt-md-0">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa fa-filter"></i> Tampilkan
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Tabel Data --}}
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-hover margin-top-10 w-p100" style="font-size: 11px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">NO</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">NAMA MAHASISWA</th>
                                    <th class="text-center align-middle">PROGRAM STUDI</th>
                                    <th class="text-center align-middle">NAMA PRESTASI</th>
                                    <th class="text-center align-middle">PENYELENGGARA</th>
                                    <th class="text-center align-middle">TAHUN</th>
                                    <th class="text-center align-middle">BUKTI</th>
                                    <th class="text-center align-middle">STATUS APPROVAL</th>
                                    <th class="text-center align-middle">ACT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle"></td>
                                    <td class="text-center align-middle">
                                        {{ optional($d->riwayat_pendidikan)->nim ?? '-' }}
                                    </td>
                                    <td class="text-start align-middle" style="width: 12%">
                                        {{ $d->nama_mahasiswa }}
                                    </td>
                                    <td class="text-start align-middle" style="width: 12%">
                                        {{ optional(optional($d->riwayat_pendidikan)->prodi)->nama_program_studi ?? '-' }}
                                    </td>
                                    <td class="text-start align-middle">
                                        {{ $d->nama_prestasi }}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ $d->penyelenggara }}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ $d->tahun_prestasi }}
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($d->file_prestasi)
                                            <a href="{{ asset('storage/' . $d->file_prestasi) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fa fa-file-pdf-o"></i> Lihat File
                                            </a>
                                        @else
                                            <span class="badge badge-secondary">Tidak Ada File</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($d->approved == 0)
                                            <span class="badge badge-warning">Menunggu Koor. Prodi</span>
                                        @elseif($d->approved == 1)
                                            <span class="badge badge-primary">Disetujui Koor. Prodi</span>
                                        @elseif($d->approved == 2)
                                            <span class="badge badge-info">Disetujui Fakultas</span>
                                        @elseif($d->approved == 3)
                                            <span class="badge badge-success">Disetujui Dir. Kemahasiswaan</span>
                                        @elseif($d->approved == 97)
                                            <span class="badge badge-danger">Dibatalkan Prodi</span>
                                            @if($d->alasan_pembatalan)
                                                <br><small class="text-muted">({{ $d->alasan_pembatalan }})</small>
                                            @endif
                                        @elseif($d->approved == 98)
                                            <span class="badge badge-danger">Dibatalkan Fakultas</span>
                                            @if($d->alasan_pembatalan)
                                                <br><small class="text-muted">({{ $d->alasan_pembatalan }})</small>
                                            @endif
                                        @elseif($d->approved == 99)
                                            <span class="badge badge-danger">Dibatalkan Ditmawa</span>
                                            @if($d->alasan_pembatalan)
                                                <br><small class="text-muted">({{ $d->alasan_pembatalan }})</small>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="row d-flex justify-content-center">
                                            @if($d->approved == 2)
                                                {{-- Form Approve Ditmawa --}}
                                                <form id="approveForm{{ $d->id_prestasi_mahasiswa }}" action="{{ route('ditmawa.data-prestasi.mandiri.approve', $d->id_prestasi_mahasiswa) }}" method="POST" class="d-inline approve-class" data-id="{{ $d->id_prestasi_mahasiswa }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm my-1 btn-success">
                                                        <i class="fa fa-check"></i> Approve
                                                    </button>
                                                </form>

                                                {{-- Form Decline Ditmawa --}}
                                                <form id="declineForm{{ $d->id_prestasi_mahasiswa }}" action="{{ route('ditmawa.data-prestasi.mandiri.decline', $d->id_prestasi_mahasiswa) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="alasan_pembatalan" id="alasan_pembatalan_{{ $d->id_prestasi_mahasiswa }}">
                                                    <button type="button" class="btn btn-sm my-1 btn-danger" onclick="confirmDecline('{{ $d->id_prestasi_mahasiswa }}')">
                                                        <i class="fa fa-times"></i> Decline
                                                    </button>
                                                </form>
                                            @elseif($d->approved < 2)
                                                <button class="btn btn-sm btn-warning" disabled>Belum Approved Fakultas</button>
                                            @else
                                                
                                            @endif
                                        </div>
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
<script src="{{ asset('assets/vendor_components/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('assets/vendor_components/sweetalert/sweetalert.min.js') }}"></script>
<script>
    function filterProdiByFakultas() {
        var selectedFakultas = $('#fakultas_view').val();
        $('#prodi_view option').each(function() {
            var prodiFakultas = $(this).data('fakultas');
            if (selectedFakultas === 'all' || !selectedFakultas || $(this).val() === 'all') {$(this).show();
            } else {
                if (prodiFakultas == selectedFakultas) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
    }

    $(function() {
        "use strict";
        filterProdiByFakultas();

        $('#data').DataTable({
            "paging": true,
            "stateSave": true,
            "order": [[ 6, "desc" ]],
            "dom": '<"top"lf<"dt-center"B>>rt<"bottom"ip><"clear">',
            "buttons": [
                {
                    "extend": 'excelHtml5',
                    "text": 'Download Excel',
                    "className": 'btn btn-primary mt-10'
                }
            ],
            "lengthMenu": [10, 25, 50, 75, 100],
            "pageLength": 10,
            "columnDefs": [{
                "targets": 0,
                "searchable": false,
                "orderable": false,
                "render": function (data, type, full, meta) {
                    return meta.settings._iDisplayStart + meta.row + 1;
                }
            }],
            "drawCallback": function (settings) {
                var api = this.api();
                var startIndex = api.context[0]._iDisplayStart;
                api.column(0, {page: 'current'}).nodes().each(function (cell, i) {
                    cell.innerHTML = startIndex + i + 1;
                });
            }
        });

        $('.approve-class').on('submit', function(e) {
            e.preventDefault();
            var formId = $(this).data('id');
            swal({
                title: 'Apakah Anda Yakin?',
                text: "Laporan prestasi ini akan disetujui di tingkat Ditmawa.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $(`#approveForm${formId}`).unbind('submit').submit();
                }
            });
        });
    });

    function confirmDecline(id) {
        swal({
            title: 'Tolak / Batalkan Prestasi',
            text: 'Masukkan alasan pembatalan / penolakan laporan prestasi ini:',
            type: 'input',
            showCancelButton: true,
            closeOnConfirm: false,
            animation: "slide-from-top",
            inputPlaceholder: "Tuliskan alasan penolakan...",
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Tolak Prestasi',
            cancelButtonText: 'Batal'
        }, function(inputValue){
            if (inputValue === false) return false;
            
            if (inputValue.trim() === "") {
                swal.showInputError("Alasan pembatalan tidak boleh kosong!");
                return false;
            }
            
            document.getElementById('alasan_pembatalan_' + id).value = inputValue;
            document.getElementById('declineForm' + id).submit();
        });
    }
</script>
@endpush