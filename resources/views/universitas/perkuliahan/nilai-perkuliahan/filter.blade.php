<div class="modal fade" id="filter-button" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="filterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterTitle">
                    {{-- filter icon fa --}}
                    <i class="fa fa-filter"></i> Filter
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 col-md-12">
                    <label for="id_prodi" class="form-label">Semester</label>
                    <select
                        multiple
                        class="form-select"
                        name="id_semester[]"
                        id="id_semester"
                    >
                        @foreach ($semester as $s)
                        <option value="{{$s->id_semester}}" {{ in_array($s->id_semester, old('id_semester', request()->get('id_semester', []))) ? 'selected' : '' }}>
                            {{$s->nama_semester}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 col-md-12">
                    <label for="id_prodi" class="form-label">Program Studi</label>
                    <select
                        multiple
                        class="form-select"
                        name="id_prodi[]"
                        id="id_prodi"
                    >
                        @foreach ($prodi as $p)
                        <option value="{{$p->id_prodi}}" {{ in_array($p->id_prodi, old('id_prodi', request()->get('id_prodi', []))) ? 'selected' : '' }}>
                            {{$p->kode_program_studi}} - {{$p->nama_program_studi}} ({{$p->nama_jenjang_pendidikan}})
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="button" class="btn btn-primary" id="apply-filter">Apply</button>
            </div>
        </div>
    </div>
</div>

