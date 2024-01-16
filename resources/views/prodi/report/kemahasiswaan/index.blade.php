@extends('layouts.prodi')
@section('title')
Report Kemahasiswaan
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
								<h2 class="mb-10">Laporan Data Kemahasiswaan</h2>
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
                        <div class="col-xl-4 col-lg-12">
                            <h3 class="fw-500 text-dark mt-0">Kemahasiswaan</h3>
                        </div>                             
                    </div>
                </div>
                <!-- /.box-header -->
                <form class="form">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Angkatan</label>
                                    <select class="form-select">
                                        <option>option 1</option>
                                        <option>option 2</option>
                                        <option>option 3</option>
                                        <option>option 4</option>
                                        <option>option 5</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Status Mahasiswa</label>
                                    <select class="form-select">
                                        <option>option 1</option>
                                        <option>option 2</option>
                                        <option>option 3</option>
                                        <option>option 4</option>
                                        <option>option 5</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-end">
                        <button type="button" class="btn btn-warning me-1">
                            <i class="fa fa-trash"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-download"></i> Export
                        </button>
                    </div>  
                </form>
            </div>
            <!-- /.box -->			
        </div>
    </div>			
</section>
@endsection

