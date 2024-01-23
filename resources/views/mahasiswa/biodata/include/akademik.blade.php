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
                                            value="{{$data->riwayat_pendidikan[0]->nama_perguruan_tinggi}}">
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
                                            value="{{$data->riwayat_pendidikan[0]->nim}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Jenis Pendaftaran</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$data->riwayat_pendidikan[0]->nama_jenis_daftar}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Program Studi</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$data->riwayat_pendidikan[0]->nama_program_studi}}">
                                    </div>
                                </div>

                                {{-- CEK KONDISI $JALUR MASUK = NULL --}}
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Jalur Pendaftaran</label>
                                        @if($data->riwayat_pendidikan[0]->jalur_masuk == Null)
                                        <input type="name" class="form-control" disabled
                                            value="Tidak diisi">
                                        @else
                                        <input type="name" class="form-control" disabled
                                            value="{{$data->riwayat_pendidikan[0]->jalur_masuk->nama_jalur_masuk}}">
                                        @endif
                                    </div>
                                </div>

                            
                                
                                
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>UKT</label>
                                        <input type="name" class="form-control" disabled
                                            value="Rp  {{number_format($data->riwayat_pendidikan[0]->biaya_masuk, 2, ',', '.') }}">
                                    </div>
                                </div>


                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Angkatan</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$data->riwayat_pendidikan[0]->angkatan}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Gelombang Masuk</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$data->riwayat_pendidikan[0]->gelombang_masuk}}">
                                    </div>
                                </div>
                                
                                <!-- CEK STATUS KELUAR = NULL-->
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        @if ($data->riwayat_pendidikan[0]->keterangan_keluar == Null)
                                            <input type="name" class="form-control" disabled
                                                value="Aktif">
                                        @else
                                        
                                            <input type="name" class="form-control" disabled
                                                value="{{$data->riwayat_pendidikan[0]->keterangan_keluar}}">
                                        @endif
                                    </div>
                                </div>
                               

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Tanggal Masuk</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{date_format(new DateTime($data->riwayat_pendidikan[0]->tanggal_daftar), 'd-m-Y') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Periode Masuk</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$data->riwayat_pendidikan[0]->nama_periode_masuk}}">
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
