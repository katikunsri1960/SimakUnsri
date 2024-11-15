@extends('layouts.dosen')
@section('title')
Monev Pembimbing Karya Ilmiah Pembimbing Utama
@endsection
@section('content')
@push('header')
<div class="mx-4">
    <a href="{{route('dosen.monev.karya-ilmiah')}}"
        class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-12">
            <div class="box pull-up">
                <div class="box-body bg-img bg-primary-light">
                    <div class="d-lg-flex align-items-center justify-content-between">
                        <div class="d-lg-flex align-items-center mb-30 mb-xl-0 w-p100">
                            <img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}"
                                class="img-fluid max-w-250" alt="" />
                            <div class="ms-30">
                                <h2 class="mb-10">{{$dosen->nama_dosen}}</h2>
                                <p class="mb-0 text-fade fs-18">Pembimbing Utama</p>
                            </div>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-12">
                    <div class="box box-body mb-0 ">
                        <div class="row">
                            <table class="table table-hover table-bordered" id="data-table">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">No</th>
                                        <th class="text-center align-middle">Prodi</th>
                                        <th class="text-center align-middle">Angkatan</th>
                                        <th class="text-center align-middle">NIM</th>
                                        <th class="text-center align-middle">Nama Mahasiswa</th>
                                        <th class="text-center align-middle">Judul Aktivitas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                    <tr>
                                        <td class="text-center align-middle">{{$loop->iteration}}</td>
                                        <td class="text-center align-middle">{{$d->nama_jenjang_pendidikan}} - {{$d->nama_program_studi}}</td>
                                        <td class="text-center align-middle">{{$d->angkatan}}</td>
                                        <td class="text-center align-middle">{{$d->nim}}</td>
                                        <td class="text-start align-middle">{{$d->nama_mahasiswa}}</td>
                                        <td class="text-start align-middle">{{$d->judul}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
</section>
@endsection
@push('js')
<script>
    $(document).ready(function() {
        $('#data-table').DataTable();
    });
</script>
@endpush
