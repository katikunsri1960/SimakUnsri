<div class="modal fade" id="createModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">
                    Tambah Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('univ.pembayaran-manual.store')}}" method="post" id="storeForm">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_registrasi_mahasiswa" class="form-label">Mahasiswa</label>
                        <select name="id_registrasi_mahasiswa" id="id_registrasi_mahasiswa" required class="form-select">

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" required class="form-select">
                            <option value="" selected disabled>-- Pilih Status --</option>
                            <option value="0">Belum Bayar</option>
                            <option value="1">Lunas</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_pembayaran" class="col-sm-2 col-form-label">Date</label>
                        <div class="col-sm-10">
                          <input class="form-control" type="date" name="tanggal_pembayaran"  id="tanggal_pembayaran" placeholder="-- Tanggal Pembayaran --">
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

