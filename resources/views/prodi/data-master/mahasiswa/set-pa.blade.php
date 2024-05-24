<div class="modal fade" id="assignDosenPa" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">
                    Assign Dosen Pembimbing Akademik
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="editForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12n mb-3">
                            <label for="id_dosen" class="form-label">Nama Dosen</label>
                            <select
                                class="form-select"
                                name="id_dosen"
                                id="edit_id_dosen" required
                            >
                                <option value="" disabled selected>-- Pilih Dosen --</option>
                                @foreach ($dosen as $d)
                                <option value="{{$d->id_dosen}}">{{$d->nidn}} - {{$d->nama_dosen}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('js')
    <script>
        $(document).ready(function () {
            $('#edit_id_dosen').select2({
                placeholder: '-- Pilih Dosen -- ',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#assignDosenPa')
            });
            
            confirmSubmit('editForm');
        });
    </script>
@endpush
