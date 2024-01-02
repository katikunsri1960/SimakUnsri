@extends('layouts.dosen')
@section('title')
Penilaian Perkuliahan Mahasiswa
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
								<h2 class="mb-10">Penilaian Perkuliahan Mahasiswa</h2>
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
                        <h3 class="fw-500 text-dark mt-0">Daftar Perkuliahan Mahasiswa</h3>
                    </div>                             
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Mata Kuliah</th>
                                    <th>Nama Mata Kuliah</th>                                    
                                    <th>Kode Kelas</th>
                                    <th>Nama Kelas</th>
                                    <th>Tanggal Akhir Perkuliahan</th>
                                    <th>Tanggal Akhir Pengisian Nilai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Customer Support</td>
                                    <td>New York</td>
                                    <td>New York</td>
                                    <td>New York</td>
                                    <td>New York</td>
                                    <td>10</td>
                                    <td>
                                        <a class="btn btn-rounded bg-warning-light" href="{{route('dosen.penilaian.presentase-penilaian-perkuliahan')}}" title="Presentase Nilai"><i class="fa fa-percent"><span class="path1"></span><span class="path2"></span></i></a>
                                        <a class="btn btn-rounded bg-success-light" href="#" title="Download DPNA"><i class="fa fa-download"><span class="path1"></span><span class="path2"></span></i></a>
                                        <a class="btn btn-rounded bg-primary-light" href="#" title="Upload DPNA"><i class="fa fa-upload"><span class="path1"></span><span class="path2"></span></i></a>
                                    </td>
                                </tr>
                            </tbody>
					  </table>
                    </div>
                </div>
            </div>
        </div>
    </div>			
</section>
@endsection

