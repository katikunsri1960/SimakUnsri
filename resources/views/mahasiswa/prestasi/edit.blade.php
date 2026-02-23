@extends('layouts.mahasiswa')
@section('title')
Edit Prestasi Mahasiswa
@endsection

@section('content')
@include('swal')

<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Edit Prestasi Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Prestasi Mahasiswa</li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('mahasiswa.prestasi.index')}}">Prestasi Non Pendanaan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Prestasi Non Pendanaan</li>
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
                @if($prestasi->approved > 0)
                    <div class="alert alert-danger">
                        Data yang sudah diverifikasi tidak dapat diedit.
                    </div>
                @endif

                <form id="form-update-prestasi"
                    action="{{ route('mahasiswa.prestasi.update', $prestasi->id) }}"
                    method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <fieldset {{ $prestasi->approved > 0 ? 'disabled' : '' }}>

                    <div class="row">

                        {{-- Kategori Prestasi --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori Prestasi</label>
                            <select name="kategori_prestasi" class="form-control" required>
                                <option value="">Pilih Kategori Prestasi</option>                                            
                                <option value="1" {{ $prestasi->kategori_prestasi == 1 ? 'selected' : '' }}>Pendanaan</option>
                                <option value="2" {{ $prestasi->kategori_prestasi == 2 ? 'selected' : '' }}>Non Pendanaan</option>
                            </select>
                        </div>

                        {{-- Nama Prestasi --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Prestasi</label>
                            <input type="text" 
                                name="nama_prestasi" 
                                class="form-control"
                                value="{{ old('nama_prestasi', $prestasi->nama_prestasi) }}"
                                required>
                        </div>

                        {{-- Jenis Prestasi --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Prestasi</label>
                            <select name="jenis_prestasi" class="form-control" required>
                                <option value="">Pilih Jenis Prestasi</option>
                                @foreach($jenis_prestasi as $jp)
                                    <option value="{{ $jp->id_jenis_prestasi }}"
                                        {{ $prestasi->id_jenis_prestasi == $jp->id_jenis_prestasi ? 'selected' : '' }}>
                                        {{ $jp->nama_jenis_prestasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tingkat Prestasi --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tingkat Prestasi</label>
                            <select name="tingkat_prestasi" class="form-control" required>
                                <option value="">Pilih Tingkat Prestasi</option>
                                @foreach($tingkat_prestasi as $tp)
                                    <option value="{{ $tp->id_tingkat_prestasi }}"
                                        {{ $prestasi->id_tingkat_prestasi == $tp->id_tingkat_prestasi ? 'selected' : '' }}>
                                        {{ $tp->nama_tingkat_prestasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tahun --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Prestasi</label>
                            <input type="number" 
                                name="tahun_prestasi"
                                class="form-control"
                                value="{{ old('tahun_prestasi', $prestasi->tahun_prestasi) }}"
                                required>
                        </div>

                        {{-- Penyelenggara --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Penyelenggara</label>
                            <input type="text" 
                                name="penyelenggara"
                                class="form-control"
                                value="{{ old('penyelenggara', $prestasi->penyelenggara) }}"
                                required>
                        </div>

                        {{-- File Prestasi --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Upload Piagam / Sertifikat (PDF Max 500 KB)
                            </label>

                            @if($prestasi->file_prestasi)
                                <div class="mb-2">
                                    <a href="{{ asset('storage/'.$prestasi->file_prestasi) }}" 
                                    target="_blank" 
                                    class="btn btn-sm btn-success">
                                    Lihat File Lama
                                    </a>
                                </div>
                            @endif

                            <input type="file" 
                                name="file_prestasi"
                                class="form-control"
                                accept="application/pdf">
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti file</small>
                        </div>

                    </div>

                    @if($prestasi->approved == 0)
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Update Data
                        </button>
                        <a href="{{ route('mahasiswa.prestasi.index') }}" 
                        class="btn btn-secondary">
                            Kembali
                        </a>
                    </div>
                    @endif

                    </fieldset>

                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@push('js')
<script>
    $(document).ready(function(){

        $('#form-update-prestasi').submit(function(e){

            e.preventDefault();

            swal({
                title: 'Update Data Prestasi',
                text: "Apakah anda yakin ingin memperbarui data ini?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Update',
                cancelButtonText: 'Batal'
            }, function(isConfirm){

                if (isConfirm) {
                    $('#form-update-prestasi').unbind('submit').submit();
                }

            });

        });

    });
</script>
@endpush