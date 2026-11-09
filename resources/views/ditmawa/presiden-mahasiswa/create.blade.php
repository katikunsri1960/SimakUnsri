@extends('layouts.ditmawa')

@section('title')
Tambah Kandidat Capresma
@endsection

@section('content')
@include('swal')

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <strong>Gagal Menyimpan Data!</strong>
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
                            <h2>Tambah Data Kandidat Capresma & Cawapresma</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Data Pasangan Calon</h4>
                    <a href="{{ route('ditmawa.presiden-mahasiswa.index') }}" class="btn btn-warning waves-effect waves-light">
                        <i class="fa fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>

                <form action="{{ route('ditmawa.presiden-mahasiswa.store') }}" method="POST" enctype="multipart/form-data" class="form-create">
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            {{-- Nomor Urut --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">No. Urut Pasangan Calon <span class="text-danger">*</span></label>
                                <input type="number" name="no_urut" class="form-control" placeholder="Contoh: 1" value="{{ old('no_urut') }}" required>
                            </div>

                            {{-- Capresma --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cari & Pilih Capresma <span class="text-danger">*</span></label>
                                <select name="id_registrasi_mahasiswa_capresma" id="select_capresma" class="form-control select2-mhs" required>
                                    <option value="">-- Ketik NIM / Nama Mahasiswa --</option>
                                </select>
                            </div>

                            {{-- Cawapresma --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cari & Pilih Cawapresma <span class="text-danger">*</span></label>
                                <select name="id_registrasi_mahasiswa_cawapresma" id="select_cawapresma" class="form-control select2-mhs" required>
                                    <option value="">-- Ketik NIM / Nama Mahasiswa --</option>
                                </select>
                            </div>

                            {{-- Visi & Misi --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Visi <span class="text-danger">*</span></label>
                                <textarea name="visi" class="form-control" rows="3" placeholder="Tuliskan Visi Paslon" required>{{ old('visi') }}</textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Misi <span class="text-danger">*</span></label>
                                <textarea name="misi" class="form-control" rows="4" placeholder="Tuliskan Misi Paslon" required>{{ old('misi') }}</textarea>
                            </div>

                            {{-- Link Video Kampanye --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Link Video Kampanye (YouTube / Drive)</label>
                                <input type="url" name="video" class="form-control" placeholder="Contoh: https://www.youtube.com/watch?v=..." value="{{ old('video') }}">
                                <small class="text-muted d-block mt-1">Opsional: Masukkan tautan/URL video perkenalan atau kampanye paslon</small>
                            </div>

                            {{-- File Upload --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Foto Paslon <span class="text-danger">*</span></label>
                                <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                                <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG (Maksimal 2MB)</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Berkas Pendukung / Lampiran <span class="text-danger">*</span></label>
                                <input type="file" name="berkas_dukung" class="form-control" accept=".pdf,.zip,.rar" required>
                                <small class="text-muted d-block mt-1">Format: PDF (Maksimal 5MB)</small>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer text-end">
                        <a href="{{ route('ditmawa.presiden-mahasiswa.index') }}" class="btn btn-secondary mr-1">Batal</a>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save mr-1"></i> Simpan Data
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

    $('.form-create').on('submit', function(e){
        var form = this;

        if (!form.checkValidity()) {
            return;
        }

        e.preventDefault();

        swal({
            title: "Simpan Data?",
            text: "Data kandidat baru akan ditambahkan",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#04a08b",
            confirmButtonText: "Ya, Simpan!",
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