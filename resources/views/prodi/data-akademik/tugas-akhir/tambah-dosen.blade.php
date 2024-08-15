@extends('layouts.prodi')
@section('title')
Dosen Pembimbing Mahasiswa
@endsection
@section('content')
@include('swal')
@push('header')
<div class="mx-4">
    <a href="{{route('prodi.data-akademik.tugas-akhir.edit-detail', ['aktivitas' => $d->id_aktivitas])}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <form class="form" action="{{route('prodi.data-akademik.tugas-akhir.store-dosen', ['aktivitas' => $d->id_aktivitas])}}" id="tambah-dosen-pembimbing" method="POST">
                    @csrf
                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-user"></i> Tambah Dosen Pembimbing Mahasiswa ({{$d->anggota_aktivitas_personal->nim}} - {{$d->anggota_aktivitas_personal->nama_mahasiswa}})</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="dosen-fields">
                                <div class="dosen-field row">
                                    <div class="col-md-5 mb-2">
                                        <label for="dosen_pembimbing" class="form-label">Nama Dosen Pembimbing</label>
                                        <select class="form-select" name="dosen_pembimbing[]" id="dosen_pembimbing" required></select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="kategori" class="form-label">Kategori Dosen Pembimbing</label>
                                        <select class="form-select" name="kategori[]" id="evaluasi" required>
                                            <option value="">-- Pilih Jenis Kategori --</option>
                                            @foreach($kategori as $k)
                                                <option value="{{$k->id_kategori_kegiatan}}">{{$k->nama_kategori_kegiatan}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label for="pembimbing_ke" class="form-label">Pembimbing Ke - </label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            name="pembimbing_ke[]"
                                            id="pembimbing_ke"
                                            aria-describedby="helpId"
                                            value="1"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-1 mb-2">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-rounded btn-sm remove-dosen form-control" style="display: none;" title="Hapus Dosen"><i class="fa fa-user-times" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                            <button id="add-dosen" type="button" class="btn btn-primary" title="Tambah Dosen"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('prodi.data-akademik.tugas-akhir.edit-detail', ['aktivitas' => $d->id_aktivitas])}}" class="btn btn-danger waves-effect waves-light">
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
                    url: "{{route('prodi.data-akademik.tugas-akhir.get-dosen')}}",
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

        // Initialize Select2 for the first select element
        var initialSelect = initializeSelect2($('#dosen_pembimbing'));
        // var initialSelect = initializeSelect2Substansi($('#substansi'));

        // Event listener for adding a new row
        $('#add-dosen').click(function() {
            var newRow = $('<div class="dosen-field row">' +
                            '<div class="col-md-5 mb-2">' +
                                '<label for="dosen_pembimbing" class="form-label">Nama Dosen</label>' +
                                '<select class="form-select select2" name="dosen_pembimbing[]" required></select>' +
                            '</div>' +
                            '<div class="col-md-4 mb-2">' +
                                '<label for="kategori" class="form-label">Jenis Evaluasi</label>' +
                                '<select class="form-select" name="kategori[]" required>' +
                                    '<option value="">-- Pilih Jenis Kategori --</option>' +
                                    '@foreach($kategori as $k)' +
                                        '<option value="{{$k->id_kategori_kegiatan}}">{{$k->nama_kategori_kegiatan}}</option>' +
                                    '@endforeach' +
                                '</select>' +
                            '</div>' +
                            '<div class="col-md-2 mb-2">' +
                                '<label for="pembimbing_ke" class="form-label">Pembimbing Ke -</label>' +
                                '<input type="text" class="form-control" name="pembimbing_ke[]" aria-describedby="helpId" value="1" required/>' +
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

        $('#tambah-dosen-pembimbing').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Manajemen Dosen Pembimbing',
                text: "Apakah anda yakin ingin menambahkan dosen pembimbing?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            },function(isConfirmed){
                if (isConfirmed) {
                    collectSelectedValues();
                    $('#tambah-dosen-pembimbing').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });
</script>
@endpush
