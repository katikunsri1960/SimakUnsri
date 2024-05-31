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
                        <table id="example1" class="table table-bordered table-striped text-center">
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
                                <tr>
                                    <td>1</td>
                                    <td>Customer Support</td>
                                    <td>New York</td>
                                    <td>New York</td>
                                    <td>10</td>
                                    <td>
                                        <a class="btn btn-rounded bg-success-light" href=""><i class="fa fa-eye"></i> Lihat RPS</a>
                                        <a class="btn btn-rounded bg-success-light" href=""><i class="fa fa-calendar-plus-o"></i> Update RPS</a>
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

