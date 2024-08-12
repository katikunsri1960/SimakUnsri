@extends('layouts.prodi')
@section('title')
Lihat RPS Mata Kuliah
@endsection
@section('content')
@push('header')
<div class="mx-4">
    <a href="{{route('prodi.data-master.mata-kuliah')}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
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
                                <h2 class="mb-10">RPS Mata Kuliah</h2>
								<p class="mb-0 text-fade fs-18">{{$matkul->nama_mata_kuliah}}</p>
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
                                    <td class="px-3"><h4>{{$matkul->kode_mata_kuliah}}</h4></td>
                                </tr>
                                <tr>
                                    <td><h4>Nama Mata Kuliah</h4></td>
                                    <td class="px-3"><h4>:</h4></td>
                                    <td class="px-3"><h4>{{$matkul->nama_mata_kuliah}}</h4></td>
                                </tr>
                                <tr>
                                    <td><h4>SKS Mata Kuliah</h4></td>
                                    <td class="px-3"><h4>:</h4></td>
                                    <td class="px-3"><h4>{{$matkul->sks_mata_kuliah}}</h4></td>
                                </tr>
                                <tr>
                                    <td><h4>Link RPS</h4></td>
                                    <td class="px-3"><h4>:</h4></td>
                                    <td class="px-3"><h4>{{$matkul->link_rps}}</h4></td>
                                </tr>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end align-middle">
                            <div class="">
                                <form action="{{route('prodi.data-master.mata-kuliah.approved-all', ['matkul' => $matkul->id_matkul])}}" method="post" id="approveAll">
                                @csrf
                                <button class="btn btn-primary btn-rounded" type="submit" @if ($data->where('approved', '0')->count() == 0)
                                    disabled
                                @endif>Setujui Semua RPS</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="rps" class="table table-bordered table-striped" style="font-size: 12px">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center align-middle">NO</th>
                                    <th class="text-center align-middle">MATERI INDONESIA</th>
                                    <th class="text-center align-middle">MATERI INGGRIS</th>
                                    <th class="text-center align-middle">PERTEMUAN</th>
                                    <th class="text-center align-middle">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-start align-middle">{{$d->materi_indonesia}}</td>
                                    <td class="text-start align-middle">{{$d->materi_inggris}}</td>
                                    <td class="text-center align-middle">{{$d->pertemuan}}</td>
                                    <td class="text-center align-middle">
                                        @if ($d->approved == '1')
                                            <span class="badge bg-success rounded">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Belum Disetujui</span>
                                        @endif
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
            $('#rps').DataTable({
                "paging": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
            });

            confirmSubmit('approveAll');
        });
    </script>
@endpush

