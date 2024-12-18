<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">
                    Ubah Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="editForm">
                @csrf
                @method('patch')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_registrasi_mahasiswa" class="form-label">Mahasiswa</label>
                        <select class="form-select" name="id_registrasi_mahasiswa" id="edit_id_registrasi_mahasiswa" required onchange="getNamaMahasiswaEdit()">
                            <option value="" disabled>-- NIM / Nama Mahasiswa --</option>
                                {{-- @foreach ($data as $d) --}}
                                <option value="{{$data[0]->id_registrasi_mahasiswa}}">({{$data[0]->nim}}) {{$data[0]->nama_mahasiswa}}</option>
                                {{-- @endforeach --}}
                        </select>                        
                    </div>
                    <div class="mb-3">
                        <label for="status_bayar" class="form-label">Status</label>
                        <select name="status_bayar" id="edit_status" required class="form-select">
                            <option value="" selected disabled>-- Pilih Status --</option>
                            <option value="0">Belum Bayar</option>
                            <option value="1">Lunas</option>
                            <option value="2">Beasiswa</option>
                            <option value="3">Tunda Bayar</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="batas_isi_krs" class="form-label">Batas Isi KRS </label>
                        <div class="col-sm-10">
                          <input class="form-control" type="date" name="batas_isi_krs"  id="edit_batas_isi_krs" placeholder="-- Batas Isi KRS --">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan </label>
                        <div class="col-sm-10">
                          <input class="form-control" type="text" name="keterangan"  id="edit_keterangan" placeholder="-- Keterangan --">
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
