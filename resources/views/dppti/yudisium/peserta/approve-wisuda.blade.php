<div class="modal fade" id="approveModal${item.id}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Approve Peserta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">No Urut Wisuda</label>
                    <input 
                        type="number"
                        class="form-control"
                        id="no_urut_${item.id}"
                        placeholder="Masukkan No Urut"
                        required>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-success" onclick="submitApprove(${item.id})">
                    Setujui
                </button>
            </div>

        </div>
    </div>
</div>