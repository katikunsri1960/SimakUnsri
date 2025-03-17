@extends('layouts.fakultas')
@section('title')
Daftar Pengajuan Cuti
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Daftar Pengajuan Cuti Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('fakultas')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Pengajuan Cuti</li>
                        <li class="breadcrumb-item active" aria-current="page">Daftar</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
@include('swal')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border d-flex justify-content-between">
                    <div class="d-flex justify-content-start">
                        <!-- Modal trigger button -->
                        <form action="{{ route('fakultas.pengajuan-cuti.index') }}" method="get" id="semesterForm">
                            <div class="mb-3">
                                <label for="semester_view" class="form-label">Semester</label>
                                <select
                                    class="form-select"
                                    name="semester_view"
                                    id="semester_view"
                                    onchange="document.getElementById('semesterForm').submit();"
                                >
                                    <option value="" selected disabled>-- Pilih Semester --</option>
                                    @foreach ($pilihan_semester as $p)
                                        <option value="{{$p->id_semester}}"
                                            @if ($semester_view != null)
                                            {{$semester_view == $p->id_semester ? 'selected' : ''}}
                                            @else
                                            {{$semester_aktif->id_semester == $p->id_semester ? 'selected' : ''}}
                                            @endif
                                            >{{$p->nama_semester}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                        {{-- <button type="button" class="btn btn-primary waves-effect waves" data-bs-toggle="modal"
                            data-bs-target="#filter-button">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                        <span class="divider-line mx-1"></span>
                        <a href="{{route('fakultas.beasiswa')}}" class="btn btn-warning waves-effect waves" >
                            <i class="fa fa-rotate"></i> Reset Filter
                        </a>
                        @include('fakultas.lain-lain.beasiswa.filter') --}}

                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Semester</th>
                                    <th class="text-center align-middle">Program Studi</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">Nama Mahasiswa</th>
                                    <th class="text-center align-middle">Alasan Pengajuan</th>
                                    <th class="text-center align-middle">Status </th>
                                    <th class="text-center align-middle">File Pendukung</th>
                                    <th class="text-center align-middle">Alasan Ditolak</th>
                                    <th class="text-center align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                @include('fakultas.lain-lain.pengajuan-cuti.decline')
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td class="text-start align-middle" style="white-space:nowrap;">{{$d->nama_semester}}</td>
                                        <td class="text-start align-middle">{{$d->prodi->nama_jenjang_pendidikan}} - {{$d->prodi->nama_program_studi}}</td>
                                        <td class="text-start align-middle">{{$d->riwayat->nim}}</td>
                                        <td class="text-start align-middle">{{$d->nama_mahasiswa}}</td>
                                        <td class="text-start align-middle">{{$d->alasan_cuti}}</td>
                                        <td class="text-start align-middle text-nowrap">
                                            <a href="{{ $d->file_pendukung ? asset('storage/' . $d->file_pendukung) : '#' }}" target="_blank" class="btn btn-sm btn-primary mb-5 {{ $d->file_pendukung ? '' : 'd-none' }}">
                                                <i class="fa fa-file-pdf-o"></i> Lihat File
                                            </a>
                                            <span class="badge badge-danger mb-5 {{ $d->file_pendukung ? 'd-none' : '' }}">File tidak diupload</span>
                                        </td>
                                           
                                        <td class="text-center align-middle" style="width:10%">
                                            @if($d->approved == 0)
                                                <span class="badge badge-l badge-danger mb-5">Belum Disetujui</span>
                                            @elseif($d->approved == 1)
                                                <span class="badge badge-l badge-warning mb-5">Disetujui Fakultas</span>
                                            @elseif($d->approved == 2)
                                                <span class="badge badge-l badge-success mb-5">Disetujui BAK</span>
                                            @elseif($d->approved == 3)
                                                <span class="badge badge-l badge-danger mb-5">Ditolak Fakultas</span>
                                            @elseif($d->approved == 4)
                                                <span class="badge badge-l badge-danger mb-5">Ditolak BAK</span>
                                            @endif
                                        </td>
                                        <td class="text-start align-middle">{{$d->alasan_pembatalan}}</td>
                                        <td class="text-center align-middle text-nowrap">
                                            <div class="row">
                                                @if($d->approved == 0)
                                                <form action="{{route('fakultas.pengajuan-cuti.approve', $d)}}" method="post" id="approveForm{{$d->id_cuti}}" class="approve-class" data-id='{{$d->id_cuti}}'>
                                                    @csrf
                                                    <div class="row  mb-5">
                                                        <button 
                                                        type="submit" 
                                                        class="btn btn-sm btn-success" title="Setujui Pengajuan Cuti"><i class="fa fa-thumbs-up"></i> Approve</button>
                                                    </div>
                                                </form>
                                                @endif
                                                @if($d->approved < 3)
                                                    <a href="#" class="btn btn-danger btn-sm mb-5" title="Tolak Bimbingan" data-bs-toggle="modal" data-bs-target="#pembatalanModal{{$d->id}}"><i class="fa fa-ban"></i> Decline</a>
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

<script>
    $(function () {
        $('#data').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            // "responsive": true,
        });
    });

    $('.approve-class').submit(function(e){
        e.preventDefault();
        var form = this;
        swal({
            title: 'Apakah anda yakin?',
            text: "Anda akan menyetujui pengajuan cuti ini.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                form.submit();
            }
        });
    });
</script>

@endpush

