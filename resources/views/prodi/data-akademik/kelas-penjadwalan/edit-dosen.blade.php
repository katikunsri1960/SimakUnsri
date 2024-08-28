@extends('layouts.prodi')
@section('title')
Dosen Pengajar Kelas Kuliah
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Ubah Dosen Pengajar Kelas Kuliah</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('prodi.data-akademik.non-tugas-akhir')}}">Data Akademik</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('prodi.data-akademik.non-tugas-akhir')}}">Kelas dan Penjadwalan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ubah Detail Aktivitas Non @if (Auth::user()->fk->nama_jenjang_pendidikan == 'S1')
                        Skripsi
                        @elseif (Auth::user()->fk->nama_jenjang_pendidikan == 'S2')
                        Tesis
                        @elseif (Auth::user()->fk->nama_jenjang_pendidikan == 'S3')
                        Disertasi
                        @else
                        Tugas Akhir
                        @endif</li>
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
                <form class="form" action="{{route('prodi.data-akademik.non-tugas-akhir.update-detail', ['aktivitas' => $d->id_aktivitas])}}" id="update-detail" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">
                        <h3 class="text-info mb-0 mt-40"><i class="fa fa-user"></i> Detail Aktivitas Non @if (Auth::user()->fk->nama_jenjang_pendidikan == 'S1')
                        Skripsi
                        @elseif (Auth::user()->fk->nama_jenjang_pendidikan == 'S2')
                        Tesis
                        @elseif (Auth::user()->fk->nama_jenjang_pendidikan == 'S3')
                        Disertasi
                        @else
                        Tugas Akhir
                        @endif</h3>
                        <hr class="my-15">
                        <div class="form-group">
                            <div class="mb-4">
                                <label for="nim" class="form-label">NIM</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nim"
                                    id="nim"
                                    aria-describedby="helpId"
                                    value="{{$d->anggota_aktivitas_personal->nim}}"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-4">
                                <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mahasiswa"
                                    id="nama_mahasiswa"
                                    aria-describedby="helpId"
                                    value="{{$d->anggota_aktivitas_personal->nama_mahasiswa}}"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-4">
                                <label for="judul" class="form-label">Judul</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="judul"
                                    id="judul"
                                    aria-describedby="helpId"
                                    value="{{$d->judul}}"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-4">
                                <label for="semester" class="form-label">Semester</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="semester"
                                    id="semester"
                                    aria-describedby="helpId"
                                    value="{{$d->nama_semester}}"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-4">
                                <label for="jenis_aktivitas" class="form-label">Jenis Aktivitas</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="jenis_aktivitas"
                                    id="jenis_aktivitas"
                                    aria-describedby="helpId"
                                    value="{{$d->nama_jenis_aktivitas}}"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-4">
                                <label for="sk_tugas" class="form-label">SK Tugas Aktivitas</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="sk_tugas"
                                    id="sk_tugas"
                                    aria-describedby="helpId"
                                    placeholder="Masukkan Nomor SK Tugas"
                                    value="{{$d->sk_tugas}}"
                                    required
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Tanggal SK Tugas</label>
                                    <div class="row">
                                        <div class="col-sm-4 mb-2">
                                            <select class="form-select" name="tanggal_sk" id="tanggal_sk" required>
                                                <option value="">Tanggal</option>
                                                @for($i=1;$i <= 31;$i++)
                                                    @php
                                                        $based_num = 0;
                                                        $num = $based_num.$i;
                                                    @endphp
                                                    <option value="{{$i < 10 ? $num : $i}}" {{ substr($d->tanggal_sk_tugas, 8, 2) == $i ? 'selected' : '' }}>{{$i < 10 ? $num : $i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-sm-4 mb-2">
                                            <select class="form-select" name="bulan_sk" id="bulan_sk" required>
                                                <option value="">Bulan</option>
                                                @for($i=1;$i <= 12;$i++)
                                                    @php
                                                        $based_num = 0;
                                                        $num = $based_num.$i;
                                                    @endphp
                                                    <option value="{{$i < 10 ? $num : $i}}" {{ substr($d->tanggal_sk_tugas, 5, 2) == $i ? 'selected' : '' }}>{{$i < 10 ? $num : $i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-sm-4 mb-2">
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="tahun_sk"
                                                id="tahun_sk"
                                                aria-describedby="helpId"
                                                placeholder="Tahun"
                                                value="{{date('Y')}}"
                                                disabled
                                                required
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('prodi.data-akademik.non-tugas-akhir')}}" class="btn btn-danger waves-effect waves-light">
                            Batal
                        </a>
                        <button type="submit" id="submit-button" class="btn btn-primary waves-effect waves-light">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
            <div class="box-header with-border">
                    <div class="row mb-2">
                        <div class="col-lg-6">
                            <div class="d-flex justify-content-start">
                                <h3 class="text-info mb-0"><i class="fa fa-user"></i> Dosen Pembimbing Mahasiswa</h3>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex justify-content-end">
                                <a type="button" class="btn btn-success" href="{{route('prodi.data-akademik.non-tugas-akhir.tambah-dosen', $d->id_aktivitas)}}"><i class="fa fa-plus"></i> Tambah Dosen</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-hover margin-top-10 w-p100"
                            style="font-size: 11px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">PEMBIMBING KE</th>
                                    <th class="text-center align-middle">NAMA</th>
                                    <th class="text-center align-middle">KATEGORI PEMBIMBING</th>
                                    <th class="text-center align-middle">STATUS PEMBIMBING</th>
                                    <th class="text-center align-middle">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($d->bimbing_mahasiswa as $b)
                                <tr>
                                    <td class="text-center align-middle">{{$b->pembimbing_ke}}</td>
                                    <td class="text-center align-middle">
                                        {{$b->nama_dosen}}
                                    </td>
                                    <td class="text-center align-middle" style="width: 15%">
                                        {{$b->nama_kategori_kegiatan}}
                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($b->approved == 0 && $b->approved_dosen == 0)
                                        <span class="badge badge-lg badge-danger">Belum Disetujui</span>
                                        @elseif ($b->approved == 0 && $b->approved_dosen > 0)
                                        <span class="badge badge-lg badge-warning">Menunggu Persetujuan Prodi</span>
                                        @else
                                        <span class="badge badge-lg badge-success">Approved</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="row d-flex justify-content-center px-3">
                                            @if (($b->approved == 0 && $b->approved_dosen > 0) || ($b->approved == 0 && $b->approved_dosen == 0))
                                                <a href="{{route('prodi.data-akademik.non-tugas-akhir.edit-dosen', $b->id)}}" class="btn btn-warning btn-sm my-2" title="Edit"><i
                                                        class="fa fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('prodi.data-akademik.non-tugas-akhir.delete-dosen', ['bimbing' => $b->id]) }}" method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="row">
                                                        <button type="submit" class="btn btn-danger btn-sm my-2 delete-button">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </div> 
                                                </form>
                                            @else
                                                <h4>Data sudah di setujui</h4>
                                            @endif
                                        </div>
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
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#update-detail').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Update Detail Aktivitas',
                text: "Apakah anda yakin?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            },function(isConfirmed){
                if (isConfirmed) {
                    $('#update-detail-tesis').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });

        $('.delete-button').click(function(e){
            e.preventDefault();
            var form = $(this).closest('form');
            swal({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }, function(isConfirmed){
                if (isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
