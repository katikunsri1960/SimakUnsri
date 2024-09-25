@extends('layouts.dosen')
@section('title')
Bimbingan Akademik Dosen
@endsection
@section('content')
@push('header')
<div class="mx-4">
    <a href="{{route('dosen.pembimbing.bimbingan-akademik.detail', ['riwayat' => $mahasiswa])}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-12">
			<div class="box pull-up">
				<div class="box-body bg-img bg-primary-light">
					<div class="d-lg-flex align-items-center justify-content-between">
						<div class="d-lg-flex align-items-center mb-30 mb-xl-0 w-p100">
			    			<img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" class="img-fluid max-w-250" alt="" />
							<div class="ms-30">
                                <h2 class="mb-10">KHS</h2>
								<p class="mb-0 text-fade fs-18">{{$mahasiswa->nama_mahasiswa}}</p>
							</div>
						</div>
					<div>
				</div>
			</div>
		</div>
    </div>
    @include('swal')
    <div class="row">
        <div class="col-xxl-12 py-10 mx-10">
            @foreach($data_aktivitas as $d)
                <div class="box">
                    <div class="box-body mb-0 bg-white">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped text-left">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle">{{$d->nama_semester}}</th>
                                            <th class="text-center align-middle">SKS Semester</th>
                                            <th class="text-center align-middle">SKS Total</th>
                                            <th class="text-center align-middle">IP Semester</th>
                                            <th class="text-center align-middle">IP Komulatif</th>
                                            <th class="text-center align-middle">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @if($d->id_status_mahasiswa == 'A')
                                                <td class="text-center align-middle"><span class="badge badge-primary me-15">{{$d->nama_status_mahasiswa}}</span></td>
                                            @else
                                                <td class="text-center align-middle">{{$d->nama_status_mahasiswa}}</td>
                                            @endif
                                            <td class="text-center align-middle">{{$d->sks_semester}}</td>
                                            <td class="text-center align-middle">{{$d->sks_total}}</td>
                                            <td class="text-center align-middle">{{$d->ips}}</td>
                                            <td class="text-center align-middle">{{$d->ipk}}</td>
                                            <td class="text-center align-middle">
                                                <a type="button" href="{{route('dosen.pembimbing.bimbingan-akademik.detail-khs', ['riwayat' => $mahasiswa->id_registrasi_mahasiswa,'semester' => $d->id_semester])}}" class="btn btn-success waves-effect waves-light">
                                                <i class="fa-solid fa-eye"></i> Lihat KHS
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                </div>
            @endforeach	
        </div>
    </div> 
</section>
@endsection

