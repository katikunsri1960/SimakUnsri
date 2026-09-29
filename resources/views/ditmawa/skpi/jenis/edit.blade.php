<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Tambahkan rounded-4 dan overflow-hidden di sini -->
        <div class="modal-content rounded-4 overflow-hidden border-0 shadow">

            <form id="formEdit" method="POST" class="form-edit">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Jenis Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Bidang <span class="text-danger">*</span></label>
                        <select name="bidang_id" id="edit_bidang_id" class="form-control" required>
                            @foreach($bidang as $b)
                                <option value="{{ $b->id }}">
                                    {{ $b->nama_bidang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dropdown Sub Bidang Edit (Default Hidden) -->
                    <div class="mb-3" id="wrapper_sub_bidang_edit" style="display: none;">
                        <label>Sub Bidang Kegiatan <span class="text-danger">*</span></label>
                        <select name="sub_bidang_id" id="edit_sub_bidang_id" class="form-control">
                            <option value="">-- Pilih Sub Bidang --</option>
                            @foreach($subBidang as $sb)
                                <option value="{{ $sb->id }}">{{ $sb->nama_sub_bidang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Nama Jenis <span class="text-danger">*</span></label>
                        <input type="text" name="nama_jenis" id="edit_nama_jenis" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Kriteria <span class="text-danger">*</span></label>
                        <textarea name="kriteria" id="edit_kriteria" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Skor <span class="text-danger">*</span></label>
                        <input type="number" name="skor" id="edit_skor" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>

            </form>

        </div>
    </div>
</div>