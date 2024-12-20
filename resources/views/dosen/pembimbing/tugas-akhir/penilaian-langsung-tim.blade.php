@extends('layouts.dosen')
@section('title')
Penilaian Langsung Mahasiswa
@endsection
@section('content')
@push('header')
<div class="mx-4">
    <a href="{{route('dosen.pembimbing.bimbingan-tugas-akhir.asistensi', ['aktivitas' => $data])}}"
        class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush
@include('swal')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <form class="form" action="{{route('dosen.pembimbing.bimbingan-tugas-akhir.penilaian-langsung.store', ['aktivitas' => $data->id])}}" id="penilaian-langsung" method="POST">
                    @csrf
                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Detail Aktivitas Mahasiswa</h4>
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
                                    required
                                />
                            </div>
                        </div>
                        <h4 class="text-info mb-0"><i class="fa fa-user"></i> Penilaian Langsung Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="row">
                            <div class="col-md-1 text-center align-middle">
                                <h4>No</h4>
                            </div>
                            <div class="col-md-5 text-center align-middle">
                                <h4>Nama Komponen Penilaian</h4>
                            </div>
                            <div class="col-md-6 text-center align-middle">
                                <h4>Nilai (N)</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-1 text-center align-middle">
                                <h4>1.</h4>
                            </div>
                            <div class="col-md-5">
                                <h4>Penilaian Langsung Mahasiswa</h4>
                                <ul>
                                    <li>Keaktifan mahasiswa</li>
                                    <li>Kreativitas</li>
                                    <li>Kerjasama</li>
                                    <li>Kemampuan berdiskusi</li>
                                    <li>Sikap</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input name="nilai_langsung" type="number" class="form-control" value="{{$data_nilai->nilai_angka ?? 0}}" step="0.01" max="100">
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
                        <a type="button" href="{{route('dosen.pembimbing.bimbingan-tugas-akhir.asistensi', ['aktivitas' => $data])}}" class="btn btn-danger btn-rounded waves-effect waves-light">
                            Batal
                        </a>
                        <button type="submit" id="submit-button" class="btn btn-primary btn-rounded waves-effect waves-light" 
                                @if($data_nilai) disabled @endif>
                            <i class="fa fa-hdd-o"></i> Simpan
                        </button>
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
        $('#penilaian-langsung').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Penilaian Langsung Mahasiswa',
                text: "Apakah anda yakin ingin melanjutkan?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            },function(isConfirmed){
                if (isConfirmed) {
                    $('#penilaian-langsung').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });
</script>

@endpush
