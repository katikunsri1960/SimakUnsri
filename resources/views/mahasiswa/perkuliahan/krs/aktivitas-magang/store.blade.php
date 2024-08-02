@extends('layouts.mahasiswa')
@section('title')
Pengajuan Aktivitas Magang
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Tambah Aktivitas Magang</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('mahasiswa.perkuliahan.aktivitas-magang.index')}}">Aktivitas Magang</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Aktivitas Magang</li>
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
                <form class="form" action="{{route('mahasiswa.perkuliahan.aktivitas-magang.store')}}" id="tambah-aktivitas-magang" method="POST" enctype="multipart/form-data">
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
                        <h4 class="text-info mb-0 mt-40"><i class="fa fa-user"></i> Aktivitas Magang Kampus Merdeka</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="magang-fields">
                                <div class="magang-field row">
                                    <div class="col-lg-12 mb-2">
                                        <label for="nama_instansi" class="form-label">Nama Instansi</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="nama_instansi"
                                            id="nama_instansi"
                                            aria-describedby="helpId"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="magang-field row">
                                    <div class="col-lg-12 mb-2">
                                        <label for="lokasi" class="form-label">Nama Kota</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="lokasi"
                                            id="lokasi"
                                            aria-describedby="helpId"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('mahasiswa.perkuliahan.aktivitas-magang.index')}}" class="btn btn-danger waves-effect waves-light">
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
        $('#tambah-aktivitas-magang').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Tambah Aktivitas magang',
                text: "Apakah anda yakin ingin?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            },function(isConfirmed){
                if (isConfirmed) {
                    $('#tambah-aktivitas-magang').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });

</script>
@endpush
