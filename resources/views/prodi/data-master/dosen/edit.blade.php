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
            <form action="{{route('prodi.data-master.dosen.gelar.store')}}" method="post" id="editForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_dosen" id="id_dosen">
                    <div class="row">
                        <!-- <div class="col-md-4 mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" class="form-control" name="nip" id="nip" aria-describedby="helpId"
                                placeholder="" required/>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nidn" class="form-label">NIDN</label>
                            <input type="text" class="form-control" name="nidn" id="nidn" aria-describedby="helpId"
                                placeholder="" required/>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nuptk" class="form-label">NUPTK</label>
                            <input type="text" class="form-control" name="nuptk" id="nuptk" aria-describedby="helpId"
                                placeholder="" required/>
                        </div> -->
                        <div class="col-md-3 mb-3">
                            <label for="gelar_depan" class="form-label">Gelar Depan</label>
                            <input type="text" class="form-control" name="gelar_depan" id="gelar_depan"
                                aria-describedby="helpId" placeholder="" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" name="nama" id="nama" aria-describedby="helpId"
                                placeholder="" required disabled/>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="gelar_belakang" class="form-label">Gelar Belakang</label>
                            <input type="text" class="form-control" name="gelar_belakang" id="gelar_belakang"
                                aria-describedby="helpId" placeholder="" />
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
