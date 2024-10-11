@extends('layouts.mahasiswa')
@section('title')
Pengajuan Cuti Mahasiswa
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
								<h2 class="mb-10">Halaman Pengajuan Cuti Mahasiswa,  {{auth()->user()->name}}</h2>
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
                        <h3 class="fw-500 text-dark mt-0">Daftar Pengajuan Cuti Mahasiswa</h3>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-xl-12 col-lg-12 text-end">
                        <div class="btn-group">
                            <a class="btn btn-rounded bg-success-light " href="{{route('mahasiswa.pengajuan-cuti.tambah')}}"><i class="fa fa-plus"><span class="path1"></span><span class="path2"></span></i> Tambah Pengajuan Cuti</a>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Semester</th>
                                    <th>Alasan Pengajuan Cuti</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td class="text-start align-middle" style="white-space:nowrap;">{{$d->nama_semester}}</td>
                                        <td class="text-start align-middle" style="white-space:nowrap;">{{$d->alasan_cuti}}</td>
                                        <td class="text-center align-middle" style="width:10%">
                                            @if($d->approved == 0)
                                                <span class="badge badge-xl badge-danger-light mb-5">Belum Disetujui</span>
                                            @elseif($d->approved == 1)
                                                <span class="badge badge-xl badge-warning-light mb-5">Disetujui Fakultas</span>
                                            @elseif($d->approved == 2)
                                                <span class="badge badge-xl badge-success-light mb-5">Disetujui BAK</span>
                                            @endif
                                        </td>
                                        {{-- <td>{{$d->file_pendukung}}</td> --}}
                                        <td class="text-center align-middle" style="width:3%">
                                            <form action="{{route('mahasiswa.pengajuan-cuti.delete',$d->id_cuti)}}" method="post" class="delete-form" data-id="{{$d->id_cuti}}" id="deleteForm{{$d->id_cuti}}">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger" data-id="{{ $d->id_cuti }}" title="Hapus Data">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                        {{-- <td>
                                            <form action="{{ route('mahasiswa.pengajuan-cuti.delete', $d->id_cuti) }}" method="POST" class="delete-form">
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

        @if ($showAlert1)
         
            swal({
                title: 'Pengajuan Cuti Tidak Diizinkan',
                text: 'Anda tidak bisa mengajukan cuti, karena Anda Mahasiswa Program Pendidikan Profesi',
                type: 'warning',
                button: 'OK'
            }, function() {
                window.location.href = "{{ route('mahasiswa.dashboard') }}"; // Ganti dengan rute yang sesuai, jika ada
            });
        @elseif ($showAlert2)
            swal({
                title: 'Pengajuan Cuti Tidak Diizinkan',
                text: 'Anda tidak bisa mengajukan cuti, karena Anda Mahasiswa Penerima Beasiswa',
                type: 'warning',
                button: 'OK'
            }, function() {
                window.location.href = "{{ route('mahasiswa.dashboard') }}"; // Ganti dengan rute yang sesuai, jika ada
            });
        @elseif ($showAlert3)
            swal({
                title: 'Pengajuan Cuti Tidak Diizinkan',
                text: 'Anda tidak bisa mengajukan cuti, karena belum menyelesaikan 4 semester',
                type: 'warning',
                button: 'OK'
            }, function() {
                window.location.href = "{{ route('mahasiswa.dashboard') }}"; // Ganti dengan rute yang sesuai, jika ada
            });
        @elseif ($showAlert4)
            swal({
                title: 'Pengajuan Cuti Tidak Diizinkan',
                text: 'Anda tidak bisa mengajukan cuti, karena belum menyelesaikan 50% dari SKS Total Yang harus ditempuh.',
                type: 'warning',
                button: 'OK'
            }, function() {
                window.location.href = "{{ route('mahasiswa.dashboard') }}"; // Ganti dengan rute yang sesuai, jika ada
            });
        @endif

        $('.delete-form').submit(function(e){
            e.preventDefault();
            var formId = $(this).data('id');
            swal({
                title: 'Apakah Anda Yakin??',
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
