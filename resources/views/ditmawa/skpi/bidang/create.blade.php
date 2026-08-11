<div class="modal fade" id="modalCreateBidang" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('bak.skpi.bidang.store') }}" method="POST" class="form-create">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Bidang Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Nama Bidang</label>
                        <input type="text" name="nama_bidang" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>