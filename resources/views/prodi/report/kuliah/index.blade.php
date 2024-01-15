@extends('layouts.prodi')
@section('title')
Report Perkuliahan Mahasiswa
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
								<h2 class="mb-10">Laporan Data Perkuliahan Mahasiswa</h2>
								<p class="mb-0 text-fade fs-18">Universitas Sriwijaya</p>
							</div>
						</div>
					<div>
				</div>
			</div>							
		</div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-12">
            <div class="box bg-light">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-xl-4 col-lg-12 pb-20">
                            <a class="btn btn-rounded bg-warning-light" href="{{route('dosen.penilaian.penilaian-perkuliahan')}}"><i class="fa fa-chevron-left"><span class="path1"></span><span class="path2"></span></i> Kembali</a>
                        </div>                             
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-12">
                            <h3 class="fw-500 text-dark mt-0">Perkuliahan Mahasiswa</h3>
                        </div>                             
                    </div>
                </div>
                <!-- /.box-header -->
                <form class="form">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">

                            <div class="form-group">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" placeholder="First Name">
                            </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" placeholder="Last Name">
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">E-mail</label>
                                <input type="text" class="form-control" placeholder="E-mail">
                            </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Contact Number</label>
                                <input type="text" class="form-control" placeholder="Phone">
                            </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-end">
                        <button type="button" class="btn btn-warning me-1">
                            <i class="ti-trash"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti-save-alt"></i> Save
                        </button>
                    </div>  
                </form>
            </div>
            <!-- /.box -->			
        </div>
    </div>			
</section>
@endsection

