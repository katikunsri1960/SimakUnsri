<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle">
                    EDIT GELAR AKADEMIK
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('prodi.data-master.dosen.gelar.store')}}" method="post" id="editForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_dosen" id="id_dosen">
                    <div class="row box-outline-success bs-3 border-success rounded mx-5">
                        <div class="col-md-12 mb-3">
                            <h4 for="nama" class="form-label mt-5">NAMA DOSEN</h4>
                            <input type="text" class="form-control" name="nama" id="nama" aria-describedby="helpId"
                                placeholder="" required disabled/>
                        </div>
                    </div>
                    <div class="row mx-5">
                        <div class="col-md-6 mb-3 box-outline-success bs-3 border-success rounded mt-10">
                            <h4 for="gelar_depan" class="form-label mt-5">GELAR DEPAN</h4>
                            <div class="col-md-12 mb-3">
                                <label for="gelar_depan_s1" class="form-label">Gelar Depan S1</label>
                                <input type="text" class="form-control" name="gelar_depan_s1" id="gelar_depan_s1"
                                    aria-describedby="helpId" placeholder="" />
                                <small class="text-danger">Inputkan gelar akademik beserta tanda baca! </small>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="gelar_depan_s2" class="form-label">Gelar Depan S2</label>
                                <input type="text" class="form-control" name="gelar_depan_s2" id="gelar_depan_s2"
                                    aria-describedby="helpId" placeholder="" />
                                <small class="text-danger">Inputkan gelar akademik beserta tanda baca! </small>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="gelar_depan_s3" class="form-label">Gelar Depan S3</label>
                                <input type="text" class="form-control" name="gelar_depan_s3" id="gelar_depan_s3"
                                    aria-describedby="helpId" placeholder="" />
                                <small class="text-danger">Inputkan gelar akademik beserta tanda baca! </small>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="gelar_depan_gb" class="form-label">Gelar Depan Guru Besar</label>
                                <input type="text" class="form-control" name="gelar_depan_gb" id="gelar_depan_gb"
                                    aria-describedby="helpId" placeholder="" />
                                <small class="text-danger">Inputkan gelar akademik beserta tanda baca! </small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3 box-outline-success bs-3 border-success rounded mt-10">
                            <h4 for="gelar_depan" class="form-label mt-5">GELAR BELAKANG</h4>
                            <div class="col-md-12 mb-3">
                                <label for="gelar_belakang_s1" class="form-label">Gelar Belakang S1</label>
                                <input type="text" class="form-control" name="gelar_belakang_s1" id="gelar_belakang_s1"
                                    aria-describedby="helpId" placeholder="" />
                                <small class="text-danger">Inputkan gelar akademik beserta tanda baca! </small>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="gelar_belakang_s2" class="form-label">Gelar Belakang S2</label>
                                <input type="text" class="form-control" name="gelar_belakang_s2" id="gelar_belakang_s2"
                                    aria-describedby="helpId" placeholder="" />
                                <small class="text-danger">Inputkan gelar akademik beserta tanda baca! </small>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="gelar_belakang_s3" class="form-label">Gelar Belakang S3</label>
                                <input type="text" class="form-control" name="gelar_belakang_s3" id="gelar_belakang_s3"
                                    aria-describedby="helpId" placeholder="" />
                                <small class="text-danger">Inputkan gelar akademik beserta tanda baca! </small>
                            </div>
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
