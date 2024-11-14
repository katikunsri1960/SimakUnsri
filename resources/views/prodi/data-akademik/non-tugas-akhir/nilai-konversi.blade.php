@extends('layouts.prodi')
@section('title')
Nilai Konversi Aktivitas
@endsection
@section('content')
@include('swal')
@push('header')
<div class="mx-4">
    <a href="{{route('prodi.data-akademik.non-tugas-akhir')}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
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
                <form class="form" action="{{route('prodi.data-akademik.non-tugas-akhir.nilai-konversi.store', ['aktivitas' => $d->id_aktivitas])}}" id="tambah-konversi" method="POST">
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
                                    value="{{$d->anggota_aktivitas_personal->nim}}"
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
                                    value="{{$d->anggota_aktivitas_personal->nama_mahasiswa}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="tgl_akhir" class="form-label">Jenis Aktivitas (SKS Konversi)</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="tgl_akhir"
                                    id="tgl_akhir"
                                    aria-describedby="helpId"
                                    value="{{$d->nama_jenis_aktivitas}} ({{$d->sks_aktivitas}} SKS)"
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
                                    value="{{$d->sk_tugas}}"
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
                                    @foreach ($d->bimbing_mahasiswa as $db)
                                    <tr>
                                        <td class="text-center align-middle">{{$db->pembimbing_ke}}</td>
                                        <td class="text-center align-middle">{{$db->nama_dosen}}</td>
                                        <td class="text-start align-middle" style="width: 15%">{{$db->nama_kategori_kegiatan}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if(!$konversi->isEmpty())
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Konversi Aktivitas Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="table-responsive">
                            <table id="data" class="table table-bordered table-hover margin-top-10 w-p100"
                                style="font-size: 11px">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">Kode Mata Kuliah</th>
                                        <th class="text-center align-middle">Nama Mata Kuliah</th>
                                        <th class="text-center align-middle">SKS Mata Kuliah</th>
                                        <th class="text-center align-middle">Nilai Angka</th>
                                        <th class="text-center align-middle">Nilai Indeks</th>
                                        <th class="text-center align-middle">Nilai Huruf</th>
                                        <th class="text-center align-middle">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($konversi as $k)
                                    <tr>
                                        <td class="text-center align-middle">
                                            {{ $k->matkul->kode_mata_kuliah ?? '-' }}
                                        </td>
                                        <td class="text-start align-middle">
                                            {{ $k->matkul->nama_mata_kuliah ?? '-' }}
                                        </td>
                                        <td class="text-center align-middle" style="width: 15%">
                                            {{ $k->matkul->sks_mata_kuliah ?? '-' }}
                                        </td>
                                        <td class="text-center align-middle" style="width: 15%">
                                            {{ $k->nilai_angka ?? '-' }}
                                        </td>
                                        <td class="text-center align-middle" style="width: 15%">
                                            {{ $k->nilai_indeks ?? '-' }}
                                        </td>
                                        <td class="text-center align-middle" style="width: 15%">
                                            {{ $k->nilai_huruf ?? '-' }}
                                        </td>
                                        <td class="text-center align-middle" style="width: 15%">
                                            <button type="button" class="btn btn-danger btn-sm my-2 delete-button" data-id="{{ $k->id_konversi_aktivitas }}">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                        <h4 class="text-info mb-0"><i class="fa fa-edit"></i> Input Nilai Konversi</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="konversi-fields">
                                <div class="konversi-field row">
                                    <div class="col-md-5 mb-2">
                                        <label for="mata_kuliah" class="form-label">Nama Mata Kuliah</label>
                                        <select class="form-select" name="mata_kuliah[]" id="mata_kuliah" required></select>
                                    </div>
                                    <div class="col-md-5 mb-2">
                                        <label for="nilai_angka" class="form-label">Nilai Konversi</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            name="nilai_angka[]"
                                            id="nilai_angka"
                                            aria-describedby="helpId"
                                            value="0"
                                            step="0.01"
                                            max="100"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-rounded btn-sm remove-konversi form-control" style="display: none;" title="Hapus Nilai Konversi"><i class="fa fa-user-times" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                            <p>*Notes Jumlah total dari seluruh sks mata kuliah tidak boleh melebihi batas sks yang dapat dikonversi.</p>
                            <button id="add-konversi" type="button" class="btn btn-primary" title="Tambah Dosen"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('prodi.data-akademik.non-tugas-akhir')}}" class="btn btn-danger waves-effect waves-light">
                            Batal
                        </a>
                        <button type="submit" id="submit-button" class="btn btn-primary waves-effect waves-light">Simpan</button>
                    </div>
                </form>
                <!-- Delete Form (Hidden) -->
                <form id="delete-form" action="" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
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
                    url: "{{route('prodi.data-akademik.non-tugas-akhir.get-matkul', ['nim' => $d->anggota_aktivitas_personal->nim])}}",
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
                                    text: item.kode_mata_kuliah + "-" + item.nama_mata_kuliah + " ( " + item.sks_mata_kuliah + " SKS" + " )",
                                    id: item.id_matkul
                                }
                            })
                        };
                    },
                }
            });
        }

        // Initialize Select2 for the first select element
        var initialSelect = initializeSelect2($('#mata_kuliah'));
        // var initialSelect = initializeSelect2Substansi($('#substansi'));

        // Event listener for adding a new row
        $('#add-konversi').click(function() {
            var newRow = $('<div class="konversi-field row">' +
                            '<div class="col-md-5 mb-2">' +
                                '<label for="mata_kuliah" class="form-label">Nama Mata Kuliah</label>' +
                                '<select class="form-select select2" name="mata_kuliah[]" required></select>' +
                            '</div>' +
                            '<div class="col-md-5 mb-2">' +
                                '<label for="nilai_angka" class="form-label">Nilai Konversi</label>' +
                                '<input type="number" class="form-control" name="nilai_angka[]" aria-describedby="helpId" value="0" required/>' +
                            '</div>' +
                            '<div class="col-md-1 mb-2">' +
                                '<label class="form-label">&nbsp;</label>' +
                                '<button type="button" class="btn btn-danger btn-rounded btn-sm remove-konversi form-control" style="display: none;" title="Hapus Nilai Konversi"><i class="fa fa-user-times" aria-hidden="true"></i></button>' +
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

        $('#tambah-konversi').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Nilai Konversi Aktivitas Mahasiswa',
                text: "Apakah anda yakin ingin menambahkan konversi aktivitas?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            },function(isConfirmed){
                if (isConfirmed) {
                    collectSelectedValues();
                    $('#tambah-konversi').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });

        $('.delete-button').click(function(e){
            e.preventDefault();

            const id = this.getAttribute('data-id');
            const form = document.getElementById('delete-form');
            form.setAttribute('action', `/prodi/data-akademik/non-tugas-akhir/nilai-konversi/delete/${id}`);

            swal({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
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
