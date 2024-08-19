@extends('layouts.universitas')
@section('title')
Semester Aktif
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Semester Aktif</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Pengaturan</li>
                        <li class="breadcrumb-item active" aria-current="page">Semester Aktif</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
@include('swal')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-body">
                    <form action="{{route('univ.pengaturan.semester-aktif.store')}}" method="post" id="form-store">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="id_semester" class="form-label">Semester</label>
                                <select class="form-select" name="id_semester" id="id_semester" required>
                                    <option value="">-- Pilih Semester --</option>
                                    @foreach ($semester as $s)
                                    <option value="{{$s->id_semester}}" {{ $data && $data->id_semester == $s->id_semester ? 'selected' : '' }}>{{$s->nama_semester}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="krs_mulai" class="form-label">Tanggal Mulai KRS</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" name="krs_mulai" id="krs_mulai" aria-describedby="helpId" placeholder="" required value="{{ $data ? $data->krs_mulai : '' }}"/>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="krs_selesai" class="form-label">Tanggal Akhir KRS</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" name="krs_selesai" id="krs_selesai" aria-describedby="helpId" placeholder="" required value="{{ $data ? $data->krs_selesai : '' }}"/>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="batas_isi_nilai" class="form-label">Batas Pengisian Nilai</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" name="batas_isi_nilai" id="batas_isi_nilai" aria-describedby="helpId" placeholder="" required value="{{ $data ? $data->id_batas_isi_nilai : '' }}"/>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="batas_bayar_ukt" class="form-label">Batas Bayar UKT</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" name="batas_bayar_ukt" id="batas_bayar_ukt" aria-describedby="helpId" placeholder="" required value="{{ $data ? $data->batas_bayar_ukt : '' }}"/>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="tanggal_mulai_kprs" class="form-label">Tanggal Mulai KPRS</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" name="tanggal_mulai_kprs" id="tanggal_mulai_kprs" aria-describedby="helpId" placeholder="" required value="{{ $data ? $data->id_tanggal_mulai_kprs : '' }}"/>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="tanggal_akhir_kprs" class="form-label">Tanggal Akhir KPRS</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" name="tanggal_akhir_kprs" id="tanggal_akhir_kprs" aria-describedby="helpId" placeholder="" required value="{{ $data ? $data->id_tanggal_akhir_kprs : '' }}"/>
                                </div>
                            </div>
                        </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label class="form-label" style="opacity: 0;">Submit</label> <!-- Invisible label for alignment -->
                                <button type="submit" class="form-control btn btn-sm btn-primary waves-effect waves-light">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('assets/js/flatpickr/flatpickr.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/js/flatpickr/flatpickr.js')}}"></script>

<script>
    $(function () {
        "use strict";

        $('#id_semester').select2({
            placeholder: 'Pilih Semester',
            allowClear: true,
            width: '100%',
        });

        flatpickr("#krs_selesai", {
            dateFormat: "d-m-Y",
        });

        flatpickr("#krs_mulai", {
            dateFormat: "d-m-Y",
        });

        flatpickr("#batas_isi_nilai", {
            dateFormat: "d-m-Y",
        });

        flatpickr("#tanggal_mulai_kprs", {
            dateFormat: "d-m-Y",
        });

        flatpickr("#tanggal_akhir_kprs", {
            dateFormat: "d-m-Y",
        });

        flatpickr("#batas_bayar_ukt", {
            dateFormat: "d-m-Y",
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
