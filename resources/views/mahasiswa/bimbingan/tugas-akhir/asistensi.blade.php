@extends('layouts.mahasiswa')
@section('title')
Bimbingan Tugas Akhir Mahasiswa
@endsection
@section('content')
@push('header')
<div class="mx-4">
    <a href="{{route('mahasiswa.bimbingan.bimbingan-tugas-akhir')}}"
        class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush
@include('swal')
@include('mahasiswa.bimbingan.tugas-akhir.asistensi-tambah')
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-md-12">
            <div class="box box-widget widget-user-2">
                <div class="widget-user-header bg-gradient-secondary">
                    <div class="widget-user-image">
                        @php
                        $imagePath =
                        public_path('storage/'.$aktivitas->anggota_aktivitas_personal->mahasiswa->angkatan.'/'.$aktivitas->anggota_aktivitas_personal->mahasiswa->nim.'.jpg');
                        @endphp
                        <img class="rounded bg-success-light"
                            src="{{file_exists($imagePath) ? asset('storage/'.$aktivitas->anggota_aktivitas_personal->mahasiswa->angkatan.'/'.$aktivitas->anggota_aktivitas_personal->mahasiswa->nim.'.jpg') : asset('images/images/avatar/avatar-15.png')}}"
                            alt="User Avatar">
                    </div>
                    <h3 class="widget-user-username">{{$aktivitas->anggota_aktivitas_personal->nama_mahasiswa}} </h3>
                    <h4 class="widget-user-desc">NIM: {{$aktivitas->anggota_aktivitas_personal->nim}}<br
                            class="mb-1">ANGKATAN: {{$aktivitas->anggota_aktivitas_personal->mahasiswa->angkatan}}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="row box-body">
                    <h3 class="text-info mb-0"><i class="fa fa-book"></i> Detail Aktivitas {{$aktivitas->nama_jenis_aktivitas}}</h3>
                    <hr class="my-15">
                    <div class="col-xl-12 col-lg-12 d-flex justify-content-between">
                        <div class="d-flex justify-content-start">
                            <table class="table">
                                <tr>
                                    <td class="text-left">Judul</td>
                                    <td class="text-center">:</td>
                                    <td class="text-left" style="text-align: justify">{{$aktivitas->judul}}</td>
                                </tr>
                                <tr>
                                    <td class="text-left text-nowrap">No. SK</td>
                                    <td class="text-center">:</td>
                                    <td class="text-left" style="text-align: justify">{{$aktivitas->sk_tugas}}</td>
                                </tr>
                                <tr>
                                    <td class="text-left text-nowrap">Tanggal Mulai</td>
                                    <td class="text-center">:</td>
                                    <td class="text-left" style="text-align: justify">{{$aktivitas->id_tanggal_mulai}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left text-nowrap align-middle">Pembimbing</td>
                                    <td class="text-center align-middle">:</td>
                                    <td class="text-left align-middle">
                                        <ul style="padding: 0; padding-left:0.8rem">
                                            @foreach ($aktivitas->bimbing_mahasiswa as $bimbingan)
                                            <li>Pembimbing {{$bimbingan->pembimbing_ke}} : {{$bimbingan->nama_dosen}}  
                                                @if ($bimbingan->approved == 0)
                                                    <span class="badge bg-warning mx-5">Menunggu Persetujuan</span>
                                                @elseif ($bimbingan->approved == 1)
                                                    <span class="badge bg-success mx-5">Disetujui</span>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left text-nowrap">Status Sidang</td>
                                    <td class="text-center">:</td>
                                    <td class="text-left align-middle">
                                        @if ($aktivitas->approve_sidang == 0)
                                            <span class="badge bg-warning mx-5">Belum Diajukan Sidang</span>
                                        @elseif ($aktivitas->approve_sidang == 1)
                                            <span class="badge bg-primary mx-5">Sudah Diajukan Sidang</span>
                                            <ul style="padding: 0; padding-left:0.8rem">
                                                @foreach ($aktivitas->uji_mahasiswa as $uji)
                                                <li>Penguji Ke-{{$uji->penguji_ke}} : {{$uji->nama_dosen}}  
                                                    @if ($uji->status_uji_mahasiswa == 0)
                                                        <span class="badge bg-warning mx-5">Belum Disetujui</span>
                                                    @elseif ($uji->status_uji_mahasiswa == 1)
                                                        <span class="badge bg-success mx-5">Sudah Disetujui KoProdi</span>
                                                    @elseif ($uji->status_uji_mahasiswa == 2)
                                                        <span class="badge bg-success mx-5">Sudah Disetujui Dosen Penguji</span>
                                                    @elseif ($uji->status_uji_mahasiswa == 1)
                                                        <span class="badge bg-danger mx-5">Diabtalkan Oleh Dosen Penguji</span>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mt-2 box-body">
                    <h3 class="text-info mb-0"><i class="fa fa-pencil"></i> Daftar Asistensi {{$aktivitas->nama_jenis_aktivitas}}</h3>
                    <hr class="my-15">
                    <div class="col-xl-12 col-lg-12 mb-15 text-end">
                        @if($aktivitas->approve_sidang != 1)
                            <div class="btn-group">
                                <a class="btn btn-rounded bg-success-light" href="#" data-bs-toggle="modal" data-bs-target="#tambahAsistensiModal"><i class="fa fa-plus"><span class="path1"></span><span class="path2"></span></i> Tambah Asistensi</a>
                            </div>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table id="dt" class="table table-bordered table-striped" style="font-size: 12px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle" style="width: 5%">No</th>
                                    <th class="text-center align-middle">Tanggal</th>
                                    <th class="text-center align-middle">Uraian Asistensi</th>
                                    <th class="text-center align-middle">Pembimbing</th>
                                    <th class="text-center align-middle">Status</th>
                                    {{-- <th class="text-center align-middle">Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center align-middle">{{$d->id_tanggal}}</td>
                                    <td class="text-left align-middle" style="text-align: justify">{{$d->uraian}}</td>
                                    <td class="text-center align-middle">{{$d->dosen ? $d->dosen->nama_dosen : '-'}}</td>
                                    <td class="text-center align-middle">
                                        @if ($d->approved == 0)
                                        <span class="badge bg-warning">Menunggu Persetujuan</span>
                                        @elseif ($d->approved == 1)
                                        <span class="badge bg-success">Disetujui</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($aktivitas->approve_sidang == 1)
    <div class="row mt-5">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-body">
                    <form class="form" action="#" id="update-detail-sidang" method="POST">
                        <h3 class="text-info mb-0"><i class="fa fa-user"></i> Detail Sidang Mahasiswa</h3>
                        <hr class="my-15">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="mb-4">
                                        <label for="jenis_aktivitas" class="form-label">Jadwal Sidang</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="jenis_aktivitas"
                                            id="jenis_aktivitas"
                                            aria-describedby="helpId"
                                            value="{{$aktivitas->jadwal_ujian}}"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="mb-4">
                                        <label for="sk_tugas" class="form-label">Jam Mulai Sidang</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="sk_tugas"
                                            id="sk_tugas"
                                            aria-describedby="helpId"
                                            value="{{$aktivitas->jadwal_jam_mulai}}"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="mb-4">
                                        <label for="sk_tugas" class="form-label">Jam Selesai Sidang</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="sk_tugas"
                                            id="sk_tugas"
                                            aria-describedby="helpId"
                                            value="{{$aktivitas->jadwal_jam_selesai}}"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr class="my-15">
                    <h4>Dosen Pembimbing<h4>
                    <div class="row mt-10">
                        <div class="table-responsive">
                            <table id="dt" class="table table-bordered table-striped text-center" style="font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th>Pembimbing Ke -</th>
                                        <th>Nama Dosen</th>
                                        <th>Kategori Pembimbing</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($aktivitas->bimbing_mahasiswa as $b)
                                        <tr>
                                            <td>{{$b->pembimbing_ke}}</td>
                                            <td>{{$b->nama_dosen}}<br>({{$b->nidn}})</td>
                                            <td>{{$b->nama_kategori_kegiatan}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <h4>Dosen Penguji<h4>
                    <div class="row mt-10">
                        <div class="table-responsive">
                            <table id="dt" class="table table-bordered table-striped text-center" style="font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th>Penguji Ke -</th>
                                        <th>Nama Dosen</th>
                                        <th>Kategori Penguji</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($aktivitas->uji_mahasiswa as $u)
                                        <tr>
                                            <td>{{$u->penguji_ke}}</td>
                                            <td>{{$u->nama_dosen}}<br>({{$u->nidn}})</td>
                                            <td>{{$u->nama_kategori_kegiatan}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="my-15">
                    <h4>Notulensi Sidang Mahasiswa<h4>
                    <div class="row mt-10">
                        <div class="table-responsive">
                            <table id="dt" class="table table-bordered table-striped text-center" style="font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Lokasi</th>
                                        <th>Tanggal Pelaksanaan</th>
                                        <th>Jam Mulai Sidang</th>
                                        <th>Jam Selesai Sidang</th>
                                        <th>Jam Mulai Presentasi</th>
                                        <th>Jam Selesai Presentasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_pelaksanaan->notulensi_sidang as $ns)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$ns->lokasi}}</td>
                                            <td>{{$ns->tanggal_sidang}}</td>
                                            <td>{{$ns->jam_mulai_sidang}}</td>
                                            <td>{{$ns->jam_selesai_sidang}}</td>
                                            <td>{{$ns->jam_mulai_presentasi}}</td>
                                            <td>{{$ns->jam_selesai_presentasi}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Notulensi : </td>
                                            <td colspan="6">{{$ns->uraian}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <h4>Catatan Perbaikan Mahasiswa<h4>
                    <div class="row mt-10">
                        <div class="table-responsive">
                            <table id="dt" class="table table-bordered table-striped text-center" style="font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Dosen</th>
                                        <th>Catatan Perbaikan</th>
                                        <th>Batas Akhir Perbaikan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_pelaksanaan->revisi_sidang as $r)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$r->dosen->nama_dosen}}<br>({{$r->dosen->nidn}})</td>
                                            <td>{{$r->uraian}}</td>
                                            <td>{{$r->tanggal_batas_revisi}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- <h4>Nilai Sidang Mahasiswa<h4>
                    <div class="row mt-10">
                        <div class="table-responsive">
                            <table id="dt" class="table table-bordered table-striped text-center" style="font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Dosen</th>
                                        <th>Tanggal Penilaian</th>
                                        <th>Nilai Kualitas Skripsi</th>
                                        <th>Nilai Presentasi dan Diskusi</th>
                                        <th>Nilai Performansi</th>
                                        <th>Nilai Akhir Dosen (Jumlah dari BxN)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_pelaksanaan->penilaian_sidang as $p)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$p->dosen->nama_dosen}}<br>({{$p->dosen->nidn}})</td>
                                            <td>{{$p->tanggal_penilaian_sidang}}</td>
                                            <td>{{$p->nilai_kualitas_skripsi}}</td>
                                            <td>{{$p->nilai_presentasi_dan_diskusi}}</td>
                                            <td>{{$p->nilai_performansi}}</td>
                                            <td>{{$p->nilai_akhir_dosen}}</td>
                                            <td>
                                                @if ($p->approved_prodi == 0)
                                                    <span class="badge bg-warning">Menunggu Persetujuan Prodi</span>
                                                @elseif ($p->approved_prodi == 1)
                                                    <span class="badge bg-success">Disetujui</span>
                                                @else
                                                    <span class="badge bg-danger">Dibatalkan</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    @endif
</section>
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('assets/vendor_components/select2/dist/css/select2.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>
    $(document).ready(function() {        
        $('.select2').select2();

        $('#dt').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
        });
    });

</script>
@endpush
