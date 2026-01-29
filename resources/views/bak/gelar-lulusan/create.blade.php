<div class="modal fade" id="createModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="createModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalTitle">
                    Tambah Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('bak.gelar-lulusan.store')}}" method="post" id="createForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="id_prodi" class="form-label">Program Studi</label>
                            <select class="form-select" name="id_prodi" id="id_prodi" required></select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gelar_panjang_new" class="form-label">Gelar Panjang</label>
                            <input type="text" class="form-control" name="gelar_panjang_new" id="gelar_panjang_new"
                                aria-describedby="helpId" placeholder="" required/>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gelar_new" class="form-label">Gelar Singkatan</label>
                            <input type="text" class="form-control" name="gelar_new" id="gelar_new" aria-describedby="helpId"
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
