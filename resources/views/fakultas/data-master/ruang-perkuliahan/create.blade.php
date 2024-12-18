<div class="modal fade" id="tambahRuangKuliah" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="ruangKuliahTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ruangKuliahTitle">
                    Tambah Ruang Kuliah
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="{{route('fakultas.data-master.ruang-perkuliahan.store')}}" id="tambah-ruang" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="nama_ruang" class="form-label">Nama Ruang Kuliah</label>
                            <input
                                type="text"
                                class="form-control"
                                name="nama_ruang"
                                id="nama_ruang"
                                aria-describedby="helpId"
                                placeholder="Masukkan Nama Ruang"
                                onkeydown="upperCaseF(this)"
                                required
                            />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <select class="form-select" name="lokasi" id="lokasi" required>
                            <option value="Indralaya">Indralaya</option>
                            <option value="Palembang">Palembang</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="kapasitas_ruang" class="form-label">Kapasitas Ruang Kuliah</label>
                            <input
                                type="number"
                                class="form-control"
                                name="kapasitas_ruang"
                                id="kapasitas_ruang"
                                aria-describedby="helpId"
                                placeholder="Masukkan Kapasitas Ruang"
                                required
                            />
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
@push('js')
<script>
    const myModal = new bootstrap.Modal(
                        document.getElementById("tambahRuangKuliah"),
                        options,
                    );

    function upperCaseF(a){
        setTimeout(function(){
            a.value = a.value.toUpperCase();
        }, 1);
    }
</script>
@endpush
