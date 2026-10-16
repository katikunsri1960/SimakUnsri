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
            <form action="{{ route('ditmawa.data-prestasi.mandiri.store') }}" method="POST" id="form-prestasi" enctype="multipart/form-data">
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
                                <small class="text-muted">Informasi kompetisi, prestasi, dan dokumen bukti utama.</small>
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
                                @error('kategori_prestasi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
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
                                    <option value="Luring" {{ old('bentuk') == 'Luring' ? 'selected' : '' }}>Tatap Muka / Luring</option>
                                    <option value="Daring" {{ old('bentuk') == 'Daring' ? 'selected' : '' }}>Daring / Online</option>
                                </select>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">URL Website / Link Informasi Lomba</label>
                                    <input type="url" name="url_kompetisi" class="form-control" value="{{ old('url_kompetisi') }}" placeholder="https://...">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Upload Dokumen Sertifikat (PDF Max 500 KB) <span class="text-danger">*</span></label>
                                    <input type="file" name="file_sertifikat" class="form-control @error('file_sertifikat') is-invalid @enderror" accept="application/pdf" required>
                                    <small class="text-muted d-block mt-1">Format file harus PDF dan maksimal 500 KB.</small>
                                    @error('file_sertifikat')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Sertifikat <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_sertifikat" class="form-control" value="{{ old('tanggal_sertifikat') }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Upload Dokumentasi / Foto UPP (PDF Max 500 KB)</label>
                                    <input type="file" name="file_foto_upp" class="form-control @error('file_foto_upp') is-invalid @enderror" accept="application/pdf">
                                    <small class="text-muted d-block mt-1">Opsional, format PDF maksimal 500 KB.</small>
                                    @error('file_foto_upp')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Upload Dokumen Surat Undangan (PDF Max 500 KB)</label>
                                    <input type="file" name="file_undangan" class="form-control @error('file_undangan') is-invalid @enderror" accept="application/pdf">
                                    <small class="text-muted d-block mt-1">Opsional, format PDF maksimal 500 KB.</small>
                                    @error('file_undangan')
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
                        <!-- Single Input SK Pembimbing untuk Seluruh Dosen -->
                        <div class="row mb-3 pb-3 border-bottom g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nomor SK Pembimbing</label>
                                <input type="text" name="no_sk_pembimbing" class="form-control" value="{{ old('no_sk_pembimbing') }}" placeholder="Contoh: 0123/UN9/SK/2026">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tanggal SK Pembimbing</label>
                                <input type="date" name="tanggal_sk_pembimbing" class="form-control" value="{{ old('tanggal_sk_pembimbing') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Upload SK Pembimbing (PDF Max 500 KB)</label>
                                <input type="file" name="file_sk_pembimbing" class="form-control @error('file_sk_pembimbing') is-invalid @enderror" accept="application/pdf">
                                <small class="text-muted d-block mt-1">Satu SK ini berlaku untuk seluruh dosen pembimbing.</small>
                                @error('file_sk_pembimbing')
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
                    <button type="button" class="btn btn-primary px-4" id="btn-preview-modal">
                        <i class="fa fa-save me-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- MODAL PREVIEW SEBELUM SIMPAN --}}
<div class="modal fade" id="modal-preview" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fa fa-check-circle me-2"></i> Konfirmasi Data Prestasi & Pembimbing</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle me-1"></i> Harap periksa kembali ringkasan data sebelum disimpan ke dalam sistem.
                </div>

                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th width="35%">Nama Kompetisi</th>
                            <td id="pv-kompetisi">-</td>
                        </tr>
                        <tr>
                            <th>Cabang / Bidang</th>
                            <td id="pv-cabang">-</td>
                        </tr>
                        <tr>
                            <th>Peringkat & Tingkat</th>
                            <td id="pv-peringkat-tingkat">-</td>
                        </tr>
                        <tr>
                            <th>Kategori Kegiatan Dosen</th>
                            <td id="pv-kategori-dosen" class="fw-bold text-primary">-</td>
                        </tr>
                        <tr>
                            <th>Mahasiswa</th>
                            <td id="pv-mahasiswa">-</td>
                        </tr>
                        <tr>
                            <th>Dosen Pembimbing</th>
                            <td id="pv-dosen">-</td>
                        </tr>
                        <tr>
                            <th>File SK Pembimbing</th>
                            <td id="pv-sk-file">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-edit me-1"></i> Edit Kembali
                </button>
                <button type="button" class="btn btn-success" id="btn-confirm-submit">
                    <i class="fa fa-check me-1"></i> Ya, Simpan Data
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/vendor_components/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/vendor_components/select2/dist/js/select2.full.min.js') }}"></script>

<script>
$(document).ready(function() {
    var mhsCounter = 1;
    var dosenCounter = 1;

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
                    return { q: params.term };
                },
                processResults: function (data) {
                    return {
                        results: data.results || []
                    };
                },
                cache: true
            }
        });
    }

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
                    return { q: params.term };
                },
                processResults: function (data) {
                    return {
                        results: data.results || []
                    };
                },
                cache: true
            }
        });
    }

    $('.select2-mhs').each(function() { initializeSelect2Mhs($(this)); });
    $('.select2-dosen').each(function() { initializeSelect2Dosen($(this)); });

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

    $(document).on('click', '.remove-mhs', function() {
        $(this).closest('.mhs-field').remove();
        updateRemoveButtonMhs();
    });

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
    // LOGIKA MODAL PREVIEW & DETERMINASI KEGIATAN
    // ==========================================
    $('#btn-preview-modal').click(function() {
        var form = document.getElementById('form-prestasi');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        var idTingkat = $('select[name="id_tingkat_prestasi"]').val();
        var namaTingkat = $('select[name="id_tingkat_prestasi"] option:selected').text().trim();
        var peringkat = $('select[name="peringkat"]').val();
        var namaPeringkat = $('select[name="peringkat"] option:selected').text().trim();

        // Determinasi Kategori Kegiatan
        var isJuara = ['1', '2', '3', 'JUARA1', 'JUARA2', 'JUARA3'].includes(peringkat);
        var namaKategori = '';

        if (idTingkat === '6') {
            namaKategori = isJuara 
                ? 'Membimbing mahasiswa mengikuti kompetisi dibidang akademik dan kemahasiswaan bereputasi dan mencapai juara tingkat Internasional'
                : 'Membimbing mahasiswa menghasilkan produk saintifik bereputasi dan mendapat pengakuan tingkat Internasional';
        } else if (idTingkat === '5') {
            namaKategori = isJuara
                ? 'Membimbing mahasiswa mengikuti kompetisi dibidang akademik dan kemahasiswaan bereputasi dan mencapai juara tingkat Nasional'
                : 'Membimbing mahasiswa menghasilkan produk saintifik bereputasi dan mendapat pengakuan tingkat Nasional';
        } else {
            namaKategori = 'Membina kegiatan mahasiswa di bidang akademik dan kemahasiswaan, termasuk dalam kegiatan ini adalah membimbing mahasiswa menghasilkan produk saintifik, membimbing mahasiswa mengikuti kompetisi di bidang akademik dan kemahasiswaan';
        }

        // Susun daftar Mahasiswa
        var listMhs = [];
        $('.select2-mhs').each(function(index) {
            var text = $(this).find('option:selected').text();
            if (text) listMhs.push((index + 1) + '. ' + text);
        });

        // Susun daftar Dosen
        var listDosen = [];
        $('.select2-dosen').each(function(index) {
            var text = $(this).find('option:selected').text();
            if (text) listDosen.push((index + 1) + '. ' + text);
        });

        // Nama File SK Pembimbing
        var fileSkInput = $('input[name="file_sk_pembimbing"]')[0];
        var namaFileSk = (fileSkInput && fileSkInput.files.length > 0) ? fileSkInput.files[0].name : 'Tidak Ada SK';

        // Render data ke modal
        $('#pv-kompetisi').text($('input[name="nama_kompetisi"]').val());
        $('#pv-cabang').text($('input[name="nama_cabang"]').val());
        $('#pv-peringkat-tingkat').text(namaPeringkat + ' (' + namaTingkat + ')');
        $('#pv-kategori-dosen').text(namaKategori);
        $('#pv-mahasiswa').html(listMhs.length > 0 ? listMhs.join('<br>') : '-');
        $('#pv-dosen').html(listDosen.length > 0 ? listDosen.join('<br>') : 'Tanpa Dosen Pembimbing');
        $('#pv-sk-file').text(namaFileSk);

        // Tampilkan modal
        $('#modal-preview').modal('show');
    });

    $('#btn-confirm-submit').click(function() {
        $('#form-prestasi').submit();
    });
});
</script>
@endpush