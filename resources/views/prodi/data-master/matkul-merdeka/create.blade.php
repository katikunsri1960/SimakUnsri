<div class="modal fade" id="kampusMerdeka" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">
                    Tambah Mata Kuliah Kampus Merdeka
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('prodi.data-master.matkul-merdeka.store')}}" method="post" id="masukForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <select class="form-select" name="id_matkul" id="matkulTambah">
                            <option value="">-- Pilih Mata Kuliah --</option>
                            @foreach ($matkul as $m)
                            @if ($m->mata_kuliah)
                            @foreach ($m->mata_kuliah as $mk)
                            <option value="{{$mk->id_matkul}}">({{$mk->kode_mata_kuliah}}) {{$mk->nama_mata_kuliah}}</option>
                            @endforeach
                            @endif
                            @endforeach
                        </select>
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
