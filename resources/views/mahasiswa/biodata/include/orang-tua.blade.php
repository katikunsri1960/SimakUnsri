<div class="tab-pane " id="orang-tua" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light rounded20 big-side-section">
            <div class="row">
                <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                    <div class="box box-body">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <h3 class="fw-500 text-dark mt-0">Orang Tua</h3>
                            </div>                             
                        </div>
                        <div class="row">
                                <div class="col-lg-6">
                                    <h3 class="text-center">Biodata Ayah</h3>
                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{ $biodata->nama_ayah }}">
                                    </div>
                                    <div class="form-group">
                                        <label>No. HP</label>
                                        <input type="name" class="form-control" disabled
                                            value="0811-0000-0000">
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" disabled
                                            value="ayah@gmail.com">
                                    </div>
                                    <div class="form-group">
                                        <label>NIK</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{ $biodata->nik_ayah }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal Lahir</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{ $biodata->tanggal_lahir_ayah }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Status Hidup</label>
                                        <select class="form-select" disabled aria-label="Default select example">
                                            <option selected>Hidup</option>
                                            <option value="1">Hidup</option>
                                            <option value="2">Meninggal</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Pendidikan Terakhir</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{ $biodata->nama_pendidikan_ayah }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Penghasilan</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{ $biodata->nama_penghasilan_ayah }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <input type="name" class="form-control" disabled
                                            value="">
                                    <div class="form-group">
                                        <label>Kecamatan</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{ $biodata->nama_wilayah }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Kab/Kota</label>
                                        <input type="name" class="form-control" disabled
                                            value="">
                                    </div>
                                    <div class="form-group">
                                        <label>Provinsi</label>
                                        <input type="name" class="form-control" disabled
                                            value="">
                                    </div>
                                    <div class="form-group">
                                        <label>Kode Pos</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{ $biodata->kode_pos }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                    <h3 class="text-center">Biodata Ibu</h3>
                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{ $biodata->nama_ibu_kandung }}">
                                    </div>
                                    <div class="form-group">
                                        <label>No. HP</label>
                                        <input type="name" class="form-control" disabled
                                            value="0811-0000-0000">
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" disabled
                                            value="ibu@gmail.com">
                                    </div>
                                    <div class="form-group">
                                        <label>NIK</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{ $biodata->nik_ibu }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal Lahir</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{ $biodata->tanggal_lahir_ibu }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Status Hidup</label>
                                        <select class="form-select" disabled aria-label="Default select example">
                                            <option selected>Hidup</option>
                                            <option value="1">Hidup</option>
                                            <option value="2">Meninggal</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Pendidikan Terakhir</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{ $biodata->nama_pendidikan_ibu }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Penghasilan</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{ $biodata->nama_penghasilan_ibu }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <input type="name" class="form-control" disabled
                                            value="">
                                    <div class="form-group">
                                        <label>Kecamatan</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{ $biodata->nama_wilayah }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Kab/Kota</label>
                                        <input type="name" class="form-control" disabled
                                            value="">
                                    </div>
                                    <div class="form-group">
                                        <label>Provinsi</label>
                                        <input type="name" class="form-control" disabled
                                            value="">
                                    </div>
                                    <div class="form-group">
                                        <label>Kode Pos</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{ $biodata->kode_pos }}">
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