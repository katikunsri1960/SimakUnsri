<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">
                    Tambah Pertanyaan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="editForm">
                @csrf
                @method('patch')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="question_indonesia" class="form-label">Pertanyaan Bahasa Indonesia</label>
                        <textarea class="form-control" name="question_indonesia" id="edit_question_indonesia"
                            rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="question_english" class="form-label">Pertanyaan Bahasa Inggris</label>
                        <textarea class="form-control" name="question_english" id="edit_question_english"
                            rows="3" required></textarea>
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
