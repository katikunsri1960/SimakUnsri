@extends('layouts.ditmawa')

@section('title', 'Edit Data Prestasi Mahasiswa')

@section('content')
@include('swal')

<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Edit Prestasi Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('ditmawa') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('ditmawa.data-prestasi.mandiri.index') }}">Data Prestasi</a></li>
                        <li class="breadcrumb-item active">Edit Data</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h4 class="box-title text-dark fw-bold">Edit Data Prestasi</h4>
        </div>
        <form action="{{ route('ditmawa.data-prestasi.mandiri.update', $prestasi->id_prestasi) }}" method="POST" id="form-edit-prestasi">
            @csrf
            @method('PUT')
            <div class="box-body">
                <div class="row g-3">
                    {{-- Kategori Pendanaan & Level --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Kategori Pendanaan <span class="text-danger">*</span></label>
                        <select name="kategori_pendanaan" class="form-select" required>
                            <option value="1" {{ old('kategori_pendanaan', $prestasi->kategori_prestasi) == '1' ? 'selected' : '' }}>Pendanaan</option>
                            <option value="2" {{ old('kategori_pendanaan', $prestasi->kategori_prestasi) == '2' ? 'selected' : '' }}>Non Pendanaan</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Level / Tingkat <span class="text-danger">*</span></label>
                        <select name="id_tingkat_prestasi" class="form-select" required>
                            @foreach($tingkat_prestasi as $t)
                                <option value="{{ $t->id_tingkat_prestasi }}" {{ old('id_tingkat_prestasi', $prestasi->id_tingkat_prestasi) == $t->id_tingkat_prestasi ? 'selected' : '' }}>
                                    {{ $t->nama_tingkat_prestasi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kategori Prestasi / Jenis Dikti --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Kategori Prestasi / Jenis SKPI <span class="text-danger">*</span></label>
                        <select name="kategori_prestasi" class="form-select" required>
                            <option value="4" {{ old('kategori_prestasi', $prestasi->kategori_simkatmawa ?? $prestasi->id_jenis_prestasi) == '4' ? 'selected' : '' }}>Riset dan Inovasi : STEM</option>
                            <option value="5" {{ old('kategori_prestasi', $prestasi->kategori_simkatmawa ?? $prestasi->id_jenis_prestasi) == '5' ? 'selected' : '' }}>Riset dan Inovasi : SSH</option>
                            <option value="2" {{ old('kategori_prestasi', $prestasi->kategori_simkatmawa ?? $prestasi->id_jenis_prestasi) == '2' ? 'selected' : '' }}>Seni dan Budaya</option>
                            <option value="3" {{ old('kategori_prestasi', $prestasi->kategori_simkatmawa ?? $prestasi->id_jenis_prestasi) == '3' ? 'selected' : '' }}>Olahraga</option>
                            <option value="1" {{ old('kategori_prestasi', $prestasi->kategori_simkatmawa ?? $prestasi->id_jenis_prestasi) == '1' ? 'selected' : '' }}>Minat Khusus</option>
                        </select>
                    </div>

                    {{-- Nama Kompetisi --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Nama Kompetisi / Lomba <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kompetisi" class="form-control" value="{{ old('nama_kompetisi', $prestasi->nama_prestasi) }}" required>
                    </div>

                    {{-- Nama Cabang & Peringkat --}}
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-bold">Nama Cabang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_cabang" class="form-control" value="{{ old('nama_cabang', $prestasi->nama_prestasi) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Peringkat <span class="text-danger">*</span></label>
                        <select name="peringkat" class="form-select" required>
                            <option value="1" {{ old('peringkat', $prestasi->peringkat) == '1' ? 'selected' : '' }}>Juara 1</option>
                            <option value="2" {{ old('peringkat', $prestasi->peringkat) == '2' ? 'selected' : '' }}>Juara 2</option>
                            <option value="3" {{ old('peringkat', $prestasi->peringkat) == '3' ? 'selected' : '' }}>Juara 3</option>
                            <option value="4" {{ old('peringkat', $prestasi->peringkat) == '4' ? 'selected' : '' }}>Harapan 1</option>
                            <option value="5" {{ old('peringkat', $prestasi->peringkat) == '5' ? 'selected' : '' }}>Harapan 2</option>
                            <option value="9" {{ old('peringkat', $prestasi->peringkat) == '9' ? 'selected' : '' }}>Finalis / Peserta</option>
                        </select>
                    </div>

                    {{-- Penyelenggara --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Nama Penyelenggara <span class="text-danger">*</span></label>
                        <input type="text" name="penyelenggara" class="form-control" value="{{ old('penyelenggara', $prestasi->penyelenggara) }}" required>
                    </div>

                    {{-- Jumlah PT, Kepesertaan, Bentuk --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Jumlah PT / Negara Peserta <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah_pt_peserta" class="form-control" value="{{ old('jumlah_pt_peserta', $prestasi->jumlah_unit_peserta ?? 1) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Kepesertaan <span class="text-danger">*</span></label>
                        <select name="kepesertaan" id="select-kepesertaan" class="form-select" required>
                            <option value="0" {{ old('kepesertaan', optional($prestasi->aktivitas_mahasiswa)->jenis_anggota) == '0' ? 'selected' : '' }}>Personal</option>
                            <option value="1" {{ old('kepesertaan', optional($prestasi->aktivitas_mahasiswa)->jenis_anggota) == '1' ? 'selected' : '' }}>Kelompok</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Bentuk <span class="text-danger">*</span></label>
                        <select name="bentuk" class="form-select" required>
                            <option value="LURING" {{ old('bentuk', $prestasi->bentuk) == 'LURING' ? 'selected' : '' }}>Tatap Muka / Luring</option>
                            <option value="DARING" {{ old('bentuk', $prestasi->bentuk) == 'DARING' ? 'selected' : '' }}>Daring / Online</option>
                        </select>
                    </div>

                    {{-- URL Kompetisi --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">URL Kompetisi / Lomba</label>
                        <input type="url" name="url_kompetisi" class="form-control" value="{{ old('url_kompetisi', $prestasi->url_kompetisi) }}" placeholder="https://...">
                    </div>

                    {{-- Link Sertifikat & Tanggal --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Link Dokumen Sertifikat <span class="text-danger">*</span></label>
                        <input type="url" name="url_sertifikat" class="form-control" value="{{ old('url_sertifikat', $prestasi->file_prestasi) }}" required placeholder="https://...">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tanggal Sertifikat <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_sertifikat" class="form-control" value="{{ old('tanggal_sertifikat', optional($prestasi->aktivitas_mahasiswa)->tanggal_sk_tugas ? date('Y-m-d', strtotime($prestasi->aktivitas_mahasiswa->tanggal_sk_tugas)) : date('Y-m-d')) }}" required>
                    </div>

                    {{-- Link Foto UPP & Undangan --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Link Foto UPP</label>
                        <input type="url" name="url_foto_upp" class="form-control" value="{{ old('url_foto_upp', $prestasi->file_foto_upp) }}" placeholder="https://...">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Link Dokumen Undangan</label>
                        <input type="url" name="url_undangan" class="form-control" value="{{ old('url_undangan', $prestasi->file_undangan) }}" placeholder="https://...">
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-md-12 mb-4">
                        <label class="form-label fw-bold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', optional($prestasi->aktivitas_mahasiswa)->keterangan) }}</textarea>
                    </div>

                    <hr class="my-3">

                    {{-- SECTION DATA MAHASISWA / ANGGOTA KELOMPOK --}}
                    <div class="col-12 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="fa fa-users me-2 text-info"></i> Data Mahasiswa Peserta</h5>
                            <button id="add-mhs" type="button" class="btn btn-success btn-sm">
                                <i class="fa fa-plus me-1"></i> Tambah Mahasiswa
                            </button>
                        </div>
                        <div id="mhs-fields">
                            @if(isset($anggota_mhs) && count($anggota_mhs) > 0)
                                @foreach($anggota_mhs as $idx => $m)
                                    <div class="mhs-field row mb-2 align-items-center">
                                        <div class="col-md-11 mb-2">
                                            <label class="form-label label-mhs fw-semibold">Mahasiswa {{ $idx + 1 }} {{ $idx === 0 ? '(Ketua/Pelapor)' : '' }}</label>
                                            <select class="form-select select2-mhs" name="mahasiswa[]" required>
                                                <option value="{{ $m->id_registrasi_mahasiswa ?? $m->id_mahasiswa }}" selected>
                                                    {{ $m->nim }} - {{ $m->nama_mahasiswa }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-1 mb-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm remove-mhs form-control" title="Hapus Mahasiswa">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="mhs-field row mb-2 align-items-center">
                                    <div class="col-md-11 mb-2">
                                        <label class="form-label label-mhs fw-semibold">Mahasiswa 1 (Ketua/Pelapor)</label>
                                        <select class="form-select select2-mhs" name="mahasiswa[]" required>
                                            @if($prestasi->riwayat_pendidikan)
                                                <option value="{{ $prestasi->riwayat_pendidikan->id_registrasi_mahasiswa ?? $prestasi->id_mahasiswa }}" selected>
                                                    {{ $prestasi->riwayat_pendidikan->nim }} - {{ $prestasi->nama_mahasiswa }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-1 mb-2">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-rounded btn-sm remove-mhs form-control" style="display: none;" title="Hapus Mahasiswa">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr class="my-3">

                    {{-- SECTION DATA DOSEN PEMBIMBING --}}
                    <div class="col-12 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="fa fa-user-md me-2 text-info"></i> Data Dosen Pendamping & ST</h5>
                            <button id="add-dosen" type="button" class="btn btn-success btn-sm">
                                <i class="fa fa-plus me-1"></i> Tambah Dosen
                            </button>
                        </div>

                        {{-- Input ST Pembimbing --}}
                        <div class="row mb-3 bg-light p-3 rounded">
                            <div class="col-md-6 mb-2">
                                <label class="form-label fw-bold">Nomor ST Pembimbing</label>
                                <input type="text" name="no_sk_pembimbing" class="form-control" value="{{ old('no_sk_pembimbing', optional($prestasi->aktivitas_mahasiswa)->sk_tugas) }}" placeholder="Contoh: 0123/UN9/SK/2026">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label fw-bold">URL ST Pembimbing</label>
                                <input type="url" name="url_sk_pembimbing" class="form-control" value="{{ old('url_sk_pembimbing', $prestasi->sk_pembimbing) }}" placeholder="https://...">
                            </div>
                        </div>

                        <div id="dosen-fields">
                            @if(isset($dosen_bimbing) && count($dosen_bimbing) > 0)
                                @foreach($dosen_bimbing as $idx => $d)
                                    <div class="dosen-field row mb-2 align-items-center">
                                        <div class="col-md-11 mb-2">
                                            <label class="form-label label-dosen fw-semibold">Dosen Pendamping {{ $idx + 1 }}</label>
                                            <select class="form-select select2-dosen" name="dosen_bimbing[]">
                                                <option value="{{ $d->id_dosen }}" selected>
                                                    {{ $d->nidn ?? $d->nuptk ?? '-' }} - {{ $d->nama_dosen }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-1 mb-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm remove-dosen form-control" title="Hapus Dosen">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="dosen-field row mb-2 align-items-center">
                                    <div class="col-md-11 mb-2">
                                        <label class="form-label label-dosen fw-semibold">Dosen Pendamping 1</label>
                                        <select class="form-select select2-dosen" name="dosen_bimbing[]"></select>
                                    </div>
                                    <div class="col-md-1 mb-2">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-rounded btn-sm remove-dosen form-control" style="display: none;" title="Hapus Dosen">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            <div class="box-footer text-end">
                <a href="{{ route('ditmawa.data-prestasi.mandiri.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-success fw-bold px-4">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</section>
@endsection

@push('js')
<script src="{{ asset('assets/vendor_components/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/vendor_components/sweetalert/sweetalert.min.js') }}"></script>

<script>
$(document).ready(function() {
    var mhsCounter = {{ isset($anggota_mhs) ? count($anggota_mhs) : 1 }};
    var dosenCounter = {{ isset($dosen_bimbing) ? count($dosen_bimbing) : 1 }};

    // Helper Exclude Selected untuk Select2 Dinamis
    function getSelectedMahasiswa() {
        var selected = [];
        $('.select2-mhs').each(function() {
            var val = $(this).val();
            if (val) selected.push(val);
        });
        return selected;
    }

    function getSelectedDosen() {
        var selected = [];
        $('.select2-dosen').each(function() {
            var val = $(this).val();
            if (val) selected.push(val);
        });
        return selected;
    }

    // Inisialisasi Select2 Mahasiswa via AJAX Ditmawa
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

    // Inisialisasi Select2 Dosen via AJAX Ditmawa
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

    // Bind Inisialisasi Select2 Awal
    $('.select2-mhs').each(function() { initializeSelect2Mhs($(this)); });
    $('.select2-dosen').each(function() { initializeSelect2Dosen($(this)); });

    // Tambah Baris Mahasiswa
    $('#add-mhs').click(function() {
        mhsCounter++;
        var newRow = $(
            '<div class="mhs-field row mb-2 align-items-center">' +
                '<div class="col-md-11 mb-2">' +
                    '<label class="form-label label-mhs fw-semibold">Mahasiswa ' + mhsCounter + '</label>' +
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
    });

    // Tambah Baris Dosen
    $('#add-dosen').click(function() {
        dosenCounter++;
        var newRow = $(
            '<div class="dosen-field row mb-2 align-items-center">' +
                '<div class="col-md-11 mb-2">' +
                    '<label class="form-label label-dosen fw-semibold">Dosen Pendamping ' + dosenCounter + '</label>' +
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
    });

    // Event Hapus Baris
    $(document).on('click', '.remove-mhs', function() {
        if ($('.mhs-field').length > 1) {
            $(this).closest('.mhs-field').remove();
        } else {
            swal("Peringatan", "Minimal harus ada 1 mahasiswa peserta.", "warning");
        }
    });

    $(document).on('click', '.remove-dosen', function() {
        $(this).closest('.dosen-field').remove();
    });
});
</script>
@endpush