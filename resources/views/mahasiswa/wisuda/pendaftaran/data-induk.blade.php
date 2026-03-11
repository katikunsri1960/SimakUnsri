@extends('layouts.mahasiswa')
@section('title')
Pendaftaran Yudisium Mahasiswa
@endsection
@section('content')
{{-- @push('header')
<div class="mx-4">
    <a href="{{route('mahasiswa.wisuda.index')}}"
        class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush --}}
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Data Induk Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Yudisium</li>
                        <li class="breadcrumb-item active" aria-current="page">Pendaftaran</li>
                        <li class="breadcrumb-item active" aria-current="page">Data Induk Mahasiswa</li>
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
                <form class="form" action="{{route('mahasiswa.wisuda.pendaftaran.data-induk-store')}}" id="data-induk" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">
                        @php
                            $disabled = ($wisuda && $wisuda->verified_induk == 1) ? 'disabled' : '';
                        @endphp
                        {{-- MAHASISWA --}}
                        <h4 class="text-primary mb-0"><i class="fa fa-user"></i> Data Diri Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="data-wisuda-field row">
                            <div class=" col-lg-6 mb-3">
                                <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
                                <input type="text" class="form-control" name="nama_mahasiswa" id="nama_mahasiswa" aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->nama_mahasiswa}}" disabled required/>
                            </div>
                            <div class=" col-lg-6 mb-3">
                                <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                                <input type="text"class="form-control"name="nik"id="nik"aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->nik}}" {{$disabled}} required/>
                            </div>
                        </div>
                        
                        <h4 class="text-primary mb-10 mt-10">Alamat Mahasiswa</h4>
                        <div class="data-wisuda-field row" style="margin-left: 10px;">
                            <div class=" col-lg-12 mb-3">
                                <label for="jalan" class="form-label">Jalan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="jalan" id="jalan" aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->jalan}}" {{$disabled}} required/>
                            </div>
                            <div class=" col-lg-4 mb-3">
                                <label for="dusun" class="form-label">Dusun</label>
                                <input type="text"class="form-control"name="dusun"id="dusun"aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->dusun}}" {{$disabled}}/>
                            </div>
                            <div class=" col-lg-2 mb-3">
                                <label for="rt" class="form-label">RT <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="rt" id="rt" aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->rt}}" {{$disabled}} required/>
                            </div>
                            <div class=" col-lg-2 mb-3">
                                <label for="rw" class="form-label">RW <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="rw" id="rw" aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->rw}}" {{$disabled}} required/>
                            </div>
                            <div class=" col-lg-4 mb-3">
                                <label for="kelurahan" class="form-label">Kelurahan <span class="text-danger">*</span></label>
                                <input type="text"class="form-control"name="kelurahan"id="kelurahan"aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->kelurahan}}" {{$disabled}} required/>
                            </div>
                            <div class=" col-lg-4 mb-3">
                                <label for="kode_pos" class="form-label">Kode Pos <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="kode_pos" id="kode_pos" aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->kode_pos}}" {{$disabled}}/>
                            </div>
                            <div class="col-lg-8 mb-3">
                                <label for="id_wilayah" class="form-label">
                                    Wilayah <span class="text-danger">*</span>
                                </label>
                                <select id="id_wilayah" name="id_wilayah" style="width:100%" {{$disabled}}></select>
                            </div>

                            <div class=" col-lg-6 mb-3">
                                <label for="handphone" class="form-label">No. Telp/HP <span class="text-danger">*</span></label>
                                <input type="text"class="form-control"name="handphone"id="handphone"aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->handphone}}" {{$disabled}} required/>
                            </div>
                            <div class=" col-lg-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="text"class="form-control"name="email"id="email"aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->email}}" {{$disabled}} required/>
                            </div>
                        </div>
                        
                        {{-- ORANG TUA --}}
                        <h4 class="text-primary mb-0" style="padding-top: 40px;">
                            <i class="fa-solid fa-users"></i> Data Diri Orang Tua
                        </h4>
                        <hr class="my-15">
                        <div class="data-wisuda-field row">
                            <div class=" col-lg-6 mb-3">
                                <label for="nama_ayah" class="form-label">
                                    Nama Ayah <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="nama_ayah" id="nama_ayah" aria-describedby="helpId"
                                    value="{{ strtoupper($riwayat_pendidikan->biodata->nama_ayah) }}" {{$disabled}} required/>
                            </div>

                            <div class=" col-lg-6 mb-3">
                                <label for="no_hp_ayah" class="form-label">
                                    Nomor Handphone Ayah <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="no_hp_ayah" id="no_hp_ayah" aria-describedby="helpId"
                                    value="{{ $riwayat_pendidikan->biodata->no_hp_ayah }}" {{$disabled}} required/>
                            </div>

                            <div class=" col-lg-6 mb-3">
                                <label for="nama_ibu" class="form-label">
                                    Nama Ibu Kandung <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="nama_ibu" id="nama_ibu" aria-describedby="helpId"
                                    value="{{ $riwayat_pendidikan->biodata->nama_ibu_kandung }}" disabled required/>
                            </div>

                            <div class=" col-lg-6 mb-3">
                                <label for="no_hp_ibu" class="form-label">
                                    Nomor Handphone Ibu <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="no_hp_ibu" id="no_hp_ibu" aria-describedby="helpId"
                                    value="{{ $riwayat_pendidikan->biodata->no_hp_ibu }}" {{$disabled}} required/>
                            </div>

                            <div class=" col-lg-12 mb-3">
                                <label for="alamat_orang_tua" class="form-label">
                                    ALAMAT ORANG TUA <span class="text-danger">*</span>
                                </label>
                                <textarea placeholder="MASUKKAN ALAMAT ORANG TUA" class="form-control" name="alamat_orang_tua" id="alamat_orang_tua" {{$disabled}} aria-describedby="helpId" required>{{ strtoupper($riwayat_pendidikan->biodata->alamat_orang_tua ?? '') }}</textarea>
                            </div>
                        </div>


                        <h4 class="text-primary mb-0" style="padding-top: 40px;"><i class="fa-solid fa-school"></i> Data Asal Sekolah Mahasiswa</h4>
                        <hr class="my-15">
                        
                        <div class="form-group">
                            @if ($asal_sekolah)
                                <div id="data-sekolah-fields">
                                    <h4 class="text-primary mb-0">Sekolah Dasar</h4>
                                    <div class="data-sekolah-field row" style="margin-left: 10px;">
                                        <div class=" col-lg-4 mb-3">
                                            <label for="nama_sd" class="form-label">Nama Sekolah</label>
                                            <input type="text" class="form-control" name="nama_sd" id="nama_sd" aria-describedby="helpId"
                                                value="{{$asal_sekolah->rm_pddk_sd_nama ?? '-'}}" disabled required/>
                                        </div>
                                        <div class=" col-lg-6 mb-3">
                                            <label for="lokasi_sd" class="form-label">Alamat Sekolah</label>
                                            <input type="text"class="form-control" name="lokasi_sd" id="lokasi_sd" aria-describedby="helpId"
                                                value="{{$asal_sekolah->rm_pddk_sd_lokasi ?? '-'}}" disabled required/>
                                        </div>
                                        <div class="col-lg-2 mb-3">
                                            <label for="lulus_sd" class="form-label">Tahun Lulus</label>
                                            <input type="text"class="form-control" name="lulus_sd" id="lulus_sd" aria-describedby="helpId"
                                                value="{{$asal_sekolah->rm_pddk_sd_thn_lulus ?? '-'}}" disabled required/>
                                        </div>
                                    </div>
                                </div>
                                <div id="data-sekolah-fields">
                                    <h4 class="text-primary mb-0">Sekolah Menengah Pertama</h4>
                                    <div class="data-sekolah-field row" style="margin-left: 10px;">
                                        <div class=" col-lg-4 mb-3">
                                            <label for="nama_smp" class="form-label">Nama Sekolah</label>
                                            <input type="text" class="form-control" name="nama_smp" id="nama_smp" aria-describedby="helpId"
                                                value="{{$asal_sekolah->rm_pddk_sltp_nama ?? '-'}}" disabled required/>
                                        </div>
                                        <div class=" col-lg-6 mb-3">
                                            <label for="lokasi_smp" class="form-label">Alamat Sekolah</label>
                                            <input type="text"class="form-control" name="lokasi_smp" id="lokasi_smp" aria-describedby="helpId"
                                                value="{{$asal_sekolah->rm_pddk_sltp_lokasi ?? '-'}}" disabled required/>
                                        </div>
                                        <div class="col-lg-2 mb-3">
                                            <label for="lulus_smp" class="form-label">Tahun Lulus</label>
                                            <input type="text"class="form-control" name="lulus_smp" id="lulus_smp" aria-describedby="helpId"
                                                value="{{$asal_sekolah->rm_pddk_sltp_thn_lulus ?? '-'}}" disabled required/>
                                        </div>
                                    </div>
                                </div>
                                <div id="data-sekolah-fields">
                                    <h4 class="text-primary mb-0">Sekolah Menengah Atas</h4>
                                    <div class="data-sekolah-field row" style="margin-left: 10px;">
                                        <div class=" col-lg-4 mb-3">
                                            <label for="nama_slta" class="form-label">Nama Sekolah</label>
                                            <input type="text" class="form-control" name="nama_slta" id="nama_slta" aria-describedby="helpId"
                                                value="{{$asal_sekolah->nama_sekolah ?? '-' }}" disabled required/>
                                        </div>
                                        <div class=" col-lg-6 mb-3">
                                            <label for="lokasi_slta" class="form-label">Alamat Sekolah</label>
                                            <input type="text"class="form-control" name="lokasi_slta" id="lokasi_slta" aria-describedby="helpId"
                                                value="{{strtoupper($asal_sekolah->nama_kabupaten ?? '-') }}, {{strtoupper($asal_sekolah->nama_provinsi ?? '-')}}" disabled required/>
                                        </div>
                                        <div class="col-lg-2 mb-3">
                                            <label for="lulus_slta" class="form-label">Tahun Lulus</label>
                                            <input type="text"class="form-control" name="lulus_slta" id="lulus_slta" aria-describedby="helpId"
                                                value="{{$asal_sekolah->rm_pddk_slta_thn_lulus ?? '-' }}" disabled required/>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning" role="alert">
                                    Akses data asal sekolah tidak tersedia!
                                </div>
                            @endif

                            @if($wisuda && $wisuda->verified_induk == 1)
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">File Ijazah Terakhir</label>

                                <div class="border rounded p-2">
                                    <iframe 
                                        src="{{ asset($wisuda->ijazah_terakhir_file) }}" 
                                        width="100%" 
                                        height="500px"
                                        style="border:none;">
                                    </iframe>
                                </div>

                                <div class="mt-2">
                                    <a href="{{ asset($wisuda->ijazah_terakhir_file) }}" target="_blank" class="btn btn-success btn-sm">
                                        <i class="fa fa-download"></i> Download File
                                    </a>
                                </div>
                            </div>
                            @else
                            <div id="data-sekolah-fields">
                                <h4 class="text-primary mb-0">File Ijazah Terakhir</h4>
                                <div class="data-sekolah-field row" style="margin-left: 10px;">
                                    <div class="col-lg-6 mb-3">
                                        <label for="ijazah_terakhir_file" class="form-label">Upload Ijazah Terakhir (.pdf)</label><span class="text-danger"> *</span>
                                        <input type="file"
                                            class="form-control"
                                            name="ijazah_terakhir_file"
                                            id="ijazah_terakhir_file"
                                            aria-describedby="fileHelpId"
                                            accept=".pdf"
                                            required
                                        />
                                        <small id="fileHelpId" class="form-text text-danger">
                                            Maksimal ukuran file <strong>500 KB</strong>.
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="box-footer">
                        @if($wisuda && $wisuda->verified_induk == 1)
                            <div class="checkbox p-3 border border-success rounded bg-light-success">
                                <input type="checkbox" id="pernyataan_data" name="pernyataan_data" checked disabled>
                                <label for="pernyataan_data" class="text-success fw-bold">
                                    Data Induk telah diverifikasi. Perubahan data tidak diperbolehkan.
                                </label>
                            </div>
                            <div class="form-group mt-20">
                                <a type="button" href="{{route('mahasiswa.wisuda.index')}}" class="btn btn-danger waves-effect waves-light">
                                    Kembali
                                </a>
                                <a type="button" href="{{route('mahasiswa.wisuda.pendaftaran.data-akademik')}}" class="btn btn-primary waves-effect waves-light">
                                    Lanjutkan
                                </a>
                            </div>
                        @else
                            <div class="checkbox">
                                <input type="checkbox" id="pernyataan_data" name="pernyataan_data">
                                <label for="pernyataan_data">
                                    Dengan ini saya menyatakan bahwa Data Induk saya telah sesuai dengan ijazah terakhir yang saya miliki, dan saya bersedia menggunakan data tersebut untuk keperluan Administrasi Yudisium, Wisuda, Pencetakan Ijazah, dan Transkrip.
                                </label>
                            </div>
                            <div class="form-group mt-20">
                                <a type="button" href="{{route('mahasiswa.wisuda.index')}}" class="btn btn-danger waves-effect waves-light">
                                    Batal
                                </a>
                                <button type="submit" id="submit-button" class="btn btn-primary waves-effect waves-light">Simpan</button>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>
$(document).ready(function () {

    let placeholderText = 'Pilih Kecamatan';

    $('#id_wilayah').select2({
        placeholder: placeholderText,
        width: '100%',
        minimumInputLength: 3,
        ajax: {
            url: "{{ route('mahasiswa.wisuda.get-kecamatan') }}",
            type: "GET",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            id: item.id_wilayah,
                            text: item.nama_wilayah + ', ' + item.kab_kota.nama_wilayah
                        };
                    })
                };
            }
        }
    });

    // VALIDASI SUBMIT
    $('#data-induk').on('submit', function (e) {
        e.preventDefault();

        let wilayah = $('#id_wilayah').val();
        let pernyataan = $('#pernyataan_data').is(':checked');

        // VALIDASI WILAYAH
        if (!wilayah) {
            swal({
                title: 'Wilayah Belum Dipilih',
                text: 'Silakan pilih Kecamatan terlebih dahulu.',
                type: 'error',
                confirmButtonText: 'OK'
            });

            $('#id_wilayah').next('.select2-container')
                .find('.select2-selection')
                .css('border', '1px solid red');

            return false;
        }

        // VALIDASI CHECKBOX PERNYATAAN
        if (!pernyataan) {
            swal({
                title: 'Pernyataan Belum Dicentang',
                text: 'Silakan centang pernyataan bahwa data induk sudah benar sebelum menyimpan.',
                type: 'warning',
                confirmButtonText: 'OK'
            });

            $('#pernyataan_data').focus();

            return false;
        }

        // KONFIRMASI SIMPAN
        swal({
            title: 'Persertujuan',
            text: 'Dengan ini saya menyatakan bahwa Data Induk saya telah sesuai dengan ijazah terakhir yang saya miliki, dan saya bersedia menggunakan data tersebut untuk keperluan Administrasi Yudisium, Wisuda, Pencetakan Ijazah, dan Transkrip',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Setujui',
            cancelButtonText: 'Batal'
        }, function (isConfirmed) {
            if (isConfirmed) {
                $('#data-induk').off('submit').submit();
                $('#spinner').show();
            }
        });
    });

    // Hilangkan border merah saat dipilih
    $('#id_wilayah').on('change', function () {
        $('#id_wilayah').next('.select2-container')
            .find('.select2-selection')
            .css('border', '');
    });

});
</script>

@endpush
