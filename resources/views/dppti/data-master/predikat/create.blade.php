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
            <form method="post" id="createForm" action="{{route('bak.data-master.predikat.store')}}">
                @csrf
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nip" class="form-label">Bahasa Indonesia</label>
                            <input type="text" class="form-control" name="indonesia" id="indonesia" aria-describedby="helpId"
                                placeholder="" required/>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jabatan" class="form-label">Bahasa Inggris</label>
                            <input type="text" class="form-control" name="inggris" id="inggris"
                                aria-describedby="helpId" placeholder="" required />
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
