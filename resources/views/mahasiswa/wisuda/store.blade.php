@extends('layouts.mahasiswa')
@section('title')
Pendaftaran Wisuda Mahasiswa
@endsection
@section('content')
@push('header')
<div class="mx-4">
    <a href="{{route('mahasiswa.wisuda.index')}}"
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
                        {{-- MAHASISWA --}}
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
                        
                        <h4 class="text-info mb-10 mt-10">Alamat Mahasiswa</h4>
                        <div class="data-wisuda-field row" style="margin-left: 10px;">
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
                            <div class="col-lg-8 mb-3">
                                <label for="id_wilayah" class="form-label">Wilayah</label>
                                <select id="id_wilayah"  name="id_wilayah"></select>
                            </div>
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
                        
                        {{-- ORANG TUA --}}
                        <h4 class="text-info mb-0" style="padding-top: 40px;">
                            <i class="fa-solid fa-users"></i> Data Diri Orang Tua
                        </h4>
                        <hr class="my-15">
                        <div class="data-wisuda-field row">
                            <div class=" col-lg-6 mb-3">
                                <label for="nama_ayah" class="form-label">
                                    NAMA AYAH <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="nama_ayah" id="nama_ayah" aria-describedby="helpId"
                                    value="{{ strtoupper($riwayat_pendidikan->biodata->nama_ayah) }}" required/>
                            </div>

                            <div class=" col-lg-6 mb-3">
                                <label for="no_hp_ayah" class="form-label">
                                    Nomor Handphone Ayah <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="no_hp_ayah" id="no_hp_ayah" aria-describedby="helpId"
                                    value="{{ $riwayat_pendidikan->biodata->no_hp_ayah }}" required/>
                            </div>

                            <div class=" col-lg-6 mb-3">
                                <label for="nama_ibu" class="form-label">
                                    Nama Ibu Kandung <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="nama_ibu" id="nama_ibu" aria-describedby="helpId"
                                    value="{{ $riwayat_pendidikan->biodata->nama_ibu_kandung }}" disabled required/>
                            </div>

                            <div class=" col-lg-6 mb-3">
                                <label for="no_hp_ibu" class="form-label">
                                    Nomor Handphone Ibu <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="no_hp_ibu" id="no_hp_ibu" aria-describedby="helpId"
                                    value="{{ $riwayat_pendidikan->biodata->no_hp_ibu }}" required/>
                            </div>

                            <div class=" col-lg-12 mb-3">
                                <label for="alamat_orang_tua" class="form-label">
                                    ALAMAT ORANG TUA <span class="text-danger">*</span>
                                </label>
                                <textarea placeholder="MASUKKAN ALAMAT ORANG TUA" class="form-control" name="alamat_orang_tua" id="alamat_orang_tua" aria-describedby="helpId" required>{{ strtoupper($riwayat_pendidikan->biodata->alamat_orang_tua ?? '') }}</textarea>
                            </div>
                        </div>


                        <h4 class="text-info mb-0" style="padding-top: 40px;"><i class="fa-solid fa-school"></i> Data Asal Sekolah Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="data-sekolah-fields">
                                <h4 class="text-info mb-0">Sekolah Dasar</h4>
                                <div class="data-sekolah-field row" style="margin-left: 10px;">
                                    <div class=" col-lg-4 mb-3">
                                        <label for="nama_sd" class="form-label">Nama Sekolah</label>
                                        <input type="text" class="form-control" name="nama_sd" id="nama_sd" aria-describedby="helpId"
                                            value="{{$asal_sekolah->rm_pddk_sd_nama ?? '-'}}" disabled required/>
                                    </div>
                                    <div class=" col-lg-6 mb-3">
                                        <label for="lokasi_sd" class="form-label">Alamat Sekolah</label>
                                        <input type="text"class="form-control" name="lokasi_sd" id="lokasi_sd" aria-describedby="helpId"
                                            value="{{$asal_sekolah->rm_pddk_sd_lokasi ?? '-'}}" disabled required/>
                                    </div>
                                    <div class="col-lg-2 mb-3">
                                        <label for="lulus_sd" class="form-label">Tahun Lulus</label>
                                        <input type="text"class="form-control" name="lulus_sd" id="lulus_sd" aria-describedby="helpId"
                                            value="{{$asal_sekolah->rm_pddk_sd_thn_lulus ?? '-'}}" disabled required/>
                                    </div>
                                </div>
                            </div>
                            <div id="data-sekolah-fields">
                                <h4 class="text-info mb-0">Sekolah Menengah Pertama</h4>
                                <div class="data-sekolah-field row" style="margin-left: 10px;">
                                    <div class=" col-lg-4 mb-3">
                                        <label for="nama_smp" class="form-label">Nama Sekolah</label>
                                        <input type="text" class="form-control" name="nama_smp" id="nama_smp" aria-describedby="helpId"
                                            value="{{$asal_sekolah->rm_pddk_sltp_nama ?? '-'}}" disabled required/>
                                    </div>
                                    <div class=" col-lg-6 mb-3">
                                        <label for="lokasi_smp" class="form-label">Alamat Sekolah</label>
                                        <input type="text"class="form-control" name="lokasi_smp" id="lokasi_smp" aria-describedby="helpId"
                                            value="{{$asal_sekolah->rm_pddk_sltp_lokasi ?? '-'}}" disabled required/>
                                    </div>
                                    <div class="col-lg-2 mb-3">
                                        <label for="lulus_smp" class="form-label">Tahun Lulus</label>
                                        <input type="text"class="form-control" name="lulus_smp" id="lulus_smp" aria-describedby="helpId"
                                            value="{{$asal_sekolah->rm_pddk_sltp_thn_lulus ?? '-'}}" disabled required/>
                                    </div>
                                </div>
                            </div>
                            <div id="data-sekolah-fields">
                                <h4 class="text-info mb-0">Sekolah Menengah Atas</h4>
                                <div class="data-sekolah-field row" style="margin-left: 10px;">
                                    <div class=" col-lg-4 mb-3">
                                        <label for="nama_slta" class="form-label">Nama Sekolah</label>
                                        <input type="text" class="form-control" name="nama_slta" id="nama_slta" aria-describedby="helpId"
                                            value="{{$asal_sekolah->nama_sekolah ?? '-' }}" disabled required/>
                                    </div>
                                    <div class=" col-lg-6 mb-3">
                                        <label for="lokasi_slta" class="form-label">Alamat Sekolah</label>
                                        <input type="text"class="form-control" name="lokasi_slta" id="lokasi_slta" aria-describedby="helpId"
                                            value="{{strtoupper($asal_sekolah->nama_kabupaten ?? '-') }}, {{strtoupper($asal_sekolah->nama_provinsi ?? '-')}}" disabled required/>
                                    </div>
                                    <div class="col-lg-2 mb-3">
                                        <label for="lulus_slta" class="form-label">Tahun Lulus</label>
                                        <input type="text"class="form-control" name="lulus_slta" id="lulus_slta" aria-describedby="helpId"
                                            value="{{$asal_sekolah->rm_pddk_slta_thn_lulus ?? '-' }}" disabled required/>
                                    </div>
                                </div>
                            </div>
                            <div id="data-sekolah-fields">
                                <h4 class="text-info mb-0">File Ijazah Terakhir</h4>
                                <div class="data-sekolah-field row" style="margin-left: 10px;">
                                    <div class="col-lg-6 mb-3">
                                        <label for="ijazah_terakhir_file" class="form-label">Upload Ijazah Terakhir (.pdf)</label>
                                        <input type="file" class="form-control" name="ijazah_terakhir_file" id="ijazah_terakhir_file"
                                            aria-describedby="fileHelpId" accept=".pdf" required />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4 class="text-info mb-0" style="padding-top: 40px;"><i class="fa fa-university"></i> Data Akademik Mahasiswa</h4>
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
                                
                        <h4 class="text-info mb-0" style="padding-top: 40px;"><i class="fa fa-graduation-cap"></i> Data Wisuda Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="data-wisuda-fields">
                                <div class="data-wisuda-field row">
                                    <div class=" col-lg-6 mb-3">
                                        <label for="wisuda_ke" class="form-label">Wisuda Ke-</label>
                                        <select id="wisuda_ke" name="wisuda_ke" class="form-select" required>
                                            <option value="">-- Pilih Angkatan Wisuda --</option>
                                            @if ($wisuda_ke)
                                                <option value="{{$wisuda_ke->periode}}">{{$wisuda_ke->periode}}</option>
                                            @else
                                                <option value="0">Tidak ada periode Wisuda</option>
                                            @endif
                                            
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


                        
                        <h4 class="text-info mb-0" style="padding-top: 40px;"><i class="fa fa-book"></i> Data Tugas Akhir Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="data-wisuda-fields">
                                {{-- <div class="data-wisuda-field row">Data Wisuda Mahasiswa</h4> --}}
                                <div class="data-wisuda-field row">
                                    <div class=" col-lg-12 mb-3">
                                        <label for="judul_ta" class="form-label">Judul {{$aktivitas->nama_jenis_aktivitas}} dalam Bahasa Indonesia</label>
                                        <textarea type="text" class="form-control" name="judul_ta" id="judul_ta" aria-describedby="helpId"
                                            disabled required>{{$aktivitas->judul}}
                                        </textarea>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="judul_eng" class="form-label">Judul {{$aktivitas->nama_jenis_aktivitas}} dalam Bahasa Inggris</label>
                                        <textarea type="text" class="form-control" name="judul_eng" id="judul_eng" aria-describedby="helpId"
                                            placeholder="Masukkan Judul {{$aktivitas->nama_jenis_aktivitas}} dalam Bahasa Inggris" required></textarea>
                                    </div>
                                    <div class=" col-lg-12 mb-3">
                                        <label for="kosentrasi" class="form-label">Bidang Kajian Utama (BKU) / Kosentrasi</label>
                                        <input type="text"class="form-control"name="kosentrasi"id="kosentrasi"aria-describedby="helpId" placeholder="Masukkan Kosentrasi" required/>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="tgl_sk_pembimbing" class="form-label">Tanggal SK Pembimbing</label>
                                        <input type="date" class="form-control" name="tgl_sk_pembimbing" id="tgl_sk_pembimbing" aria-describedby="helpId" required />
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="no_sk_pembimbing" class="form-label">Nomor SK Pembimbing</label>
                                        <input type="text" class="form-control" name="no_sk_pembimbing" id="no_sk_pembimbing" aria-describedby="helpId" required />
                                    </div>
                                    <div class=" col-lg-12 mb-3">
                                        <label for="abstrak_ta" class="form-label">Abstrak {{$aktivitas->nama_jenis_aktivitas}}</label>
                                        <textarea type="text" class="form-control" name="abstrak_ta" id="abstrak_ta" aria-describedby="helpId"
                                        placeholder="Masukkan Abstrak {{$aktivitas->nama_jenis_aktivitas}}" required></textarea>
                                    </div>
                                </div>
                                <h4 class="text-info mb-10 mt-10">File Abstrak {{$aktivitas->nama_jenis_aktivitas}}</h4>
                                <div class="data-wisuda-field row">
                                    {{-- <div class="col-lg-12 mb-3"> --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="abstrak_file" class="form-label">Bahasa Indonesia(.pdf)</label>
                                            <input type="file" class="form-control" name="abstrak_file" id="abstrak_file"
                                                aria-describedby="fileHelpId" accept=".pdf" required />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="abstrak_file_eng" class="form-label">Bahasa Inggris(.pdf)</label>
                                            <input type="file" class="form-control" name="abstrak_file_eng" id="abstrak_file_eng"
                                                aria-describedby="fileHelpId" accept=".pdf" required />
                                        </div>
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
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
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

    $("#id_wilayah").select2({
        placeholder: {
            id: '{{$riwayat_pendidikan->biodata->id_wilayah}}',
            text: '{{$riwayat_pendidikan->biodata->nama_wilayah}}, {{$riwayat_pendidikan->biodata->wilayah->kab_kota->nama_wilayah}}'
        },
        width: '100%',
        minimumInputLength: 3,
        ajax: {
            url: "{{route('mahasiswa.wisuda.get-kecamatan')}}",
            type: "GET",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // search term
                };
            },
            processResults: function (data) {
                // console.log(data); // Display data in console
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.nama_wilayah+", " + item.kab_kota.nama_wilayah,
                            id: item.id_wilayah
                        }
                    })
                };
            },
        }
    }).data('select2').$container.find('.select2-selection__placeholder').css('color', 'black');
</script>
@endpush
