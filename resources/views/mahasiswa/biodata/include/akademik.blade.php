<div class="tab-pane" id="akademik" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light rounded20 big-side-section">
            <div class="row">
                <div class="row">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                        <div class="box box-body">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12">
                                    <h3 class="fw-500 text-dark mt-0 mb-20">Akademik</h3>
                                </div>                             
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Perguruan Tinggi</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$biodata->nama_perguruan_tinggi}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Dosen PA/Wali</label>
                                        <input type="name" class="form-control" disabled
                                            value="Prof. Dr. Erwin, M.Si">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>NIM</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$biodata->nim}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Jenis Pendaftaran</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$biodata->nama_jenis_daftar}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Program Studi</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$biodata->nama_program_studi}}">
                                    </div>
                                </div>


                                <!-- JALUR MASUK -->
                                @if ($biodata->id_jalur_masuk == 1)
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Jalur Pendaftaran</label>
                                        <input type="name" class="form-control" disabled
                                            value="SNMPTN / SNBP">
                                    </div>
                                </div>
                                @elseif ($biodata->id_jalur_masuk == 2)
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Jalur Pendaftaran</label>
                                        <input type="name" class="form-control" disabled
                                            value="SBMPTN / SNBT">
                                    </div>
                                </div>
                                @elseif ($biodata->id_jalur_masuk == 3)
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Jalur Pendaftaran</label>
                                        <input type="name" class="form-control" disabled
                                            value="USM / USMB">
                                    </div>
                                </div>
                                @else
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Jalur Pendaftaran</label>
                                        <input type="name" class="form-control" disabled
                                            value="Lainnya">
                                    </div>
                                </div>
                                @endif
                                
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Jalur Pendaftaran</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$biodata->id_jalur_masuk}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>UKT</label>
                                        <input type="name" class="form-control" disabled
                                            value="Rp  {{number_format($biodata->biaya_masuk, 2, ',', '.') }}">
                                    </div>
                                </div>


                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Angkatan</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$biodata->angkatan}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Gelombang Masuk</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$biodata->gelombang_masuk}}">
                                    </div>
                                </div>
                                
                                <!-- STATUS KELUAR -->
                                @if ($biodata->keterangan_keluar == Null)
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <input type="name" class="form-control" disabled
                                            value="Aktif">
                                    </div>
                                </div>
                               @else
                               <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$biodata->keterangan_keluar}}">
                                    </div>
                                </div>
                                @endif

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Tanggal Masuk</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{date_format(new DateTime($biodata->tanggal_daftar), 'd-m-Y') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Periode Masuk</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$biodata->nama_periode_masuk}}">
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
