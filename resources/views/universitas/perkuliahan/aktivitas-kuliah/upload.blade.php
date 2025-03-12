<div class="modal fade" id="uploadModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">
                    Upload Data Aktivitas Kuliah Mahasiswa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('univ.perkuliahan.aktivitas-kuliah.upload')}}" method="post"
            enctype="multipart/form-data" id="uploadForm">
            @csrf
            <div class="modal-body">
                {{-- upload file --}}
                <div class="form-group">
                    <label for="file">File</label>
                    <input type="file" name="file" id="file" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="type" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button>
            </div>
        </form>
        </div>
    </div>
</div>
