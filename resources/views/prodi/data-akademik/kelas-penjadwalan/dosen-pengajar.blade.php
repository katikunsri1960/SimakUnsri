@extends('layouts.prodi')
@section('title')
Dosen Kelas Perkuliahan
@endsection
@section('content')
@include('swal')
@php
    $id_matkul = $kelas[0]['id_matkul'];
@endphp
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Dosen Kelas Perkuliahan</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Akademik</li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('prodi.data-akademik.kelas-penjadwalan')}}">Kelas dan Penjadwalan</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('prodi.data-akademik.kelas-penjadwalan.detail', ['id_matkul' => $id_matkul])}}">Detail Kelas dan Penjadwalan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dosen Pengajar Kelas</li>
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
                <form class="form" action="{{route('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar.store', ['id_matkul' => $id_matkul, 'nama_kelas_kuliah' => $kelas[0]['nama_kelas_kuliah']])}}" id="tambah-dosen-pengajar" method="POST">
                    @csrf
                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Detail Kelas Kuliah</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nama_mata_kuliah" class="form-label">Nama Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mata_kuliah"
                                    id="nama_mata_kuliah"
                                    aria-describedby="helpId"
                                    value="{{$kelas[0]['nama_kelas_kuliah']}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="tgl_mulai" class="form-label">Tanggal Mulai Efektif Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="tgl_mulai"
                                    id="tgl_mulai"
                                    aria-describedby="helpId"
                                    value="{{$kelas[0]['tanggal_mulai_efektif']}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="tgl_akhir" class="form-label">Tanggal Akhir Efektif Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="tgl_akhir"
                                    id="tgl_akhir"
                                    aria-describedby="helpId"
                                    value="{{$kelas[0]['tanggal_akhir_efektif']}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nama_mata_kuliah" class="form-label">Nama Mata Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mata_kuliah"
                                    id="nama_mata_kuliah"
                                    aria-describedby="helpId"
                                    value="{{$kelas[0]['kode_mata_kuliah'].' - '.$kelas[0]['nama_mata_kuliah']}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="nama_mata_kuliah" class="form-label">Nama Ruang Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mata_kuliah"
                                    id="nama_mata_kuliah"
                                    aria-describedby="helpId"
                                    value="{{$kelas[0]['nama_ruang'].' - '.$kelas[0]['lokasi']}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="mode_kelas" class="form-label">Mode Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="mode_kelas"
                                    id="mode_kelas"
                                    aria-describedby="helpId"
                                    value="@if($kelas[0]['mode'] == 'O'){{'Online'}}@elseif($kelas[0]['mode'] == 'F'){{'Offline'}}@elseif($kelas[0]['mode'] == 'M'){{'Campuran'}}@else{{'Mode Tidak Terdata'}}@endif"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="lingkup_kelas" class="form-label">Lingkup Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="lingkup_kelas"
                                    id="lingkup_kelas"
                                    aria-describedby="helpId"
                                    value="@if($kelas[0]['lingkup'] == '1'){{'Internal'}}@elseif($kelas[0]['mode'] == '2'){{'External'}}@elseif($kelas[0]['mode'] == '3'){{'Campuran'}}@else{{'Lingkup Tidak Terdata'}}@endif"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="jadwal_kelas" class="form-label">Jadwal Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="jadwal_kelas"
                                    id="jadwal_kelas"
                                    aria-describedby="helpId"
                                    value="{{$kelas[0]['jadwal_hari'].' ('.$kelas[0]['jadwal_jam_mulai'].' - '.$kelas[0]['jadwal_jam_selesai'].')'}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <h4 class="text-info mb-0"><i class="fa fa-user"></i> Dosen Pengajar Kelas</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="dosen-fields">
                                <div class="dosen-field row">
                                    <div class="col-md-5 mb-2">
                                        <label for="dosen_kelas_kuliah" class="form-label">Nama Dosen</label>
                                        <select class="form-select" name="dosen_kelas_kuliah[]" id="dosen_pengajar" required></select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="evaluasi" class="form-label">Jenis Evaluasi</label>
                                        <select class="form-select" name="evaluasi[]" id="evaluasi" required>
                                            <option value="">-- Pilih Jenis Evaluasi --</option>
                                            @foreach($evaluasi as $e)
                                                <option value="{{$e->id_jenis_evaluasi}}">{{$e->nama_jenis_evaluasi}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- <div class="col-md-3 mb-2">
                                        <label for="substansi_kuliah" class="form-label">Substansi Kuliah</label>
                                        <select class="form-select" name="substansi_kuliah[]" id="substansi"></select>
                                    </div> -->
                                    <div class="col-md-3 mb-2">
                                        <label for="rencana_minggu_pertemuan" class="form-label">Rencana Minggu Pertemuan</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="rencana_minggu_pertemuan[]"
                                            id="rencana_minggu_pertemuan"
                                            aria-describedby="helpId"
                                            value="0"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-1 mb-2">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-rounded btn-sm remove-dosen form-control" style="display: none;" title="Hapus Dosen"><i class="fa fa-user-times" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                            <p>*Notes Jumlah total dari seluruh rencana minggu pertemuan dosen harus sama dengan 16 pertemuan</p>
                            <button id="add-dosen" type="button" class="btn btn-primary" title="Tambah Dosen"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('prodi.data-akademik.kelas-penjadwalan.detail', ['id_matkul' => $id_matkul])}}" class="btn btn-danger waves-effect waves-light">
                            Batal
                        </a>
                        <button type="submit" id="submit-button" class="btn btn-primary waves-effect waves-light">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
    $(document).ready(function(){

        // Array to store selected values from Select2 inputs
        var selectedValues = [];

        function initializeSelect2(selectElement) {
            return selectElement.select2({
                placeholder : '-- Pilih Dosen --',
                minimumInputLength: 3,
                ajax: {
                    url: "{{route('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar.get-dosen')}}",
                    type: "GET",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term // search term
                        };
                    },
                    processResults: function (data) {
                        console.log(data);
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama_dosen + " ( " + item.nama_program_studi + " )",
                                    id: item.id_registrasi_dosen
                                }
                            })
                        };
                    },
                }
            });
        }

        // function initializeSelect2Substansi(selectElement) {
        //     return selectElement.select2({
        //         placeholder : '-- Pilih Substansi Kuliah --',
        //         minimumInputLength: 3,
        //         ajax: {
        //             url: "{{route('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar.get-substansi')}}",
        //             type: "GET",
        //             dataType: 'json',
        //             delay: 250,
        //             data: function (params) {
        //                 return {
        //                     q: params.term // search term
        //                 };
        //             },
        //             processResults: function (data) {
        //                 return {
        //                     results: $.map(data, function (item) {
        //                         return {
        //                             text: item.nama_substansi + " ( " + item.sks_mata_kuliah + " )",
        //                             id: item.id_substansi
        //                         }
        //                     })
        //                 };
        //             },
        //         }
        //     });
        // }

        // Initialize Select2 for the first select element
        var initialSelect = initializeSelect2($('#dosen_pengajar'));
        // var initialSelect = initializeSelect2Substansi($('#substansi'));

        // Event listener for adding a new row
        $('#add-dosen').click(function() {
            var newRow = $('<div class="dosen-field row">' +
                            '<div class="col-md-5 mb-2">' +
                                '<label for="dosen_kelas_kuliah" class="form-label">Nama Dosen</label>' +
                                '<select class="form-select select2" name="dosen_kelas_kuliah[]" required></select>' +
                            '</div>' +
                            '<div class="col-md-4 mb-2">' +
                                '<label for="evaluasi" class="form-label">Jenis Evaluasi</label>' +
                                '<select class="form-select" name="evaluasi[]" required>' +
                                    '<option value="">-- Pilih Jenis Evaluasi --</option>' +
                                    '@foreach($evaluasi as $e)' +
                                        '<option value="{{$e->id_jenis_evaluasi}}">{{$e->nama_jenis_evaluasi}}</option>' +
                                    '@endforeach' +
                                '</select>' +
                            '</div>' +
                            // '<div class="col-md-3 mb-2">' +
                            //     '<label for="substansi_kuliah" class="form-label">Substansi Kuliah</label>' +
                            //     '<select class="form-select select2-sub" name="substansi_kuliah[]"></select>' +
                            // '</div>' +
                            '<div class="col-md-3 mb-2">' +
                                '<label for="rencana_minggu_pertemuan" class="form-label">Rencana Minggu Pertemuan</label>' +
                                '<input type="text" class="form-control" name="rencana_minggu_pertemuan[]" aria-describedby="helpId" value="0" required/>' +
                            '</div>' +
                            '<div class="col-md-1 mb-2">' +
                                '<label class="form-label">&nbsp;</label>' +
                                '<button type="button" class="btn btn-danger btn-rounded btn-sm remove-dosen form-control" style="display: none;" title="Hapus Dosen"><i class="fa fa-user-times" aria-hidden="true"></i></button>' +
                            '</div>' +
                        '</div>');

            // Append the new row
            newRow.appendTo('#dosen-fields');

            // Initialize Select2 for the new select element
            var newSelect = newRow.find('.select2');
            initializeSelect2(newSelect);
            newSelect.val(null).trigger('change');

            // var newSelectSubstansi = newRow.find('.select2-sub');
            // initializeSelect2Substansi(newSelectSubstansi);
            // newSelectSubstansi.val(null).trigger('change');

            // Show the remove button
            newRow.find('.remove-dosen').show();
        });

        // Event listener for removing a row
        $(document).on('click', '.remove-dosen', function() {
            $(this).closest('.dosen-field').remove();
        });

        // Example function to collect selected values
        function collectSelectedValues() {
            selectedValues = [];
            $('.select2').each(function() {
                var selectedValue = $(this).val();
                selectedValues.push(selectedValue);
            });
            console.log(selectedValues);
        }

        $('#tambah-dosen-pengajar').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Manajemen Dosen Kelas Kuliah',
                text: "Apakah anda yakin ingin menambahkan dosen pengajar?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            },function(isConfirmed){
                if (isConfirmed) {
                    collectSelectedValues();
                    $('#tambah-dosen-pengajar').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });

    function upperCaseF(a){
        setTimeout(function(){
            a.value = a.value.toUpperCase();
        }, 1);
    }

</script>
@endpush
