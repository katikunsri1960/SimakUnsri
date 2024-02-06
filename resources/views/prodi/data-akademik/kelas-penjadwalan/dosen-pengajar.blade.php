@extends('layouts.prodi')
@section('title')
Dosen Kelas Perkuliahan
@endsection
@section('content')
@include('swal')
@php
    $id_matkul = $kelas[0]['id_matkul'];
@endphp
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Tambah Kelas Perkuliahan</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Akademik</li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('prodi.data-akademik.kelas-penjadwalan')}}">Kelas dan Penjadwalan</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('prodi.data-akademik.kelas-penjadwalan.detail', ['id_matkul' => $id_matkul])}}">Detail Kelas dan Penjadwalan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dosen Pengajar Kelas</li>
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
                <form class="form" action="{{route('prodi.data-akademik.kelas-penjadwalan.store', ['id_matkul' => $id_matkul])}}" id="tambah-dosen-pengajar" method="POST">
                    @csrf
                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Detail Kelas Kuliah</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nama_mata_kuliah" class="form-label">Nama Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mata_kuliah"
                                    id="nama_mata_kuliah"
                                    aria-describedby="helpId"
                                    value="{{$kelas[0]['nama_kelas_kuliah']}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="tgl_mulai" class="form-label">Tanggal Mulai Efektif Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="tgl_mulai"
                                    id="tgl_mulai"
                                    aria-describedby="helpId"
                                    value="{{$kelas[0]['tanggal_mulai_efektif']}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="tgl_akhir" class="form-label">Tanggal Akhir Efektif Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="tgl_akhir"
                                    id="tgl_akhir"
                                    aria-describedby="helpId"
                                    value="{{$kelas[0]['tanggal_akhir_efektif']}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nama_mata_kuliah" class="form-label">Nama Mata Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mata_kuliah"
                                    id="nama_mata_kuliah"
                                    aria-describedby="helpId"
                                    value="{{$kelas[0]['kode_mata_kuliah'].' - '.$kelas[0]['nama_mata_kuliah']}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="nama_mata_kuliah" class="form-label">Nama Ruang Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mata_kuliah"
                                    id="nama_mata_kuliah"
                                    aria-describedby="helpId"
                                    value="{{$kelas[0]['nama_ruang'].' - '.$kelas[0]['lokasi']}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="mode_kelas" class="form-label">Mode Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="mode_kelas"
                                    id="mode_kelas"
                                    aria-describedby="helpId"
                                    value="@if($kelas[0]['mode'] == 'O'){{'Online'}}@elseif($kelas[0]['mode'] == 'F'){{'Offline'}}@elseif($kelas[0]['mode'] == 'M'){{'Campuran'}}@else{{'Mode Tidak Terdata'}}@endif"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="lingkup_kelas" class="form-label">Lingkup Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="lingkup_kelas"
                                    id="lingkup_kelas"
                                    aria-describedby="helpId"
                                    value="@if($kelas[0]['lingkup'] == '1'){{'Internal'}}@elseif($kelas[0]['mode'] == '2'){{'External'}}@elseif($kelas[0]['mode'] == '3'){{'Campuran'}}@else{{'Lingkup Tidak Terdata'}}@endif"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="jadwal_kelas" class="form-label">Jadwal Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="jadwal_kelas"
                                    id="jadwal_kelas"
                                    aria-describedby="helpId"
                                    value="{{$kelas[0]['jadwal_hari'].' ('.$kelas[0]['jadwal_jam_mulai'].' - '.$kelas[0]['jadwal_jam_selesai'].')'}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <h4 class="text-info mb-0"><i class="fa fa-user"></i> Dosen Pengajar Kelas</h4>
                        <hr class="my-15">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label">Dosen Pengajar 1</label>
                                    <div class="mb-2">
                                        <select class="form-select" name="jam_mulai" id="jam_mulai" required>
                                            <option value="">-- Pilih Dosen 1 --</option>
                                            @foreach($dosen as $d)
                                                <option value="{{$d->id_dosen}}">{{$d->nama_dosen}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label">Dosen Pengajar 2</label>
                                    <div class="mb-2">
                                        <select class="form-select" name="jam_selesai" id="jam_selesai">
                                            <option value="">-- Pilih Dosen 2 --</option>
                                            @foreach($dosen as $d)
                                                <option value="{{$d->id_dosen}}">{{$d->nama_dosen}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label">Dosen Pengajar 3</label>
                                    <div class="mb-2">
                                        <select class="form-select" name="jam_selesai" id="jam_selesai">
                                            <option value="">-- Pilih Dosen 3 --</option>
                                            @foreach($dosen as $d)
                                                <option value="{{$d->id_dosen}}">{{$d->nama_dosen}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('prodi.data-akademik.kelas-penjadwalan.detail', ['id_matkul' => $id_matkul])}}" class="btn btn-danger waves-effect waves-light">
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
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>

    $('#tambah-dosen-pengajar').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Manajemen Dosen Kelas Kuliah',
            text: "Apakah anda yakin ingin menambahkan dosen pengajar?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#tambah-kelas').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });

    function upperCaseF(a){
        setTimeout(function(){
            a.value = a.value.toUpperCase();
        }, 1);
    }
    
</script>
@endpush
