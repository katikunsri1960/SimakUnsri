<div class="modal fade" id="declineModal${item.id}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alasan Penolakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Alasan Penolakan</label>
                    <input class="form-control"
                        id="alasan_pembatalan${item.id}"
                        placeholder="Masukkan alasan penolakan">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-danger" onclick="submitDecline(${item.id})">
                    Tolak
                </button>
            </div>
        </div>
    </div>
</div>