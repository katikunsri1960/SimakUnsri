<div class="tab-pane active" id="data-diri" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light rounded20 big-side-section">
            <div class="row">
                <div class="col-xxl-12 col-xl-12 col-lg-12 pt-20 px-20">
                    <div class="box">		
                        <div class="text-white box-body bg-img text-center m-20 py-65" 
                        style="background-image: url({{asset('images/images/gallery/creative/img-12.jpg')}});">
                        </div>
                        <div class="box-body up-mar100 pb-0">	
                            <div class=" justify-content-center">
                                <div class="align-bottom">
                                    <!-- <div class="row"> -->
                                        <div class="bg-white px-10 text-center pt-15 w-120 ms-20 mb-0 rounded20 mb-20" style="display:inline-block">
                                            <a href="#" class="w-80">
                                                <img class="avatar avatar-xxl rounded20 bg-light img-fluid" src="{{asset('images/images/avatar/avatar-15.png')}}" alt="">
                                                
                                            </a>
                                            
                                        </div>
                                        <button type="button" class="waves-effect waves-light btn btn-outline btn-primary btn-xs mb-5 mt-70"><i class="fa fa-pen"> </i> Ganti Foto Profil</button>
                                    <!-- </div> -->
                                    <div class="ms-30 mb-15">
                                        <h5 class="my-10 mb-0 text-dark fw-500 fs-18">{{$data->nama_mahasiswa}}</h5>
                                        <span class="text-fade mt-5">{{$data->nama_program_studi}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>			
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title fw-500">Data Diri</h3>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="name" class="form-control" disabled
                                                value="{{$data->nama_mahasiswa}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>NIK</label>
                                            <input type="name" class="form-control" disabled
                                                value="{{$data->nik}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>NPWP</label>
                                            <input type="name" class="form-control" disabled
                                                value="{{$data->npwp}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Tempat Lahir</label>
                                            <input type="name" class="form-control" disabled
                                                value="{{$data->tempat_lahir}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Tanggal Lahir</label>
                                            <input type="name" class="form-control" disabled
                                                value="{{$data->tanggal_lahir}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Agama</label>
                                            <input type="name" class="form-control" disabled
                                                value="{{$data->nama_agama}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Kewarganegaraan</label>
                                            <input type="name" class="form-control" disabled
                                                value="{{$data->kewarganegaraan}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>No. Telpon</label>
                                            <input type="name" class="form-control"
                                                value="{{$data->telepon}}">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <!-- <label>Jenis Kelamin</label>
                                            <input type="name" class="form-control" disabled
                                                value="{{$data->jenis_kelamin}}"> -->
                                        </div>
                                    </div>

                                    <!-- JENIS KELAMIN -->
                                    @if ($data->jenis_kelamin == "Laki-laki" )
                                        <div class="col-lg-6">
                                            <label class="mb-5">Jenis Kelamin</label>
                                            <div class="form-group ">
                                                <div class="form-check form-check-inline">
                                                    <input class="with-gap radio-col-success" type="radio" name="jenisKelamin" id="exampleRadios1" value="kelamin1" checked disabled>
                                                    <label class="form-check-label" for="exampleRadios1">
                                                        Laki-Laki
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="with-gap radio-col-success" type="radio" name="jenisKelamin" id="exampleRadios2" value="kelamin2" disabled>
                                                    <label class="form-check-label" for="exampleRadios2">
                                                        Perempuan
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="with-gap radio-col-success" type="radio" name="jenisKelamin" id="exampleRadios3" value="kelamin3" disabled>
                                                    <label class="form-check-label" for="exampleRadios3">
                                                        Lainnya
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($data->jenis_kelamin == "Perempuan" )
                                        <div class="col-lg-6">
                                            <label class="mb-5">Jenis Kelamin</label>
                                            <div class="form-group ">
                                                <div class="form-check form-check-inline">
                                                    <input class="with-gap radio-col-success" type="radio" name="jenisKelamin" id="exampleRadios1" value="kelamin1"  disabled>
                                                    <label class="form-check-label" for="exampleRadios1">
                                                        Laki-Laki
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="with-gap radio-col-success" type="radio" name="jenisKelamin" id="exampleRadios2" value="kelamin2" checked disabled>
                                                    <label class="form-check-label" for="exampleRadios2">
                                                        Perempuan
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="with-gap radio-col-success" type="radio" name="jenisKelamin" id="exampleRadios3" value="kelamin3" disabled>
                                                    <label class="form-check-label" for="exampleRadios3">
                                                        Lainnya
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-lg-6">
                                            <label class="mb-5">Jenis Kelamin</label>
                                            <div class="form-group ">
                                                <div class="form-check form-check-inline">
                                                    <input class="with-gap radio-col-success" type="radio" name="jenisKelamin" id="exampleRadios1" value="kelamin1"  disabled>
                                                    <label class="form-check-label" for="exampleRadios1">
                                                        Laki-Laki
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="with-gap radio-col-success" type="radio" name="jenisKelamin" id="exampleRadios2" value="kelamin2"  disabled>
                                                    <label class="form-check-label" for="exampleRadios2">
                                                        Perempuan
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="with-gap radio-col-success" type="radio" name="jenisKelamin" id="exampleRadios3" value="kelamin3" checked disabled>
                                                    <label class="form-check-label" for="exampleRadios3">
                                                        Lainnya
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Golongan Darah</label>
                                            <select class="form-select" aria-label="Default select example" >
                                                <option selected>A</option>
                                                <option value="1">A</option>
                                                <option value="2">B</option>
                                                <option value="3">AB</option>
                                                <option value="4">O</option>
                                                <!-- <option value="5">Ojek</option> -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Tinggi Badan</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" 
                                                value="172">
                                                <span class="input-group-addon">cm</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6"> 
                                        <div class="form-group">
                                            <label>Berat Badan</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" 
                                                value="80">
                                                <span class="input-group-addon">kg</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="mb-5">Ukuran Baju</label>
                                        <div class="form-group ">
                                            <div class="form-check form-check-inline">
                                                <input class="with-gap radio-col-success" type="radio" name="radioBaju" id="exampleRadios1" value="ukBaju1"  >
                                                <label class="form-check-label" for="exampleRadios1">
                                                    S
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="with-gap radio-col-success" type="radio" name="radioBaju" id="exampleRadios2" value="ukBaju2" >
                                                <label class="form-check-label" for="exampleRadios2">
                                                    M
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="with-gap radio-col-success" type="radio" name="radioBaju" id="exampleRadios3" value="ukBaju3" checked >
                                                <label class="form-check-label" for="exampleRadios3">
                                                    L
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="with-gap radio-col-success" type="radio" name="radioBaju" id="exampleRadios4" value="ukBaju4" >
                                                <label class="form-check-label" for="exampleRadios4">
                                                    XL
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="with-gap radio-col-success" type="radio" name="radioBaju" id="exampleRadios5" value="ukBaju5" >
                                                <label class="form-check-label" for="exampleRadios5">
                                                    XXL
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="with-gap radio-col-success" type="radio" name="radioBaju" id="exampleRadios6" value="ukBaju6" >
                                                <label class="form-check-label" for="exampleRadios6">
                                                    XXXL
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <h4 class="box-title text-info mt-50 mb-0"><i class="ti-user me-15"></i> Kontak Mahasiswa</h4>
                                    <hr class="my-15">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="name" class="form-control"
                                                value="{{$data->email}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>No. HP</label>
                                            <input type="name" class="form-control"
                                                value="{{$data->handphone}}">
                                        </div>
                                    </div>
                                </div>
                            </div>       
                            <div class="box-footer text-end">
                                <button type="submit" class="btn btn-primary">
                                <i class="ti-save-alt"></i> Simpan
                            </button>
                        </div>		                   
                        </div>
                        <!-- </div> -->
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>