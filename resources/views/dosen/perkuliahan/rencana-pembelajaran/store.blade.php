@extends('layouts.dosen')
@section('title')
Rencana Pembelajaran Semester
@endsection
@section('content')
@include('swal')
@php
    $id_matkul = $matkul->id_matkul;
@endphp
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Tambah Rencana Pembelajaran</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dosen.perkuliahan.rencana-pembelajaran')}}">Rencana Pembelajaran</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dosen.perkuliahan.rencana-pembelajaran.detail', ['matkul' => $id_matkul])}}">Detail Rencana Pembelajaran</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Rencana Pembelajaran</li>
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
                <form class="form" action="{{route('dosen.perkuliahan.rencana-pembelajaran.store', ['matkul' => $id_matkul])}}" id="tambah-rencana-pembelajaran" method="POST">
                    @csrf
                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-book"></i> Detail Mata Kuliah</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="kode_mata_kuliah" class="form-label">Kode Mata Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="kode_mata_kuliah"
                                    id="kode_mata_kuliah"
                                    aria-describedby="helpId"
                                    value="{{$matkul->kode_mata_kuliah}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="nama_mata_kuliah" class="form-label">Nama Mata Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mata_kuliah_kelas"
                                    id="nama_mata_kuliah"
                                    aria-describedby="helpId"
                                    value="{{$matkul->nama_mata_kuliah}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <h4 class="text-info mb-0"><i class="fa fa-pencil-square-o"></i> Rencana Pembelajaran Semester</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="link_rps" class="form-label">Link Rencana Pembelajaran Semester</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="link_rps"
                                    id="link_rps"
                                    aria-describedby="helpId"
                                    value="{{$matkul->link_rps}}"
                                    placeholder="Masukkan Link Repository Rencana Pembelajaran Semester"
                                    required
                                />
                            </div>
                        </div>
                        <hr>
                        @if ($rps->count() > 0)
                        <div class="row my-3 p-3">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">Pertemuan</th>
                                        <th class="text-center align-middle">Materi Indonesia</th>
                                        <th class="text-center align-middle">Materi Inggris</th>
                                        <th class="text-center align-middle">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rps as $d)
                                        <tr>
                                            <td class="text-center align-middle">{{$d->pertemuan}}</td>
                                            <td class="text-start align-middle">{{$d->materi_indonesia}}</td>
                                            <td class="text-start align-middle">{{$d->materi_inggris}}</td>
                                            <td class="text-center align-middle">@if($d->approved == 0)
                                                <span class="badge badge-danger">Belum di Setujui<span>
                                                @elseif($d->approved == 1)
                                                    <span class="badge badge-success">Sudah di Setujui<span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        @endif
                        <div class="form-group mt-2">
                            <div id="rps-fields">
                                @if(old('pertemuan'))
                                    @foreach(old('pertemuan') as $index => $pertemuan)
                                        <div class="rps-field row">
                                            <div class="col-md-1 mb-2">
                                                <label for="pertemuan" class="form-label">Pertemuan Ke -</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="pertemuan[]"
                                                    id="pertemuan"
                                                    aria-describedby="helpId"
                                                    value="{{ old('pertemuan.' . $index) }}"
                                                    required
                                                />
                                            </div>
                                            <div class="col-md-5 mb-2">
                                                <label for="materi_indo" class="form-label">Materi Indonesia</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="materi_indo[]"
                                                    id="materi_indo"
                                                    aria-describedby="helpId"
                                                    value="{{ old('materi_indo.' . $index) }}"
                                                    placeholder="Masukkan Materi Dalam Bahasa Indonesia"
                                                    required
                                                />
                                            </div>
                                            <div class="col-md-5 mb-2">
                                                <label for="materi_inggris" class="form-label">Materi Inggris</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="materi_inggris[]"
                                                    id="materi_inggris"
                                                    aria-describedby="helpId"
                                                    value="{{ old('materi_inggris.' . $index) }}"
                                                    placeholder="Masukkan Materi Dalam Bahasa Inggris"
                                                    required
                                                />
                                            </div>
                                            <div class="col-md-1 mb-2">
                                                <label class="form-label">&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-rounded btn-sm remove-rps form-control" title="Hapus RPS"><i class="fa fa-user-times" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="rps-field row">
                                        <div class="col-md-1 mb-2">
                                            <label for="pertemuan" class="form-label">Pertemuan Ke -</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="pertemuan[]"
                                                id="pertemuan"
                                                aria-describedby="helpId"
                                                value=""
                                                required
                                            />
                                        </div>
                                        <div class="col-md-5 mb-2">
                                            <label for="materi_indo" class="form-label">Materi Indonesia</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="materi_indo[]"
                                                id="materi_indo"
                                                aria-describedby="helpId"
                                                value=""
                                                placeholder="Masukkan Materi Dalam Bahasa Indonesia"
                                                required
                                            />
                                        </div>
                                        <div class="col-md-5 mb-2">
                                            <label for="materi_inggris" class="form-label">Materi Inggris</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="materi_inggris[]"
                                                id="materi_inggris"
                                                aria-describedby="helpId"
                                                value=""
                                                placeholder="Masukkan Materi Dalam Bahasa Inggris"
                                                required
                                            />
                                        </div>
                                        <div class="col-md-1 mb-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm remove-rps form-control" style="display: none;" title="Hapus RPS"><i class="fa fa-user-times" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button id="add-rps" type="button" class="btn btn-primary" title="Tambah RPS"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('dosen.perkuliahan.rencana-pembelajaran.detail', ['matkul' => $id_matkul])}}" class="btn btn-danger waves-effect waves-light">
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

        // Event listener for adding a new row
        $('#add-rps').click(function() {
            var newRow = $('<div class="rps-field row">' +
                                '<div class="col-md-1 mb-2">' +
                                    '<label for="pertemuan" class="form-label">Pertemuan Ke -</label>' +
                                    '<input type="text" class="form-control" name="pertemuan[]" id="pertemuan" aria-describedby="helpId" value="" required/>' +
                                '</div>' +
                                '<div class="col-md-5 mb-2">' +
                                   ' <label for="materi_indo" class="form-label">Materi Indonesia</label>' +
                                    '<input type="text" class="form-control" name="materi_indo[]" id="materi_indo" aria-describedby="helpId" value="" placeholder="Masukkan Materi Dalam Bahasa Indonesia" required/>' +
                                '</div>' +
                                '<div class="col-md-5 mb-2">' +
                                    '<label for="materi_inggris" class="form-label">Materi Inggris</label>' +
                                    '<input type="text" class="form-control" name="materi_inggris[]" id="materi_inggris" aria-describedby="helpId" value="" placeholder="Masukkan Materi Dalam Bahasa Inggris" required/>' +
                                '</div>' +
                                '<div class="col-md-1 mb-2">' +
                                    '<label class="form-label">&nbsp;</label>' +
                                    '<button type="button" class="btn btn-danger btn-rounded btn-sm remove-rps form-control" style="display: none;" title="Hapus RPS"><i class="fa fa-user-times" aria-hidden="true"></i></button>' +
                                '</div>' +
                            '</div>');

            // Append the new row
            newRow.appendTo('#rps-fields');

            // Show the remove button
            newRow.find('.remove-rps').show();
        });

        // Event listener for removing a row
        $(document).on('click', '.remove-rps', function() {
            $(this).closest('.rps-field').remove();
        });

        $('#tambah-rencana-pembelajaran').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Menambahkan RPS Mata Kuliah',
                text: "Apakah anda yakin ingin menambahkan RPS?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            },function(isConfirmed){
                if (isConfirmed) {
                    $('#tambah-rencana-pembelajaran').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });

</script>
@endpush


