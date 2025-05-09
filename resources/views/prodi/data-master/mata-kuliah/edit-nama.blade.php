<div class="modal fade" id="modalEditNama" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="modalEditNamaTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditNamaTitle">
                    Edit Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="formEditNama">
                @csrf
            <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="kode_mk" class="form-label">Kode MK</label>
                                <input type="text" class="form-control" name="kode_mk" id="kode_mk"
                                    aria-describedby="helpId" disabled />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="nama_mk_id" class="form-label">Nama MK (ID)</label>
                                <input type="text" class="form-control" name="nama_mk_id" id="nama_mk_id"
                                    aria-describedby="helpId" disabled />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="nama_mata_kuliah_english" class="form-label">Nama MK (EN)</label>
                                <input type="text" class="form-control" name="nama_mata_kuliah_english" id="nama_mata_kuliah_english"
                                    aria-describedby="helpId" required />
                            </div>
                        </div>
                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Batalkan
                </button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
        </div>
    </div>
</div>
