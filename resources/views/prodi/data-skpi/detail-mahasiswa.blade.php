@extends('layouts.prodi')
@section('title')
Dashboard
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Data Ajuan SKPI {{$wisuda->nama_mahasiswa}}</h3>

            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{route('prodi')}}">
                                <i class="mdi mdi-home-outline"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">Yudisium</li>
                        <li class="breadcrumb-item">Pendaftaran</li>
                        <li class="breadcrumb-item active">Data SKPI Mahasiswa</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between my-10">
        <a href="{{route('prodi.data-skpi.index')}}" class="btn btn-warning btn-sm btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
        <!-- <button type="submit" class="btn btn-success btn-rounded waves-effect waves-light"><i class="fa fa-edit"></i> Update Detail Ajuan</button> -->
    </div>
</div>
<section class="content">
    <div class="row">
        <div class="col-12">
            @foreach($skpi_bidang as $bidang)
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border d-flex justify-content-between align-items-center mx-20">
                    <div>
                        <h4 class="text-primary my-2">
                            {{$bidang->nama_bidang}} : {{$bidang->nama_kegiatan}}
                        </h4>
                    </div>

                    <div>
                        @if(!$wisuda || $wisuda->verified_skpi == 0)
                        <button type="button"
                            class="btn btn-sm btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#modalSkpi{{$bidang->id}}">
                            Tambah Data
                        </button>
                        @endif
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th class="text-center" width="20">No</th>
                                    <th class="text-center" width="200">Nama Kegiatan</th>
                                    <th class="text-center" width="200">Jenis</th>
                                    <th class="text-center" width="200">Kategori</th>
                                    <th class="text-center" width="100">File Pendukung</th>
                                    <th class="text-center" width="100">Skor</th>
                                    <th class="text-center" width="100">Status</th>
                                    <th class="text-center" width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data->where('bidang_id',$bidang->id)->values() as $index => $d)
                                <tr>
                                    <td class="text-center">{{ $index+1 }}</td>
                                    <td class="text-start">{{ $d->nama_kegiatan }}</td>
                                    <td class="text-start">{{ $d->nama_jenis_skpi }}</td>
                                    <td class="text-start">{{ $d->kriteria }}</td>
                                    <td class="text-center">
                                        <a href="{{ asset('storage/'.$d->file_pendukung) }}" 
                                            class="btn btn-sm btn-success text-center" target="_blank">
                                            <i class="fa fa-file-o"></i>
                                            Lihat File
                                        </a>
                                    </td>                                    
                                    <td class="text-center">{{ $d->skor }}</td>
                                    <td class="text-center align-middle">
                                        <div class="row">
                                            @if($wisuda->finalisasi_data == 1)
                                                @if($d->approved == 0)
                                                    <span class="badge badge-lg badge-warning">Belum Disetujui</span>
                                                @elseif($d->approved == 1)
                                                    <span class="badge badge-lg badge-primary mb-5">Disetujui Koor. Prodi</span>
                                                @elseif($d->approved == 2)
                                                    <span class="badge badge-lg badge-primary mb-5">Disetujui Fakultas</span>
                                                @elseif($d->approved == 3)
                                                    <span class="badge badge-lg badge-success mb-5">Disetujui Dir. Akademik</span>
                                                @elseif($d->approved == 97)
                                                    <span class="badge badge-lg badge-danger mb-5">Ditolak Koor. Prodi <br> Alasan pembatalan : {{$d->alasan_pembatalan}}</span>
                                                @elseif($d->approved == 98)
                                                    <span class="badge badge-lg badge-danger mb-5">Ditolak Fakultas <br> Alasan pembatalan : {{$d->alasan_pembatalan}}</span>
                                                @elseif($d->approved == 99)
                                                    <span class="badge badge-lg badge-danger mb-5">Ditolak Dir. Akademik <br> Alasan pembatalan : {{$d->alasan_pembatalan}}</span>
                                                @endif
                                            @else
                                                <span class="badge badge-lg badge-danger">Belum Finalisasi Data</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <!-- @php
                                            $disabled = ($wisuda && $wisuda->approved > 1) ? 'disabled' : '';
                                        @endphp -->

                                        @if($d->approved < 1)
                                        <button
                                            type="button"
                                            class="btn btn-success btn-sm my-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalSkpi{{$bidang->id}}-{{$d->id}}">
                                            Approve
                                        </button>

                                        <!-- <form action="{{route('mahasiswa.wisuda.pendaftaran.data-skpi-delete',$d->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button" class="btn btn-danger btn-sm btn-delete">
                                                <i class="fa fa-trash"></i>
                                            </button>

                                        </form> -->
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        Belum ada data kegiatan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- INCLUDE MODAL --}}
                @include('prodi.data-skpi.edit')
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection


@push('js')

<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('assets/vendor_components/select2/dist/css/select2.min.css')}}">

<script>
    $(document).ready(function () {

        // VALIDASI SUBMIT
        $('#data-skpi').on('submit', function (e) {
            e.preventDefault();

            let pernyataan = $('#pernyataan_data').is(':checked');

            // VALIDASI CHECKBOX PERNYATAAN
            if (!pernyataan) {
                swal({
                    title: 'Pernyataan Belum Dicentang',
                    text: 'Silakan centang pernyataan bahwa data akademik sudah benar sebelum menyimpan.',
                    type: 'warning',
                    confirmButtonText: 'OK'
                });

                $('#pernyataan_data').focus();

                return false;
            }

            // KONFIRMASI SIMPAN
            swal({
                title: 'Persertujuan',
                text: 'Dengan ini saya menyatakan bahwa Data SKPI saya telah sesuai, dan saya bersedia menggunakan data tersebut untuk keperluan Pencetakan SKPI.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Setujui',
                cancelButtonText: 'Batal'
            }, function (isConfirmed) {
                if (isConfirmed) {
                    $('#data-skpi').off('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });
    $(document).ready(function(){

        /* DATATABLE */
        $('.datatable').each(function(){
            $(this).DataTable({
                responsive: false,
                autoWidth: false,
                pageLength: 5
            });
        });

        /* SELECT2 DALAM MODAL */
        $('.modal').on('shown.bs.modal', function () {

            $(this).find('.select2').select2({
                dropdownParent: $(this),
                width:'100%'
            });

        });

    });


    /* DELETE CONFIRMATION */
    $(document).on('click', '.btn-delete', function(e){

        e.preventDefault();

        let form = $(this).closest('form');

        swal({

            title: "Hapus Data?",
            text: "Data SKPI akan dihapus permanen.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus",
            cancelButtonText: "Batal"

        }, function(isConfirm){

            if(isConfirm){
                form.submit();
            }

        });

    });
</script>

@endpush