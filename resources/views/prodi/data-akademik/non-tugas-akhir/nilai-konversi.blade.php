@extends('layouts.prodi')
@section('title')
Tambah Dosen Kelas Perkuliahan
@endsection
@section('content')
@include('swal')
@push('header')
<div class="mx-4">
    <a href="{{route('prodi.data-akademik.non-tugas-akhir.edit-detail', ['aktivitas' => $data->id_aktivitas])}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Nilai Konversi Aktivitas Mahasiswa</h3>      
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
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Detail Aktivitas Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nim" class="form-label">NIM</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nim"
                                    id="nim"
                                    aria-describedby="helpId"
                                    value="{{$kelas[0]['nama_kelas_kuliah']}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="tgl_mulai" class="form-label">Nama Mahasiswa</label>
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
                                <label for="tgl_akhir" class="form-label">Jenis Aktivitas</label>
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
                                <label for="nama_mata_kuliah" class="form-label">SK Aktivitas Mahasiswa</label>
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
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Pembimbing Aktivitas Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="table-responsive">
                            <table id="data" class="table table-bordered table-hover margin-top-10 w-p100"
                                style="font-size: 11px">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">No</th>
                                        <th class="text-center align-middle">Nama Pembimbing</th>
                                        <th class="text-center align-middle">Status Pembimbing</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                    <tr>
                                        <td class="text-center align-middle"></td>
                                        <td class="text-center align-middle">
                                            {{$d->anggota_aktivitas_personal ? $d->anggota_aktivitas_personal->nim : "-"}}
                                        </td>
                                        <td class="text-start align-middle" style="width: 15%">
                                            {{$d->anggota_aktivitas_personal ? $d->anggota_aktivitas_personal->nama_mahasiswa : "-"}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <h4 class="text-info mb-0"><i class="fa fa-user"></i> Konversi Aktivitas Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="konversi-fields">
                                <div class="konversi-field row">
                                    <div class="col-md-5 mb-2">
                                        <label for="mata_kuliah" class="form-label">Nama Mata Kuliah</label>
                                        <select class="form-select" name="mata_kuliah[]" id="matkul" required></select>
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
                            <!-- <button id="add-dosen" type="button" class="btn btn-primary" title="Tambah Dosen"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button> -->
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
                placeholder : '-- Pilih Mata Kuliah --',
                minimumInputLength: 3,
                ajax: {
                    url: "{{route('prodi.data-akademik.non-tugas-akhir.get-matkul')}}",
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
                                    text: item.kode_mata_kuliah + "-" + item.nama_mata_kuliah + " ( " + item.sks_mata_kuliah + " )",
                                    id: item.id_matkul
                                }
                            })
                        };
                    },
                }
            });
        }

        // Initialize Select2 for the first select element
        var initialSelect = initializeSelect2($('#matkul'));
        // var initialSelect = initializeSelect2Substansi($('#substansi'));

        // Event listener for adding a new row
        $('#add-konversi').click(function() {
            var newRow = $('<div class="konversi-field row">' +
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
            newRow.appendTo('#konversi-fields');

            // Initialize Select2 for the new select element
            var newSelect = newRow.find('.select2');
            initializeSelect2(newSelect);
            newSelect.val(null).trigger('change');

            // Show the remove button
            newRow.find('.remove-konversi').show();
        });

        // Event listener for removing a row
        $(document).on('click', '.remove-konversi', function() {
            $(this).closest('.konversi-field').remove();
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
