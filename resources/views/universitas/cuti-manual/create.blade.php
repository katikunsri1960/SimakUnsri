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
            <form action="{{route('univ.cuti-manual.store')}}" method="post" id="storeForm">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_registrasi_mahasiswa" class="form-label">Mahasiswa</label>
                        <select name="id_registrasi_mahasiswa" id="id_registrasi_mahasiswa" required class="form-select">
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_semester" class="form-label">Semester</label>
                        <select class="form-select" name="id_semester" id="id_semester" required>
                            <option value="">-- Pilih Semester --</option>
                            @foreach ($semester as $s)
                            <option value="{{$s->id_semester}}">{{$s->nama_semester}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="alasan_cuti" class="form-label">Alasan Pengajuan Cuti </label>
                        <div class="col-sm-10">
                          <input class="form-control" type="text" name="alasan_cuti"  id="alasan_cuti" placeholder="-- Alasan Pengajuan Cuti --">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="no_sk" class="form-label">Nomor SK </label>
                        <div class="col-sm-10">
                          <input class="form-control" type="text" name="no_sk"  id="no_sk" placeholder="-- Nomor SK --">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_sk" class="form-label">Tanggal SK </label>
                        <div class="col-sm-10">
                          <input class="form-control" type="date" name="tanggal_sk"  id="tanggal_sk" placeholder="-- Pilih Tanggal SK --">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="handphone" class="form-label">No. HP </label>
                        <div class="col-sm-10">
                          <input class="form-control" type="text" name="handphone"  id="handphone" placeholder="-- No. HP --">
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

