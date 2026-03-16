<div class="modal fade" id="modalSkpi{{$bidang->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered rounded">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Tambah SKPI - {{$bidang->nama_bidang}}
                </h5>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <form id="formSkpi{{$bidang->id}}"
                action="{{route('mahasiswa.wisuda.pendaftaran.data-skpi.store')}}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <div class="modal-body">

                    <input type="hidden"
                        name="id_bidang"
                        value="{{$bidang->id}}">

                    <div class="mb-3">
                        <label class="form-label">Nama Kegiatan</label>

                        <input type="text"
                            name="nama_kegiatan"
                            class="form-control"
                            placeholder="Masukkan Nama Kegiatan Anda"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tahun Kegiatan</label>

                        <select name="tahun_kegiatan" class="form-control" required>
                            <option value="">Pilih Tahun</option>
                            @for($i = date('Y'); $i >= 2000; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-3">

                        <label class="form-label">Jenis Kegiatan</label>

                        <select name="id_jenis_skpi"
                            class="form-control select2"
                            required>

                            <option value="" disabled selected>
                                Pilih Jenis
                            </option>

                            @foreach($skpi_jenis_kegiatan->where('bidang_id',$bidang->id) as $jenis)

                            <option value="{{$jenis->id}}">
                                {{$jenis->nama_jenis}} ({{$jenis->kriteria}})
                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            File Pendukung
                        </label>

                        <input type="file"
                            name="file_pendukung"
                            class="form-control"
                            accept="application/pdf"
                            required
                            onchange="validateFileSize(this)">

                        <small class="text-danger">
                            Format PDF, maks. 500KB
                        </small>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                        class="btn btn-primary">
                        Simpan
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>