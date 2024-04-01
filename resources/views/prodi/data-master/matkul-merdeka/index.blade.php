@extends('layouts.prodi')
@section('title')
Mata Kuliah
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Master Mata Kuliah Kampus Merdeka</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Mata Kuliah Kampus Merdeka</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>

<section class="content">
    @include('swal')
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-success waves-effect waves-light"  data-bs-toggle="modal"
                        data-bs-target="#kampusMerdeka"><i class="fa fa-plus"></i> Tambah MK Kampus Merdeka</button>
                    </div>
                    @include('prodi.data-master.matkul-merdeka.create')
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">KODE MK</th>
                                <th class="text-center align-middle">NAMA MK</th>
                                <th class="text-center align-middle">SKS</th>
                                <th class="text-center align-middle">AKSI</th>
                             </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $d)
                            <tr>
                                <td class="text-center align-middle">{{$loop->iteration}}</td>
                                <td class="text-center align-middle">{{$d->matkul->kode_mata_kuliah}}</td>
                                <td class="text-center align-middle">{{$d->matkul->nama_mata_kuliah}}</td>
                                <td class="text-center align-middle">{{$d->matkul->sks_mata_kuliah}}</td>
                                <td class="text-center align-middle"></td>
                            </tr>
                            @endforeach
                          </tbody>
                      </table>
                      </div>
                </div>

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
        $('#matkulTambah').select2({
            placeholder: "-- Pilih Mata Kuliah --",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#kampusMerdeka')
        });

        $('#masukForm').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Apakah Anda Yakin??',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $('#masukForm').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });

    });
</script>
@endpush
