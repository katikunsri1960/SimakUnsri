@extends('layouts.dosen')
@section('title')
Detail Kelas Kuliah
@endsection
@section('content')
@push('header')
<div class="mx-4">
    <a href="{{route('dosen.perkuliahan.jadwal-kuliah')}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
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
                                <h2 class="mb-10">Detail Kelas Kuliah Dosen</h2>
								<p class="mb-0 text-fade fs-18">{{$data[0]->nama_mata_kuliah}} - {{$data[0]->nama_kelas_kuliah}}</p>
							</div>
						</div>
					<div>
				</div>
			</div>
		</div>
    </div>
    @include('swal')
    <div class="row">
        <div class="col-xxl-12">
            <div class="box box-body mb-0">
                <div class="row">
                    <div class="col-xl-12 col-lg-12  d-flex justify-content-between">
                        <div class="d-flex justify-content-start">
                            <table>
                                <tr>
                                    <td><h4>Kode Mata Kuliah</h4></td>
                                    <td class="px-3"><h4>:</h4></td>
                                    <td class="px-3"><h4>{{$data[0]->kode_mata_kuliah}}</h4></td>
                                </tr>
                                <tr>
                                    <td><h4>Nama Mata Kuliah</h4></td>
                                    <td class="px-3"><h4>:</h4></td>
                                    <td class="px-3"><h4>{{$data[0]->nama_mata_kuliah}}</h4></td>
                                </tr>
                                <tr>
                                    <td><h4>Nama Kelas Kuliah</h4></td>
                                    <td class="px-3"><h4>:</h4></td>
                                    <td class="px-3"><h4>{{$data[0]->nama_kelas_kuliah}}</h4></td>
                                </tr>
                                <tr>
                                    <td><h4>Jadwal Kelas Kuliah</h4></td>
                                    <td class="px-3"><h4>:</h4></td>
                                    <td class="px-3"><h4>{{$data[0]->jadwal_hari}}, {{$data[0]->jadwal_jam_mulai}} - {{$data[0]->jadwal_jam_selesai}}</h4></td>
                                </tr>
                                <tr>
                                    <td><h4>Dosen Pengajar Kelas Kuliah</h4></td>
                                    <td class="px-3"><h4>:</h4></td>
                                    <td class="px-3">
                                        <ul>
                                            @foreach($data as $d)
                                                <li><h4>{{$d->nama_dosen}}</h4></li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="rps" class="table table-bordered table-striped" style="font-size: 12px">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center align-middle">NO</th>
                                    <th class="text-center align-middle">ANGKATAN</th>
                                    <th class="text-center align-middle">NIM MAHASISWA</th>
                                    <th class="text-center align-middle">NAMA MAHASISWA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($peserta as $p)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center align-middle">{{$p->angkatan}}</td>
                                    <td class="text-start align-middle">{{$p->nim}}</td>
                                    <td class="text-center align-middle">{{$p->nama_mahasiswa}}</td>
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
            $('#rps').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
            });

            confirmSubmit('approveAll');
        });
    </script>
@endpush

