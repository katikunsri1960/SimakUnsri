<div class="modal fade" id="createModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document" >
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="createModalLabel">
                    Tambah Pengajuan Cuti
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{route('univ.cuti-manual.store')}}"  method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body p-30">
                <div class="row form-group">
                    <div class="col-lg-6 mb-3">
                        <label for="id_registrasi_mahasiswa" class="form-label">Mahasiswa</label>
                        <select id="id_registrasi_mahasiswa"  name="id_registrasi_mahasiswa"></select>
                    </div>
                </div>
                {{-- <div class="box-body"> --}}
                <h4 class="text-info mb-0 mt-20"><i class="fa fa-university"></i> Data Mahasiswa</h4>
                <hr class="my-15">
                <div class="row form-group">
                    <div class="col-lg-6 mb-3">
                        <label for="fakultas_mahasiswa" class="form-label">Fakultas</label>
                        <input type="text" id="fakultas_mahasiswa" name="fakultas_mahasiswa" class="form-control" disabled />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="jurusan_mahasiswa" class="form-label">Jurusan</label>
                        <input type="text" id="jurusan_mahasiswa" name="jurusan_mahasiswa" class="form-control" disabled />
                    </div>
                </div>
                <div class="row form-group">
                    {{-- <div class="col-lg-4 mb-3">
                        <label for="jenjang_mahasiswa" class="form-label">Jenjang Pendidikan</label>
                        <input type="text" id="jenjang_mahasiswa" name="jenjang_mahasiswa" class="form-control" disabled />
                    </div> --}}
                    <div class="col-lg-6 mb-3">
                        <label for="prodi_mahasiswa" class="form-label">Program Studi</label>
                        <input type="text" id="prodi_mahasiswa" name="prodi_mahasiswa" class="form-control" disabled />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="id_semester" class="form-label">Semester</label>
                        <input type="text" id="id_semester" name="id_semester" class="form-control" disabled />
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-lg-6 mb-3">
                        <label for="handphone" class="form-label">Handphone</label>
                        <input type="text" id="handphone" name="handphone" class="form-control" disabled />
                    </div>
                </div>
                <h4 class="text-info mb-0 mt-20"><i class="fa fa-user"></i> Pengajuan Cuti Mahasiswa</h4>
                <hr class="my-15">
                <div class="form-group">
                    <div id="cuti-fields">
                        <div class="sk-field row mb-10">
                            <div class="col-lg-6 mb-2">
                                <label for="no_sk" class="form-label">Nomor SK Pengajuan Cuti</label>
                                <input type="text" class="form-control" id="no_sk" name="no_sk" aria-describedby="helpId" placeholder="-- Nomor SK --"/>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label for="tanggal_sk" class="form-label">Tanggal SK Pengajuan Cuti</label>
                                <input type="date" class="form-control" id="tanggal_sk" name="tanggal_sk" aria-describedby="helpId" placeholder="-- Pilih Tanggal SK --"/>
                            </div>
                        </div>
                        <div class="alasan-field row mb-10">
                            <div class="col-lg-6 mb-2">
                                <label for="alasan_cuti" class="form-label">Alasan Pengajuan Cuti</label>
                                <input type="text" class="form-control" id="alasan_cuti" name="alasan_cuti" aria-describedby="helpId" placeholder="-- Alasan Pengajuan Cuti --"/>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="file_pendukung" class="form-label">File Pendukung (.pdf)</label>
                                <input type="file" class="form-control" id="file_pendukung" name="file_pendukung" aria-describedby="fileHelpId" accept=".pdf"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-30">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
        </div>
    </div>
</div>

