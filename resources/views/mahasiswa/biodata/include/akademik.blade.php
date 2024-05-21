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
                            @foreach ($riwayat_pendidikan as $data)
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Perguruan Tinggi</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$data->nama_perguruan_tinggi}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Dosen PA/Wali</label>
                                        @if (!empty($data->nama_dosen))
                                            <input type="name" class="form-control" disabled
                                            value="{{$data->nama_dosen}}">
                                        @else
                                            <input type="name" class="form-control" disabled
                                            value="Tidak diisi">
                                        @endif
                                        
                                        
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>NIM</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$data->nim}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Jenis Pendaftaran</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$data->nama_jenis_daftar}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Program Studi</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$data->nama_program_studi}}">
                                    </div>
                                </div>

                                {{-- CEK KONDISI $JALUR MASUK = NULL --}}
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Jalur Pendaftaran</label>
                                        @if($data->jalur_masuk == Null)
                                        <input type="name" class="form-control" disabled
                                            value="Tidak diisi">
                                        @else
                                        <input type="name" class="form-control" disabled
                                            value="{{$data->jalur_masuk->nama_jalur_masuk}}">
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>UKT</label>
                                        <input type="name" class="form-control" disabled
                                            value="Rp  {{number_format($data->biaya_kuliah_smt, 2, ',', '.') }}">
                                    </div>
                                </div>


                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Angkatan</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$data->angkatan}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Gelombang Masuk</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$data->gelombang_masuk}}">
                                    </div>
                                </div>
                                
                                <!-- CEK STATUS KELUAR = NULL-->
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        @if ($data->keterangan_keluar == Null)
                                            <input type="name" class="form-control" disabled
                                                value="Aktif">
                                        @else
                                        
                                            <input type="name" class="form-control" disabled
                                                value="{{$data->nama_status_mahasiswa}}">
                                        @endif
                                    </div>
                                </div>
                               

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Tanggal Masuk</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{date_format(new DateTime($data->tanggal_daftar), 'd-m-Y') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Periode Masuk</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$data->nama_periode_masuk}}">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>
