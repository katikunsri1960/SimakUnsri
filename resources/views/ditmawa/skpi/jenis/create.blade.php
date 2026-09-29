<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Tambahkan rounded-4 dan overflow-hidden di sini -->
        <div class="modal-content rounded-4 overflow-hidden border-0 shadow">

            <form action="{{route('ditmawa.skpi.jenis.store')}}" method="POST" class="form-create">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jenis Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Bidang Kegiatan <span class="text-danger">*</span></label>
                        <select name="bidang_id" id="create_bidang_id" class="form-control" required>
                            <option value="">-- Pilih Bidang --</option>
                            @foreach($bidang as $b)
                                <option value="{{$b->id}}">
                                    {{$b->nama_bidang}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dropdown Sub Bidang (Default Hidden) -->
                    <div class="mb-3" id="wrapper_sub_bidang_create" style="display: none;">
                        <label>Sub Bidang Kegiatan <span class="text-danger">*</span></label>
                        <select name="sub_bidang_id" id="create_sub_bidang_id" class="form-control">
                            <option value="">-- Pilih Sub Bidang --</option>
                            @foreach($subBidang as $sb)
                                <option value="{{$sb->id}}">{{$sb->nama_sub_bidang}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Nama Jenis <span class="text-danger">*</span></label>
                        <input type="text" name="nama_jenis" class="form-control" required placeholder="-- Nama Jenis Kegiatan --">
                    </div>

                    <div class="mb-3">
                        <label>Kriteria <span class="text-danger">*</span></label>
                        <textarea name="kriteria" class="form-control" rows="3" placeholder="-- Kriteria Kegiatan --" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Skor <span class="text-danger">*</span></label>
                        <input type="number" name="skor" class="form-control" required placeholder="-- Skor Kegiatan --">
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