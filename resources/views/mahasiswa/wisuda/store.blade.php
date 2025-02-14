@extends('layouts.mahasiswa')
@section('title')
Pendaftaran Wisuda Mahasiswa
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Pendaftaran Wisuda Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('mahasiswa.wisuda.index')}}">Wisuda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pendaftaran Wisuda Mahasiswa</li>
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
                <form class="form" action="{{route('mahasiswa.wisuda.store')}}" id="tambah-wisuda" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Data Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="row form-group">
                            <div class=" col-lg-6 mb-3">
                                <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
                                <input type="text" class="form-control" name="nama_mahasiswa" id="nama_mahasiswa" aria-describedby="helpId"
                                    value="{{$data->nama_mahasiswa}}" disabled required/>
                            </div>
                            <div class=" col-lg-6 mb-3">
                                <label for="nim" class="form-label">NIM Mahasiswa</label>
                                <input type="text"class="form-control"name="nim"id="nim"aria-describedby="helpId"
                                    value="{{$data->nim}}" disabled required/>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class=" col-lg-6 mb-3">
                                <label for="fakultas" class="form-label">Fakultas</label>
                                <input type="text" class="form-control" name="fakultas" id="fakultas" aria-describedby="helpId"
                                    value="{{$data->prodi->fakultas->nama_fakultas}}" disabled required/>
                            </div>
                            <div class=" col-lg-6 mb-3">
                                <label for="jurusan" class="form-label">Jurusan</label>
                                <input type="text"class="form-control"name="jurusan"id="jurusan"aria-describedby="helpId"
                                    value="{{$data->prodi->jurusan->nama_jurusan_id}}" disabled required/>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class=" col-lg-6 mb-3">
                                <label for="jenjang_pendidikan" class="form-label">Program Pendidikan</label>
                                <input type="text" class="form-control" name="jenjang_pendidikan" id="jenjang_pendidikan" aria-describedby="helpId"
                                    value="{{$data->prodi->nama_jenjang_pendidikan}}" disabled required/>
                            </div>
                            <div class=" col-lg-6 mb-3">
                                <label for="prodi" class="form-label">Program Studi</label>
                                <input type="text"class="form-control"name="prodi"id="prodi"aria-describedby="helpId"
                                    value="{{$data->prodi->nama_program_studi}}" disabled required/>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class=" col-lg-6 mb-3">
                                <label for="semester" class="form-label">Semester</label>
                                <input type="text" class="form-control" name="semester" id="semester" aria-describedby="helpId"
                                    value="{{$semester_aktif->semester->nama_semester}}" disabled required/>
                            </div>
                            {{-- <div class=" col-lg-6 mb-3">
                                <label for="nim" class="form-label">Program Studi</label>
                                <input type="text"class="form-control"name="nim"id="nim"aria-describedby="helpId"
                                    value="{{$data->prodi->nama_program_studi}}" disabled required/>
                            </div> --}}
                        </div>
                        <div class="row form-group">
                            <h4 class="mt-10">Alamat</h4>
                            <div class=" col-lg-12 mb-3">
                                <label for="jalan" class="form-label">Jalan</label>
                                <input type="text" class="form-control" name="jalan" id="jalan" aria-describedby="helpId"
                                    value="{{$data->biodata->jalan}}" required/>
                            </div>
                            <div class=" col-lg-4 mb-3">
                                <label for="dusun" class="form-label">Dusun</label>
                                <input type="text"class="form-control"name="dusun"id="dusun"aria-describedby="helpId"
                                    value="{{$data->biodata->dusun}}"/>
                            </div>
                            <div class=" col-lg-2 mb-3">
                                <label for="rt" class="form-label">RT</label>
                                <input type="text" class="form-control" name="rt" id="rt" aria-describedby="helpId"
                                    value="{{$data->biodata->rt}}" required/>
                            </div>
                            <div class=" col-lg-2 mb-3">
                                <label for="rw" class="form-label">RW</label>
                                <input type="text" class="form-control" name="rw" id="rw" aria-describedby="helpId"
                                    value="{{$data->biodata->rw}}" required/>
                            </div>
                            <div class=" col-lg-4 mb-3">
                                <label for="kelurahan" class="form-label">Kelurahan</label>
                                <input type="text"class="form-control"name="kelurahan"id="kelurahan"aria-describedby="helpId"
                                    value="{{$data->biodata->kelurahan}}" required/>
                            </div>
                            <div class=" col-lg-4 mb-3">
                                <label for="kode_pos" class="form-label">Kode Pos</label>
                                <input type="text" class="form-control" name="kode_pos" id="kode_pos" aria-describedby="helpId"
                                    value="{{$data->biodata->kode_pos}}"/>
                            </div>
                            <div class=" col-lg-8 mb-3">
                                <label for="nama_wilayah" class="form-label">Kecamatan / Kabupaten / Provinsi</label>
                                <input type="text"class="form-control"name="nama_wilayah"id="nama_wilayah"aria-describedby="helpId"
                                    value="{{$data->biodata->nama_wilayah}}" required/>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="handphone" class="form-label">No. Telp/HP</label>
                                <input type="text"class="form-control"name="handphone"id="handphone"aria-describedby="helpId"
                                    value="{{$data->biodata->handphone}}" required/>
                            </div>
                        </div>
                        
                        <h4 class="text-info mb-0 mt-40"><i class="fa fa-user"></i> Pengajuan Cuti Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="cuti-fields">
                                <div class="cuti-field row">
                                    <div class="col-lg-12 mb-2">
                                        <label for="alasan_cuti" class="form-label">Alasan Pengajuan Cuti</label>
                                        <input type="text" class="form-control" name="alasan_cuti" id="alasan_cuti"
                                            aria-describedby="helpId" placeholder="Masukkan Alasan Pengajuan Cuti" required />
                                    </div>
                                </div>
                                <div class="cuti-field row">
                                    <div class="col-md-12 mb-2">
                                        <label for="file_pendukung" class="form-label">File Pendukung (.pdf)</label>
                                        <input type="file" class="form-control" name="file_pendukung" id="file_pendukung"
                                            aria-describedby="fileHelpId" accept=".pdf" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('mahasiswa.wisuda.index')}}" class="btn btn-danger waves-effect waves-light">
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
        $('#tambah-wisuda').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Pelaporan Pengajuan Cuti',
                text: "Apakah anda yakin ingin?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            },function(isConfirmed){
                if (isConfirmed) {
                    $('#tambah-wisuda').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });

</script>
@endpush
