@extends('layouts.ditmawa')

@section('title', 'Detail Prestasi Mahasiswa')

@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Detail Prestasi Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('ditmawa') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('ditmawa.data-prestasi.mandiri.index') }}">Data Prestasi</a></li>
                        <li class="breadcrumb-item active">Detail Data</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h4 class="box-title text-dark fw-bold">View Data</h4>
        </div>
        <div class="box-body">
            <div class="row g-3">
                {{-- Level & Kategori --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Level</label>
                    <div class="form-control bg-light text-dark border-0">{{ $prestasi->nama_tingkat_prestasi ?? '-' }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kategori</label>
                    <div class="form-control bg-light text-dark border-0">{{ $prestasi->nama_jenis_prestasi ?? '-' }}</div>
                </div>

                {{-- Nama Kompetisi / Lomba --}}
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Nama Kompetisi / Lomba</label>
                    <div class="form-control bg-light text-dark border-0">{{ $prestasi->nama_prestasi }}</div>
                </div>

                {{-- Nama Cabang & Peringkat --}}
                <div class="col-md-8 mb-3">
                    <label class="form-label fw-bold">Nama Cabang</label>
                    <div class="form-control bg-light text-dark border-0">{{ $prestasi->nama_prestasi }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Peringkat</label>
                    <div class="form-control bg-light text-dark border-0">Juara {{ $prestasi->peringkat }}</div>
                </div>

                {{-- Nama Penyelenggara --}}
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Nama Penyelenggara</label>
                    <div class="form-control bg-light text-dark border-0">{{ $prestasi->penyelenggara }}</div>
                </div>

                {{-- Jumlah PT, Kepesertaan, Bentuk --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Jumlah PT / Negara Peserta</label>
                    <div class="form-control bg-light text-dark border-0">{{ $prestasi->jumlah_unit_peserta ?? '-' }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Kepesertaan</label>
                    <div class="form-control bg-light text-dark border-0">{{ optional($prestasi->aktivitas_mahasiswa)->nama_jenis_anggota ?? 'Kelompok' }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Bentuk</label>
                    <div class="form-control bg-light text-dark border-0">{{ $prestasi->bentuk ?? '-' }}</div>
                </div>

                {{-- URL Kompetisi --}}
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">URL Kompetisi / Lomba</label>
                    <div class="form-control bg-light border-0">
                        @if($prestasi->url_kompetisi)
                            <a href="{{ $prestasi->url_kompetisi }}" target="_blank" class="text-primary text-break">{{ $prestasi->url_kompetisi }}</a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                {{-- Sertifikat & Tanggal --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Link Dokumen Sertifikat</label>
                    <div class="form-control bg-light border-0">
                        @if($prestasi->file_prestasi)
                            <a href="{{ $prestasi->file_prestasi }}" target="_blank" class="text-primary text-break">{{ $prestasi->file_prestasi }}</a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Sertifikat</label>
                    <div class="form-control bg-light text-dark border-0">{{ optional($prestasi->aktivitas_mahasiswa)->tanggal_sk_tugas ? date('Y-m-d', strtotime($prestasi->aktivitas_mahasiswa->tanggal_sk_tugas)) : '-' }}</div>
                </div>

                {{-- Link Foto UPP & Undangan --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Link Foto UPP</label>
                    <div class="form-control bg-light border-0">
                        @if($prestasi->file_foto_upp)
                            <a href="{{ $prestasi->file_foto_upp }}" target="_blank" class="text-primary text-break">{{ $prestasi->file_foto_upp }}</a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Link Dokumen Undangan</label>
                    <div class="form-control bg-light border-0">
                        @if($prestasi->file_undangan)
                            <a href="{{ $prestasi->file_undangan }}" target="_blank" class="text-primary text-break">{{ $prestasi->file_undangan }}</a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                {{-- Keterangan --}}
                <div class="col-md-12 mb-4">
                    <label class="form-label fw-bold">Keterangan</label>
                    <div class="form-control bg-light text-dark border-0">{{ optional($prestasi->aktivitas_mahasiswa)->keterangan ?? '-' }}</div>
                </div>

                {{-- TABEL DATA MAHASISWA --}}
                <div class="col-12 mb-4">
                    <h5 class="fw-bold text-dark mb-3">Data Mahasiswa</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 10%" class="text-center">No</th>
                                    <th style="width: 30%">NIM</th>
                                    <th>Nama Mahasiswa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($anggota_mhs as $idx => $m)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $idx + 1 }}</td>
                                        <td>{{ $m->nim ?? '-' }}</td>
                                        <td>{{ $m->nama_mahasiswa }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center fw-bold">1</td>
                                        <td>{{ optional($prestasi->riwayat_pendidikan)->nim ?? '-' }}</td>
                                        <td>{{ $prestasi->nama_mahasiswa }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABEL DATA DOSEN --}}
                <div class="col-12 mb-4">
                    <h5 class="fw-bold text-dark mb-3">Data Dosen</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 10%" class="text-center">No</th>
                                    <th style="width: 25%">NUPTK/NIDN</th>
                                    <th style="width: 35%">Nama Dosen</th>
                                    <th>Surat Tugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dosen_bimbing as $idx => $d)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $idx + 1 }}</td>
                                        <td>{{ $d->nidn ?? $d->nuptk ?? '-' }}</td>
                                        <td>{{ $d->nama_dosen }}</td>
                                        <td>
                                            @if($d->file_surat_tugas)
                                                <a href="{{ $d->file_surat_tugas }}" target="_blank" class="text-primary text-break">{{ $d->file_surat_tugas }}</a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Tidak ada data dosen pendamping</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <a href="{{ route('ditmawa.data-prestasi.mandiri.index') }}" class="btn btn-warning px-4 text-white fw-bold">Cancel</a>
        </div>
    </div>
</section>
@endsection