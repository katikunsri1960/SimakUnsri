@extends('layouts.prodi')
@section('title')
Ubah Dosen Pengajar Mahasiswa
@endsection
@section('content')
@include('swal')
@push('header')
<div class="mx-4">
    <a href="{{route('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar.manajemen', ['id_kelas' => $data->id_kelas_kuliah])}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <form class="form" action="{{route('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar.update', ['id' => $data->id])}}" id="ubah-dosen-pengajar" method="POST">
                    @csrf
                    <div class="box-body">
                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <div class="d-flex justify-content-start">
                                    <h4 class="text-info mb-0"><i class="fa fa-user"></i> Ubah Dosen Pengajar Kelas ({{$data->kelas_kuliah->matkul->nama_mata_kuliah}} - {{$data->kelas_kuliah->nama_kelas_kuliah}})</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex justify-content-end">
                                    <p class="text-danger">* Dosen Pengajar dapat di ganti saat status data belum di sinkronisasi</p>
                                </div>
                            </div>
                        </div>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="dosen-fields">
                                <div class="dosen-field row">
                                    <div class="col-md-3 mb-2">
                                        <label for="dosen_pengajar" class="form-label">Nama Dosen Pengajar</label>
                                        <select class="form-select" name="dosen_pengajar[]" id="dosen_pengajar" required>
                                            <option value="{{ $data->id_registrasi_dosen }}"
                                                {{ $data->id_registrasi_dosen != '' ? 'selected' : '' }}>
                                                ({{ $data->dosen->nidn }}) - {{ $data->dosen->nama_dosen }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label for="evaluasi" class="form-label">Jenis Evaluasi</label>
                                        <select class="form-select" name="evaluasi[]" id="evaluasi" required>
                                            <option value="">-- Pilih Jenis Evaluasi --</option>
                                            @foreach($evaluasi as $e)
                                                <option value="{{$e->id_jenis_evaluasi}}" {{$e->id_jenis_evaluasi == $data->id_jenis_evaluasi ? 'selected' : ''}}>{{$e->nama_jenis_evaluasi}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label for="rencana_minggu_pertemuan" class="form-label">Rencana Minggu Pertemuan</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            name="rencana_minggu_pertemuan[]"
                                            id="rencana_minggu_pertemuan"
                                            aria-describedby="helpId"
                                            value="{{$data->rencana_minggu_pertemuan}}"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label for="realisasi_minggu_pertemuan" class="form-label">Realisasi Minggu Pertemuan</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            name="realisasi_minggu_pertemuan[]"
                                            id="realisasi_minggu_pertemuan"
                                            aria-describedby="helpId"
                                            value="{{$data->realisasi_minggu_pertemuan}}"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar.manajemen', ['id_kelas' => $data->id_kelas_kuliah])}}" class="btn btn-danger waves-effect waves-light">
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
        var initialSelect = initializeSelect2($('#dosen_pengajar'));

        // Example function to collect selected values
        function collectSelectedValues() {
            selectedValues = [];
            $('.select2').each(function() {
                var selectedValue = $(this).val();
                selectedValues.push(selectedValue);
            });
   
        }

        $('#ubah-dosen-pengajar').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Manajemen Dosen pengajar',
                text: "Apakah anda yakin ingin merubah dosen pengajar?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            },function(isConfirmed){
                if (isConfirmed) {
                    collectSelectedValues();
                    $('#ubah-dosen-pengajar').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });
</script>
@endpush
