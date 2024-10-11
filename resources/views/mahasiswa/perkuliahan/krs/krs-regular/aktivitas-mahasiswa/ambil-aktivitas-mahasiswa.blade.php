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
            <div class="box box-outline-success bs-3 border-success mb-20 shadow-lg">
                <div class="content-header">
                    <div class="d-flex align-items-center">
                        <div class="me-auto">
                            <h3 class="page-title">TAMBAH AKTIVITAS {{ strtoupper($mk_konversi->nama_jenis_aktivitas) }}</h3>
                            {{-- <div class="d-inline-block align-items-center">
                                <nav>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('mahasiswa.krs')}}">Kartu Rencana Studi</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Ambil Aktivitas {{$aktivitas_mk->nama_mata_kuliah}}</li>
                                    </ol>
                                </nav>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <section class="content m-20">
                    <div class="col-12">
                        <div class="container-fluid">
                            <form action="{{ route('mahasiswa.krs.simpan-aktivitas') }}" method="POST" id='form-aktivitas'>
                                @csrf

                                <h4 class="text-info mb-20"><i class="fa fa-book"></i>  DATA AKTIVITAS</h4>
                                {{-- <hr class="my-15"> --}}
                                <div class="form-group mb-20">
                                    <div id="judul-fields">
                                        <div class="judul-field row">
                                            <div class="col-md-8 mb-2">
                                                <label>Judul</label>
                                                <textarea id="judul" class="form-control" name="judul" placeholder="-- Masukkan Judul Aktivitas --" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-20">
                                    <div id="lokasi-fields">
                                        <div class="lokasi-field row">
                                            <div class="col-md-8 mb-2">
                                                <label for="lokasi" class="form-label">Lokasi</label>
                                                <input type="text" id="lokasi" class="form-control" name="lokasi" placeholder="-- Masukkan Lokasi Aktivitas --" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-20">
                                    <div id="keterangan-fields">
                                        <div class="keterangan-field row">
                                            <div class="col-md-8 mb-2">
                                                <label for="keterangan" class="form-label">Keterangan</label>
                                                <input type="text" id="keterangan" class="form-control" name="keterangan" placeholder="-- Masukkan Keterangan Aktivitas --">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="text-info mt-40"><i class="fa fa-user"></i>  Dosen Pembimbing</h4>
                                <hr class="my-15">
                                @php
                                    $no_a=1;
                                @endphp
                                <div class="form-group mb-20">
                                    <div id="dosen-fields">
                                        <div class="dosen-field row">

                                        </div>
                                    </div>
                                    <button id="add-dosen" type="button" class="btn btn-primary" title="Tambah Dosen"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
                                </div>

                                <div class="row mt-20 mb-20">
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="id_matkul_konversi" value="{{ $mk_konversi->id_matkul }}">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
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

    $(document).ready(function(){

        // Array to store selected values from Select2 inputs
        var selectedValues = [];
        var maxDosen = 4; // Batas maksimum dosen pembimbing

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
                                }
                            })
                        };
                    },
                }
            });
        }

        // Initialize Select2 for the first select element
        var initialSelect = initializeSelect2($('#dosen_bimbing'));
        // var initialSelect = initializeSelect2Substansi($('#substansi'));

        var dosenCounter = 1; // Inisialisasi counter

        // Event listener untuk menambahkan baris baru
        $('#add-dosen').click(function() {
            if ($('.dosen-field').length > maxDosen) {
                swal({
                    title: "Batas Maksimum Tercapai",
                    text: "Anda hanya dapat menambahkan maksimal 4 dosen pembimbing.",
                    type: "warning",
                    button: "OK",
                });
                return;
            }
            var newRow = $('<div class="dosen-field row">' +
                                '<div class="col-md-8 mb-2">' +
                                    '<label for="dosen_bimbing_aktivitas_' + dosenCounter + '" class="form-label">Nama Dosen Pembimbing ' + dosenCounter + '</label>' +
                                    '<select id="dosen_bimbing_aktivitas_' + dosenCounter + '" class="form-select select2" name="dosen_bimbing_aktivitas[]" required></select>' +
                                '</div>' +
                                '<div class="col-md-1 mb-2">' +
                                    '<label class="form-label">&nbsp;</label>' +
                                    '<button type="button" class="btn btn-danger btn-rounded btn-sm remove-dosen form-control" style="display: none;" title="Hapus Dosen"><i class="fa fa-user-times" aria-hidden="true"></i></button>' +
                                '</div>' +
                            '</div>');



                // Append the new row
                newRow.appendTo('#dosen-fields');

                // Initialize Select2 untuk elemen select baru
                var newSelect = newRow.find('.select2');
                initializeSelect2(newSelect, dosenCounter);
                newSelect.val(null).trigger('change');

                // Show the remove button
                newRow.find('.remove-dosen').show();

                // Increment counter
                dosenCounter++;
            });

            // Event listener for removing a row
            $(document).on('click', '.remove-dosen', function() {
                $(this).closest('.dosen-field').remove();
                // Reset dosenCounter
                dosenCounter = $('.dosen-field').length;
            });

        // Example function to collect selected values
        function collectSelectedValues() {
            selectedValues = [];
            $('.select2').each(function() {
                var selectedValue = $(this).val();
                selectedValues.push(selectedValue);
            });
         
        }
    });

    function upperCaseF(a){
        setTimeout(function(){
            a.value = a.value.toUpperCase();
        }, 1);
    }

</script>
@endpush
