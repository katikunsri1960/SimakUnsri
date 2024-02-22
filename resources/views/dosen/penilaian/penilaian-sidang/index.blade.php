@extends('layouts.dosen')
@section('title')
Penilaian Sidang Mahasiswa
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
								<h2 class="mb-10">Penilaian Sidang Mahasiswa</h2>
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
            <div class="box box-body mb-0 bg-light">
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <h3 class="fw-500 text-dark mt-0">Daftar Sidang Mahasiswa</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Prodi</th>
                                    <th>Jenis Aktivitas</th>
                                    <th>Mahasiswa</th>
                                    <th>Penguji</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$d->prodi->nama_jenjang_pendidikan}} - {{$d->prodi->nama_program_studi}}</td>
                                    <td>{{$d->jenis_aktivitas_mahasiswa->nama_jenis_aktivitas_mahasiswa}}</td>
                                    <td class="text-start align-middle">
                                        <ul>
                                        @foreach ($d->anggota_aktivitas as $m)
                                            <li>
                                                {{$m->mahasiswa->nama_mahasiswa}}
                                                ({{$m->mahasiswa->nim}})
                                            </li>
                                        @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-start align-middle">
                                        <ul>
                                        @foreach ($d->uji_mahasiswa as $u)
                                            <li>{{$u->nama_kategori_kegiatan}} : {{$u->dosen->nama_dosen}}</li>
                                        @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <a class="btn btn-rounded bg-warning-light" href="{{route('dosen.perkuliahan.kesediaan-waktu-bimbingan')}}" title="Presentase Nilai"><i class="fa fa-percent"><span class="path1"></span><span class="path2"></span></i></a>
                                        <a class="btn btn-rounded bg-success-light" href="{{route('dosen.perkuliahan.kesediaan-waktu-bimbingan')}}" title="Download DPNA"><i class="fa fa-download"><span class="path1"></span><span class="path2"></span></i></a>
                                        <a class="btn btn-rounded bg-primary-light" href="{{route('dosen.perkuliahan.kesediaan-waktu-bimbingan')}}" title="Upload DPNA"><i class="fa fa-upload"><span class="path1"></span><span class="path2"></span></i></a>
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
            "paging": false,
            "ordering": true,
            "searching": true,
            "scrollCollapse": true,
            "scrollY": "550px",
        });

    });
</script>

@endpush

