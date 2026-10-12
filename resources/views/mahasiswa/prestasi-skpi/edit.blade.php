@extends('layouts.mahasiswa')
@section('title', 'Edit Prestasi Mahasiswa')

@section('content')
@include('swal')

<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Edit Prestasi Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('mahasiswa.dashboard')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{route('mahasiswa.prestasi-skpi.index')}}">Prestasi Mahasiswa</a></li>
                        <li class="breadcrumb-item active">Edit Prestasi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success p-20">
                @if($skpi->approved > 0 || ($skpi->prestasi && $skpi->prestasi->approved > 0))
                    <div class="alert alert-danger">
                        Data yang sudah diverifikasi tidak dapat diperbarui.
                    </div>
                @endif

                <form id="form-update-prestasi" action="{{ route('mahasiswa.prestasi-skpi.update', $skpi->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <fieldset {{ ($skpi->approved > 0 || ($skpi->prestasi && $skpi->prestasi->approved > 0)) ? 'disabled' : '' }}>
                        <div class="row">
                            {{-- Kategori --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori Prestasi <span class="text-danger">*</span></label>
                                <select name="kategori_prestasi" class="form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="1" {{ old('kategori_prestasi', optional($skpi->prestasi)->kategori_prestasi) == 1 ? 'selected' : '' }}>Pendanaan</option>
                                    <option value="2" {{ old('kategori_prestasi', optional($skpi->prestasi)->kategori_prestasi) == 2 ? 'selected' : '' }}>Non Pendanaan</option>
                                </select>
                            </div>

                            {{-- Nama Prestasi / Kegiatan --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Prestasi <span class="text-danger">*</span></label>
                                <input type="text" name="nama_prestasi" class="form-control" value="{{ old('nama_prestasi', $skpi->nama_kegiatan) }}" required>
                            </div>

                            {{-- Jenis Prestasi SKPI --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Prestasi <span class="text-danger">*</span></label>
                                <select name="jenis_prestasi" class="form-select" required>
                                    <option value="">-- Pilih Jenis Prestasi --</option>
                                    @foreach($jenis_prestasi as $id_jenis => $nama_jenis)
                                        <option value="{{ $id_jenis }}" {{ old('jenis_prestasi', $skpi->id_jenis_skpi) == $id_jenis ? 'selected' : '' }}>
                                            {{ $nama_jenis }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tingkat Prestasi --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tingkat Prestasi <span class="text-danger">*</span></label>
                                <select name="tingkat_prestasi" class="form-select" required>
                                    <option value="">-- Pilih Tingkat Prestasi --</option>
                                    @foreach($tingkat_prestasi as $tp)
                                        <option value="{{ $tp->id_tingkat_prestasi }}" {{ old('tingkat_prestasi', optional($skpi->prestasi)->id_tingkat_prestasi) == $tp->id_tingkat_prestasi ? 'selected' : '' }}>
                                            {{ $tp->nama_tingkat_prestasi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tahun --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Prestasi <span class="text-danger">*</span></label>
                                <input type="number" name="tahun_prestasi" class="form-control" value="{{ old('tahun_prestasi', $skpi->tahun) }}" min="2000" max="{{ date('Y') }}" required>
                            </div>

                            {{-- Penyelenggara --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penyelenggara <span class="text-danger">*</span></label>
                                <input type="text" name="penyelenggara" class="form-control" value="{{ old('penyelenggara', optional($skpi->prestasi)->penyelenggara) }}" required>
                            </div>

                            {{-- Preview Piagam & Upload --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">File Piagam / Sertifikat (PDF Max 500 KB)</label>
                                
                                @if($skpi->file_pendukung)
                                    <div class="card mb-3 border bg-light">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span class="fw-bold text-dark">
                                                    <i class="fa fa-file-pdf-o text-danger me-2"></i> Preview Piagam Saat Ini
                                                </span>
                                                <a href="{{ asset('storage/' . $skpi->file_pendukung) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-external-link"></i> Buka di Tab Baru
                                                </a>
                                            </div>
                                            
                                            <div class="ratio ratio-16x9 rounded overflow-hidden border" style="max-height: 400px;">
                                                <embed src="{{ asset('storage/' . $skpi->file_pendukung) }}" type="application/pdf" width="100%" height="400px" />
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <input type="file" name="file_prestasi" class="form-control" accept="application/pdf">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengganti file piagam yang sudah ada.</small>
                            </div>
                        </div>

                        @if($skpi->approved == 0)
                            <div class="text-end mt-10">
                                <button type="submit" class="btn btn-primary">Update Data</button>
                                <a href="{{ route('mahasiswa.prestasi-skpi.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        @endif
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection