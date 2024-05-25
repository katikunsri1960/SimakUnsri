@extends('layouts.dosen')
@section('title')
Bimbingan Akademik Dosen
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
                                <h2 class="mb-10">KRS</h2>
								<p class="mb-0 text-fade fs-18">{{$riwayat->nama_mahasiswa}}</p>
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
                        <table>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td>{{$riwayat->nama_mahasiswa}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped" style="font-size: 12px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">NO</th>
                                    <th class="text-center align-middle">MK</th>
                                    <th class="text-center align-middle">SKS</th>
                                    <th class="text-center align-middle">KELAS</th>
                                    <th class="text-center align-middle">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center align-middle">{{$d->kelas_kuliah->matkul->kode_mata_kuliah}} - {{$d->kelas_kuliah->matkul->nama_mata_kuliah}}</td>
                                    <td class="text-center align-middle">{{$d->kelas_kuliah->matkul->sks_mata_kuliah}}</td>
                                    <td class="text-center align-middle">{{$d->kelas_kuliah->nama_kelas}}</td>
                                    <td class="text-center align-middle">
                                        {{-- <a href="{{route('dosen.pembimbing.krs.detail', ['riwayat' => $d])}}" class="btn btn-primary btn-rounded btn-sm">Detail</a> --}}
                                    </td>
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

