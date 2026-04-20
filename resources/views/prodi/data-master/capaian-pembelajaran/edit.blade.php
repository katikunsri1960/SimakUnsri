
{{-- ================= MODAL EDIT ================= --}}
<div class="modal fade" id="editModal{{$d->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="{{route('prodi.data-master.cpl.update', $d->id)}}" method="POST" class="form-edit">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit CPL</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    {{-- KURIKULUM (READONLY) --}}
                    <div class="mb-3">
                        <label class="form-label">Kurikulum</label>
                        <select class="form-select" disabled>
                            @foreach ($list_kurikulum as $m)
                                <option value="{{$m->id_kurikulum}}"
                                    {{$d->id_kurikulum == $m->id_kurikulum ? 'selected' : ''}}>
                                    {{$m->nama_kurikulum}}
                                </option>
                            @endforeach
                        </select>

                        <!-- supaya tetap terkirim -->
                        <input type="hidden" name="id_kurikulum" value="{{$d->id_kurikulum}}">
                    </div>

                    {{-- KODE CPL (TETAP) --}}
                    <div class="mb-3">
                        <label class="form-label">Kode CPL</label>
                        <input type="text"
                            class="form-control"
                            value="{{$d->kode_cpl}}"
                            readonly>
                    </div>

                    {{-- NAMA CPL --}}
                    <div class="mb-3">
                        <label class="form-label">Nama CPL</label>
                        <input type="text"
                            name="nama_cpl"
                            class="form-control"
                            value="{{$d->nama_cpl}}"
                            required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>

            </form>

        </div>
    </div>
</div>