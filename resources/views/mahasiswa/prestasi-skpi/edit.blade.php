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
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{route('mahasiswa.prestasi.index')}}">Prestasi Mahasiswa</a></li>
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
                @if($prestasi->approved > 0)
                    <div class="alert alert-danger">
                        Data yang sudah diverifikasi tidak dapat diperbarui.
                    </div>
                @endif

                <form id="form-update-prestasi" action="{{ route('mahasiswa.prestasi.update', $prestasi->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <fieldset {{ $prestasi->approved > 0 ? 'disabled' : '' }}>
                        <div class="row">
                            {{-- Kategori --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori Prestasi <span class="text-danger">*</span></label>
                                <select name="kategori_prestasi" class="form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="1" {{ $prestasi->kategori_prestasi == 1 ? 'selected' : '' }}>Pendanaan</option>
                                    <option value="2" {{ $prestasi->kategori_prestasi == 2 ? 'selected' : '' }}>Non Pendanaan</option>
                                </select>
                            </div>

                            {{-- Nama Prestasi --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Prestasi <span class="text-danger">*</span></label>
                                <input type="text" name="nama_prestasi" class="form-control" value="{{ old('nama_prestasi', $prestasi->nama_prestasi) }}" required>
                            </div>

                            {{-- Bidang SKPI --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bidang Kegiatan SKPI <span class="text-danger">*</span></label>
                                <select name="bidang_id" id="edit_bidang_id" class="form-select" required>
                                    <option value="">-- Pilih Bidang SKPI --</option>
                                    @foreach($bidang as $b)
                                        <option value="{{ $b->id }}" {{ $prestasi->bidang_id == $b->id ? 'selected' : '' }}>
                                            {{ $b->nama_bidang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Sub Bidang SKPI --}}
                            <div class="col-md-6 mb-3" id="wrapper_sub_bidang_edit" style="{{ $prestasi->bidang_id == 4 ? '' : 'display: none;' }}">
                                <label class="form-label">Sub Bidang Kegiatan <span class="text-danger">*</span></label>
                                <select name="sub_bidang_id" id="edit_sub_bidang_id" class="form-select" {{ $prestasi->bidang_id == 4 ? 'required' : '' }}>
                                    <option value="">-- Pilih Sub Bidang --</option>
                                    @foreach($sub_bidang as $sb)
                                        <option value="{{ $sb->id }}" {{ $prestasi->sub_bidang_id == $sb->id ? 'selected' : '' }}>
                                            {{ $sb->nama_sub_bidang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Jenis Prestasi --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Prestasi <span class="text-danger">*</span></label>
                                <select name="jenis_prestasi" class="form-select" required>
                                    <option value="">-- Pilih Jenis Prestasi --</option>
                                    @foreach($jenis_prestasi as $jp)
                                        <option value="{{ $jp->id_jenis_prestasi }}" {{ $prestasi->id_jenis_prestasi == $jp->id_jenis_prestasi ? 'selected' : '' }}>
                                            {{ $jp->nama_jenis_prestasi }}
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
                                        <option value="{{ $tp->id_tingkat_prestasi }}" {{ $prestasi->id_tingkat_prestasi == $tp->id_tingkat_prestasi ? 'selected' : '' }}>
                                            {{ $tp->nama_tingkat_prestasi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tahun --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Prestasi <span class="text-danger">*</span></label>
                                <input type="number" name="tahun_prestasi" class="form-control" value="{{ old('tahun_prestasi', $prestasi->tahun_prestasi) }}" required>
                            </div>

                            {{-- Penyelenggara --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penyelenggara <span class="text-danger">*</span></label>
                                <input type="text" name="penyelenggara" class="form-control" value="{{ old('penyelenggara', $prestasi->penyelenggara) }}" required>
                            </div>

                            {{-- File Prestasi --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Upload Piagam / Sertifikat (PDF Max 500 KB)</label>
                                @if($prestasi->file_prestasi)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/'.$prestasi->file_prestasi) }}" target="_blank" class="btn btn-sm btn-success">
                                            <i class="fa fa-file-pdf-o"></i> Lihat File Saat Ini
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="file_prestasi" class="form-control" accept="application/pdf">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah sertifikat.</small>
                            </div>
                        </div>

                        @if($prestasi->approved == 0)
                            <div class="text-end mt-10">
                                <button type="submit" class="btn btn-primary">Update Data</button>
                                <a href="{{ route('mahasiswa.prestasi.index') }}" class="btn btn-secondary">Kembali</a>
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

        // Toggle Sub Bidang Edit
        $('#edit_bidang_id').on('change', function(){
            let val = $(this).val();
            if (parseInt(val) === 4) {
                $('#wrapper_sub_bidang_edit').slideDown();
                $('#edit_sub_bidang_id').prop('required', true);
            } else {
                $('#wrapper_sub_bidang_edit').slideUp();
                $('#edit_sub_bidang_id').prop('required', false).val('');
            }
        });

        // Submit Confirm
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