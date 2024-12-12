@extends('layouts.mahasiswa')
@section('title')
Ambil Aktivitas Mahasiswa
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Tambah Aktivitas {{ ucfirst($mk_konversi->nama_jenis_aktivitas) }}</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('mahasiswa.dashboard')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">KRS</li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('mahasiswa.krs.index')}}">Daftar KRS</a></li>
                        {{-- <li class="breadcrumb-item" aria-current="page"><a href="{{route('mahasiswa.krs.get-aktivitas')}}">Detail Kelas dan Penjadwalan</a></li> --}}
                        <li class="breadcrumb-item active" aria-current="page">Tambah Aktivitas {{ ucfirst($mk_konversi->nama_jenis_aktivitas) }}</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-12">
            <form action="{{ route('mahasiswa.krs.simpan-aktivitas') }}" method="POST" id='form-aktivitas'>
                @csrf
                <div class="box box-outline-success bs-3 border-success">
                    <div class="box-body">
                        <h3 class="text-info mb-0"><i class="fa fa-user"></i> Detail Kelas Kuliah</h3>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="judul-fields">
                                <div class="judul-field row">
                                    <div class="col-md-12 mb-2">
                                        <label for="judul">Judul</label>
                                        <textarea 
                                            id="judul" 
                                            class="form-control py-20" 
                                            name="judul" 
                                            placeholder="-- Masukkan Judul Aktivitas --" 
                                            @if(!empty($riwayat_aktivitas) && isset($riwayat_aktivitas->judul)) readonly @endif required
                                            >{{ !empty($riwayat_aktivitas) && isset($riwayat_aktivitas->judul) ? $riwayat_aktivitas->judul : NULL }}</textarea>
                                        <input type="hidden" name="id_aktivitas" value="{{ $riwayat_aktivitas ? $riwayat_aktivitas->id_aktivitas : NULL }}">
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="form-group">
                            <div class="lokasi-field row">
                                <div class="col-md-12 mb-2">
                                    <label for="lokasi" class="form-label">Lokasi</label>
                                    <input 
                                        type="text" 
                                        id="lokasi" 
                                        class="form-control" 
                                        name="lokasi" 
                                        placeholder="-- Masukkan Lokasi Aktivitas --" 
                                        maxlength="80" 
                                        value="{{!empty($riwayat_aktivitas) && isset($riwayat_aktivitas->lokasi) ? $riwayat_aktivitas->lokasi : '' }}" 
                                        @if(!empty($riwayat_aktivitas) && isset($riwayat_aktivitas->lokasi)) readonly @endif required>
                                </div>
                            </div>                            
                        </div>
                        <div class="form-group">
                            <div class="lokasi-field row">
                                <div class="col-md-12 mb-4">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <input 
                                        type="text" 
                                        id="keterangan" 
                                        class="form-control" 
                                        name="keterangan" 
                                        placeholder="-- Masukkan Keterangan Aktivitas --"
                                        value="{{ !empty($riwayat_aktivitas) && isset($riwayat_aktivitas->keterangan) ? $riwayat_aktivitas->keterangan : '' }}" 
                                        @if(!empty($riwayat_aktivitas) && isset($riwayat_aktivitas->keterangan)) readonly @endif>
                                </div>
                            </div>
                        </div>                        
                    </div>                                        
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="box box-outline-success bs-3 border-success">
                        <div class="box-header with-border">
                                <div class="row mb-2">
                                    <div class="col-lg-6">
                                        <div class="d-flex justify-content-start">
                                            <h3 class="text-info mb-0"><i class="fa fa-user"></i> Dosen Pembimbing </h3>
                                        </div>
                                    </div>
                                    @if (empty($riwayat_aktivitas) && empty($riwayat_aktivitas->bimbing_mahasiswa))
                                        <div class="col-lg-6">
                                            <div class="d-flex justify-content-end mb-10">
                                                <button id="add-dosen" type="button" class="btn btn-success mt-10" title="Tambah Dosen"><i class="fa fa-plus" aria-hidden="true"></i> Tambah Dosen</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
            
                            <div class="box-body">
                                {{-- <h4 class="text-info mt-40"><i class="fa fa-user"></i> Dosen Pembimbing</h4>
                                <hr class="my-15"> --}}
                                <div class="form-group mb-20">
                                    <div id="dosen-fields">
                                        @if (!empty($riwayat_aktivitas) && $riwayat_aktivitas->bimbing_mahasiswa->isNotEmpty())
                                            @foreach ($riwayat_aktivitas->bimbing_mahasiswa as $index => $bimbing)
                                                <div class="dosen-field row">
                                                    <div class="col-md-8 mb-5">
                                                        <label for="dosen_bimbing_aktivitas_{{ $index + 1 }}" class="form-label">
                                                            Nama Dosen Pembimbing {{ $index + 1 }}
                                                        </label>
                                                        <select 
                                                            id="dosen_bimbing_aktivitas_{{ $index + 1 }}" 
                                                            class="form-select select2" 
                                                            name="dosen_bimbing_aktivitas_disabled[]" 
                                                            disabled>
                                                            <option value="{{ $bimbing->dosen->id_dosen }}" selected>
                                                                {{ $bimbing->dosen->nidn }} - {{ $bimbing->dosen->nama_dosen }}
                                                            </option>
                                                        </select>

                                                        {{-- Hidden input untuk memastikan nilai tetap terkirim --}}
                                                        <input 
                                                            type="hidden" 
                                                            name="dosen_bimbing_aktivitas[]" 
                                                            value="{{ $bimbing->dosen->id_dosen }}">

                                                    </div>
                                                    <div class="col-md-1 mb-5">
                                                        <label class="form-label">&nbsp;</label>
                                                        {{-- <button 
                                                            type="button" 
                                                            class="btn btn-danger btn-rounded btn-sm remove-dosen form-control" 
                                                            title="Hapus Dosen">
                                                            <i class="fa fa-user-times" aria-hidden="true"></i>
                                                        </button> --}}
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="dosen-field row">
                                                <div class="col-md-8 mb-2">
                                                    <label for="dosen_bimbing_aktivitas_1" class="form-label">Nama Dosen Pembimbing 1</label>
                                                    <select id="dosen_bimbing_aktivitas_1" class="form-select select2" name="dosen_bimbing_aktivitas[]" required></select>
                                                </div>

                                                <div class="col-md-1 mb-2">
                                                    <label class="form-label">&nbsp;</label>
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-danger btn-rounded btn-sm remove-dosen form-control" 
                                                        style="display: none;" 
                                                        title="Hapus Dosen">
                                                        <i class="fa fa-user-times" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-20 mb-20">
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="id_matkul_konversi" value="{{ $mk_konversi->id_matkul }}">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>   
</section>
@endsection
@push('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('js')
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> --}}
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
    $('.ambil-aktivitas').click(function() {
        var idMatkul = $(this).data('id-matkul');
        window.location.href = '/mahasiswa/krs/ambil-aktivitas/' + idMatkul;
    });

    $(document).ready(function() {
        var maxDosen = 4; // Batas maksimum dosen pembimbing
        var dosenCounter = $('.dosen-field').length + 1; // Hitung jumlah dosen yang sudah ada

        function initializeSelect2(selectElement) {
            return selectElement.select2({
                placeholder : '-- Pilih Dosen --',
                minimumInputLength: 3,
                ajax: {
                    url: "{{route('mahasiswa.krs.dosen-pembimbing.get-dosen')}}",
                    type: "GET",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term // search term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nidn + " - "+item.nama_dosen + " ( " + item.nama_program_studi + " )",
                                    id: item.id_dosen
                                };
                            })
                        };
                    },
                }
            });
        }

        // Initialize Select2 for existing elements
        $('.select2').each(function() {
            initializeSelect2($(this));
        });

        // Event listener untuk menambahkan dosen
        $('#add-dosen').click(function() {
            if ($('.dosen-field').length >= maxDosen) {
                swal({
                    title: "Batas Maksimum Tercapai",
                    text: "Anda hanya dapat menambahkan maksimal 4 dosen pembimbing.",
                    type: "warning",
                    button: "OK",
                });
                return;
            }

            var newRow = $(
                '<div class="dosen-field row">' +
                    '<div class="col-md-8 mb-2">' +
                        '<label for="dosen_bimbing_aktivitas_' + dosenCounter + '" class="form-label">Nama Dosen Pembimbing ' + dosenCounter + '</label>' +
                        '<select id="dosen_bimbing_aktivitas_' + dosenCounter + '" class="form-select select2" name="dosen_bimbing_aktivitas[]" required></select>' +
                    '</div>' +
                    '<div class="col-md-1 mb-2">' +
                        '<label class="form-label">&nbsp;</label>' +
                        '<button type="button" class="btn btn-danger btn-rounded btn-sm remove-dosen form-control" title="Hapus Dosen">' +
                            '<i class="fa fa-user-times" aria-hidden="true"></i>' +
                        '</button>' +
                    '</div>' +
                '</div>'
            );

            $('#dosen-fields').append(newRow);

            var newSelect = newRow.find('.select2');
            initializeSelect2(newSelect);
            dosenCounter++;
        });

        // Event listener untuk menghapus dosen
        $(document).on('click', '.remove-dosen', function() {
            $(this).closest('.dosen-field').remove();
            dosenCounter = $('.dosen-field').length + 1;
        });
    });


    function upperCaseF(a){
        setTimeout(function(){
            a.value = a.value.toUpperCase();
        }, 1);
    }

</script>
@endpush
