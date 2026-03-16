@extends('layouts.bak')
@section('title')
Data Ajuan SKPI
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Data Ajuan SKPI : {{$mahasiswa->nama_mahasiswa}}</h3>

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
        <a href="{{route('bak.skpi.data.index')}}" class="btn btn-warning btn-sm btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
        <!-- <button type="submit" class="btn btn-success btn-rounded waves-effect waves-light"><i class="fa fa-edit"></i> Update Detail Ajuan</button> -->
    </div>
</div>
<section class="content">
    <div class="row">
        <div class="col-12">
            
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border d-flex justify-content-between align-items-center mx-20">
                    <div>
                        <h4 class="text-primary my-2">
                            Data Ajuan SKPI
                        </h4>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="table-skpi">
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
                                @forelse($data as $i => $d)
                                <tr>
                                    <td class="text-center">{{$i +1}}</td>
                                    <td class="text-start">{{ $d->nama_kegiatan }}</td>
                                    <td class="text-start">{{ $d->nama_jenis_skpi }}</td>
                                    <td class="text-start">{{ $d->kriteria }}</td>
                                    <td class="text-center">
                                        @if($d->file_pendukung)
                                        <a href="{{ asset('storage/'.$d->file_pendukung) }}"
                                        class="btn btn-sm btn-success"
                                        target="_blank">
                                        <i class="fa fa-file-o"></i> Lihat File
                                        </a>
                                        @else
                                        <span class="text-muted">Tidak ada file</span>
                                        @endif
                                    </td>                                    
                                    <td class="text-center">{{ $d->skor }}</td>
                                    <td class="text-center align-middle">
                                        <div>
                                            @if($mahasiswa->finalisasi_data == 1)
                                                @if($d->approved == 0)
                                                    <span class="badge badge-lg badge-warning">Belum Disetujui</span>
                                                @elseif($d->approved == 1)
                                                    <span class="badge badge-lg badge-primary mb-5">Disetujui Koor. Prodi</span>
                                                @elseif($d->approved == 2)
                                                    <span class="badge badge-lg badge-primary mb-5">Disetujui Fakultas</span>
                                                @elseif($d->approved == 3)
                                                    <span class="badge badge-lg badge-success mb-5">Disetujui Dir. Akademik</span>
                                                @elseif($d->approved == 97)
                                                    <span class="badge badge-lg badge-danger mb-5">Ditolak Koor. Prodi <br> ({{$d->alasan_pembatalan}})</span>
                                                @elseif($d->approved == 98)
                                                    <span class="badge badge-lg badge-danger mb-5">Ditolak Fakultas <br> ({{$d->alasan_pembatalan}})</span>
                                                @elseif($d->approved == 99)
                                                    <span class="badge badge-lg badge-danger mb-5">Ditolak Dir. Akademik <br> ({{$d->alasan_pembatalan}})</span>
                                                @endif
                                            @else
                                                <span class="badge badge-lg badge-danger">Belum Finalisasi Data</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <!-- @php
                                            $disabled = ($mahasiswa && $mahasiswa->approved > 1) ? 'disabled' : '';
                                        @endphp -->

                                        @if($d->approved == 2 && $mahasiswa->finalisasi_data == 1)
                                        <button 
                                            type="button"
                                            class="btn btn-success btn-sm my-1 btn-approve"
                                            data-id="{{$d->id}}">
                                            <i class="fa fa-check"></i> Approve
                                        </button>
                                        @endif

                                        @if( $d->approved == 2 || $d->approved == 3)
                                        <form action="{{route('bak.skpi.data.decline',$d->id)}}" method="POST" class="form-decline">
                                            @csrf
                                            <input type="hidden" name="alasan_pembatalan" class="alasan-input">

                                            <button type="button" class="btn btn-danger btn-sm btn-decline">
                                                <i class="fa fa-times"></i> Decline
                                            </button>
                                        </form>
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
                            <tfoot>
                                <tr class="bg-light fw-bold">
                                    <td colspan="5" class="text-center">
                                        TOTAL SKOR
                                    </td>
                                    <td class="text-center">
                                        {{ $total_skor}}
                                    </td>
                                    <td colspan="2" class="text-center">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>
$(document).ready(function(){
    $('#table-skpi').DataTable({
        responsive: false,
        autoWidth: false,
        pageLength: 10,
        ordering: false
    });
});

$(document).on('click','.btn-approve',function(){
    let id = $(this).data('id');
    swal({
        title: "Approve SKPI?",
        text: "Data kegiatan SKPI akan disetujui.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#04a08b", // hijau
        confirmButtonText: "Ya, Setujui",
        cancelButtonText: "Batal"
    }, function(isConfirm){
        if(isConfirm){
            $.ajax({
                url: "{{ url('bak/wisuda/skpi/approve') }}/"+id,
                type: "POST",
                data:{
                    _token: "{{ csrf_token() }}"
                },
                success:function(res){
                    if(res.status == "success"){
                        swal("Berhasil", res.message, "success");
                        setTimeout(function(){
                            location.reload();
                        },1000);
                    }else{
                        swal("Gagal", res.message, "error");
                    }
                },
                error:function(xhr){
                    console.log(xhr.responseText);
                    swal("Error","Terjadi kesalahan server","error");
                }
            });
        }
    });
});


$(document).on('click','.btn-decline',function(){

    let form = $(this).closest('form');
    let url = form.attr('action');

    swal({
        title: "Tolak kegiatan SKPI?",
        text: "Masukkan alasan penolakan:",
        type: "input",
        showCancelButton: true,
        confirmButtonText: "Ya, Tolak",
        confirmButtonColor: "#dc3545",
        cancelButtonText: "Batal",
        inputPlaceholder: "Alasan penolakan"
    }, function(inputValue){

        if(inputValue === false) return false;

        if(inputValue === ""){
            swal.showInputError("Alasan tidak boleh kosong!");
            return false;
        }
        $.ajax({
            url: url,
            type: "POST",
            data:{
                _token: "{{ csrf_token() }}",
                alasan_pembatalan: inputValue
            },
            success:function(res){
                swal("Berhasil", res.message, "success");
                setTimeout(function(){
                    location.reload();
                },800);
            },
            error:function(xhr){
                console.log(xhr.responseText);
                swal("Error","Terjadi kesalahan server","error");
            }
        });
    });
});

</script>

@endpush