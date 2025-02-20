<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle">
                    Tambah Periode Wisuda
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="editForm">
                @method('patch')
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="periode" class="form-label">Periode Wisuda</label>
                            <input type="number" class="form-control" name="periode" id="edit_periode"
                                aria-describedby="helpId" placeholder="" required/>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_wisuda" class="form-label">Tanggal Wisuda</label>
                            <input type="text" class="form-control" name="tanggal_wisuda" id="edit_tanggal_wisuda"
                                aria-describedby="helpId" placeholder="" required readonly/>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_mulai_daftar" class="form-label">Tanggal Mulai Pendaftaran</label>
                            <input type="text" class="form-control" name="tanggal_mulai_daftar"
                                id="edit_tanggal_mulai_daftar" aria-describedby="helpId" placeholder="" required readonly/>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_akhir_daftar" class="form-label">Tanggal Akhir Pendaftaran</label>
                            <input type="text" class="form-control" name="tanggal_akhir_daftar"
                                id="edit_tanggal_akhir_daftar" aria-describedby="helpId" placeholder="" required readonly/>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_akhir_daftar" class="form-label">Aktifkan Periode</label>
                            <select class="form-select" name="is_active" id="edit_is_active" required>
                                <option selected>-- Pilih Salah Satu --</option>
                                <option value="1">Ya, Aktifkan</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batalkan
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
