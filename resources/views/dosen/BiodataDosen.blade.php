@extends('layouts.dosen')
@section('title')
Biodata Dosen
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
								<h2 class="mb-10">Biodata Dosen</h2>
								<p class="mb-0 text-fade fs-18">Universitas Sriwijaya</p>
							</div>
						</div>
					<div>
				</div>
			</div>							
		</div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-12">
            <div class="bg-primary-light rounded20  big-side-section">
                <div class="row">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 pt-20 px-20">
                        <div class="box">		
                            <div class="text-white box-body bg-img text-center m-20 py-65" style="background-image: url({{asset('images/images/gallery/creative/img-12.jpg')}});">
                            </div>
                            <div class="box-body up-mar100 pb-0">	
                                <div class=" justify-content-center">
                                    <div>
                                        <div class="bg-white px-10 text-center pt-15 w-120 ms-20 mb-0 rounded20 mb-20">
                                            <a href="#" class="w-80">
                                                <img class="avatar avatar-xxl rounded20 bg-light img-fluid" src="{{asset('images/images/avatar/avatar-16.png')}}" alt="">
                                            </a>	
                                        </div>
                                        <div class="ms-30 mb-15">
                                            <h5 class="my-10 mb-0 text-dark fw-500 fs-18">Nama Dosen</h5>
                                            <span class="text-fade mt-5">Nama Homebase Program Studi</span>
                                        </div>
                                    </div>
                                </div>
                            </div>					
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                            <div class="box box-body">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12">
                                        <h3 class="fw-500 text-dark mt-0">Biodata</h3>
                                    </div>                             
                                </div>
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Nama</th>
                                                    <td> : </td>
                                                    <td>Mark</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">NIP</th>
                                                    <td> : </td>
                                                    <td>Jacob</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">NIDN</th>
                                                    <td> : </td>
                                                    <td>Larry</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>		
</section>
@endsection
