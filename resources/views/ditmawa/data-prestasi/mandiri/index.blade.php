@extends('layouts.ditmawa')

@section('title')
Data Prestasi Mandiri Mahasiswa (Ditmawa)
@endsection

@section('content')
@include('swal')

{{-- Style khusus untuk tombol icon & badge mirip gambar referensi --}}
<style>
    .btn-action-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        line-height: 32px;
        text-align: center;
        border-radius: 6px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        transition: all 0.2s ease-in-out;
    }
    .btn-action-approve {
        background-color: #d1fae5 !important;
        color: #059669 !important;
    }
    .btn-action-approve:hover {
        background-color: #a7f3d0 !important;
        color: #047857 !important;
    }
    .btn-action-decline {
        background-color: #ffe4e6 !important;
        color: #e11d48 !important;
    }
    .btn-action-decline:hover {
        background-color: #fecdd3 !important;
        color: #be123c !important;
    }
    .btn-action-detail {
        background-color: #e0f2fe !important;
        color: #0284c7 !important;
    }
    .btn-action-detail:hover {
        background-color: #bae6fd !important;
        color: #0369a1 !important;
    }
    .btn-action-edit {
        background-color: #e0e7ff !important;
        color: #4f46e5 !important;
    }
    .btn-action-edit:hover {
        background-color: #c7d2fe !important;
        color: #4338ca !important;
    }
    .btn-action-delete {
        background-color: #ffe4e6 !important;
        color: #e11d48 !important;
    }
    .btn-action-delete:hover {
        background-color: #fecdd3 !important;
        color: #be123c !important;
    }
    .badge-status-pill {
        padding: 6px 14px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 11px;
        display: inline-block;
    }
    .badge-verified {
        background-color: #e6f4ea;
        color: #137333;
    }
    .badge-submitted {
        background-color: #fef7e0;
        color: #b06000;
    }
    .badge-rejected {
        background-color: #fce8e6;
        color: #c5221f;
    }
</style>

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
                    
                    {{-- Header Action: Tombol Tambah Data --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="box-title mb-0 fw-bold text-dark">Daftar Prestasi Mandiri</h4>
                        <a href="{{ route('ditmawa.data-prestasi.mandiri.create') }}" class="btn btn-success">
                            <i class="fa fa-plus me-1"></i> Tambah Data Prestasi
                        </a>
                    </div>
                    <hr class="my-15">

                    {{-- Form Filter Tiga Tingkat --}}
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

                    {{-- Tabel Data Sesuai Gambar Referensi --}}
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100" style="font-size: 12px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle" style="width: 3%">NO</th>
                                    <th class="text-start align-middle" style="width: 30%">Lomba/Kompetisi</th>
                                    <th class="text-start align-middle">Cabang</th>
                                    <th class="text-start align-middle">Prestasi</th>
                                    <th class="text-center align-middle">Tahun</th>
                                    <th class="text-center align-middle">PT</th>
                                    <th class="text-center align-middle" style="width: 18%">Status</th>
                                    <th class="text-center align-middle" style="width: 12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle"></td>
                                    
                                    {{-- Kolom Lomba/Kompetisi --}}
                                    <td class="text-start align-middle">
                                        <span class="fw-bold text-dark d-block">{{ $d->nama_prestasi }}</span>
                                        <small class="text-muted">{{ $d->nama_tingkat_prestasi ?? 'Nasional' }} · {{ $d->nama_jenis_prestasi ?? 'Riset dan Inovasi : STEM' }}</small>
                                    </td>

                                    {{-- Kolom Cabang --}}
                                    <td class="text-start align-middle">
                                        {{ $d->nama_prestasi }}
                                    </td>

                                    {{-- Kolom Prestasi --}}
                                    <td class="text-start align-middle fw-semibold">
                                        Juara {{ $d->peringkat }}
                                    </td>

                                    {{-- Kolom Tahun --}}
                                    <td class="text-center align-middle">
                                        {{ $d->tahun_prestasi }}
                                    </td>

                                    {{-- Kolom Perguruan Tinggi --}}
                                    <td class="text-center align-middle">
                                        Universitas Sriwijaya
                                    </td>

                                    {{-- KOLOM STATUS (Badge Status Bertahap & Tombol Approve / Decline Ditmawa) --}}
                                    <td class="text-center align-middle">
                                        @if($d->approved == 3)
                                            {{-- Disetujui Ditmawa --}}
                                            <span class="badge-status-pill btn-success-light">
                                                <i class="fa fa-check-circle me-1"></i> Disetujui Ditmawa
                                            </span>
                                        @elseif($d->approved == 97)
                                            {{-- Dibatalkan Prodi --}}
                                            <span class="badge-status-pill btn-danger-light">
                                                <i class="fa fa-times-circle me-1"></i> Dibatalkan Prodi
                                            </span>
                                            @if($d->alasan_pembatalan)
                                                <br><small class="text-danger">({{ $d->alasan_pembatalan }})</small>
                                            @endif
                                        @elseif($d->approved == 98)
                                            {{-- Dibatalkan Fakultas --}}
                                            <span class="badge-status-pill btn-danger-light">
                                                <i class="fa fa-times-circle me-1"></i> Dibatalkan Fakultas
                                            </span>
                                            @if($d->alasan_pembatalan)
                                                <br><small class="text-danger">({{ $d->alasan_pembatalan }})</small>
                                            @endif
                                        @elseif($d->approved == 99)
                                            {{-- Dibatalkan Ditmawa --}}
                                            <span class="badge-status-pill btn-danger-light">
                                                <i class="fa fa-times-circle me-1"></i> Dibatalkan Ditmawa
                                            </span>
                                            @if($d->alasan_pembatalan)
                                                <br><small class="text-danger">({{ $d->alasan_pembatalan }})</small>
                                            @endif
                                        @else
                                            {{-- Status Bertahap --}}
                                            <div class="mb-1">
                                                @if($d->approved == 0)
                                                    <span class="badge-status-pill btn-primary-light">Menunggu Koor. Prodi</span>
                                                @elseif($d->approved == 1)
                                                    <span class="badge-status-pill btn-primary-light">Disetujui Koor. Prodi</span>
                                                @elseif($d->approved == 2)
                                                    <span class="badge-status-pill btn-primary-light">Disetujui Fakultas</span>
                                                @endif
                                            </div>

                                            {{-- Tombol Approve & Decline Ditmawa (Langsung enable untuk Pendanaan (1) atau setelah Disetujui Fakultas (2)) --}}
                                            @if($d->kategori_prestasi == 1 || $d->approved == 2)
                                                <div class="d-flex justify-content-center align-items-center gap-1 mt-1">
                                                    <form id="approveForm{{ $d->id_prestasi_mahasiswa ?? $d->id_prestasi }}" action="{{ route('ditmawa.data-prestasi.mandiri.approve', $d->id_prestasi_mahasiswa ?? $d->id_prestasi) }}" method="POST" class="d-inline approve-class" data-id="{{ $d->id_prestasi_mahasiswa ?? $d->id_prestasi }}">
                                                        @csrf
                                                        <button type="submit" class="btn-action-icon btn-action-approve" title="Approve Ditmawa">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    </form>

                                                    <form id="declineForm{{ $d->id_prestasi_mahasiswa ?? $d->id_prestasi }}" action="{{ route('ditmawa.data-prestasi.mandiri.decline', $d->id_prestasi_mahasiswa ?? $d->id_prestasi) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="alasan_pembatalan" id="alasan_pembatalan_{{ $d->id_prestasi_mahasiswa ?? $d->id_prestasi }}">
                                                        <button type="button" class="btn-action-icon btn-action-decline" onclick="confirmDecline('{{ $d->id_prestasi_mahasiswa ?? $d->id_prestasi }}')" title="Tolak Prestasi">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endif
                                    </td>

                                    {{-- KOLOM AKSI (Detail, Edit, Hapus - Icon Button Minimalis) --}}
                                    <td class="text-center align-middle">
                                        <div class="d-flex justify-content-center align-items-center gap-1">
                                            {{-- Tombol Detail (Selalu Tampil) --}}
                                            <a href="{{ route('ditmawa.data-prestasi.mandiri.detail', $d->id_prestasi) }}" class="btn-action-icon btn-action-detail" title="Detail Data">
                                                <i class="fa fa-eye"></i>
                                            </a>

                                            @if($d->feeder == 0 && $d->simkatmawa == 0 && $d->pdunsri == 0)
                                                {{-- Tombol Edit (Hanya tampil jika BELUM Verified) --}}
                                                <a href="{{ route('ditmawa.data-prestasi.mandiri.edit', $d->id_prestasi) }}" class="btn-action-icon btn-action-edit" title="Edit Data">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            @endif

                                            @if($d->feeder == 0 && $d->simkatmawa == 0 && $d->pdunsri == 0)
                                                {{-- Tombol Hapus (Hanya tampil jika BELUM Verified) --}}
                                                <form id="delete-form-{{ $d->id_prestasi }}" action="{{ route('ditmawa.data-prestasi.mandiri.delete', $d->id_prestasi) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn-action-icon btn-action-delete" onclick="deleteConfirm('{{ $d->id_prestasi }}')" title="Hapus Data">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
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
            "order": [[ 4, "desc" ]],
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

    function deleteConfirm(id) {
        swal({
            title: "Hapus Data Prestasi?",
            text: "Apakah Anda yakin ingin menghapus data prestasi mahasiswa ini?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }, function(isConfirm) {
            if (isConfirm) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush