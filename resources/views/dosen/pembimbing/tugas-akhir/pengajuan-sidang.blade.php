@extends('layouts.dosen')
@section('title')
Pengajuan Sidang Mahasiswa
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Pengajuan Sidang Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dosen.pembimbing.bimbingan-tugas-akhir')}}">Bimbingan Tugas Akhir</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dosen.pembimbing.bimbingan-tugas-akhir.asistensi', $data)}}">Asistensi Mahasiswa</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pengajuan Sidang Mahasiswa</li>
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
                <form class="form" action="{{route('dosen.pembimbing.bimbingan-tugas-akhir.ajuan-sidang.store', ['aktivitas' => $data->id_aktivitas])}}" id="approve-sidang" method="POST">
                    @csrf
                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Detail Bimbing Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mahasiswa"
                                    id="nama_mahasiswa"
                                    aria-describedby="helpId"
                                    value="{{$data->anggota_aktivitas_personal->nama_mahasiswa}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nim" class="form-label">NIM</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nim"
                                    id="nim"
                                    aria-describedby="helpId"
                                    value="{{$data->anggota_aktivitas_personal->nim}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="angkatan" class="form-label">Angkatan</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="angkatan"
                                    id="angkatan"
                                    aria-describedby="helpId"
                                    value="{{$data->anggota_aktivitas_personal->angkatan}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="judul" class="form-label">Judul</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="judul"
                                    id="judul"
                                    aria-describedby="helpId"
                                    value="{{$data->judul}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <h4 class="text-info mb-0"><i class="fa fa-user"></i> Dosen Penguji Sidang</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="penguji-fields"></div>
                            <p>*Notes Penguji tidak wajib diajukan dari dosen pembimbing.</p>
                            <button id="add-dosen" type="button" class="btn btn-primary" title="Tambah Dosen"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('dosen.pembimbing.bimbingan-tugas-akhir.asistensi', ['aktivitas' => $data])}}" class="btn btn-danger waves-effect waves-light">
                            Batal
                        </a>
                        <button type="submit" id="submit-button" class="btn btn-primary waves-effect waves-light">Ajukan</button>
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

        // Function to initialize Select2 on an element
        function initializeSelect2(selectElement) {
            return selectElement.select2({
                placeholder: '-- Pilih Dosen --',
                minimumInputLength: 3,
                ajax: {
                    url: "{{route('dosen.pembimbing.bimbingan-tugas-akhir.get-dosen')}}",
                    type: "GET",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term // search term
                        };
                    },
                    processResults: function (data) {
                        console.log('Received data:', data);
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama_dosen + " ( " + item.nama_program_studi + " )",
                                    id: item.id_registrasi_dosen
                                };
                            })
                        };
                    }
                }
            });
        }

        // Initialize Select2 for the first select element
        var initialSelect = initializeSelect2($('#dosen_penguji'));

        // Event listener for adding a new row
        $('#add-dosen').click(function() {
            var newRow = $('<div class="penguji-field row">' +
                            '<div class="col-md-5 mb-2">' +
                                '<label for="dosen_penguji" class="form-label">Nama Dosen</label>' +
                                '<select class="form-select select2" name="dosen_penguji[]"></select>' +
                            '</div>' +
                            '<div class="col-md-3 mb-2">' +
                                '<label for="penguji_ke" class="form-label">Penguji Ke -</label>' +
                                '<input type="text" class="form-control" name="penguji_ke[]" aria-describedby="helpId" value="1" />' +
                            '</div>' +
                            '<div class="col-md-1 mb-2">' +
                                '<label class="form-label">&nbsp;</label>' +
                                '<button type="button" class="btn btn-danger btn-rounded btn-sm remove-dosen form-control" title="Hapus Dosen"><i class="fa fa-user-times" aria-hidden="true"></i></button>' +
                            '</div>' +
                        '</div>');

            // Append the new row
            newRow.appendTo('#penguji-fields');

            // Initialize Select2 for the new select element
            var newSelect = newRow.find('.select2');
            initializeSelect2(newSelect);
            newSelect.val(null).trigger('change');
        });

        // Event listener for removing a row
        $(document).on('click', '.remove-dosen', function() {
            $(this).closest('.penguji-field').remove();
        });

        // Function to collect selected values
        function collectSelectedValues() {
            selectedValues = [];
            $('.select2').each(function() {
                var selectedValue = $(this).val();
                if (selectedValue) {
                    selectedValues.push(selectedValue);
                }
            });
            // console.log(selectedValues);
        }

        // Form submission with SweetAlert confirmation
        $('#approve-sidang').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Persetujuan Sidang Mahasiswa',
                text: "Apakah anda yakin ingin melanjutkan?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            },function(isConfirmed){
                if (isConfirmed) {
                    collectSelectedValues();
                    $('#approve-sidang').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });
</script>

@endpush
