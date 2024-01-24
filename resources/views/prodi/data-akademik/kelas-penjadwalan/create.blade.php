<div class="modal fade" id="tambahKelasKuliah" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="ruangKuliahTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ruangKuliahTitle">
                    Tambah Kelas Kuliah
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="{{route('prodi.data-akademik.kelas-penjadwalan.store')}}" id="tambah-kelas" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="nama_mata_kuliah" class="form-label">Nama Mata Kuliah</label>
                            <input
                                type="text"
                                class="form-control"
                                name="nama_mata_kuliah"
                                id="nama_mata_kuliah"
                                aria-describedby="helpId"
                                placeholder="Masukkan Nama Mata Kuliah"
                                onkeydown="upperCaseF(this)"
                                required
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai Efektif</label>
                        <div class="mb-3">
                            <input class="form-control" type="date" value="{{date('Y-m-d')}}" id="tanggal_mulai">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_akhir" class="form-label">Tanggal Akhir Efektif</label>
                        <div class="mb-3">
                            <input class="form-control" type="date" value="{{date('Y-m-d')}}" id="tanggal_akhir">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="kapasitas_kelas" class="form-label">Kapasitas Kelas Kuliah</label>
                            <input
                                type="text"
                                class="form-control"
                                name="kapasitas_kelas"
                                id="kapasitas_kelas"
                                aria-describedby="helpId"
                                placeholder="Masukkan Kapasitas Kelas Kuliah"
                                required
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="mode_kelas" class="form-label">Mode Kelas Kuliah</label>
                            <select class="form-select" name="mode_kelas" id="mode_kelas" required>
                                <option value="Indralaya">Indralaya</option>
                                <option value="Palembang">Palembang</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="lingkup_kelas" class="form-label">Lingkup Kelas Kuliah</label>
                            <select class="form-select" name="mode_kelas" id="mode_kelas" required>
                                <option value="Indralaya">Indralaya</option>
                                <option value="Palembang">Palembang</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect waves-light" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Optional: Place to the bottom of scripts -->
<script>
    const myModal = new bootstrap.Modal(
                        document.getElementById("tambahKelasKuliah"),
                        options,
                    );

    function upperCaseF(a){
        setTimeout(function(){
            a.value = a.value.toUpperCase();
        }, 1);
    }
</script>
