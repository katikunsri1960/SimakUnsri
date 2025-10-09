<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">
                    Ubah Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="editForm">
                @csrf
                @method('patch')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="edit_status" required class="form-select">
                                <option value="" selected disabled>-- Pilih Status --</option>
                                <option value="0">Diajukan</option>
                                <option value="2">Disetujui Prodi</option>
                                <option value="3">Disetujui Fakultas</option>
                                <option value="4">Disetujui BAK</option>
                                <option value="5">Ditolak</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="edit_keterangan" rows="3"></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="batas_bayar" class="form-label">Janji Bayar</label>
                        <input type="date" name="batas_bayar" id="edit_batas_bayar" required class="form-select">
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
