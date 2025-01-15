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
        <form action="{{route('univ.perkuliahan.aktivitas-kuliah.store')}}"  method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body p-30">
                <div class="row form-group">
                    <div class="col-lg-6 mb-3">
                        <label for="id_registrasi_mahasiswa" class="form-label">Mahasiswa</label>
                        <select id="id_registrasi_mahasiswa"  name="id_registrasi_mahasiswa"></select>
                    </div>
                </div>
                <h4 class="text-info mb-0 mt-20"><i class="fa fa-user"></i> Data Aktivitas Kuliah Mahasiswa</h4>
                <hr class="my-15">
                <div class="row form-group">
                    {{-- <div class="col-lg-6 mb-3">
                        <label for="id_semester" class="form-label">Semester</label>
                        <select id="id_semester" name="id_semester" class="form-control">
                            <option value="">-- Pilih Semester --</option>
                            @foreach($semester as $semester)
                                <option value="{{ $semester->id_semester }}">{{ $semester->nama_semester }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="col-lg-12 mb-3">
                        <label for="status_mahasiswa_id" class="form-label">Status Mahasiswa</label>
                        <select name="status_mahasiswa_id" id="status_mahasiswa_id" required class="form-select">
                            <option value="" selected disabled>-- Pilih Status --</option>
                            <option value="A">Aktif</option>
                            <option value="M">Kampus Merdeka</option>
                            <option value="C">Cuti</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div id="cuti-fields">
                        <div class="akm-field row mb-10">
                            <div class="col-lg-6 mb-2">
                                <label for="ips" class="form-label">IPS</label>
                                <input type="text" class="form-control" id="ips" name="ips" aria-describedby="helpId" placeholder="-- IPS --"/>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label for="sks_semester" class="form-label">SKS Semester</label>
                                <input type="text" class="form-control" id="sks_semester" name="sks_semester" aria-describedby="helpId" placeholder="-- SKS Semester --"/>
                            </div>
                        </div>
                        <div class="alasan-field row mb-10">
                            <div class="col-lg-6 mb-2">
                                <label for="ipk" class="form-label">IPK</label>
                                <input type="text" class="form-control" id="ipk" name="ipk" aria-describedby="helpId" placeholder="-- IPK --"/>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label for="sks_total" class="form-label">SKS Total</label>
                                <input type="text" class="form-control" id="sks_total" name="sks_total" aria-describedby="helpId" placeholder="-- SKS Total --"/>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-6 mb-3">
                                <label for="id_semester" class="form-label">Semester</label>
                                <select id="id_semester" name="id_semester" class="form-control">
                                    <option value="">-- Pilih Semester --</option>
                                    @foreach($semester as $semester)
                                        <option value="{{ $semester->id_semester }}">{{ $semester->nama_semester }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="id_pembiayaan" class="form-label">Id Pembiayaan</label>
                                <select name="id_pembiayaan" id="id_pembiayaan" required class="form-select">
                                    <option value="" selected disabled>-- Pilih Status --</option>
                                    <option value="1">Mandiri</option>
                                    <option value="2">Beasiswa Tidak Penuh</option>
                                    <option value="3">Beasiswa Penuh</option>
                                </select>
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

