@extends('layouts.fakultas')
@section('title')
Pendaftaran Wisuda Fakultas
@endsection
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
								<h2 class="mb-10">Pendaftaran Wisuda Fakultas</h2>
								<p class="mb-0 text-fade fs-18">Universitas Sriwijaya</p>
							</div>
						</div>
					<div>
				</div>
			</div>							
		</div>
    </div>
    <div class="row">
        <div class="col-xxl-12">
            <div class="box box-body mb-0">
                <div class="row">
                    <div class="col-xl-6 col-lg-12">
                        <h3 class="fw-500 text-dark mt-0">Daftar Peserta Wisuda Fakultas</h3>
                    </div>                             
                </div>
                {{-- <div class="row mb-5">
                    <div class="col-xl-12 col-lg-12 text-end">
                        <div class="btn-group">
                            <a class="btn btn-rounded bg-success-light " href="{{route('fakultas.pengajuan-cuti.tambah')}}"><i class="fa fa-plus"><span class="path1"></span><span class="path2"></span></i> Tambah Pendaftaran Wisuda</a>
                        </div>   
                    </div>                           
                </div><br> --}}
                <div class="row">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pas Foto</th>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Program Studi</th>
                                    <th>No SK Yudisium</th>
                                    <th>Tanggal SK Yudisium</th>
                                    <th>Status Pendaftaran Wisuda</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                @include('fakultas.lain-lain.wisuda.approve-wisuda')
                                @include('fakultas.lain-lain.wisuda.decline-wisuda')
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td class="text-center align-middle text-nowrap">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#fotoModal{{$d->id}}">
                                                <img src="{{ asset($d->pas_foto) }}" alt="Pas Foto" style="width: 150px;" title="Lihat Foto">
                                            </a>
                                            <!-- Modal -->
                                            <div class="modal fade" id="fotoModal{{$d->id}}" tabindex="-1" aria-labelledby="fotoModalLabel{{$d->id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content rounded-3">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="fotoModalLabel{{$d->id}}">PAS FOTO {{$d->nama_mahasiswa}}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center m-20">
                                                            <img src="{{ asset($d->pas_foto) }}" alt="Pas Foto" style="width: 100%; max-width: 500px;" class="rounded-3">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">{{$d->nim}}</td>
                                        <td class="text-start align-middle">{{$d->nama_mahasiswa}}</td>
                                        <td class="text-start align-middle">{{$d->prodi->nama_jenjang_pendidikan}} - {{$d->prodi->nama_program_studi}}</td>
                                        <td class="text-start align-middle">{{$d->no_sk_yudisium}}</td>
                                        <td class="text-start align-middle">{{$d->tgl_sk_yudisium}}</td>
                                        
                                        
                                        <td class="text-center align-middle" style="width:10%">
                                            @if($d->approved == 0)
                                                <span class="badge badge-xl badge-warning-light mb-5">Belum Disetujui Koor. Prodi</span>
                                            @elseif($d->approved == 1)
                                                <span class="badge badge-xl badge-primary-light mb-5">Disetujui Koor. Prodi</span>
                                            @elseif($d->approved == 2)
                                                <span class="badge badge-xl badge-primary-light mb-5">Disetujui Fakultas</span>
                                            @elseif($d->approved == 3)
                                                <span class="badge badge-xl badge-success-light mb-5">Disetujui BAK</span>
                                            @elseif($d->approved == 97)
                                                <span class="badge badge-xl badge-danger-light mb-5">Ditolak Koor. Prodi</span>
                                            @elseif($d->approved == 98)
                                                <span class="badge badge-xl badge-danger-light mb-5">Ditolak Fakultas</span>
                                            @elseif($d->approved == 99)
                                                <span class="badge badge-xl badge-danger-light mb-5">Ditolak BAK</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle text-nowrap">
                                            <div class="row">
                                                @if($d->approved == 1)
                                                    <a href="#" class="btn btn-success btn-sm my-2 
                                                    {{-- @if($d->approved != 1) disabled @endif --}}
                                                    " title="Setujui Pangajuan" data-bs-toggle="modal" data-bs-target="#approveModal{{$d->id}}">
                                                        <i class="fa fa-check"> </i> Approve
                                                    </a>
                                                @endif
                                                @if($d->approved == 1 || $d->approved == 2)
                                                    <a href="#" class="btn btn-danger btn-sm my-2" title="Tolak Pangajuan" data-bs-toggle="modal" data-bs-target="#declineModal{{$d->id}}"><i class="fa fa-ban"> </i>  Decline</a>
                                                @endif
                                                <a href="{{ asset($d->abstrak_file) }}" target="_blank" class="btn btn-sm btn-primary my-2">
                                                    <i class="fa fa-file-pdf-o"></i> File Abstrak
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
</script>

@endpush

