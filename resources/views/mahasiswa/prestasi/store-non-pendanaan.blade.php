@extends('layouts.mahasiswa')
@section('title')
Dosen Kelas Perkuliahan
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Tambah Prestasi Non Pendanaan</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Prestasi Mahasiswa</li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('mahasiswa.prestasi.prestasi-non-pendanaan')}}">Prestasi Non Pendanaan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Prestasi Non Pendanaan</li>
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
                <form class="form" action="{{route('mahasiswa.prestasi.prestasi-non-pendanaan.store')}}" id="tambah-prestasi-non-pendanaan" method="POST">
                    @csrf
                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Data Mahasiswa</h4>
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
                                    value="{{$data->nama_mahasiswa}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nim" class="form-label">NIM Mahasiswa</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nim"
                                    id="nim"
                                    aria-describedby="helpId"
                                    value="{{$data->nim}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="prodi" class="form-label">Program Studi</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="prodi"
                                    id="prodi"
                                    aria-describedby="helpId"
                                    value="{{$data->nama_program_studi}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <h4 class="text-info mb-0"><i class="fa fa-user"></i> Prestasi Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="prestasi-fields">
                                <div class="prestasi-field row">
                                    <div class="col-md-2 mb-2">
                                        <label for="nama_prestasi" class="form-label">Nama Prestasi</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="nama_prestasi[]"
                                            id="nama_prestasi"
                                            aria-describedby="helpId"
                                            value=""
                                            placeholder="Masukkan Nama Prestasi"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label for="evaluasi" class="form-label">Jenis Prestasi</label>
                                        <select class="form-select" name="jenis_prestasi[]" id="jenis_prestasi" required>
                                            <option value="">Pilih Jenis Prestasi</option>
                                            @foreach($jenis_prestasi as $j)
                                                <option value="{{$j->id_jenis_prestasi}}">{{$j->nama_jenis_prestasi}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label for="evaluasi" class="form-label">Tingkat Prestasi</label>
                                        <select class="form-select" name="tingkat_prestasi[]" id="tingkat_prestasi" required>
                                            <option value="">Pilih Tingkat Prestasi</option>
                                            @foreach($tingkat_prestasi as $t)
                                                <option value="{{$t->id_tingkat_prestasi}}">{{$t->nama_tingkat_prestasi}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label for="tahun_prestasi" class="form-label">Tahun Prestasi</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            name="tahun_prestasi[]"
                                            id="tahun_prestasi"
                                            aria-describedby="helpId"
                                            value=""
                                            placeholder="Masukkan Tahun Pelaksanaan Lomba"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label for="penyelenggara" class="form-label">Penyelenggara</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="penyelenggara[]"
                                            id="penyelenggara"
                                            aria-describedby="helpId"
                                            value=""
                                            placeholder="Masukkan Instansi / Organisasi Penyelenggara"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-1 mb-2">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-rounded btn-sm remove-prestasi form-control" style="display: none;" title="Hapus Prestasi"><i class="fa fa-user-times" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                            <!-- <p>*Prestasi Harus di Buktikan Sertifikat atau Foto Kegiatan</p> -->
                            <button id="add-prestasi" type="button" class="btn btn-primary" title="Tambah Prestasi"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('mahasiswa.prestasi.prestasi-non-pendanaan')}}" class="btn btn-danger waves-effect waves-light">
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
<script>
    $(document).ready(function(){
        // Event listener for adding a new row
        $('#add-prestasi').click(function() {
            var newRow = $('<div class="prestasi-field row">'+
                                    '<div class="col-md-2 mb-2">' +
                                        '<label for="nama_prestasi" class="form-label">Nama Prestasi</label>'+
                                        '<input type="text" class="form-control" name="nama_prestasi[]" id="nama_prestasi" aria-describedby="helpId" value="" placeholder="Masukkan Nama Prestasi" required/>'+
                                    '</div>'+
                                    '<div class="col-md-2 mb-2">'+
                                        '<label for="evaluasi" class="form-label">Jenis Prestasi</label>'+
                                        '<select class="form-select" name="jenis_prestasi[]" id="jenis_prestasi" required>'+
                                            '<option value="">-- Pilih Jenis Prestasi --</option>'+
                                            '@foreach($jenis_prestasi as $j)'+
                                                '<option value="{{$j->id_jenis_prestasi}}">{{$j->nama_jenis_prestasi}}</option>'+
                                            '@endforeach'+
                                        '</select>'+
                                    '</div>'+
                                    '<div class="col-md-2 mb-2">'+
                                        '<label for="evaluasi" class="form-label">Tingkat Prestasi</label>'+
                                        '<select class="form-select" name="tingkat_prestasi[]" id="tingkat_prestasi" required>'+
                                            '<option value="">-- Pilih Tingkat Prestasi --</option>'+
                                            '@foreach($tingkat_prestasi as $t)'+
                                                '<option value="{{$t->id_tingkat_prestasi}}">{{$t->nama_tingkat_prestasi}}</option>'+
                                            '@endforeach'+
                                        '</select>'+
                                    '</div>'+
                                    '<div class="col-md-2 mb-2">'+
                                        '<label for="tahun_prestasi" class="form-label">Tahun Prestasi</label>'+
                                        '<input type="number" class="form-control" name="tahun_prestasi[]" id="tahun_prestasi" aria-describedby="helpId" value="" placeholder="Masukkan Tahun Pelaksanaan Lomba" required/>'+
                                    '</div>'+
                                    '<div class="col-md-3 mb-2">'+
                                        '<label for="penyelenggara" class="form-label">Penyelenggara</label>'+
                                        '<input type="number" class="form-control" name="penyelenggara[]" id="penyelenggara" aria-describedby="helpId" value="" placeholder="Masukkan Instansi / Organisasi Penyelenggara" required/>'+
                                    '</div>'+
                                    '<div class="col-md-1 mb-2">'+
                                        '<label class="form-label">&nbsp;</label>'+
                                        '<button type="button" class="btn btn-danger btn-rounded btn-sm remove-prestasi form-control" style="display: none;" title="Hapus Prestasi"><i class="fa fa-user-times" aria-hidden="true"></i></button>'+
                                    '</div>' +
                                '</div>');

            // Append the new row
            newRow.appendTo('#prestasi-fields');

            // Show the remove button
            newRow.find('.remove-prestasi').show();
        });

        // Event listener for removing a row
        $(document).on('click', '.remove-prestasi', function() {
            $(this).closest('.prestasi-field').remove();
        });

        $('#tambah-prestasi-non-pendanaan').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Pelaporan Prestasi Non Pendanaan',
                text: "Apakah anda yakin ingin?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            },function(isConfirmed){
                if (isConfirmed) {
                    $('#tambah-prestasi-non-pendanaan').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });

</script>
@endpush
