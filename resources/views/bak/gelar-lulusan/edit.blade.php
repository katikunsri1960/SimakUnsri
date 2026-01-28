<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle">
                    Edit Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('bak.gelar-lulusan.update')}}" method="post" id="editForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_gelar" id="id_gelar">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nip" class="form-label">Fakultas</label>
                            <input type="text" class="form-control" name="fakultas" id="fakultas" aria-describedby="helpId"
                                placeholder="" disabled/>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prodi" class="form-label">Prodi</label>
                            <input type="text" class="form-control" name="prodi" id="prodi"
                                aria-describedby="helpId" placeholder="" disabled />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gelar_panjang" class="form-label">Gelar Panjang</label>
                            <input type="text" class="form-control" name="gelar_panjang" id="gelar_panjang"
                                aria-describedby="helpId" placeholder="" required/>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gelar" class="form-label">Gelar Singkatan</label>
                            <input type="text" class="form-control" name="gelar" id="gelar" aria-describedby="helpId"
                                placeholder="" required/>
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
