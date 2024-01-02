<div class="modal fade" id="tambahRuangKuliah" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="ruangKuliahTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ruangKuliahTitle">
                    Tambah Ruang Kuliah
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nama_ruang" class="form-label">Nama Ruang Kuliah</label>
                    <input
                        type="text"
                        class="form-control"
                        name="nama_ruang"
                        id="nama_ruang"
                        aria-describedby="helpId"
                        placeholder=""
                    />
                </div>
                <div class="mb-3">
                    <label for="lokasi" class="form-label">Lokasi</label>
                    <input
                        type="text"
                        class="form-control"
                        name="lokasi"
                        id="lokasi"
                        aria-describedby="helpId"
                        placeholder=""
                    />
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect waves-light" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="button" class="btn btn-primary waves-effect waves-light">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Optional: Place to the bottom of scripts -->
<script>
    const myModal = new bootstrap.Modal(
                        document.getElementById("tambahRuangKuliah"),
                        options,
                    );
</script>
