@extends('layouts.prodi')
@section('title')
Edit Kelas Perkuliahan
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Edit Kelas Perkuliahan</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Akademik</li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('prodi.data-akademik.kelas-penjadwalan')}}">Kelas dan Penjadwalan</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('prodi.data-akademik.kelas-penjadwalan.detail', ['id_matkul' => $kelas->id_matkul])}}">Detail Kelas dan Penjadwalan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Kelas Perkuliahan</li>
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
                <form class="form" action="{{route('prodi.data-akademik.kelas-penjadwalan.update', ['id_matkul' => $kelas->id_matkul, 'id_kelas' => $kelas->id_kelas_kuliah])}}" id="edit-kelas" method="POST">
                    @csrf
                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Detail Kelas Kuliah</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nama_mata_kuliah" class="form-label">Nama Mata Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mata_kuliah"
                                    id="nama_mata_kuliah"
                                    aria-describedby="helpId"
                                    value="{{$kelas->kode_mata_kuliah.' - '.$kelas->nama_mata_kuliah}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nama_mata_kuliah" class="form-label">Nama Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mata_kuliah"
                                    id="nama_mata_kuliah"
                                    aria-describedby="helpId"
                                    value="{{$kelas->nama_kelas_kuliah}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nama_mata_kuliah" class="form-label">Ruang Perkuliahan</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mata_kuliah"
                                    id="nama_mata_kuliah"
                                    aria-describedby="helpId"
                                    value="{{$kelas->nama_ruang}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Mulai Efektif</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                                        <input type="text" class="form-control" name="tanggal_mulai" id="tanggal_mulai" aria-describedby="helpId" placeholder="" required value="{{ $kelas ? $kelas->tanggal_mulai_efektif : '' }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Akhir Efektif</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                                        <input type="text" class="form-control" name="tanggal_akhir" id="tanggal_akhir" aria-describedby="helpId" placeholder="" required value="{{ $kelas ? $kelas->tanggal_akhir_efektif : '' }}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-4">
                                <label for="kapasitas_kelas" class="form-label">Kapasitas Kelas Kuliah</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    name="kapasitas_kelas"
                                    id="kapasitas_kelas"
                                    aria-describedby="helpId"
                                    placeholder="Masukkan Kapasitas Kelas Kuliah"
                                    value="$kelas->kapasitas"
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-4">
                                <label for="mode_kelas" class="form-label">Mode Kelas Kuliah</label>
                                <select class="form-select" name="mode_kelas" id="mode_kelas" required>
                                    <option value="{{$kelas->mode}}">{{$kelas->mode = 'O' ? 'Online' : $kelas->mode = 'F' ? 'Offline' : $kelas->mode = 'M' ? 'Campuran' : ''}}</option>
                                    <option value="">-- Pilih Mode Kelas --</option>
                                    <option value="O">Online</option>
                                    <option value="F">Offline</option>
                                    <option value="M">Campuran</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-4">
                                <label for="lingkup_kelas" class="form-label">Lingkup Kelas Kuliah</label>
                                <select class="form-select" name="lingkup_kelas" id="lingkup_kelas" required>
                                    <option value="{{$kelas->mode}}">{{$kelas->lingkup = '1' ? 'Internal' : $kelas->lingkup = '2' ? 'External' : $kelas->lingkup = '3' ? 'Campuran' : ''}}</option>
                                    <option value="">-- Pilih Lingkup Kelas --</option>
                                    <option value="1">Internal</option>
                                    <option value="2">External</option>
                                    <option value="3">Campuran</option>
                                </select>
                            </div>
                        </div>
                        <h4 class="text-info mb-0"><i class="fa fa-calendar-o"></i> Jadwal Kelas Kuliah</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="jadwal_hari" class="form-label">Jadwal Hari</label>
                                <select class="form-select" name="jadwal_hari" id="jadwal_hari" required>
                                    <option value="{{$kelas->jadwal_hari}}">{{$kelas->jadwal_hari}}</option>
                                    <option value="">-- Pilih Hari --</option>
                                    <option value="SENIN">SENIN</option>
                                    <option value="SELASA">SELASA</option>
                                    <option value="RABU">RABU</option>
                                    <option value="KAMIS">KAMIS</option>
                                    <option value="JUMAT">JUMAT</option>
                                    <option value="SABTU">SABTU</option>
                                    <option value="MINGGU">MINGGU</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Jam Mulai Kelas</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-clock"></i></span>
                                        <input type="text" class="form-control" name="jam_mulai" id="jam_mulai" aria-describedby="helpId" placeholder="" required value="{{ $kelas ? $kelas->jadwal_jam_mulai : '' }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Jam Selesai Kelas</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-clock"></i></span>
                                        <input type="text" class="form-control" name="jam_selesai" id="jam_selesai" aria-describedby="helpId" placeholder="" required value="{{ $kelas ? $kelas->jadwal_jam_selesai : '' }}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('prodi.data-akademik.kelas-penjadwalan.detail', ['id_matkul' => $kelas->id_matkul])}}" class="btn btn-danger waves-effect waves-light">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                    </div>
                </form>
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
<script src="{{asset('assets/js/flatpickr/flatpickr.js')}}"></script>
<script>

$(function () {
        "use strict";

        $('#id_semester').select2({
            placeholder: 'Pilih Semester',
            allowClear: true,
            width: '100%',
        });

        flatpickr("#tanggal_mulai", {
            dateFormat: "d-m-Y",
        });

        flatpickr("#tanggal_akhir", {
            dateFormat: "d-m-Y",
        });

        flatpickr("#jam_mulai", {
            dateFormat: "H:i:s",
        });

        flatpickr("#jam_selesai", {
            dateFormat: "H:i:s",
        });

        $('#edit-kelas').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Ubah Kelas Kuliah',
                text: "Apakah anda yakin ingin merubah detail kelas?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $('#edit-kelas').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });
</script>
@endpush
