@extends('layouts.mahasiswa')
@section('title')
Pendaftaran Wisuda Mahasiswa
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
            <h3 class="page-title">Data Wisuda Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Yudisium</li>
                        <li class="breadcrumb-item active" aria-current="page">Pendaftaran</li>
                        <li class="breadcrumb-item active" aria-current="page">Data Wisuda Mahasiswa</li>
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
                <form class="form" action="{{route('mahasiswa.wisuda.pendaftaran.data-wisuda-store')}}" id="data-wisuda" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">

                        @php
                        $disabled = ($wisuda && $wisuda->finalisasi_data == 1) ? 'disabled' : '';
                        @endphp

                        <h4 class="text-primary mb-0" style="padding-top: 40px;">
                            <i class="fa fa-graduation-cap"></i> Data Wisuda Mahasiswa
                        </h4>

                        <hr class="my-15">

                        <div class="form-group">
                            <div id="data-wisuda-fields">

                                <div class="data-wisuda-field row">

                                    {{-- WISUDA KE --}}
                                    <div class="col-lg-12 mb-3">

                                        <label for="wisuda_ke" class="form-label">
                                            Wisuda Ke-
                                        </label>
                                        <span class="text-danger"> *</span>

                                        <select id="wisuda_ke" name="wisuda_ke" class="form-select" required {{$disabled}}>

                                            <option value="">-- Pilih Angkatan Wisuda --</option>

                                            @if($wisuda_ke)

                                            <option
                                                value="{{$wisuda_ke->periode}}"
                                                {{ (old('wisuda_ke',$wisuda->wisuda_ke ?? '') == $wisuda_ke->periode) ? 'selected' : '' }}>
                                                {{$wisuda_ke->periode}}
                                            </option>

                                            @else

                                            <option value="0">Tidak ada periode Wisuda</option>

                                            @endif

                                        </select>

                                    </div>



                                    {{-- PAS FOTO --}}
                                    <div class="col-md-12 mb-3">

                                        <label for="pas_foto" class="form-label">
                                            Foto Wisuda (.jpg / .png)
                                        </label>
                                        <span class="text-danger"> *</span>

                                        <input type="file"
                                            class="form-control"
                                            name="pas_foto"
                                            id="pas_foto"
                                            accept=".jpg,.jpeg,.png"
                                            {{$disabled}}
                                            @if(!$wisuda || !$wisuda->pas_foto) required @endif
                                        />

                                        <small class="form-text text-danger">
                                            Maksimal ukuran file <strong>500 KB</strong>.
                                            @if($wisuda && $wisuda->pas_foto)
                                                Kosongkan jika tidak ingin mengganti file.
                                            @endif
                                        </small>


                                        {{-- PREVIEW FOTO JIKA SUDAH ADA --}}
                                        @if($wisuda && $wisuda->pas_foto)

                                        <div class="mt-3">

                                            <label class="form-label">
                                                Preview Foto Wisuda
                                            </label>

                                            <div class="text-center">

                                                <img src="{{ asset('/storage/'. $wisuda->pas_foto) }}"
                                                    style="width:120px;height:160px;object-fit:cover;border:1px solid #ddd;padding:4px;">

                                            </div>

                                            <div class="text-center mt-2">

                                                <a href="{{ asset('/storage/'. $wisuda->pas_foto) }}"
                                                    target="_blank"
                                                    class="btn btn-success btn-sm">

                                                    <i class="fa fa-download"></i>
                                                    Download Foto

                                                </a>

                                            </div>

                                        </div>

                                        @endif



                                        {{-- CONTOH FOTO --}}
<div class="mt-3">

    <label class="form-label fw-bold">
        Contoh Foto Wisuda (Rasio 3:4)
    </label>

    <div style="display:flex; gap:10px; justify-content:center; margin-top:10px;">

        <div class="text-center">
            <img src="{{ asset('images/contoh_wisuda_laki.jpg') }}"
                style="width:120px;height:160px;object-fit:cover;border:1px solid #ddd;padding:4px;">
            <small class="d-block mt-1 text-muted">
                Laki-laki
            </small>
        </div>

        <div class="text-center">
            <img src="{{ asset('images/contoh_wisuda_perempuan.jpg') }}"
                style="width:120px;height:160px;object-fit:cover;border:1px solid #ddd;padding:4px;">
            <small class="d-block mt-1 text-muted">
                Perempuan
            </small>
        </div>

    </div>

</div>


                                        {{-- CATATAN --}}
                                        <small class="form-text text-danger d-block mt-3">

                                            Gunakan rasio <strong>3:4</strong> |
                                            Disarankan minimal <strong>300 x 400 piksel</strong> |
                                            Format <strong>.jpg / .png</strong>

                                        </small>


                                        <small class="form-text text-start">

                                            <strong>Catatan:</strong>

                                            <ul>
                                                <li>
                                                    Foto yang telah diupload tidak dapat diganti oleh mahasiswa dan akan tercetak di Ijazah Anda.
                                                </li>

                                                <li>
                                                    Pastikan menyesuaikan Pas Foto dengan ketentuan di atas.
                                                </li>

                                                <li>
                                                    Laki Laki : Latar Belakang Biru, Kemeja Putih, Dasi Hitam.<br>
                                                    Perempuan : Latar Belakang Merah, Kebaya, Selendang.
                                                </li>

                                            </ul>

                                        </small>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        @if($wisuda && $wisuda->finalisasi_data == 1)
                        <div class="checkbox p-3 border border-success rounded bg-light-success">
                            <input type="checkbox" id="pernyataan_data" name="pernyataan_data" checked disabled>
                            <label for="pernyataan_data" class="text-success fw-bold">
                                Data Wisuda telah difinalisasi. Perubahan data tidak diperbolehkan.
                            </label>
                        </div>
                        <div class="form-group mt-20">
                            <a type="button" href="{{route('mahasiswa.wisuda.pendaftaran.data-tugas-akhir')}}" class="btn btn-danger waves-effect waves-light">
                                Kembali
                            </a>
                            <a type="button" href="{{route('mahasiswa.wisuda.pendaftaran.data-skpi')}}" class="btn btn-primary waves-effect waves-light">
                                Lanjutkan
                            </a>
                        </div>
                        @else
                        {{-- <div class="checkbox">
                            <input type="checkbox" id="pernyataan_data" name="pernyataan_data">
                            <label for="pernyataan_data">
                                Dengan ini saya menyatakan bahwa <b>Data Wisuda</b> saya telah sesuai, dan tidak akan melakukan perubahan, serta saya mengizinkan data tersebut digunakan untuk keperluan Administrasi Yudisium, Wisuda, Pencetakan Ijazah dan Transkrip.
                            </label>
                        </div> --}}
                        <div class="form-group mt-20">
                            <a type="button" href="{{route('mahasiswa.wisuda.pendaftaran.data-tugas-akhir')}}" class="btn btn-danger waves-effect waves-light">
                                Kembali
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
    $(document).ready(function() {
        // VALIDASI SUBMIT
        $('#data-wisuda').on('submit', function(e) {
            e.preventDefault();

            // let pernyataan = $('#pernyataan_data').is(':checked');

            // VALIDASI CHECKBOX PERNYATAAN
            // if (!pernyataan) {
            //     swal({
            //         title: 'Pernyataan Belum Dicentang',
            //         text: 'Silakan centang pernyataan bahwa data akademik sudah benar sebelum menyimpan.',
            //         type: 'warning',
            //         confirmButtonText: 'OK'
            //     });

            //     $('#pernyataan_data').focus();

            //     return false;
            // }

            // KONFIRMASI SIMPAN
            swal({
                title: 'Persertujuan',
                text: 'Dengan ini saya menyatakan bahwa Data Wisuda saya telah sesuai, dan saya mengizinkan data tersebut digunakan untuk keperluan Administrasi Yudisium, Wisuda, Pencetakan Ijazah, dan Transkrip',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Setujui',
                cancelButtonText: 'Batal'
            }, function(isConfirmed) {
                if (isConfirmed) {
                    $('#data-wisuda').off('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });
</script>

@endpush