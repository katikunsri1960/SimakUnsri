@extends('layouts.ditmawa')

@section('title')
Edit Kandidat Capresma
@endsection

@section('content')
@include('swal')

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <strong>Gagal Memperbarui Data!</strong>
        <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<section class="content">
    <div class="row align-items-end mb-3">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3">
                            <img src="{{ asset('images/images/svg-icon/color-svg/custom-14.svg') }}" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h2>Edit Data Paslon No. Urut {{ $no_urut }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="box box-outline-warning bs-3 border-warning">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Form Update Data Paslon</h4>
                    <a href="{{ route('ditmawa.presiden-mahasiswa.index') }}" class="btn btn-warning waves-effect waves-light">
                        <i class="fa fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>

                <form action="{{ route('ditmawa.presiden-mahasiswa.update', $no_urut) }}" method="POST" enctype="multipart/form-data" class="form-edit">
                    @csrf
                    @method('PUT')

                    <div class="box-body">
                        <div class="row">
                            {{-- Nomor Urut --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">No. Urut Paslon <span class="text-danger">*</span></label>
                                <input type="number" name="no_urut" class="form-control" placeholder="Contoh: 1" value="{{ old('no_urut', $no_urut) }}" required>
                            </div>

                            {{-- Capresma --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cari & Pilih Capresma <span class="text-danger">*</span></label>
                                <select name="id_registrasi_mahasiswa_capresma" id="select_capresma" class="form-control select2-mhs" required>
                                    @if($capresma)
                                        <option value="{{ $capresma->id_registrasi_mahasiswa }}" selected>
                                            {{ $capresma->nim }} - {{ $capresma->nama_mahasiswa }}
                                        </option>
                                    @else
                                        <option value="">-- Ketik NIM / Nama Mahasiswa --</option>
                                    @endif
                                </select>
                            </div>

                            {{-- Cawapresma --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cari & Pilih Cawapresma <span class="text-danger">*</span></label>
                                <select name="id_registrasi_mahasiswa_cawapresma" id="select_cawapresma" class="form-control select2-mhs" required>
                                    @if($cawapresma)
                                        <option value="{{ $cawapresma->id_registrasi_mahasiswa }}" selected>
                                            {{ $cawapresma->nim }} - {{ $cawapresma->nama_mahasiswa }}
                                        </option>
                                    @else
                                        <option value="">-- Ketik NIM / Nama Mahasiswa --</option>
                                    @endif
                                </select>
                            </div>

                            {{-- Visi & Misi --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Visi <span class="text-danger">*</span></label>
                                <textarea name="visi" class="form-control" rows="3" required>{{ old('visi', $visi) }}</textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Misi <span class="text-danger">*</span></label>
                                <textarea name="misi" class="form-control" rows="4" required>{{ old('misi', $misi) }}</textarea>
                            </div>

                            {{-- Link Video Kampanye (Diubah Required) --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Link Video Kampanye (YouTube / Drive)</label>
                                @if($video)
                                    <div class="mb-2 p-2 bg-light rounded border d-flex align-items-center justify-content-between">
                                        <small class="text-muted"><i class="fa fa-youtube-play text-danger me-1"></i> Link Video Saat Ini: <a href="{{ $video }}" target="_blank" class="fw-bold text-primary">{{ $video }}</a></small>
                                        <a href="{{ $video }}" target="_blank" class="btn btn-xs btn-outline btn-danger">
                                            <i class="fa fa-external-link"></i> Buka Tautan
                                        </a>
                                    </div>
                                @endif
                                <input type="url" name="video" class="form-control" placeholder="Contoh: https://www.youtube.com/watch?v=..." value="{{ old('video', $video) }}" required>
                                <small class="text-muted d-block mt-1">Wajib: Masukkan tautan/URL video perkenalan atau kampanye paslon</small>
                            </div>

                            {{-- Preview & Upload Foto Paslon (Diubah Required jika foto belum pernah ada) --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Foto Paslon</label>
                                @if($foto)
                                    <div class="card p-2 mb-2 bg-light border text-center">
                                        <small class="text-muted mb-2 d-block">Foto Paslon Saat Ini:</small>
                                        <img src="{{ asset('storage/' . $foto) }}" alt="Foto Paslon Lama" class="img-fluid rounded border border-primary shadow-sm mx-auto" style="max-height: 500px; object-fit: cover;">
                                    </div>
                                @else
                                    <div class="p-3 mb-2 bg-light border rounded text-center text-muted">
                                        <small>Belum ada foto yang diunggah</small>
                                    </div>
                                @endif
                                <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg" {{ $foto ? '' : 'required' }}>
                                <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG (Maksimal 2MB). {{ $foto ? 'Pilih file baru jika ingin mengganti foto' : '' }}</small>
                            </div>

                            {{-- Preview & Upload Berkas Pendukung (Pratinjau PDF Embedded + Required jika berkas belum pernah ada) --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Berkas Pendukung</label>
                                @if($berkas)
                                    @php
                                        $extension = strtolower(pathinfo($berkas, PATHINFO_EXTENSION));
                                    @endphp
                                    <div class="card p-2 mb-2 bg-light border">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <small class="text-muted"><i class="fa fa-file-pdf-o text-danger me-1"></i> Berkas saat ini: <strong>{{ basename($berkas) }}</strong></small>
                                            <a href="{{ asset('storage/' . $berkas) }}" target="_blank" class="btn btn-xs btn-info">
                                                <i class="fa fa-external-link me-1"></i> Buka di Tab Baru
                                            </a>
                                        </div>

                                        {{-- Embedded PDF Viewer jika file berformat PDF --}}
                                        @if($extension === 'pdf')
                                            <div class="ratio ratio-4x3 rounded border overflow-hidden" style="max-height: 500px;">
                                                <iframe src="{{ asset('storage/' . $berkas) }}#toolbar=0" style="width: 100%; height: 500px; border: none;"></iframe>
                                            </div>
                                        @else
                                            <div class="p-3 text-center bg-white border rounded">
                                                <i class="fa fa-file-archive-o fa-2x text-warning mb-1"></i>
                                                <p class="mb-0 small text-muted">Pratinjau tidak tersedia untuk format {{ strtoupper($extension) }}. Silakan unduh melalui tombol di atas.</p>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="p-3 mb-2 bg-light border rounded text-center text-muted">
                                        <small>Belum ada berkas pendukung yang diunggah</small>
                                    </div>
                                @endif
                                <input type="file" name="berkas_dukung" class="form-control" accept=".pdf,.zip,.rar" {{ $berkas ? '' : 'required' }}>
                                <small class="text-muted d-block mt-1">Format: PDF, ZIP, RAR (Maksimal 5MB). {{ $berkas ? 'Pilih file baru jika ingin mengganti berkas' : '' }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer text-end">
                        <a href="{{ route('ditmawa.presiden-mahasiswa.index') }}" class="btn btn-secondary mr-1">Batal</a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fa fa-pencil mr-1"></i> Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script src="{{ asset('assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
<script src="{{ asset('assets/vendor_components/sweetalert/sweetalert.min.js') }}"></script>

<script>
$(document).ready(function(){

    function getSelectedMahasiswa() {
        let selected = [];
        $('.select2-mhs').each(function() {
            let val = $(this).val();
            if (val) {
                selected.push(val);
            }
        });
        return selected;
    }

    function initializeSelect2Mhs(element) {
        return element.select2({
            placeholder: '-- Ketik NIM / Nama Mahasiswa --',
            minimumInputLength: 3,
            ajax: {
                url: "{{ route('ditmawa.get-mahasiswa') }}",
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { 
                        q: params.term,
                        selected: getSelectedMahasiswa()
                    }; 
                },
                processResults: function (data) { 
                    let resultsData = $.map(data.results || data, function (item) {
                        return {
                            id: item.id_registrasi_mahasiswa || item.id_reg_mhs || item.id,
                            text: item.text || (item.nim + ' - ' + item.nama_mahasiswa)
                        };
                    });

                    return { results: resultsData }; 
                },
                cache: true
            }
        });
    }

    initializeSelect2Mhs($('#select_capresma'));
    initializeSelect2Mhs($('#select_cawapresma'));

    $('.form-edit').on('submit', function(e){
        var form = this;

        if (!form.checkValidity()) {
            return;
        }

        e.preventDefault();

        swal({
            title: "Update Data?",
            text: "Perubahan data kandidat akan disimpan",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ffc107",
            confirmButtonText: "Ya, Update!",
            cancelButtonText: "Batal"
        }, function(isConfirm){
            if (isConfirm) {
                form.submit();
            }
        });
    });

});
</script>
@endpush