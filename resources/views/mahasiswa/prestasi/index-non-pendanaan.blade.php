@extends('layouts.mahasiswa')
@section('title')
Prestasi Mahasiswa
@endsection
@section('content')
<section class="content bg-white">
<div class="row align-items-end">
    <div class="col-12">
        <div class="box pull-up">
            <div class="box-body bg-img bg-primary-light">
                <div class="d-lg-flex align-items-center justify-content-between">
                    <div class="d-lg-flex align-items-center mb-30 mb-xl-0 w-p100">
                        <img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" class="img-fluid max-w-250" alt="" />
                        <div class="ms-30">
                            <h2 class="mb-10">Halaman Prestasi Non Pendanaan Mahasiswa,  {{auth()->user()->name}}</h2>
                            <p class="mb-0 text-fade fs-18">SIMAK Universitas Sriwijaya</p>
                        </div>
                    </div>
                <div>
            </div>
        </div>							
    </div>
</div>
@include('swal')
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
                        <a class="btn btn-rounded bg-success " href="{{route('mahasiswa.prestasi.prestasi-non-pendanaan.tambah')}}"><i class="fa fa-plus"><span class="path1"></span><span class="path2"></span></i> Tambah Prestasi</a>
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
                                <th class="text-center align-middle">Tahun Pelaksanaan</th>                                    
                                <th class="text-center align-middle">Jenis Prestasi</th>
                                <th class="text-center align-middle">Tingkat Prestasi</th>
                                <th class="text-center align-middle">Penyelenggara</th>
                                <th class="text-center align-middle">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td class="text-start align-middle" style="white-space:nowrap;">{{$d->nama_prestasi}}</td>
                                    <td class="text-center align-middle">{{$d->tahun_prestasi}}</td>
                                    <td>{{$d->nama_jenis_prestasi}}</td>
                                    <td>{{$d->nama_tingkat_prestasi}}</td>
                                    <td class="text-start align-middle" style="white-space:nowrap;">{{$d->penyelenggara}}</td>
                                    <td class="text-center align-middle">
                                        <form id="delete-form-{{$d->id}}" action="{{ route('mahasiswa.prestasi.prestasi-non-pendanaan.hapus', $d->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" title="Hapus" onclick="deleteConfirmation({{$d->id}})"><i class="fa fa-trash"></i></button>
                                        </form>
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
            "searching": true,
            // "scrollCollapse": true,
            // "scrollY": "550px",
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
        },function(isConfirm){
            if (isConfirm) {
                document.getElementById('delete-form-' + id).submit();
                $('#spinner').show();
            }
        });
    }

</script>

@endpush
