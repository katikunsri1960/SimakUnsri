<div class="modal fade" id="detailModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">
                    Detail Bimbingan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <textarea class="form-control" name="judul" id="detail_judul" rows="3" readonly></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="text" class="form-control" name="tanggal_mulai" id="edit_tanggal_mulai"
                            aria-describedby="helpId" placeholder="" readonly />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="text" class="form-control" name="tanggal_selesai" id="edit_tanggal_selesai"
                            aria-describedby="helpId" placeholder="" readonly />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input type="text" class="form-control" name="lokasi" id="edit_lokasi" aria-describedby="helpId"
                            placeholder="" readonly />

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
