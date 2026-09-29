@extends('layouts.mahasiswa')
@section('title', 'Tambah Prestasi Mahasiswa')

@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Tambah Prestasi Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{route('mahasiswa.prestasi-skpi.index')}}">Prestasi Mahasiswa</a></li>
                        <li class="breadcrumb-item active">Tambah Prestasi</li>
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
                <form class="form" action="{{route('mahasiswa.prestasi-skpi.store')}}" id="tambah-prestasi" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- Default Value untuk Bidang D (4) dan Sub Bidang Kompetisi (1) --}}
                    <input type="hidden" name="bidang_id" value="4">
                    <input type="hidden" name="sub_bidang_id" value="1">

                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Data Mahasiswa</h4>
                        <hr class="my-15">
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nama Mahasiswa</label>
                                <input type="text" class="form-control" value="{{ $data->nama_mahasiswa ?? '-' }}" disabled />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">NIM Mahasiswa</label>
                                <input type="text" class="form-control" value="{{ $data->nim ?? '-' }}" disabled />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Program Studi</label>
                                <input type="text" class="form-control" value="{{ $data->nama_program_studi ?? '-' }}" disabled />
                            </div>
                        </div>

                        <h4 class="text-info mt-30"><i class="fa fa-trophy"></i> Prestasi & Kegiatan SKPI</h4>
                        <hr class="my-15">

                        <div class="row">
                            {{-- Kategori Prestasi --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori Prestasi <span class="text-danger">*</span></label>
                                <select class="form-select @error('kategori_prestasi') is-invalid @enderror" name="kategori_prestasi" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="1" {{ old('kategori_prestasi') == '1' ? 'selected' : '' }}>Pendanaan</option>
                                    <option value="2" {{ old('kategori_prestasi') == '2' ? 'selected' : '' }}>Non Pendanaan</option>
                                </select>
                                @error('kategori_prestasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Nama Prestasi --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Prestasi / Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_prestasi') is-invalid @enderror" name="nama_prestasi" value="{{ old('nama_prestasi') }}" placeholder="Contoh: Juara 1 Lomba Karya Tulis Ilmiah" required />
                                @error('nama_prestasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Jenis Prestasi --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Prestasi <span class="text-danger">*</span></label>
                                <select class="form-select @error('jenis_prestasi') is-invalid @enderror" name="jenis_prestasi" id="jenis_prestasi" required>
                                    <option value="">-- Pilih Jenis Prestasi --</option>
                                    @foreach($jenis_prestasi as $id => $nama)
                                        <option value="{{ $id }}" {{ old('jenis_prestasi') == $id ? 'selected' : '' }}>
                                            {{ $nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_prestasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tingkat Prestasi --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tingkat Prestasi <span class="text-danger">*</span></label>
                                <select class="form-select @error('tingkat_prestasi') is-invalid @enderror" name="tingkat_prestasi" required>
                                    <option value="">-- Pilih Tingkat Prestasi --</option>
                                    @foreach($tingkat_prestasi as $t)
                                        <option value="{{ $t->id_tingkat_prestasi }}" {{ old('tingkat_prestasi') == $t->id_tingkat_prestasi ? 'selected' : '' }}>
                                            {{ $t->nama_tingkat_prestasi }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tingkat_prestasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tahun --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Pelaksanaan <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('tahun_prestasi') is-invalid @enderror" name="tahun_prestasi" value="{{ old('tahun_prestasi') }}" placeholder="Contoh: 2024" required />
                                @error('tahun_prestasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Penyelenggara --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penyelenggara <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('penyelenggara') is-invalid @enderror" name="penyelenggara" value="{{ old('penyelenggara') }}" placeholder="Masukkan Instansi / Penyelenggara" required />
                                @error('penyelenggara')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Upload File --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Upload Piagam / Sertifikat (PDF Max 500 KB) <span class="text-danger">*</span></label>
                                <input type="file" name="file_prestasi" class="form-control @error('file_prestasi') is-invalid @enderror" accept="application/pdf" required>
                                <small class="text-muted d-block mt-1">Maksimal file 500 KB dan format harus PDF.</small>
                                @error('file_prestasi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="box-footer text-end">
                        <a href="{{route('mahasiswa.prestasi-skpi.index')}}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>
    $(document).ready(function(){
        // Submit alert confirmation
        $('#tambah-prestasi').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Pelaporan Prestasi',
                text: "Apakah Anda yakin data yang dimasukkan sudah benar?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }, function(isConfirmed){
                if (isConfirmed) {
                    $('#tambah-prestasi').unbind('submit').submit();
                }
            });
        });
    });
</script>
@endpush