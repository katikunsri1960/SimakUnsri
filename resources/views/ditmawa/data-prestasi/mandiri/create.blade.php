@extends('layouts.ditmawa')

@section('title')
Tambah Data Prestasi Mandiri Mahasiswa
@endsection

@section('content')
@include('swal')

<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Tambah Data Prestasi Mandiri Mahasiswa</h3>

            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('ditmawa') }}">
                                <i class="mdi mdi-home-outline"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('ditmawa.data-prestasi.mandiri.index') }}">Data Prestasi Mahasiswa</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Tambah Mandiri
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
            <form action="{{ route('ditmawa.data-prestasi.mandiri.store') }}" method="POST" id="form-prestasi">
                @csrf

                {{-- SECTION 1: DATA PRESTASI --}}
                <div class="box box-outline-success bs-3 border-success mb-4">
                    <div class="box-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-2 bg-primary text-white rounded me-2">
                                <i class="fa fa-trophy fs-5"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Data Prestasi</h5>
                                <small class="text-muted">Informasi kompetisi, prestasi, dan tautan bukti utama.</small>
                            </div>
                        </div>
                        <hr class="my-15">

                        <div class="row g-3">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Kategori<span class="text-danger">*</span></label>
                                <select name="kategori_pendanaan" class="form-select @error('kategori_pendanaan') is-invalid @enderror" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="1" {{ old('kategori_pendanaan') == '1' ? 'selected' : '' }}>Pendanaan</option>
                                    <option value="2" {{ old('kategori_pendanaan') == '2' ? 'selected' : '' }}>Non Pendanaan</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Level / Tingkat<span class="text-danger">*</span></label>
                                <select name="id_tingkat_prestasi" class="form-select @error('id_tingkat_prestasi') is-invalid @enderror" required>
                                    <option value="">Pilih Level</option>
                                    @foreach($tingkat_prestasi as $t)
                                        <option value="{{ $t->id_tingkat_prestasi }}" {{ old('id_tingkat_prestasi') == $t->id_tingkat_prestasi ? 'selected' : '' }}>
                                            {{ $t->nama_tingkat_prestasi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori_prestasi" class="form-select @error('kategori_prestasi') is-invalid @enderror" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="4" {{ old('kategori_prestasi') == '4' ? 'selected' : '' }}>Riset dan Inovasi : STEM</option>
                                    <option value="5" {{ old('kategori_prestasi') == '5' ? 'selected' : '' }}>Riset dan Inovasi : SSH</option>
                                    <option value="2" {{ old('kategori_prestasi') == '2' ? 'selected' : '' }}>Seni dan Budaya</option>
                                    <option value="3" {{ old('kategori_prestasi') == '3' ? 'selected' : '' }}>Olahraga</option>
                                    <option value="1" {{ old('kategori_prestasi') == '1' ? 'selected' : '' }}>Minat Khusus</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Nama Kompetisi / Lomba<span class="text-danger">*</span></label>
                                <input type="text" name="nama_kompetisi" class="form-control" value="{{ old('nama_kompetisi') }}" placeholder="Nama Kompetisi / Lomba" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Cabang / Bidang<span class="text-danger">*</span></label>
                                <input type="text" name="nama_cabang" class="form-control" value="{{ old('nama_cabang') }}" placeholder="Contoh: Debat / Karya Tulis / Futsal" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Peringkat / Capaian<span class="text-danger">*</span></label>
                                <select name="peringkat" class="form-select" required>
                                    <option value="">Pilih Peringkat</option>
                                    <option value="1" {{ old('peringkat') == '1' ? 'selected' : '' }}>Juara 1</option>
                                    <option value="2" {{ old('peringkat') == '2' ? 'selected' : '' }}>Juara 2</option>
                                    <option value="3" {{ old('peringkat') == '3' ? 'selected' : '' }}>Juara 3</option>
                                    <option value="4" {{ old('peringkat') == '4' ? 'selected' : '' }}>Harapan 1</option>
                                    <option value="5" {{ old('peringkat') == '5' ? 'selected' : '' }}>Harapan 2</option>
                                    <option value="9" {{ old('peringkat') == '9' ? 'selected' : '' }}>Finalis / Peserta</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Nama Penyelenggara<span class="text-danger">*</span></label>
                                <input type="text" name="penyelenggara" class="form-control" value="{{ old('penyelenggara') }}" placeholder="Instansi / Organisasi Penyelenggara" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jumlah PT Peserta<span class="text-danger">*</span></label>
                                <input type="number" name="jumlah_pt_peserta" class="form-control" value="{{ old('jumlah_pt_peserta', 1) }}" placeholder="Jumlah PT" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kepesertaan<span class="text-danger">*</span></label>
                                <select name="kepesertaan" class="form-select" required>
                                    <option value="">Pilih Kepesertaan</option>
                                    <option value="0" {{ old('kepesertaan') == '0' ? 'selected' : '' }}>Individu</option>
                                    <option value="1" {{ old('kepesertaan') == '1' ? 'selected' : '' }}>Kelompok</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Bentuk Pelaksanaan<span class="text-danger">*</span></label>
                                <select name="bentuk" class="form-select" required>
                                    <option value="">Pilih Bentuk</option>
                                    <option value="LURING" {{ old('bentuk') == 'LURING' ? 'selected' : '' }}>Tatap Muka / Luring</option>
                                    <option value="DARING" {{ old('bentuk') == 'DARING' ? 'selected' : '' }}>Daring / Online</option>
                                </select>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">URL Website / Link Informasi Lomba</label>
                                    <input type="url" name="url_kompetisi" class="form-control" value="{{ old('url_kompetisi') }}" placeholder="https://...">
                                </div>

                                {{-- URL SERTIFIKAT --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">URL Dokumen Sertifikat <span class="text-danger">*</span></label>
                                    <input type="url" name="url_sertifikat" class="form-control @error('url_sertifikat') is-invalid @enderror" value="{{ old('url_sertifikat') }}" placeholder="https://..." required>
                                    <small class="text-muted d-block mt-1">Masukkan tautan langsung ke berkas sertifikat.</small>
                                    @error('url_sertifikat')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Sertifikat <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_sertifikat" class="form-control" value="{{ old('tanggal_sertifikat') }}" required>
                                </div>

                                {{-- URL FOTO UPP --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">URL Dokumentasi / Foto UPP</label>
                                    <input type="url" name="url_foto_upp" class="form-control @error('url_foto_upp') is-invalid @enderror" value="{{ old('url_foto_upp') }}" placeholder="https://...">
                                    <small class="text-muted d-block mt-1">Opsional, tautan berkas foto / UPP.</small>
                                    @error('url_foto_upp')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- URL SURAT UNDANGAN --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">URL Dokumen Surat Undangan</label>
                                    <input type="url" name="url_undangan" class="form-control @error('url_undangan') is-invalid @enderror" value="{{ old('url_undangan') }}" placeholder="https://...">
                                    <small class="text-muted d-block mt-1">Opsional, tautan berkas surat undangan.</small>
                                    @error('url_undangan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Keterangan Tambahan</label>
                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: DATA MAHASISWA --}}
                <div class="box box-outline-success bs-3 border-success mb-4">
                    <div class="box-header with-border">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h4 class="text-info mb-0"><i class="fa fa-users"></i> Data Mahasiswa</h4>
                            </div>
                            <div class="col-6 text-end">
                                <button id="add-mhs" type="button" class="btn btn-success btn-sm" title="Tambah Mahasiswa">
                                    <i class="fa fa-plus me-1"></i> Tambah Mahasiswa
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="mhs-fields">
                            <div class="mhs-field row mb-2 align-items-center">
                                <div class="col-md-11 mb-2">
                                    <label class="form-label label-mhs">Nama Mahasiswa 1 (Ketua)</label>
                                    <select class="form-select select2-mhs" name="mahasiswa[]" required></select>
                                </div>
                                <div class="col-md-1 mb-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-rounded btn-sm remove-mhs form-control" style="display: none;" title="Hapus Mahasiswa">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: DATA DOSEN PENDAMPING & SINGLE SK PEMBIMBING --}}
                <div class="box box-outline-success bs-3 border-success mb-4">
                    <div class="box-header with-border">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h4 class="text-info mb-0"><i class="fa fa-user"></i> Dosen Pendamping</h4>
                            </div>
                            <div class="col-6 text-end">
                                <button id="add-dosen" type="button" class="btn btn-success btn-sm" title="Tambah Dosen">
                                    <i class="fa fa-plus me-1"></i> Tambah Dosen
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row mb-3 pb-3 border-bottom g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nomor ST Pembimbing</label>
                                <input type="text" name="no_sk_pembimbing" class="form-control" value="{{ old('no_sk_pembimbing') }}" placeholder="Contoh: 0123/UN9/SK/2026">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tanggal ST Pembimbing</label>
                                <input type="date" name="tanggal_sk_pembimbing" class="form-control" value="{{ old('tanggal_sk_pembimbing') }}">
                            </div>
                            {{-- URL SK PEMBIMBING --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold">URL ST Pembimbing</label>
                                <input type="url" name="url_sk_pembimbing" class="form-control @error('url_sk_pembimbing') is-invalid @enderror" value="{{ old('url_sk_pembimbing') }}" placeholder="https://...">
                                <small class="text-muted d-block mt-1">Tautan berkas ST Pembimbing.</small>
                                @error('url_sk_pembimbing')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div id="dosen-fields">
                            <div class="dosen-field row mb-2 align-items-center">
                                <div class="col-md-11 mb-2">
                                    <label class="form-label label-dosen">Nama Dosen Pendamping 1</label>
                                    <select class="form-select select2-dosen" name="dosen_bimbing[]"></select>
                                </div>
                                <div class="col-md-1 mb-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-rounded btn-sm remove-dosen form-control" style="display: none;" title="Hapus Dosen">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BUTTON FOOTER --}}
                <div class="d-flex justify-content-between mb-30">
                    <a href="{{ route('ditmawa.data-prestasi.mandiri.index') }}" class="btn btn-secondary px-4">
                        <i class="fa fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="button" class="btn btn-primary px-4" id="btn-submit-confirm">
                        <i class="fa fa-save me-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('js')
<script src="{{ asset('assets/vendor_components/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/vendor_components/select2/dist/js/select2.full.min.js') }}"></script>

<script>
$(document).ready(function() {
    var mhsCounter = 1;
    var dosenCounter = 1;

    // Function mengumpulkan ID Mahasiswa terpilih
    function getSelectedMahasiswa() {
        var selected = [];
        $('.select2-mhs').each(function() {
            var val = $(this).val();
            if (val) selected.push(val);
        });
        return selected;
    }

    // Function mengumpulkan ID Dosen terpilih
    function getSelectedDosen() {
        var selected = [];
        $('.select2-dosen').each(function() {
            var val = $(this).val();
            if (val) selected.push(val);
        });
        return selected;
    }

    // Initializer Select2 Mahasiswa (Dengan Filter Exclude)
    function initializeSelect2Mhs(element) {
        return element.select2({
            placeholder: '-- Ketik NIM / Nama Mahasiswa --',
            minimumInputLength: 3,
            ajax: {
                url: "{{ route('ditmawa.data-prestasi.mandiri.get-mahasiswa') }}",
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
                    return { results: data.results || [] }; 
                },
                cache: true
            }
        });
    }

    // Initializer Select2 Dosen (Dengan Filter Exclude)
    function initializeSelect2Dosen(element) {
        return element.select2({
            placeholder: '-- Ketik NIDN / Nama Dosen --',
            minimumInputLength: 3,
            ajax: {
                url: "{{ route('ditmawa.data-prestasi.mandiri.get-dosen') }}",
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: function (params) { 
                    return { 
                        q: params.term,
                        selected: getSelectedDosen()
                    }; 
                },
                processResults: function (data) { 
                    return { results: data.results || [] }; 
                },
                cache: true
            }
        });
    }

    // Inisialisasi awal
    $('.select2-mhs').each(function() { initializeSelect2Mhs($(this)); });
    $('.select2-dosen').each(function() { initializeSelect2Dosen($(this)); });

    // Tambah Baris Mahasiswa
    $('#add-mhs').click(function() {
        mhsCounter++;
        var newRow = $(
            '<div class="mhs-field row mb-2 align-items-center">' +
                '<div class="col-md-11 mb-2">' +
                    '<label class="form-label label-mhs">Nama Mahasiswa ' + mhsCounter + ' (Anggota)</label>' +
                    '<select class="form-select select2-mhs" name="mahasiswa[]" required></select>' +
                '</div>' +
                '<div class="col-md-1 mb-2">' +
                    '<label class="form-label">&nbsp;</label>' +
                    '<button type="button" class="btn btn-danger btn-rounded btn-sm remove-mhs form-control" title="Hapus Mahasiswa">' +
                        '<i class="fa fa-trash" aria-hidden="true"></i>' +
                    '</button>' +
                '</div>' +
            '</div>'
        );
        $('#mhs-fields').append(newRow);
        initializeSelect2Mhs(newRow.find('.select2-mhs'));
        updateRemoveButtonMhs();
    });

    // Tambah Baris Dosen
    $('#add-dosen').click(function() {
        dosenCounter++;
        var newRow = $(
            '<div class="dosen-field row mb-2 align-items-center">' +
                '<div class="col-md-11 mb-2">' +
                    '<label class="form-label label-dosen">Nama Dosen Pendamping ' + dosenCounter + '</label>' +
                    '<select class="form-select select2-dosen" name="dosen_bimbing[]"></select>' +
                '</div>' +
                '<div class="col-md-1 mb-2">' +
                    '<label class="form-label">&nbsp;</label>' +
                    '<button type="button" class="btn btn-danger btn-rounded btn-sm remove-dosen form-control" title="Hapus Dosen">' +
                        '<i class="fa fa-trash" aria-hidden="true"></i>' +
                    '</button>' +
                '</div>' +
            '</div>'
        );
        $('#dosen-fields').append(newRow);
        initializeSelect2Dosen(newRow.find('.select2-dosen'));
        updateRemoveButtonDosen();
    });

    // Hapus Baris Mahasiswa
    $(document).on('click', '.remove-mhs', function() {
        $(this).closest('.mhs-field').remove();
        updateRemoveButtonMhs();
    });

    // Hapus Baris Dosen
    $(document).on('click', '.remove-dosen', function() {
        $(this).closest('.dosen-field').remove();
        updateRemoveButtonDosen();
    });

    function updateRemoveButtonMhs() {
        if ($('.mhs-field').length <= 1) {
            $('.remove-mhs').hide();
        } else {
            $('.remove-mhs').show();
        }
    }

    function updateRemoveButtonDosen() {
        if ($('.dosen-field').length <= 1) {
            $('.remove-dosen').hide();
        } else {
            $('.remove-dosen').show();
        }
    }

    // ==========================================
    // ALERT KONFIRMASI DENGAN SWEETALERT
    // ==========================================
    $('#btn-submit-confirm').click(function() {
        var form = document.getElementById('form-prestasi');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        swal({
            title: "Apakah Anda yakin?",
            text: "Data prestasi mandiri mahasiswa ini akan disimpan ke dalam sistem.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Simpan!",
            cancelButtonText: "Batal"
        }, function(isConfirm) {
            if (isConfirm) {
                form.submit();
            }
        });
    });
});
</script>
@endpush