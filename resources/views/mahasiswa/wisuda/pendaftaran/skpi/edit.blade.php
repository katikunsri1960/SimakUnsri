@foreach($skpi_data->where('bidang_id',$bidang->id) as $row)

<div class="modal fade" id="modalSkpi{{$bidang->id}}-{{$row->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Edit SKPI - {{$bidang->nama_bidang}}
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{route('mahasiswa.wisuda.pendaftaran.data-skpi.update',$row->id)}}"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="modal-body">

                    <input type="hidden" name="id_bidang" value="{{$bidang->id}}">

                    <div class="mb-3">
                        <label class="form-label">Nama Kegiatan</label>

                        <input type="text"
                            name="nama_kegiatan"
                            class="form-control"
                            value="{{$row->nama_kegiatan}}"
                            required>
                    </div>

                    <div class="mb-3">

                        <label class="form-label">Jenis Kegiatan</label>

                        <select name="id_jenis_skpi"
                            class="form-control select2"
                            required>

                            <option value="">Pilih Jenis</option>

                            @foreach($skpi_jenis_kegiatan->where('bidang_id',$bidang->id) as $jenis)

                            <option value="{{$jenis->id}}"
                                {{$row->id_jenis_skpi == $jenis->id ? 'selected' : ''}}>
                                {{$jenis->nama_jenis}} ({{$jenis->kriteria}})
                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            File Pendukung
                        </label>

                        @if($row->file_pendukung)

                        <div class="mb-3">

                            <label class="form-label">File Pendukung Lama</label>

                            <div class="border rounded p-2 bg-light">

                                <iframe
                                    src="{{ asset('storage/'.$row->file_pendukung) }}"
                                    width="100%"
                                    height="350">
                                </iframe>
                            </div>
                        </div>
                        @endif
                        <input type="file"
                            name="file_pendukung"
                            class="form-control"
                            accept="application/pdf"
                            onchange="validateFileSize(this)">

                        <small class="text-danger">
                            Kosongkan jika tidak ingin mengganti file
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
                        Update
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

@endforeach