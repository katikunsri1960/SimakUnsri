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
            <form class="form" action="{{route('mahasiswa.prestasi-skpi.store')}}" id="tambah-prestasi" method="POST">
                @csrf
                
                {{-- Default Value untuk Bidang D (4) dan Sub Bidang Kompetisi (1) --}}
                <input type="hidden" name="bidang_id" value="4">
                <input type="hidden" name="sub_bidang_id" value="1">

                {{-- DATA MAHASISWA PELAPOR --}}
                <div class="box box-outline-success bs-3 border-success mb-4">
                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Data Mahasiswa Pelapor</h4>
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
                    </div>
                </div>

                {{-- DATA PRESTASI --}}
                <div class="box box-outline-success bs-3 border-success mb-4">
                    <div class="box-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-2 bg-primary text-white rounded me-2">
                                <i class="fa fa-trophy fs-5"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Data Prestasi & Kegiatan SKPI</h5>
                                <small class="text-muted">Informasi kompetisi, prestasi, dan tautan bukti utama.</small>
                            </div>
                        </div>
                        <hr class="my-15">

                        <div class="row g-3">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori_pendanaan" class="form-select @error('kategori_pendanaan') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="1" {{ old('kategori_pendanaan') == '1' ? 'selected' : '' }}>Pendanaan</option>
                                    <option value="2" {{ old('kategori_pendanaan') == '2' ? 'selected' : '' }}>Non Pendanaan</option>
                                </select>
                                @error('kategori_pendanaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Level / Tingkat <span class="text-danger">*</span></label>
                                <select name="id_tingkat_prestasi" class="form-select @error('id_tingkat_prestasi') is-invalid @enderror" required>
                                    <option value="">-- Pilih Level --</option>
                                    @foreach($tingkat_prestasi as $t)
                                        <option value="{{ $t->id_tingkat_prestasi }}" {{ old('id_tingkat_prestasi') == $t->id_tingkat_prestasi ? 'selected' : '' }}>
                                            {{ $t->nama_tingkat_prestasi }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_tingkat_prestasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori Prestasi / Jenis SKPI <span class="text-danger">*</span></label>
                                <select name="kategori_prestasi" class="form-select @error('kategori_prestasi') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori Prestasi --</option>
                                    <option value="1" {{ old('kategori_prestasi') == '1' ? 'selected' : '' }}>Riset dan Inovasi : STEM</option>
                                    <option value="5" {{ old('kategori_prestasi') == '5' ? 'selected' : '' }}>Riset dan Inovasi : SSH</option>
                                    <option value="2" {{ old('kategori_prestasi') == '2' ? 'selected' : '' }}>Seni dan Budaya</option>
                                    <option value="3" {{ old('kategori_prestasi') == '3' ? 'selected' : '' }}>Olahraga</option>
                                    <option value="4" {{ old('kategori_prestasi') == '4' ? 'selected' : '' }}>Minat Khusus</option>
                                </select>
                                @error('kategori_prestasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Nama Kompetisi / Lomba <span class="text-danger">*</span></label>
                                <input type="text" name="nama_kompetisi" class="form-control @error('nama_kompetisi') is-invalid @enderror" value="{{ old('nama_kompetisi') }}" placeholder="Nama Kompetisi / Lomba" required>
                                @error('nama_kompetisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Cabang / Bidang <span class="text-danger">*</span></label>
                                <input type="text" name="nama_cabang" class="form-control @error('nama_cabang') is-invalid @enderror" value="{{ old('nama_cabang') }}" placeholder="Contoh: Debat / Karya Tulis / Futsal" required>
                                @error('nama_cabang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Peringkat / Capaian <span class="text-danger">*</span></label>
                                <select name="peringkat" class="form-select @error('peringkat') is-invalid @enderror" required>
                                    <option value="">-- Pilih Peringkat --</option>
                                    <option value="1" {{ old('peringkat') == '1' ? 'selected' : '' }}>Juara 1</option>
                                    <option value="2" {{ old('peringkat') == '2' ? 'selected' : '' }}>Juara 2</option>
                                    <option value="3" {{ old('peringkat') == '3' ? 'selected' : '' }}>Juara 3</option>
                                    <option value="4" {{ old('peringkat') == '4' ? 'selected' : '' }}>Harapan 1</option>
                                    <option value="5" {{ old('peringkat') == '5' ? 'selected' : '' }}>Harapan 2</option>
                                    <option value="6" {{ old('peringkat') == '6' ? 'selected' : '' }}>Harapan 3</option>
                                    <option value="9" {{ old('peringkat') == '9' ? 'selected' : '' }}>Finalis / Peserta</option>
                                </select>
                                @error('peringkat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Nama Penyelenggara <span class="text-danger">*</span></label>
                                <input type="text" name="penyelenggara" class="form-control @error('penyelenggara') is-invalid @enderror" value="{{ old('penyelenggara') }}" placeholder="Instansi / Organisasi Penyelenggara" required>
                                @error('penyelenggara')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jumlah PT Peserta <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah_pt_peserta" class="form-control" value="{{ old('jumlah_pt_peserta', 1) }}" placeholder="Jumlah PT" required>
                            </div>

                            {{-- SELECT KEPESERTAAN DENGAN TRIGGER EVENT --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kepesertaan <span class="text-danger">*</span></label>
                                <select name="kepesertaan" id="select-kepesertaan" class="form-select" required>
                                    <option value="">-- Pilih Kepesertaan --</option>
                                    <option value="0" {{ old('kepesertaan') == '0' ? 'selected' : '' }}>Individu</option>
                                    <option value="1" {{ old('kepesertaan') == '1' ? 'selected' : '' }}>Kelompok</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Bentuk Pelaksanaan <span class="text-danger">*</span></label>
                                <select name="bentuk" class="form-select" required>
                                    <option value="">-- Pilih Bentuk --</option>
                                    <option value="LURING" {{ old('bentuk') == 'LURING' ? 'selected' : '' }}>Tatap Muka / Luring</option>
                                    <option value="DARING" {{ old('bentuk') == 'DARING' ? 'selected' : '' }}>Daring / Online</option>
                                </select>
                            </div>

                            {{-- TAUTAN URL BUKTI --}}
                            <div class="row g-3">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">URL Website / Link Informasi Lomba</label>
                                    <input type="url" name="url_kompetisi" class="form-control" value="{{ old('url_kompetisi') }}" placeholder="https://...">
                                </div>

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
                                    <input type="date" 
                                           name="tanggal_sertifikat" 
                                           class="form-control @error('tanggal_sertifikat') is-invalid @enderror" 
                                           value="{{ old('tanggal_sertifikat', date('Y-m-d')) }}" 
                                           min="{{ date('Y-01-01') }}" 
                                           max="{{ date('Y-12-31') }}" 
                                           required>
                                    <small class="text-danger d-block mt-1">Hanya dapat memilih tanggal pada tahun berjalan ({{ date('Y') }}).</small>
                                    @error('tanggal_sertifikat')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">URL Dokumentasi / Foto UPP</label>
                                    <input type="url" name="url_foto_upp" class="form-control @error('url_foto_upp') is-invalid @enderror" value="{{ old('url_foto_upp') }}" placeholder="https://...">
                                    <small class="text-muted d-block mt-1">Opsional, tautan berkas foto / UPP.</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">URL Dokumen Surat Undangan</label>
                                    <input type="url" name="url_undangan" class="form-control @error('url_undangan') is-invalid @enderror" value="{{ old('url_undangan') }}" placeholder="https://...">
                                    <small class="text-muted d-block mt-1">Opsional, tautan berkas surat undangan.</small>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Keterangan Tambahan</label>
                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION DATA ANGGOTA KELOMPOK (DITAMPILKAN HANYA JIKA KEPESERTAAN = KELOMPOK) --}}
                <div class="box box-outline-success bs-3 border-success mb-4" id="section-anggota-mhs" style="display: none;">
                    <div class="box-header with-border">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h4 class="text-info mb-0"><i class="fa fa-users"></i> Anggota Kelompok Mahasiswa</h4>
                            </div>
                            <div class="col-6 text-end">
                                <button id="add-mhs" type="button" class="btn btn-success btn-sm" title="Tambah Anggota">
                                    <i class="fa fa-plus me-1"></i> Tambah Anggota
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <p class="text-danger mb-3"><i class="fa fa-info-circle me-1"></i> Pelapor otomatis menjadi Ketua Kelompok. Silakan tambahkan anggota tim Anda di bawah ini:</p>
                        <div id="mhs-fields">
                            {{-- Field anggota dinamis disisipkan via JavaScript --}}
                        </div>
                    </div>
                </div>

                {{-- DATA DOSEN PENDAMPING & SK --}}
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
                                <label class="form-label fw-bold">Nomor SK Pembimbing</label>
                                <input type="text" name="no_sk_pembimbing" class="form-control" value="{{ old('no_sk_pembimbing') }}" placeholder="Contoh: 0123/UN9/SK/2026">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tanggal SK Pembimbing</label>
                                <input type="date" name="tanggal_sk_pembimbing" class="form-control" value="{{ old('tanggal_sk_pembimbing') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">URL SK Pembimbing</label>
                                <input type="url" name="url_sk_pembimbing" class="form-control @error('url_sk_pembimbing') is-invalid @enderror" value="{{ old('url_sk_pembimbing') }}" placeholder="https://...">
                                <small class="text-muted d-block mt-1">Tautan berkas SK Pembimbing.</small>
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

                <div class="box-footer text-end">
                    <a href="{{route('mahasiswa.prestasi-skpi.index')}}" class="btn btn-secondary me-2">Batal</a>
                    <button type="button" class="btn btn-primary" id="btn-submit-confirm">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('js')
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.full.min.js')}}"></script>

<script>
$(document).ready(function(){
    var mhsCounter = 0;
    var dosenCounter = 1;

    // Helper ID Mahasiswa & Dosen Terpilih untuk Filter Select2
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

    function initializeSelect2Mhs(element) {
        return element.select2({
            placeholder: '-- Ketik NIM / Nama Mahasiswa --',
            minimumInputLength: 3,
            ajax: {
                url: "{{ route('mahasiswa.prestasi-skpi.get-mahasiswa') }}",
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

    function initializeSelect2Dosen(element) {
        return element.select2({
            placeholder: '-- Ketik NIDN / Nama Dosen --',
            minimumInputLength: 3,
            ajax: {
                url: "{{ route('mahasiswa.prestasi-skpi.get-dosen') }}",
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

    $('.select2-dosen').each(function() { initializeSelect2Dosen($(this)); });

    // ==========================================
    // LOGIKA KEPESERTAAN (INDIVIDU vs KELOMPOK)
    // ==========================================
    function checkKepesertaan() {
        var val = $('#select-kepesertaan').val();

        if (val === '1') { // KELOMPOK
            $('#section-anggota-mhs').slideDown();
            // Jika belum ada row anggota sama sekali, tambahkan 1 row default
            if ($('#mhs-fields .mhs-field').length === 0) {
                addMhsRow();
            }
        } else { // INDIVIDU / BELUM DIPILIH
            $('#section-anggota-mhs').slideUp();
            $('#mhs-fields').empty();
            mhsCounter = 0;
        }
    }

    $('#select-kepesertaan').change(function() {
        checkKepesertaan();
    });

    // Jalankan Pengecekan Awal
    checkKepesertaan();

    // Fungsi Tambah Baris Anggota Mahasiswa
    function addMhsRow() {
        mhsCounter++;
        var newRow = $(
            '<div class="mhs-field row mb-2 align-items-center">' +
                '<div class="col-md-11 mb-2">' +
                    '<label class="form-label label-mhs">Nama Anggota Mahasiswa ' + mhsCounter + '</label>' +
                    '<select class="form-select select2-mhs" name="mahasiswa[]" required></select>' +
                '</div>' +
                '<div class="col-md-1 mb-2">' +
                    '<label class="form-label">&nbsp;</label>' +
                    '<button type="button" class="btn btn-danger btn-rounded btn-sm remove-mhs form-control" title="Hapus Anggota">' +
                        '<i class="fa fa-trash" aria-hidden="true"></i>' +
                    '</button>' +
                '</div>' +
            '</div>'
        );
        $('#mhs-fields').append(newRow);
        initializeSelect2Mhs(newRow.find('.select2-mhs'));
    }

    $('#add-mhs').click(function() {
        addMhsRow();
    });

    // Event Tambah & Hapus Dosen
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
    });

    $(document).on('click', '.remove-dosen', function() {
        $(this).closest('.dosen-field').remove();
        updateRemoveButtonDosen();
    });

    function updateRemoveButtonDosen() {
        if ($('.dosen-field').length <= 1) {
            $('.remove-dosen').hide();
        } else {
            $('.remove-dosen').show();
        }
    }

    $('#btn-submit-confirm').click(function() {
        var form = document.getElementById('tambah-prestasi');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

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
                form.submit();
            }
        });
    });
});
</script>
@endpush