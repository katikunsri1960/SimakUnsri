<div class="modal fade" id="editRuangKuliah" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="ruangKuliahTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ruangKuliahTitle">
                    Edit Ruang Kuliah
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" id="edit-ruang" method="POST">
                @csrf
                @method('patch')
                <div class="modal-body">
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="edit_nama_ruang" class="form-label">Nama Ruang Kuliah</label>
                            <input
                                type="text"
                                class="form-control"
                                name="nama_ruang"
                                id="edit_nama_ruang"
                                aria-describedby="helpId"
                                placeholder="Masukkan Nama Ruang"
                                onkeydown="upperCaseF(this)"
                                required
                            />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_lokasi" class="form-label">Lokasi</label>
                        <select class="form-select" name="lokasi" id="edit_lokasi" required>
                            <option value="Indralaya">Indralaya</option>
                            <option value="Palembang">Palembang</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect waves-light" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Optional: Place to the bottom of scripts -->
@push('js')
<script>
    const myModal = new bootstrap.Modal(
                        document.getElementById("editRuangKuliah"),
                        options,
                    );

    function upperCaseF(a){
        setTimeout(function(){
            a.value = a.value.toUpperCase();
        }, 1);
    }
</script>
@endpush
