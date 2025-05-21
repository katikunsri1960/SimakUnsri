<div class="modal fade" id="declineModal{{ $item->id }}" tabindex="-1" aria-labelledby="declineModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declineModalLabel{{ $item->id }}">Alasan Penolakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="declineForm{{ $item->id }}">
                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <label for="alasan_pembatalan{{ $item->id }}" class="form-label">Alasan Penolakan</label>
                        <textarea class="form-control" name="alasan_pembatalan" id="alasan_pembatalan{{ $item->id }}" rows="3" placeholder="Masukkan alasan penolakan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="submitDecline({{ $item->id }})">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>