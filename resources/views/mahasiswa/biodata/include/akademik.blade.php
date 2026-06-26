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
                            {{-- @foreach ($riwayat_pendidikan as $riwayat_pendidikan_terbaru) --}}
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Perguruan Tinggi</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$riwayat_pendidikan_terbaru->nama_mahasiswa}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Dosen PA/Wali</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            disabled
                                            value="{{ isset($riwayat_pendidikan_terbaru->pembimbing_akademik->nama_dosen) ? $riwayat_pendidikan_terbaru->pembimbing_akademik->nama_dosen : 'Tidak diisi' }}">
                                    </div>
                                </div>                                                              
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>NIM</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$riwayat_pendidikan_terbaru->nim}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Jenis Pendaftaran</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$riwayat_pendidikan_terbaru->nama_jenis_daftar}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Program Studi</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$riwayat_pendidikan_terbaru->nama_program_studi}}">
                                    </div>
                                </div>

                                {{-- CEK KONDISI $JALUR MASUK = NULL --}}
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Jalur Pendaftaran</label>
                                        @if($riwayat_pendidikan_terbaru->jalur_masuk == Null)
                                        <input type="name" class="form-control" disabled
                                            value="Tidak diisi">
                                        @else
                                        <input type="name" class="form-control" disabled
                                            value="{{$riwayat_pendidikan_terbaru->jalur_masuk->nama_jalur_masuk}}">
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>UKT</label>
                                        @if ($beasiswa)
                                            <input type="text" class="form-control" disabled
                                                value="Rp 0,00">
                                        @elseif(isset($tagihan))
                                            @if (isset($tagihan))
                                                <input type="text" class="form-control" disabled
                                                    value="Rp {{ number_format($tagihan->total_nilai_tagihan, 2, ',', '.') }}">
                                            @else
                                                <input type="text" class="form-control" disabled value="Belum ada pembayaran">
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Angkatan</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$riwayat_pendidikan_terbaru->angkatan}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Gelombang Masuk</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$riwayat_pendidikan_terbaru->gelombang_masuk}}">
                                    </div>
                                </div>
                                
                                <!-- CEK STATUS KELUAR = NULL-->
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        @if ($riwayat_pendidikan_terbaru->keterangan_keluar == Null)
                                            <input type="name" class="form-control" disabled
                                                value="Aktif">
                                        @else
                                            <input type="name" class="form-control" disabled
                                                value="{{$riwayat_pendidikan_terbaru->keterangan_keluar}}">
                                        @endif
                                    </div>
                                </div>
                               

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Tanggal Masuk</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{date_format(new DateTime($riwayat_pendidikan_terbaru->tanggal_daftar), 'd-m-Y') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Periode Masuk</label>
                                        <input type="name" class="form-control" disabled
                                            value="{{$riwayat_pendidikan_terbaru->nama_periode_masuk}}">
                                    </div>
                                </div>
                            </div>
                            {{-- @endforeach --}}
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>
