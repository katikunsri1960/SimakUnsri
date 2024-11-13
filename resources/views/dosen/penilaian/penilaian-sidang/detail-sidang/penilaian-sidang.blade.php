@extends('layouts.dosen')
@section('title')
Penilaian Sidang Mahasiswa
@endsection
@section('content')
@include('swal')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <form class="form" action="{{route('dosen.penilaian.sidang-mahasiswa.detail-sidang.penilaian-sidang.store', ['aktivitas' => $data->id])}}" id="penilaian-sidang" method="POST">
                    @csrf
                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Detail Sidang Mahasiswa</h4>
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
                                    value="{{$data->anggota_aktivitas_personal->nama_mahasiswa}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nim" class="form-label">NIM</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nim"
                                    id="nim"
                                    aria-describedby="helpId"
                                    value="{{$data->anggota_aktivitas_personal->nim}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="angkatan" class="form-label">Angkatan</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="angkatan"
                                    id="angkatan"
                                    aria-describedby="helpId"
                                    value="{{substr($data->anggota_aktivitas_personal->mahasiswa->id_periode_masuk,0,4)}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="judul" class="form-label">Judul</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="judul"
                                    id="judul"
                                    aria-describedby="helpId"
                                    value="{{$data->judul}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <h4 class="text-info mb-0"><i class="fa fa-user"></i> Penilaian Sidang Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="row">
                            <div class="col-md-1 text-center align-middle">
                                <h4>No</h4>
                            </div>
                            <div class="col-md-5 text-center align-middle">
                                <h4>Nama Komponen Penilaian</h4>
                            </div>
                            <div class="col-md-3 text-center align-middle">
                                <h4>Bobot (B)</h4>
                            </div>
                            <div class="col-md-3 text-center align-middle">
                                <h4>Nilai (N)</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-1 text-center align-middle">
                                <h4>1.</h4>
                            </div>
                            <div class="col-md-5">
                                <h4>Kualitas Karya Ilmiah</h4>
                                <ul>
                                    <li>Orisinalitas judul</li>
                                    <li>Keterkaitan antara judul, masalah, tujuan, manfaat, hasil, dan serta kesimpulan dan saran</li>
                                    <li>Bahasa dan kesesuaian dengan format penulisan karya ilmiah</li>
                                    <li>Kelengkapan lampiran dan data pendukung</li>
                                </ul>
                            </div>
                            <div class="col-md-3 text-center align-middle">
                                <h4>{{$bobot_kualitas_skripsi}} %</h4>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input name="kualitas_skripsi" type="number" class="form-control" value="0" step="0.01" max="100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 text-center align-middle">
                                <h4>2.</h4>
                            </div>
                            <div class="col-md-5">
                                <h4>Presentasi dan Diskusi</h4>
                                <ul>
                                    <li>Kemampuan menjelaskan metode</li>
                                    <li>Kemampuan menjelaskan hasil penelitian atau tujuan penelitian</li>
                                    <li>Kemampuan menghubungkan permasalahan dan kesimpulan</li>
                                    <li>Kemampuan menjawab pertanyaan (Diskusi)</li>
                                </ul>
                            </div>
                            <div class="col-md-3 text-center align-middle">
                                <h4>{{$bobot_presentasi_diskusi}} %</h4>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input name="presentasi" type="number" class="form-control" value="0" step="0.01" max="100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 text-center align-middle">
                                <h4>3.</h4>
                            </div>
                            <div class="col-md-5">
                                <h4>Performansi</h4>
                                <ul>
                                    <li>Penguasaan materi</li>
                                    <li>Sistematis dan logis</li>
                                    <li>Penampilan dan kesopanan</li>
                                    <li>Teknik dan kualitas presentasi</li>
                                </ul>
                            </div>
                            <div class="col-md-3 text-center align-middle">
                                <h4>{{$bobot_performansi}} %</h4>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input name="performansi" type="number" class="form-control" value="0" step="0.01" max="100">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-5">
                            <strong>
                                <p class="text-danger p-5">Data yang sudah diiskan tidak dapat dirubah kembali, mohon berhati-hati dalam menyimpan data.</p>
                            </strong>
                        </div>
                    <div class="box-footer text-end">
                        <a type="button" href="{{route('dosen.penilaian.sidang-mahasiswa.detail-sidang', ['aktivitas' => $data->id])}}" class="btn btn-danger btn-rounded waves-effect waves-light">
                        <i class="fa fa-sign-out"></i> Keluar
                        </a>
                        <button type="submit" id="submit-button" class="btn btn-primary btn-rounded waves-effect waves-light"><i class="fa fa-hdd-o"></i> Simpan</button>
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
        // Form submission with SweetAlert confirmation
        $('#penilaian-sidang').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Penilaian Sidang Mahasiswa',
                text: "Apakah anda yakin ingin melanjutkan?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            },function(isConfirmed){
                if (isConfirmed) {
                    $('#penilaian-sidang').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });
</script>

@endpush
