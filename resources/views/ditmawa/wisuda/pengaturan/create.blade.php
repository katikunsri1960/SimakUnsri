<div class="modal fade" id="createModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="createModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalTitle">
                    Tambah Periode Wisuda
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('bak.wisuda.pengaturan.store')}}" method="post" id="createForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="periode" class="form-label">Periode Wisuda</label>
                            <input type="number" class="form-control" name="periode" id="periode"
                                aria-describedby="helpId" placeholder="" required value="{{$periode}}" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_wisuda" class="form-label">Tanggal Wisuda</label>
                            <input type="text" class="form-control" name="tanggal_wisuda" id="tanggal_wisuda"
                                aria-describedby="helpId" placeholder="" required readonly value="{{old('tanggal_wisuda')}}"/>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_mulai_daftar" class="form-label">Tanggal Mulai Pendaftaran</label>
                            <input type="text" class="form-control" name="tanggal_mulai_daftar"
                                id="tanggal_mulai_daftar" aria-describedby="helpId" placeholder="" required readonly value="{{old('tanggal_mulai_daftar')}}"/>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_akhir_daftar" class="form-label">Tanggal Akhir Pendaftaran</label>
                            <input type="text" class="form-control" name="tanggal_akhir_daftar"
                                id="tanggal_akhir_daftar" aria-describedby="helpId" placeholder="" required readonly value="{{old('tanggal_akhir_daftar')}}"/>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_akhir_daftar" class="form-label">Aktifkan Periode</label>
                            <select class="form-select" name="is_active" id="is_active" required>
                                <option selected>-- Pilih Salah Satu --</option>
                                <option value="1">Ya, Aktifkan</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batalkan
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
