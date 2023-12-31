<div class="tab-pane" id="alamat" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light rounded20 big-side-section">
            <div class="row">
                <div class="row">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                        <div class="box box-body">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12">
                                    <h3 class="fw-500 text-dark mt-0">Alamat</h3>
                                </div>                             
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>Jalan</label>
                                        <input type="name" class="form-control" 
                                            value="{{ $biodata->jalan }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Dusun</label>
                                        <input type="name" class="form-control" 
                                            value="{{ $biodata->dusun }}">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>RT</label>
                                        <input type="name" class="form-control" 
                                            value="{{ $biodata->rt }}">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>RW</label>
                                        <input type="name" class="form-control" 
                                            value="{{ $biodata->rw }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Kelurahan</label>
                                        <input type="name" class="form-control" 
                                            value="{{ $biodata->kelurahan }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Kecamatan</label>
                                        <input type="name" class="form-control" 
                                            value="{{ $biodata->nama_wilayah }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Kab/Kota</label>
                                        <input type="name" class="form-control" 
                                            value="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Provinsi</label>
                                        <input type="name" class="form-control" 
                                            value="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Kode Pos</label>
                                        <input type="name" class="form-control" 
                                            value="{{ $biodata->kode_pos }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label class="mb-5">Penerima KPS</label>
                                    <div class="form-group ">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" >
                                            <label class="form-check-label" for="exampleRadios1">
                                                Ya
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2" checked>
                                            <label class="form-check-label" for="exampleRadios2">
                                                Tidak
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8"
                                    style="@if ($biodata->penerima_kps == 'Tidak') visibility: hidden @endif">
                                    <div class="form-group">
                                        <label>No. KPS</label>
                                        <input type="name" class="form-control" 
                                            value="{{ $biodata->nomor_kps }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Alat Transportasi</label>
                                        <select class="form-select" aria-label="Default select example">
                                            <option selected>Sepeda motor</option>
                                            <option value="1">Jalan kaki</option>
                                            <option value="3">Angkutan umum/bus/pete-pete</option>
                                            <option value="4">Mobil/bus antar jemput</option>
                                            <option value="5">Kereta api</option>
                                            <option value="6">Ojek</option>
                                            <option value="7">Andong/bendi/sado/dokar/delman/becak</option>
                                            <option value="8">Perahu penyeberangan/rakit/getek</option>
                                            <option value="11">Kuda</option>
                                            <option value="12">Sepeda</option>
                                            <option value="13">Sepeda motor</option>
                                            <option value="14">Mobil pribadi</option>
                                            <option value="99">Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Jenis Tinggal </label>
                                        <select class="form-select" aria-label="Default select example">
                                            <option selected>Kost</option>
                                            <option value="1">Bersama orang tua</option>
                                            <option value="2">Wali</option>
                                            <option value="3">Kost</option>
                                            <option value="4">Asrama</option>
                                            <option value="5">Panti asuhan</option>
                                            <option value="10">Rumah sendiri</option>
                                            <option value="99">Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>