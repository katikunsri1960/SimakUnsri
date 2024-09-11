@extends('layouts.fakultas')
@section('title')
Dosen Pembimbing Mahasiswa
@endsection
@section('content')
@include('swal')
@push('header')
<div class="mx-4">
    <a href="{{route('fakultas.data-akademik.tugas-akhir.edit-detail', ['aktivitas' => $data->id_aktivitas])}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <form class="form" action="{{route('fakultas.data-akademik.tugas-akhir.update-dosen', ['bimbing' => $data->id, 'aktivitas' => $data->id_aktivitas])}}" id="ubah-dosen-pembimbing" method="POST">
                    @csrf
                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-user"></i> Ubah Dosen Pembimbing Mahasiswa ({{$data->anggota_aktivitas_personal->nim}} - {{$data->anggota_aktivitas_personal->nama_mahasiswa}})</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="dosen-fields">
                                <div class="dosen-field row">
                                    <div class="col-md-5 mb-2">
                                        <label for="dosen_pembimbing" class="form-label">Nama Dosen Pembimbing</label>
                                        <select class="form-select" name="dosen_pembimbing[]" id="dosen_pembimbing" required>
                                            <option value="{{ $data->id_dosen }}" 
                                                {{ $data->id_dosen != '' ? 'selected' : '' }}>
                                                ({{ $data->nidn }}) - {{ $data->nama_dosen }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="kategori" class="form-label">Kategori Dosen Pembimbing</label>
                                        <select class="form-select" name="kategori[]" id="evaluasi" required>
                                            <option value="">-- Pilih Jenis Kategori --</option>
                                            @foreach($kategori as $k)
                                                <option value="{{$k->id_kategori_kegiatan}}" {{$k->id_kategori_kegiatan == $data->id_kategori_kegiatan ? 'selected' : ''}}>{{$k->nama_kategori_kegiatan}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label for="pembimbing_ke" class="form-label">Pembimbing Ke - </label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            name="pembimbing_ke[]"
                                            id="pembimbing_ke"
                                            aria-describedby="helpId"
                                            value="{{$data->pembimbing_ke}}"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('fakultas.data-akademik.tugas-akhir.edit-detail', ['aktivitas' => $data->id_aktivitas])}}" class="btn btn-danger waves-effect waves-light">
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
                    url: "{{route('fakultas.data-akademik.tugas-akhir.get-dosen')}}",
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
                                    id: item.id_dosen
                                }
                            })
                        };
                    },
                }
            });
        }

        // Initialize Select2 for the first select element
        var initialSelect = initializeSelect2($('#dosen_pembimbing'));

        // Example function to collect selected values
        function collectSelectedValues() {
            selectedValues = [];
            $('.select2').each(function() {
                var selectedValue = $(this).val();
                selectedValues.push(selectedValue);
            });
            console.log(selectedValues);
        }

        $('#ubah-dosen-pembimbing').submit(function(e){
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
                    $('#ubah-dosen-pembimbing').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });
</script>
@endpush
