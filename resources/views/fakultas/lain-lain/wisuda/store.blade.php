@extends('layouts.mahasiswa')
@section('title')
Pendaftaran Wisuda Mahasiswa
@endsection
@section('content')
@push('header')
<div class="mx-4">
    <a href="{{route('fakultas.wisuda.index')}}"
        class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Pendaftaran Wisuda Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('fakultas.wisuda.index')}}">Wisuda</a></li>
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
                <form class="form" action="{{route('fakultas.wisuda.store')}}" id="tambah-wisuda" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-user"></i> Data Diri Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="data-wisuda-field row">
                            <div class=" col-lg-6 mb-3">
                                <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
                                <input type="text" class="form-control" name="nama_mahasiswa" id="nama_mahasiswa" aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->nama_mahasiswa}}" disabled required/>
                            </div>
                            <div class=" col-lg-6 mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text"class="form-control"name="nik"id="nik"aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->nik}}" required/>
                            </div>
                        </div>
                        
                        <h4 class="text-info mb-10 mt-20">Alamat</h4>
                        <div class="data-wisuda-field row">
                            <div class=" col-lg-12 mb-3">
                                <label for="jalan" class="form-label">Jalan</label>
                                <input type="text" class="form-control" name="jalan" id="jalan" aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->jalan}}" required/>
                            </div>
                            <div class=" col-lg-4 mb-3">
                                <label for="dusun" class="form-label">Dusun</label>
                                <input type="text"class="form-control"name="dusun"id="dusun"aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->dusun}}"/>
                            </div>
                            <div class=" col-lg-2 mb-3">
                                <label for="rt" class="form-label">RT</label>
                                <input type="text" class="form-control" name="rt" id="rt" aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->rt}}" required/>
                            </div>
                            <div class=" col-lg-2 mb-3">
                                <label for="rw" class="form-label">RW</label>
                                <input type="text" class="form-control" name="rw" id="rw" aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->rw}}" required/>
                            </div>
                            <div class=" col-lg-4 mb-3">
                                <label for="kelurahan" class="form-label">Kelurahan</label>
                                <input type="text"class="form-control"name="kelurahan"id="kelurahan"aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->kelurahan}}" required/>
                            </div>
                            <div class=" col-lg-4 mb-3">
                                <label for="kode_pos" class="form-label">Kode Pos</label>
                                <input type="text" class="form-control" name="kode_pos" id="kode_pos" aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->kode_pos}}"/>
                            </div>
                            <div class=" col-lg-8 mb-3">
                                <label for="nama_wilayah" class="form-label">Kecamatan / Kabupaten / Provinsi</label>
                                <input type="text"class="form-control"name="nama_wilayah"id="nama_wilayah"aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->nama_wilayah}}" required/>
                            </div>
                        </div>
                        <div class="data-wisuda-field row">
                            <div class=" col-lg-6 mb-3">
                                <label for="handphone" class="form-label">No. Telp/HP</label>
                                <input type="text"class="form-control"name="handphone"id="handphone"aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->handphone}}" required/>
                            </div>
                            <div class=" col-lg-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text"class="form-control"name="email"id="email"aria-describedby="helpId"
                                    value="{{$riwayat_pendidikan->biodata->email}}" required/>
                            </div>
                        </div>

                        <h4 class="text-info mb-0 mt-40"><i class="fa fa-university"></i> Data Akademik Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="data-wisuda-fields">
                                <div class="data-wisuda-field row">
                                    <div class=" col-lg-12 mb-3">
                                        <label for="nim" class="form-label">NIM Mahasiswa</label>
                                        <input type="text"class="form-control"name="nim"id="nim"aria-describedby="helpId"
                                            value="{{$riwayat_pendidikan->nim}}" disabled required/>
                                    </div>
                                    <div class=" col-lg-2 mb-3">
                                        <label for="jenjang_pendidikan" class="form-label">Program Pendidikan</label>
                                        <input type="text" class="form-control" name="jenjang_pendidikan" id="jenjang_pendidikan" aria-describedby="helpId"
                                            value="{{$riwayat_pendidikan->prodi->nama_jenjang_pendidikan}}" disabled required/>
                                    </div>
                                    <div class=" col-lg-6 mb-3">
                                        <label for="prodi" class="form-label">Program Studi</label>
                                        <input type="text"class="form-control"name="prodi"id="prodi"aria-describedby="helpId"
                                            value="{{$riwayat_pendidikan->prodi->nama_program_studi}}" disabled required/>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label for="lokasi_kuliah">Lokasi Kuliah</label>
                                        <select id="lokasi_kuliah" name="lokasi_kuliah" class="form-select" required>
                                            <option value="">-- Pilih Lokasi Kuliah --</option>
                                            <option value="Inderalaya">Inderalaya</option>
                                            <option value="Palembang">Palembang</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="data-wisuda-field row">
                                    <div class=" col-lg-6 mb-3">
                                        <label for="jurusan" class="form-label">Jurusan</label>
                                        <input type="text"class="form-control"name="jurusan"id="jurusan"aria-describedby="helpId"
                                            value="{{$riwayat_pendidikan->prodi->jurusan->nama_jurusan_id}}" disabled required/>
                                    </div>
                                    <div class=" col-lg-6 mb-3">
                                        <label for="fakultas" class="form-label">Fakultas</label>
                                        <input type="text" class="form-control" name="fakultas" id="fakultas" aria-describedby="helpId"
                                            value="{{$riwayat_pendidikan->prodi->fakultas->nama_fakultas}}" disabled required/>
                                    </div>
                                </div>
                            </div>
                        </div>
                                
                        <h4 class="text-info mb-0 mt-40"><i class="fa fa-graduation-cap"></i> Data Wisuda Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="data-wisuda-fields">
                                <div class="data-wisuda-field row">
                                    <div class=" col-lg-6 mb-3">
                                        <label for="wisuda_ke" class="form-label">Wisuda Ke-</label>
                                        <select id="wisuda_ke" name="wisuda_ke" class="form-select" required>
                                            <option value="">-- Pilih Angkatan Wisuda --</option>
                                            @foreach($wisuda_ke as $w)
                                                <option value="{{$w}}">{{ $w }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="pas_foto" class="form-label">Foto Wisuda (.jpg / .png)</label>
                                        <input type="file" class="form-control" name="pas_foto" id="pas_foto"
                                            aria-describedby="fileHelpId" accept=".jpg" required />
                                    </div>
                                </div>
                            </div>
                        </div>


                        
                        <h4 class="text-info mb-0 mt-40"><i class="fa fa-book"></i> Data Tugas Akhir Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="data-wisuda-fields">
                                {{-- <div class="data-wisuda-field row">Data Wisuda Mahasiswa</h4> --}}
                                <div class="data-wisuda-field row">
                                    <div class=" col-lg-12 mb-3">
                                        <label for="judul_ta" class="form-label">Judul {{$aktivitas->nama_jenis_aktivitas}}</label>
                                        <textarea type="text" class="form-control" name="judul_ta" id="judul_ta" aria-describedby="helpId"
                                            disabled required>{{$aktivitas->judul}}
                                        </textarea>
                                    </div>
                                    <div class=" col-lg-12 mb-3">
                                        <label for="kosentrasi" class="form-label">Bidang Kajian Utama (BKU) / Kosentrasi</label>
                                        <input type="text"class="form-control"name="kosentrasi"id="kosentrasi"aria-describedby="helpId" placeholder="Masukkan Kosentrasi" required/>
                                    </div>
                                    <div class=" col-lg-12 mb-3">
                                        <label for="abstrak_ta" class="form-label">Abstak</label>
                                        <textarea type="text" class="form-control" name="abstrak_ta" id="abstrak_ta" aria-describedby="helpId"
                                        placeholder="Masukkan Abstrak Tugas Akhir" required></textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="abstrak_file" class="form-label">File Abstak (.pdf)</label>
                                        <input type="file" class="form-control" name="abstrak_file" id="abstrak_file"
                                            aria-describedby="fileHelpId" accept=".pdf" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('fakultas.wisuda.index')}}" class="btn btn-danger waves-effect waves-light">
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
                title: 'Pendaftaran Wisuda',
                text: "Apakah anda yakin ingin mendaftar Wisuda?",
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
