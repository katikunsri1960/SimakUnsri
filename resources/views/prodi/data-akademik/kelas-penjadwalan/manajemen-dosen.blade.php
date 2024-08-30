@extends('layouts.prodi')
@section('title')
Manajemen Dosen Kelas Perkuliahan
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Manajemen Dosen Kelas Perkuliahan</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="#">Data Akademik</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('prodi.data-akademik.kelas-penjadwalan')}}">Kelas dan Penjadwalan</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('prodi.data-akademik.kelas-penjadwalan.detail',['id_matkul' => $kelas->id_matkul])}}">Detail Kelas dan Penjadwalan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Manajemen Dosen Kelas Perkuliahan</li>
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
                <form class="form" action="">
                    <div class="box-body">
                        <h3 class="text-info mb-0"><i class="fa fa-user"></i> Detail Kelas Kuliah</h3>
                        <hr class="my-15">
                        <div class="form-group">
                            <div class="mb-4">
                                <label for="mata_kuliah" class="form-label">Nama Mata Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="mata_kuliah"
                                    id="mata_kuliah"
                                    aria-describedby="helpId"
                                    value="{{$kelas->nama_mata_kuliah}}"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-4">
                                <label for="nama_kelas" class="form-label">Nama Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_kelas"
                                    id="nama_kelas"
                                    aria-describedby="helpId"
                                    value="{{$kelas->nama_kelas_kuliah}}"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-4">
                                <label for="ruang_kelas" class="form-label">Ruang Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="ruang_kelas"
                                    id="ruang_kelas"
                                    aria-describedby="helpId"
                                    value="{{$kelas->ruang_perkuliahan->nama_ruang}} ( {{$kelas->ruang_perkuliahan->lokasi}} )"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-4">
                                <label for="nama_mahasiswa" class="form-label">Jadwal Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mahasiswa"
                                    id="nama_mahasiswa"
                                    aria-describedby="helpId"
                                    value="{{$kelas->jadwal_hari}}, {{$kelas->jadwal_jam_mulai}} - {{$kelas->jadwal_jam_selesai}}"
                                    disabled
                                />
                            </div>
                        </div>
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
                                <h3 class="text-info mb-0"><i class="fa fa-user"></i> Dosen Pengajar Kelas</h3>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex justify-content-end">
                                <a type="button" class="btn btn-success" href="{{route('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar', ['id_matkul' => $kelas->id_matkul, 'nama_kelas_kuliah' => $kelas->nama_kelas_kuliah])}}"><i class="fa fa-plus"></i> Tambah Dosen</a>
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
                                    <th class="text-center align-middle">URUTAN PENGAJAR</th>
                                    <th class="text-center align-middle">NAMA PENGAJAR</th>
                                    <th class="text-center align-middle">RENCANA MINGGU PERTEMUAN</th>
                                    <th class="text-center align-middle">REALISASI MINGGU PERTEMUAN</th>
                                    <th class="text-center align-middle">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kelas->dosen_pengajar as $d)
                                <tr>
                                    <td class="text-center align-middle">
                                        {{$d->urutan}}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{$d->dosen->nama_dosen}}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{$d->rencana_minggu_pertemuan}}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{$d->realisasi_minggu_pertemuan}}
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="row d-flex justify-content-center px-3">
                                            <a href="{{route('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar.edit', ['id' => $d->id])}}" class="btn btn-warning btn-sm my-2" title="Edit"><i class="fa fa-edit"></i> Edit</a>
                                            <form action="{{ route('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar.destroy', ['id' => $d->id]) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <div class="row">
                                                    <button type="submit" class="btn btn-danger btn-sm my-2 delete-button">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </div> 
                                            </form>
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
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script>
    $(document).ready(function(){

        $('#data').DataTable();
        
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
