@extends('layouts.dosen')
@section('title')
Jadwal Kuliah Dosen
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
								<h2 class="mb-10">Jadwal Mengajar Dosen</h2>
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
                    <div class="col-xl-4 col-lg-12">
                        <h3 class="fw-500 text-dark mt-0">Daftar Jadwal Mengajar Dosen</h3>
                    </div>                             
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Mata Kuliah</th>
                                    <th>Nama Mata Kuliah</th>                                    
                                    <th>Nama Kelas</th>
                                    <th>Ruang Perkuliahan</th>
                                    <th>Semester</th>
                                    <th>Jadwal Kuliah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$d->kode_mata_kuliah}}</td>
                                        <td>{{$d->nama_mata_kuliah}}</td>
                                        <td>{{$d->nama_kelas_kuliah}}</td>
                                        <td>{{$d->nama_ruang}}</td>
                                        <td>{{$d->nama_semester}}</td>
                                        <td>{{$d->jadwal_hari}}, {{$d->jadwal_jam_mulai}} - {{$d->jadwal_jam_selesai}}</td>
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
            "paging": true,
            "ordering": true,
            "searching": true,
            // "scrollCollapse": true,
            // "scrollY": "550px",
        });

    });
</script>

@endpush

