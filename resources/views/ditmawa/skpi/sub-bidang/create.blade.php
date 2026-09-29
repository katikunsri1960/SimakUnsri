<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Tambahkan rounded-4 dan overflow-hidden di sini -->
        <div class="modal-content rounded-4 overflow-hidden border-0 shadow">

            <form action="{{ route('ditmawa.skpi.sub-bidang.store') }}" method="POST" class="form-create">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Sub Bidang Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Bidang Utama <span class="text-danger">*</span></label>
                        <select name="bidang_id" class="form-control" required>
                            <option value="">-- Pilih Bidang --</option>
                            @foreach($bidang as $b)
                                <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Sub Bidang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_sub_bidang" class="form-control" placeholder="Contoh: Organisasi Kemahasiswaan" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>