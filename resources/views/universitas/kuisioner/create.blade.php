<div class="modal fade" id="createModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">
                    Tambah Pertanyaan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('univ.kuisioner.store')}}" method="post" id="storeForm">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="question_indonesia" class="form-label">Pertanyaan Bahasa Indonesia</label>
                        <textarea class="form-control" name="question_indonesia" id="question_indonesia"
                            rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="question_english" class="form-label">Pertanyaan Bahasa Inggris</label>
                        <textarea class="form-control" name="question_english" id="question_english"
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
