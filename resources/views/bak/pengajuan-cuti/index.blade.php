@extends('layouts.bak')
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
                        <li class="breadcrumb-item"><a href="{{route('bak')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Pengajuan Cuti</li>
                        <li class="breadcrumb-item active" aria-current="page">Daftar</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
@include('swal')
@include('bak.pengajuan-cuti.create')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border d-flex justify-content-between">
                    <div class="d-flex justify-content-start">
                        <!-- Modal trigger button -->
                        <form action="{{ route('bak.pengajuan-cuti') }}" method="get" id="semesterForm">
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
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive mt-5">
                        <table id="data" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Semester</th>
                                    <th class="text-center align-middle">Prodi</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">Nama Mahasiswa</th>
                                    <th class="text-center align-middle">Alasan Pengajuan Cuti</th>
                                    <th class="text-center align-middle">Status</th>
                                    <th class="text-center align-middle">Alasan Ditolak</th>
                                    <th class="text-center align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                @include('bak.pengajuan-cuti.pembatalan-cuti')
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td class="text-start align-middle" style="white-space:nowrap;">{{$d->nama_semester}}</td>
                                        <td class="text-start align-middle">{{$d->prodi ? $d->prodi->nama_jenjang_pendidikan : ''}} {{$d->prodi ? $d->prodi->nama_program_studi : ''}}</td>
                                        <td class="text-start align-middle">{{$d->riwayat->nim}}</td>
                                        <td class="text-start align-middle">{{$d->riwayat->nama_mahasiswa}}</td>
                                        <td class="text-start align-middle">{{$d->alasan_cuti}}</td>
                                        <td class="text-center align-middle" style="width:10%">
                                            @if($d->approved == 0)
                                                <span class="badge badge-xl badge-danger mb-5">Belum Disetujui</span>
                                            @elseif($d->approved == 1)
                                                <span class="badge badge-xl badge-warning mb-5">Disetujui Fakultas</span>
                                            @elseif($d->approved == 2)
                                                <span class="badge badge-xl badge-success mb-5">Disetujui BAK</span>
                                            @elseif($d->approved == 3)
                                                <span class="badge badge-xl badge-danger mb-5">Ditolak Fakultas</span>
                                            @elseif($d->approved == 4)
                                                <span class="badge badge-xl badge-danger mb-5">Ditolak BAK</span>
                                            @endif
                                        </td>
                                        <td class="text-start align-middle">{{$d->alasan_pembatalan}}</td>
                                        <td class="text-center align-middle text-nowrap">
                                            <div class="row">
                                                @if($d->approved == 1)
                                                <form action="{{route('bak.pengajuan-cuti.approve', $d)}}" method="post" id="approveForm{{$d->id_cuti}}" class="approve-class" data-id='{{$d->id_cuti}}'>
                                                    @csrf
                                                    <div class="row  mb-5">
                                                        <button
                                                        type="submit"
                                                        class="btn btn-sm btn-success" title="Setujui Pengajuan Cuti"><i class="fa fa-thumbs-up"></i> Approve</button>
                                                    </div>
                                                </form>
                                                @endif
                                                @if($d->approved == 1 || $d->approved < 3)
                                                    <a href="#" class="btn btn-danger btn-sm mb-5" title="Tolak Bimbingan" data-bs-toggle="modal" data-bs-target="#pembatalanModal{{$d->id}}"><i class="fa fa-ban"></i> Decline</a>
                                                @endif
                                                <a href="{{ asset('storage/' . $d->file_pendukung) }}" target="_blank" class="btn btn-sm btn-primary mb-5">
                                                    <i class="fa fa-file-pdf-o"></i> File Pendukung
                                                </a>
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
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#data').DataTable();
        });
    </script>
@endpush
