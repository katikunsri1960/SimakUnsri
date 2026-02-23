<!-- Modal Upload -->
<div class="modal fade" id="uploadModal{{$d->id}}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('mahasiswa.prestasi.upload', $d->id) }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Upload Piagam / Sertifikat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="file"
                           name="file_prestasi"
                           class="form-control"
                           accept="application/pdf"
                           required>
                    <small class="text-muted">
                        Maksimal 500 KB dan harus format PDF
                    </small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>