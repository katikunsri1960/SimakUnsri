<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Aktivitas Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    {{-- <span aria-hidden="true">&times;</span> --}}
                </button>
            </div>
            <div class="modal-body p-30">
                <input type="hidden" id="edit_id" name="id">

                <div class="form-group">
                    <label for="edit_nama_mahasiswa">Nama Mahasiswa</label>
                    <input type="text" class="form-control" id="edit_nama_mahasiswa" name="nama_mahasiswa" readonly>
                </div>
                <h4 class="text-info mb-0 mt-20"><i class="fa fa-user"></i> Data Aktivitas Kuliah Mahasiswa</h4>
                <hr class="my-15">
                <div class="row form-group mb-10">
                    <div class="col-lg-12 mb-3">
                        <label for="edit_status_mahasiswa">Status Mahasiswa</label>
                        <select id="edit_status_mahasiswa" name="status_mahasiswa" class="form-select" required>
                            <option value="">-- Pilih Status --</option>
                            <!-- Option akan diisi secara dinamis oleh JavaScript -->
                        </select>
                    </div>
                </div>

                <div class="row form-group mb-10">
                    <div class="col-lg-6 mb-3">
                        <label for="edit_ips">IPS</label>
                        <input type="number" class="form-control" id="edit_ips" name="ips">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="edit_sks_semester">SKS Semester</label>
                        <input type="number" class="form-control" id="edit_sks_semester" name="sks_semester">
                    </div>
                </div>

                <div class="row form-group mb-10">
                    <div class="col-lg-6 mb-3">
                        <label for="edit_ipk">IPK</label>
                        <input type="number" step="0.01" class="form-control" id="edit_ipk" name="ipk">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="edit_sks_total">SKS Total</label>
                        <input type="number" class="form-control" id="edit_sks_total" name="sks_total">
                    </div>
                </div>

                <div class="row form-group mb-10">
                    <div class="col-lg-6 mb-3">
                        <label for="edit_id_semester">Semester</label>
                        <select class="form-control" id="edit_id_semester" name="id_semester">
                            <option value="">-- Pilih Semester --</option>
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="edit_id_pembiayaan" class="form-label">Jenis Pembiayaan</label>
                        <select name="id_pembiayaan" id="edit_id_pembiayaan" class="form-select" required>
                            <option value="">-- Pilih Jenis Pembiayaan --</option>
                            <!-- Option akan diisi secara dinamis oleh JavaScript -->
                        </select>
                    </div>                        
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>
