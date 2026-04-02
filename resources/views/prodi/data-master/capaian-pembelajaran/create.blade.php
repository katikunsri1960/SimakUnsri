<div class="modal fade" id="kampusMerdeka" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">
                    Tambah Capaian Pembelajaran Lulusan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('prodi.data-master.cpl.store')}}" method="post" id="masukForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <select class="form-select" name="id_kurikulum" id="matkulTambah" required>
                            <option value="">-- Pilih Kurikulum --</option>
                            @foreach ($list_kurikulum as $m)
                                <option value="{{$m->id_kurikulum}}">{{$m->nama_kurikulum}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <input type="text" name="nama_cpl" class="form-control" placeholder="Nama CPL" required>
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
