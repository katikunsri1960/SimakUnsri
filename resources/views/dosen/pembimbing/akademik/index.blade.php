@extends('layouts.dosen')
@section('title')
Pembimbingan Akademik Dosen
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
								<h2 class="mb-10">Pembimbingan Akademik Dosen</h2>
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
            <div class="box box-body mb-0 bg-white">
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <h3 class="fw-500 text-dark mt-0">
                            Tahun Ajaran - {{$semester->semester->nama_semester}}</h3>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="dt" class="table table-bordered table-striped" style="font-size: 12px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">AKT</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">NAMA</th>
                                    <th class="text-center align-middle">PRODI</th>
                                    <th class="text-center align-middle">STATUS</th>
                                    <th class="text-center align-middle">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$d->angkatan}}</td>
                                    <td class="text-center align-middle">{{$d->nim}}</td>
                                    <td class="text-start align-middle">{{$d->nama_mahasiswa}}</td>
                                    <td class="text-center align-middle">{{$d->prodi->nama_jenjang_pendidikan}} {{$d->prodi->nama_program_studi}}</td>
                                    <td class="text-center align-middle">
                                        @if (($d->peserta_kelas_count ?? 0) == 0 && ($d->aktivitas_mahasiswa_count ?? 0) == 0)
                                            <span class="badge bg-danger">Tidak Ada KRS</span>
                                        @elseif (($d->peserta_kelas_setujui_count ?? 0) == 0 || ($d->aktivitas_mahasiswa_setujui_count ?? 0) == 0)
                                            <span class="badge bg-warning">Belum Disetujui</span>
                                        @else
                                            <span class="badge bg-success">Sudah Disetujui</span>
                                        @endif

                                    </td> 
                                    <td class="text-center align-middle">
                                        <a href="{{route('dosen.pembimbing.bimbingan-akademik.detail', ['riwayat' => $d])}}" class="btn btn-primary btn-rounded btn-sm">Proses KRS <i class="fa fa-file"></i></a>
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
        $('#dt').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "responsive": true,
            "stateSave": true
        });
    });
</script>
@endpush

