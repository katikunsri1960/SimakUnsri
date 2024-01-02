@extends('layouts.mahasiswa')
@section('title')
Ambil KRS
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
								<h2 class="mb-10">Kartu Rencana Studi</h2>
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
                            <a class="btn btn-rounded bg-warning-light" href="{{route('mahasiswa.krs')}}"><i class="fa fa-chevron-left"><span class="path1"></span><span class="path2"></span></i> Kembali</a>
                        </div>                             
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <h3 class="fw-500 text-dark mt-0">Riwayat Pembayaran Uang Kuliah Tunggal</h3>
                        </div>                             
                    </div>
                </div>
                <!-- /.box-header -->
                <form class="form">
                    <div class="row">
                        <div class="col-xxl-12">
                            <div class="box box-body mb-0 bg-light">
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
                                                    <th>SKS</th>
                                                    <th>Nama Dosen</th>
                                                    <th>Waktu Kuliah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>FSK11711</td>
                                                    <td>KALKULUS II</td>
                                                    <td>IDL01</td>
                                                    <td>Inderalaya A</td>
                                                    <td>3</td>
                                                    <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                    <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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

