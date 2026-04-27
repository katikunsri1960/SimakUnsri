<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{route('bak.skpi.jenis.store')}}" method="POST" class="form-create">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jenis Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Bidang Kegiatan</label>
                        <select name="bidang_id" class="form-control" required>
                            <option value="">-- Pilih Bidang --</option>
                            @foreach($bidang as $b)
                                <option value="{{$b->id}}">
                                    {{$b->nama_bidang}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Nama Jenis</label>
                        <input type="text" name="nama_jenis" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Kriteria</label>
                        <textarea name="kriteria" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Skor</label>
                        <input type="number" name="skor" class="form-control" required>
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