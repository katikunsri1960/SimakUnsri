<div class="modal fade" id="PeminatanIjazah" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="ruangKuliahTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ruangKuliahTitle">
                    Setting Peminatan Pada Transkrip
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="{{route('prodi.data-master.detail-prodi.setting-peminatan')}}" id="setting-peminatan" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="peminatan_pada_transkrip" class="form-label">Peminatan Pada Transkrip</label>
                            <select class="form-select" name="peminatan_pada_transkrip" id="peminatan_pada_transkrip">
                                <option value="1" {{ $data->peminatan_pada_transkrip == 1 ? 'selected' : '' }}>Iya</option>
                                <option value="0" {{ $data->peminatan_pada_transkrip == 0 ? 'selected' : '' }}>Tidak</option>
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
                        document.getElementById("PeminatanIjazah"),
                        options,
                    );
</script>
@endpush
