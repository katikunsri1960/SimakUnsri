@extends('layouts.mahasiswa')
@section('title', 'Prestasi Mahasiswa')

@section('content')
@include('swal')
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-12">
            <div class="box pull-up">
                <div class="box-body bg-img bg-primary-light">
                    <div class="d-lg-flex align-items-center justify-content-between">
                        <div class="d-lg-flex align-items-center mb-30 mb-xl-0 w-p100">
                            <img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" class="img-fluid max-w-250" alt="" />
                            <div class="ms-30">
                                <h2 class="mb-10">Halaman Prestasi Mahasiswa, {{auth()->user()->name}}</h2>
                                <p class="mb-0 text-fade fs-18">SIMAK Universitas Sriwijaya</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>							
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-12">
            <div class="box box-body mb-0">
                <div class="row">
                    <div class="col-xl-6 col-lg-12">
                        <h3 class="fw-500 text-dark mt-0">Daftar Prestasi Mahasiswa</h3>
                    </div>                             
                </div>
                <div class="row mb-5">
                    <div class="col-xl-12 col-lg-12 text-end">
                        <div class="btn-group">
                            <a class="btn btn-rounded bg-success" href="{{route('mahasiswa.prestasi-skpi.tambah')}}">
                                <i class="fa fa-plus"></i> Tambah Prestasi
                            </a>
                        </div>   
                    </div>                           
                </div><br>
                <div class="row">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Nama Prestasi</th>
                                    <th class="text-center align-middle">Kategori</th>
                                    <th class="text-center align-middle">Tahun</th>                                    
                                    <th class="text-center align-middle">Jenis Prestasi</th>
                                    <th class="text-center align-middle">Tingkat Prestasi</th>
                                    <th class="text-center align-middle">Penyelenggara</th>
                                    <th class="text-center align-middle">Bukti Prestasi</th>
                                    <th class="text-center align-middle">Status</th>
                                    <th class="text-center align-middle">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                    <tr>
                                        @include('mahasiswa.prestasi.upload-file')
                                        <td>{{$loop->iteration}}</td>
                                        <td class="text-start align-middle" style="white-space:nowrap;">{{$d->prestasi->nama_prestasi}}</td>
                                        <td class="text-center align-middle">{{$d->prestasi->kategori_prestasi == 1 ? 'Pendanaan' : 'Non Pendanaan'}}</td>
                                        <td class="text-center align-middle">{{$d->prestasi->tahun_prestasi}}</td>
                                        <td class="align-middle">{{$d->prestasi->nama_jenis_prestasi}}</td>
                                        <td class="align-middle">{{$d->prestasi->nama_tingkat_prestasi}}</td>
                                        <td class="text-start align-middle" style="white-space:nowrap;">{{$d->prestasi->penyelenggara}}</td>
                                        <td class="text-center align-middle">
                                            @if($d->prestasi->file_prestasi)
                                                <a href="{{ asset('storage/'.$d->prestasi->file_prestasi) }}" target="_blank" class="btn btn-sm btn-success">
                                                    <i class="fa fa-file-pdf-o"></i> Lihat File
                                                </a>
                                            @else
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#uploadModal{{$d->prestasi->id}}">
                                                    <i class="fa fa-upload"></i> Upload Piagam
                                                </button>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($d->prestasi->status_prestasi == 0)
                                                <span class="badge badge-warning">Menunggu Persetujuan Koor. Prodi</span>
                                            @elseif($d->prestasi->status_prestasi == 1)
                                                <span class="badge badge-primary">Menunggu Persetujuan Fakultas</span>
                                            @elseif($d->prestasi->status_prestasi == 2)
                                                <span class="badge badge-info">Menunggu Persetujuan Dir. Kemahasiswaan</span>
                                            @elseif($d->prestasi->status_prestasi == 3)
                                                <span class="badge badge-success">Disetujui</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($d->approved > 0 || ($d->prestasi && $d->prestasi->approved > 0))
                                                <button type="button" class="btn btn-warning btn-sm me-1" disabled title="Data sudah diverifikasi, tidak bisa diedit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm" disabled title="Data sudah diverifikasi, tidak bisa dihapus">
                                                    <i class="fa fa-lock"></i>
                                                </button>
                                            @else
                                                <a href="{{ route('mahasiswa.prestasi-skpi.edit', $d->id) }}" class="btn btn-warning btn-sm mx-1 mb-1" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                
                                                <form id="delete-form-{{ $d->id }}" action="{{ route('mahasiswa.prestasi-skpi.delete', $d->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm mx-1" title="Hapus" onclick="deleteConfirmation({{ $d->id }})">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
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
</section>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#data').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true
        });
    });

    function deleteConfirmation(id) {
        swal({
            title: 'Delete Data',
            text: "Apakah anda yakin ingin menghapus data?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush