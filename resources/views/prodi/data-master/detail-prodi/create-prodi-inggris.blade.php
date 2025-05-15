<div class="modal fade" id="ProdiInggris" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="ruangKuliahTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ruangKuliahTitle">
                    Update Nama Bahasa Inggris Prodi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="{{route('prodi.data-master.detail-prodi.prodi-inggris.store')}}" id="create-prodi-inggris" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="nama_prodi" class="form-label">Nama Program Studi (Inggris)</label>
                            <input
                                type="text"
                                class="form-control"
                                name="nama_prodi"
                                id="nama_prodi"
                                aria-describedby="helpId"
                                placeholder="Masukkan Nama Prodi"
                                value="{{$data->nama_program_studi_en}}"
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
                        document.getElementById("ProdiInggris"),
                        options,
                    );
</script>
@endpush
