<div class="modal fade" id="setKurilukumModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="setKurikulumTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setKurikulumTitle">
                    Atur Kurikulum Mahasiswa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="kurForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nim" class="form-label">NIM</label>
                           <input type="text" class="form-control" name="nim" id="edit_nim" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
                           <input type="text" class="form-control" name="nama_mahasiswa" id="edit_nama_mahasiswa" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_kurikulum" class="form-label">Kurikulum</label>
                            <select class="form-select" name="id_kurikulum" id="edit_set_id_kurikulum">
                                <option value="" selected disabled>-- Pilih Kurikulum --</option>
                                @foreach ($kurikulum as $k)
                                <option value="{{$k->id_kurikulum}}">
                                    {{$k->nama_kurikulum}}
                                </option>
                                @endforeach
                            </select>
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
