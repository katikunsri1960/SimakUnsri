<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">
                    Ubah Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- <form method="post" id="editForm">
                @csrf
                @method('patch') --}}
            <form method="post" action="{{ route('fakultas.data-master.pejabat-fakultas.update', $data->id) }}">
                @csrf
                @method('patch')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_dosen" class="form-label">Dosen</label>
                        <select name="id_dosen" id="id_dosen" required class="form-select">
                            <option value="{{ $data->id_dosen }}" selected>{{ $data->nama_dosen }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_jabatan" class="form-label">Jabatan</label>
                        <select name="id_jabatan" id="id_jabatan" required class="form-select">
                            <option value="0" {{ $data->id_jabatan == 0 ? 'selected' : '' }}>Dekan Fakultas</option>
                            <option value="1" {{ $data->id_jabatan == 1 ? 'selected' : '' }}>Wakil Dekan I</option>
                            <option value="2" {{ $data->id_jabatan == 2 ? 'selected' : '' }}>Wakil Dekan II</option>
                            <option value="3" {{ $data->id_jabatan == 3 ? 'selected' : '' }}>Wakil Dekan III</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_mulai_jabatan" class="form-label">Mulai Masa Jabatan</label>
                        <input class="form-control" type="date" name="tgl_mulai_jabatan" id="tgl_mulai_jabatan" value="{{ $data->tgl_mulai_jabatan }}">
                    </div>
                    <div class="mb-3">
                        <label for="tgl_selesai_jabatan" class="form-label">Akhir Masa Jabatan</label>
                        <input class="form-control" type="date" name="tgl_selesai_jabatan" id="tgl_selesai_jabatan" value="{{ $data->tgl_selesai_jabatan }}">
                    </div>
                    <div class="mb-3">
                        <label for="gelar_depan" class="form-label">Gelar Depan</label>
                        <input type="text" id="gelar_depan" class="form-control" name="gelar_depan" value="{{ $data->gelar_depan }}">
                    </div>
                    <div class="mb-3">
                        <label for="gelar_belakang" class="form-label">Gelar Belakang</label>
                        <input type="text" id="gelar_belakang" class="form-control" name="gelar_belakang" value="{{ $data->gelar_belakang }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>                
        </div>
    </div>
</div>
