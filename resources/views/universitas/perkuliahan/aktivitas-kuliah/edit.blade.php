<form id="editForm" method="POST">
    @csrf
    <input type="hidden" name="_method" value="PATCH"> <!-- Untuk method PATCH -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Aktivitas Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body p-30">
                    <input type="hidden" id="edit_id" name="id">

                    <!-- Nama Mahasiswa -->
                    <div class="form-group mb-20">
                        <label for="edit_nama_mahasiswa">Nama Mahasiswa</label>
                        <input type="text" class="form-control" id="edit_nama_mahasiswa" name="nama_mahasiswa" readonly>
                    </div>

                    <h4 class="text-info mb-0 mt-30"><i class="fa fa-user"></i> Data Aktivitas Kuliah Mahasiswa</h4>
                    <hr class="my-5">

                    <!-- Status Mahasiswa -->
                    <div class="row form-group mb-10">
                        <div class="col-lg-12 mb-3">
                            <label for="edit_status_mahasiswa">Status Mahasiswa</label>
                            <select id="edit_status_mahasiswa" name="id_status_mahasiswa" class="form-select" required>
                                <option value="">-- Pilih Status --</option>
                                <!-- Option akan diisi secara dinamis oleh JavaScript -->
                            </select>
                        </div>
                    </div>

                    <!-- IPS dan SKS Semester -->
                    <div class="row form-group mb-10">
                        <div class="col-lg-6 mb-3">
                            <label for="edit_ips">IPS</label>
                            <input type="number" class="form-control" id="edit_ips" name="ips" step="0.01">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="edit_sks_semester">SKS Semester</label>
                            <input type="number" class="form-control" id="edit_sks_semester" name="sks_semester">
                        </div>
                    </div>

                    <!-- IPK dan SKS Total -->
                    <div class="row form-group mb-10">
                        <div class="col-lg-6 mb-3">
                            <label for="edit_ipk">IPK</label>
                            <input type="number" class="form-control" id="edit_ipk" name="ipk" step="0.01">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="edit_sks_total">SKS Total</label>
                            <input type="number" class="form-control" id="edit_sks_total" name="sks_total">
                        </div>
                    </div>

                    <!-- Semester dan Jenis Pembiayaan -->
                    <div class="row form-group mb-10">
                        <div class="col-lg-6 mb-3">
                            <label for="edit_id_semester">Semester</label>
                            <select class="form-control" id="edit_id_semester" name="id_semester">
                                <option value="">-- Pilih Semester --</option>
                                <!-- Option akan diisi secara dinamis oleh JavaScript -->
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

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>
</form>
