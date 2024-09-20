@extends('layouts.prodi')
@section('title')
Konversi Aktivitas
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Ubah Konversi Aktivitas</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('prodi.data-aktivitas.aktivitas-mahasiswa.index')}}">Konversi Aktivitas Mahasiswa</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ubah Konversi Aktivitas</li>
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
                <form class="form" action="{{route('prodi.data-aktivitas.aktivitas-mahasiswa.update', ['rencana_ajar' => $mk_konversi->id])}}" id="update-konversi-aktivitas" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">
                        <h4 class="text-info mb-0 mt-40"><i class="fa fa-user"></i> Konversi Aktivitas</h4>
                        <hr class="my-15">
                        <div class="form-group mb-20">
                            <div id="jenis-aktivitas-fields">
                                <div class="jenis-aktivitas-field row">
                                    <div class="col-md-12 mb-10">
                                        <label>Jenis Aktivitas</label>
                                        <select id="jenis_aktivitas" name="jenis_aktivitas" class="form-select" required>
                                            <option value="" disabled selected>-- Pilih Jenis Aktivitas --</option>
                                            @foreach($jenis_aktivitas as $aktivitas)
                                                <option value="{{ $aktivitas['id_jenis_aktivitas'] }}" 
                                                    {{ $aktivitas['id_jenis_aktivitas'] == $mk_konversi->id_jenis_aktivitas ? 'selected' : '' }}>
                                                    {{ $aktivitas['nama_jenis_aktivitas'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="kurikulum-fields">
                                <div class="kurikulum-field row">
                                    <div class="col-md-12 mb-10">
                                        <label>Kurikulum Mata Kuliah</label>
                                        <select id="kurikulum" name="kurikulum" class="form-select" required>
                                        <option value="" disabled selected>-- Pilih Kurikulum --</option>
                                        @foreach($kurikulum_aktif as $kurikulum)
                                            <option value="{{ $kurikulum['id_kurikulum'] }}" 
                                                {{ $kurikulum['id_kurikulum'] == $mk_konversi->id_kurikulum ? 'selected' : '' }}>
                                                {{ $kurikulum['nama_kurikulum'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div id="mk-fields">
                                <div class="mk-field row">
                                    <label for="mk_konversi" class="form-label">Mata Kuliah</label>
                                    <div class="col-md-12 mb-2">
                                        <select class="form-select" name="mk_konversi" id="mk_konversi" required>
                                            <option value="" disabled selected>-- Pilih Mata kuliah --</option>
                                            <option value="{{ $mk_konversi->id_matkul }}" 
                                                {{ $mk_konversi->id_matkul != '' ? 'selected' : '' }}>
                                                {{ $mk_konversi->nama_mata_kuliah }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="tipe-penilaian-fields">
                                <div class="tipe-penilaian-field row">
                                    <div class="col-md-12 mb-10">
                                        <label>Penilaian Langsung</label>
                                        <select id="tipe_penilaian" name="tipe_penilaian" class="form-select" required>
                                            <option value="" disabled selected>-- Pilih Penilaian Langsung --</option>
                                            <option value="1" {{ $mk_konversi->penilaian_langsung == 1 ? 'selected' : '' }}>Ya</option>
                                            <option value="0" {{ $mk_konversi->penilaian_langsung == 2 ? 'selected' : '' }}>Tidak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('prodi.data-aktivitas.aktivitas-mahasiswa.index')}}" class="btn btn-danger waves-effect waves-light">
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
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#update-konversi-aktivitas').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Konversi Aktivitas',
                text: "Apakah anda yakin?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            },function(isConfirmed){
                if (isConfirmed) {
                    $('#update-konversi-aktivitas').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });

        var kurikulum_id = $('#kurikulum').val();
    
        if (kurikulum_id) {
            initializeSelect2($('#mk_konversi'), kurikulum_id);
        }
        
        $('#kurikulum').change(function() {
            kurikulum_id = $(this).val();
            
            // Clear previous selections
            $('#mk_konversi').val(null).trigger('change');
            
            // Re-initialize Select2
            initializeSelect2($('#mk_konversi'), kurikulum_id);
        });

        // $('#kurikulum').change(function() {
        //     var kurikulum_id = $(this).val();
        //     var selectElement = initializeSelect2($('#mk_konversi'), kurikulum_id);
        // });

        function initializeSelect2(selectElement, kurikulum_id) {
            return selectElement.select2({
                placeholder : '-- Pilih Mata Kuliah --',
                minimumInputLength: 3,
                width: 'resolve', // Auto width
                ajax: {
                    url: "{{route('prodi.data-aktivitas.aktivitas-mahasiswa.get_mk')}}",
                    type: "GET",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            kurikulum_id: kurikulum_id,
                            q: params.term // search term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama_mata_kuliah + " ( " + item.kode_mata_kuliah + " )",
                                    id: item.id_matkul
                                }
                            })
                        };
                    },
                }
            });
        }
    });
</script>
@endpush
