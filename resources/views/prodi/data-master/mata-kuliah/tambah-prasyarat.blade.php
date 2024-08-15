@extends('layouts.prodi')
@section('title')
Tambah Prasyarat
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Tambah Prasyarat Mata Kuliah</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Master</li>
                        <li class="breadcrumb-item" aria-current="page"><a
                                href="{{route('prodi.data-master.mata-kuliah')}}">Mata Kuliah</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Prasyarat</li>
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
                <div class="box-header with-border">
                    <h3>PRASYARAT : {{$matkul->kode_mata_kuliah}} - {{$matkul->nama_mata_kuliah}} </h3>
                </div>
                <div class="box-body">
                    <form action="{{route('prodi.data-master.mata-kuliah.store-prasyarat', ['matkul' => $matkul])}}" method="post" id="form-store">
                        @csrf
                        <div class="mb-3">
                            <label for="" class="form-label">Mata Kuliah Prasyarat</label>
                            <select multiple class="form-select form-select-lg" name="prasyarat[]" id="prasyarat">
                                @foreach ($prasyarat->mata_kuliah as $m)
                                @if ($m->id_matkul != $matkul->id_matkul)
                                <option value="{{$m->id_matkul}}">({{$m->kode_mata_kuliah}}) - {{$m->nama_mata_kuliah}}
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-success" type="submit">Simpan</button>
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
    $(function() {
        "use strict";

        $('#data').DataTable();
        $('#prasyarat').select2({
            placeholder: 'Pilih Prasyarat',
            allowClear: true
        });

        $('#form-store').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Apakah anda yakin?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $('#spinner').show();
                    $('#form-store').unbind('submit').submit();
                }
            });
        });
    });
</script>
@endpush
