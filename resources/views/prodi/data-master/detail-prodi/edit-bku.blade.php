<div class="modal fade" id="EditBKU{{$db->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="ruangKuliahTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ruangKuliahTitle">
                    Tambah BKU 
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="{{route('prodi.data-master.detail-prodi.update-bku', $db->id)}}" id="create-bku" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="bku_prodi_id" class="form-label">Nama Bidang Kajian Utama (Indonesia)</label>
                            <input
                                type="text"
                                class="form-control"
                                name="bku_prodi_id"
                                id="bku_prodi_id"
                                aria-describedby="helpId"
                                placeholder="Masukkan Nama BKU (Indonesia)"
                                value="{{$db->bku_prodi_id}}"
                                required
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="bku_prodi_en" class="form-label">Nama Bidang Kajian Utama (Inggris)</label>
                            <input
                                type="text"
                                class="form-control"
                                name="bku_prodi_en"
                                id="bku_prodi_en"
                                aria-describedby="helpId"
                                placeholder="Masukkan BKU (Inggris)"
                                value="{{$db->bku_prodi_en}}"
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
                        document.getElementById("EditBKU{{$db->id}}"),
                        options,
                    );
</script>
@endpush
