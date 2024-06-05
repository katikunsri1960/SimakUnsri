@extends('layouts.dosen')
@section('title')
Rencana Pembelajaran Semester
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
								<h2 class="mb-10">Rencana Pembelajaran Semester</h2>
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
                    <div class="col-xl-12 col-lg-12">
                        <h3 class="fw-500 text-dark mt-0">Daftar Mata Kuliah Dosen</h3>
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
                                    <th>Kurikulum</th>
                                    <th>Status RPS</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                    <tr>
                                        <td class="text-center align-middle">{{$loop->iteration}}</td>
                                        <td class="text-center align-middle">{{$d->kode_mata_kuliah}}</td>
                                        <td class="text-start align-middle">{{$d->nama_mata_kuliah}}</td>
                                        <td class="text-center align-middle">{{$d->matkul->kurikulum ? $d->matkul->kurikulum->nama_kurikulum : '-'}}</td>
                                        <td>@if($d->jumlah_rps == 0 && $d->jumlah_approved == 0)
                                                <span class="badge badge-secondary">Belum di Isi<span>
                                            @elseif($d->jumlah_rps > 0 && $d->jumlah_approved >= 0 && $d->jumlah_rps != $d->jumlah_approved)
                                                <span class="badge badge-danger">Belum di Setujui<span>
                                            @elseif($d->jumlah_rps > 0 && $d->jumlah_approved > 0 && $d->jumlah_rps == $d->jumlah_approved)
                                                <span class="badge badge-success">Sudah di Setujui<span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-rounded bg-warning" href="{{route('dosen.perkuliahan.rencana-pembelajaran.detail', ['matkul' => $d->id_matkul])}}"><i class="fa fa-search"></i> Lihat RPS</a>
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

