@foreach($data as $d)
<div class="modal fade" id="modalEdit{{ $d->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{route('bak.skpi.jenis.update',$d->id)}}" method="POST" class="form-edit">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Jenis Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Bidang</label>
                        <select name="bidang_id" class="form-control" required>
                            @foreach($bidang as $b)
                                <option value="{{$b->id}}"
                                    {{$d->bidang_id == $b->id ? 'selected' : ''}}>
                                    {{$b->nama_bidang}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Nama Jenis</label>
                        <input type="text" name="nama_jenis"
                               value="{{$d->nama_jenis}}"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Kriteria</label>
                        <textarea name="kriteria" class="form-control" rows="3">
{{$d->kriteria}}
                        </textarea>
                    </div>

                    <div class="mb-3">
                        <label>Skor</label>
                        <input type="number" name="skor"
                               value="{{$d->skor}}"
                               class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>

            </form>

        </div>
    </div>
</div>
@endforeach