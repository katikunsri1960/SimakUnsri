<!-- Modal Import Excel -->
<div class="modal fade" id="modalImportExcel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Import Nomor Urut Album via Excel</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formImportExcel" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Format Header Kolom Excel:</strong><br>
                        <code>no_urut</code> | <code>nim</code> | <code>wisuda_ke</code>
                    </div>
                    <div class="form-group">
                        <label class="fw-bold mb-2">Pilih File Excel (.xlsx / .xls / .csv)</label>
                        <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xlsx, .xls, .csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitImport">
                        <i class="fa fa-upload me-1"></i> Upload & Process
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>