<div class="tab-pane active" id="data-diri" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light rounded20 big-side-section">
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
                                            <img class="avatar avatar-xxl rounded20 bg-light img-fluid" src="{{asset('images/images/avatar/avatar-15.png')}}" alt="">
                                        </a>	
                                    </div>
                                    <div class="ms-30 mb-15">
                                        <h5 class="my-10 mb-0 text-dark fw-500 fs-18">{{auth()->user()->name}}</h5>
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
                                            <!-- foreach -->
                                            <tr>
                                                <th scope="row">Nama</th>
                                                <td> : </td>
                                                <td>{{$biodata->nama_mahasiswa}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">NIK</th>
                                                <td> : </td>
                                                <td>{{$biodata->nik}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">NPWP</th>
                                                <td> : </td>
                                                <td>{{$biodata->npwp}}</td>
                                            </tr>

                                            <tr>
                                                <th scope="row">Tempat Lahir</th>
                                                <td> : </td>
                                                <td>{{$biodata->tempat_lahir}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Tanggal Lahir</th>
                                                <td> : </td>
                                                <td>{{$biodata->tanggal_lahir}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Agama</th>
                                                <td> : </td>
                                                <td>{{$biodata->nama_agama}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Kewarganegaraan</th>
                                                <td> : </td>
                                                <td>{{$biodata->kewarganegaraan}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">No. Telpon</th>
                                                <td> : </td>
                                                <td>{{$biodata->telepon}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Jenis Kelamin</th>
                                                <td> : </td>
                                                <td>{{$biodata->jenis_kelamin}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Golongan Darah</th>
                                                <td> : </td>
                                                <td>A</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Tinggi Badan</th>
                                                <td> : </td>
                                                <td>172 cm</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Berat Badan</th>
                                                <td> : </td>
                                                <td>75 kg</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Email</th>
                                                <td> : </td>
                                                <td>{{$biodata->email}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">No. HP</th>
                                                <td> : </td>
                                                <td>{{$biodata->handphone}}</td>
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