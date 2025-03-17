@extends('layouts.mahasiswa')
@section('title')
PENUNDAAN BAYAR 
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
								<h2 class="mb-10">Halaman Penundaan Bayar Mahasiswa,  {{auth()->user()->name}}</h2>
                                <p class="text-dark mb-0 fs-16">
                                    SIMAK Universitas Sriwijaya
                                </p>
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
                        <h3 class="fw-500 text-dark mt-0">Daftar Penundaan Bayar</h3>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-xl-12 col-lg-12 text-end">
                        <div class="btn-group">
                            <a class="btn btn-rounded bg-success " href="{{route('mahasiswa.penundaan-bayar.tambah')}}"><i class="fa fa-plus"><span class="path1"></span><span class="path2"></span></i> Tambah Tunda Bayar</a>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Semester</th>
                                    <th class="text-center align-middle">Alasan Pengajuan</th>
                                    <th class="text-center align-middle">File Pendukung</th>
                                    <th class="text-center align-middle">Status</th>
                                    <th class="text-center align-middle">Alasan Ditolak</th>
                                    <th class="text-center align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td class="text-start align-middle" style="white-space:nowrap;">{{$d->semester->nama_semester}}</td>
                                        <td class="text-start align-middle" style="white-space:nowrap;">{{$d->keterangan ?? '-'}}</td>
                                        <td class= "text-center align-middle text-nowrap">
                                            <a href="{{ $d->file_pendukung ? asset('storage/' . $d->file_pendukung) : '#' }}" target="_blank" class="btn btn-sm btn-primary mb-5 {{ $d->file_pendukung ? '' : 'd-none' }}">
                                                <i class="fa fa-file-pdf-o"></i> Lihat File
                                            </a>
                                        </td>
                                        <td class="text-center align-middle" style="width:10%">
                                            @if($d->approved == 0)
                                                <span class="badge badge-l badge-danger mb-5">Diajukan</span>
                                            @elseif($d->approved == 2)
                                                <span class="badge badge-l badge-warning mb-5">Disetujui Koor. Prodi</span>
                                            @elseif($d->approved == 3)
                                                <span class="badge badge-l badge-success mb-5">Disetujui Fakultas</span>
                                            @elseif($d->approved == 4)
                                                <span class="badge badge-l badge-warning mb-5">Disetujui BAK</span>
                                            @elseif($d->approved == 5)
                                                <span class="badge badge-l badge-success mb-5">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="text-start align-middle">{{$d->alasan_pembatalan}}</td>
                                        <td class="text-center align-middle" style="width:3%">
                                            <form action="{{ route('mahasiswa.penundaan-bayar.delete', $d->id) }}" method="post" class="delete-form" data-id="{{ $d->id }}" id="deleteForm{{ $d->id }}">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger" data-id="{{ $d->id }}" title="Hapus Data" 
                                                    {{ $d->approved != 0 ? 'disabled' : '' }}>
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                        {{-- <td>
                                            <form action="{{ route('mahasiswa.pengajuan-cuti.delete', $d->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger rounded-10 delete-btn">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td> --}}
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
<script src="{{ asset('assets/vendor_components/sweetalert/sweetalert.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#data').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
        });

        $('.delete-form').submit(function(e){
            e.preventDefault();
            var formId = $(this).data('id');
            swal({
                title: 'Hapus Data',
                text: "Apakah anda yakin ingin menghapus data?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $(`#deleteForm${formId}`).unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });
</script>
@endpush
