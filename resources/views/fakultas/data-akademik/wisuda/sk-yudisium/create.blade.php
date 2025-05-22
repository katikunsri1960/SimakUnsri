<div class="modal fade" id="createModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="createModalLabel" aria-hidden="true">
    {{-- <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document"> --}}
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">
                    Tambah Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('fakultas.wisuda.sk-yudisium.store')}}" method="post" id="storeForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div id="multiDataContainer">
                        <div class="row multiDataItem">
                            <div class="col-md-6 mb-3">
                                <label for="no_sk_yudisium[]" class="form-label">No SK Yudisium</label>
                                <input type="text" class="form-control" name="no_sk_yudisium[]" placeholder="Masukkan No SK Yudisium" required>
                            </div>
                        <div class="col-md-6 mb-3">
                            <label for="tgl_sk_yudisium" class="form-label">Tanggal SK Yudisium</label>
                            <input type="date" class="form-control" name="tgl_sk_yudisium" required>
                            <span class="badge badge-danger-light mt-2">Gunakan tanggal tanda tangan SK Yudisium</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tgl_yudisium" class="form-label">Tanggal Yudisium</label>
                            <input type="date" class="form-control" name="tgl_yudisium" required>
                            <span class="badge badge-danger-light mt-2">Gunakan tanggal kegiatan Yudisium</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sk_yudisium_file" class="form-label">File SK Yudisium (.pdf)</label>
                            <input type="file" class="form-control" name="sk_yudisium_file" id="sk_yudisium_file"
                                aria-describedby="fileHelpId" accept=".pdf" required />
                        </div>
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

