<div class="modal fade" id="BkuIjazah" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="ruangKuliahTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ruangKuliahTitle">
                    Setting BKU Pada Ijazah
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="{{route('prodi.data-master.detail-prodi.setting-bku')}}" id="setting-bku" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="bku_pada_ijazah" class="form-label">BKU Pada Ijazah</label>
                            <select class="form-select" name="bku_pada_ijazah" id="bku_pada_ijazah">
                                <option value="1" {{ $data->bku_pada_ijazah == 1 ? 'selected' : '' }}>Iya</option>
                                <option value="0" {{ $data->bku_pada_ijazah == 0 ? 'selected' : '' }}>Tidak</option>
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
@push('js')
<script>
    const myModal = new bootstrap.Modal(
                        document.getElementById("BkuIjazah"),
                        options,
                    );
</script>
@endpush
