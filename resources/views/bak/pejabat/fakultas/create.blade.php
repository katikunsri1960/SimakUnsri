<div class="modal fade" id="createModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">
                    Tambah Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('fakultas.data-master.pejabat-fakultas.store')}}" method="post" id="storeForm">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_dosen" class="form-label">Dosen</label>
                        <select name="id_dosen" id="id_dosen" required class="form-select">
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_jabatan" class="form-label">Jabatan</label>
                        <select name="id_jabatan" id="id_jabatan" required class="form-select">
                            <option value="" selected disabled>-- Pilih Jabatan --</option>
                            <option value="0">Dekan Fakultas</option>
                            <option value="1">Wakil Dekan Bidang Akademik</option>
                            <option value="2">Wakil Dekan Bidang Umum, Keuangan & Kepegawaian</option>
                            <option value="3">Wakil Dekan Bidang Kemahasiswaan dan Alumni</option>
                        </select>
                    </div>
                    <div id="gelar-depan-fields">
                        <div class="gelar-depan-field row ">
                            {{-- <div class="mb-3"> --}}
                                <div class="col-md-6 mb-20">
                                    <label for="tgl_mulai_jabatan" class="form-label">Tanggal Mulai Menjabat</label>
                                    <input class="form-control" type="date" name="tgl_mulai_jabatan"  id="tgl_mulai_jabatan" placeholder="-- Batas Isi KRS --">
                                </div>
                            {{-- </div> --}}
                            {{-- <div class="mb-3"> --}}
                                <div class="col-md-6 mb-20">
                                    <label for="tgl_selesai_jabatan" class="form-label">Tanggal Selesai Menjabat</label>
                                    <input class="form-control" type="date" name="tgl_selesai_jabatan"  id="tgl_selesai_jabatan" placeholder="-- Batas Isi KRS --">
                                </div>
                            {{-- </div> --}}
                        </div>
                    </div>
                    
                    <div id="gelar-depan-fields">
                        <div class="gelar-depan-field row">
                            <div class="col-md-12 mb-20">
                                <label for="gelar_depan" class="form-label">Gelar Depan</label>
                                <input type="text" id="gelar_depan" class="form-control" name="gelar_depan" placeholder="-- Masukkan Keterangan Aktivitas --">
                            </div>
                        </div>
                    </div>
                    <div id="gelar-belakang-fields">
                        <div class="gelar-belakang-field row">
                            <div class="col-md-12 mb-20">
                                <label for="gelar_belakang" class="form-label">Gelar Belakang</label>
                                <input type="text" id="gelar_belakang" class="form-control" name="gelar_belakang" placeholder="-- Masukkan Keterangan Aktivitas --">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

