<div class="modal fade" id="filter-button" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="filterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterTitle">
                    <i class="fa fa-filter"></i> Filter
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('dppti.monitoring.status-aipt.dosen')}}" method="get">
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="id_prodi" class="form-label">Program Studi</label>
                            <select multiple class="form-select" name="id_prodi[]" id="id_prodi">
                                @foreach ($prodi as $p)
                                <option value="{{$p->id_prodi}}" {{ in_array($p->id_prodi, old('id_prodi', request()->get('id_prodi', []))) ? 'selected' : '' }}>
                                    {{$p->kode_program_studi}} - {{$p->nama_program_studi}} ({{$p->nama_jenjang_pendidikan}})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="jenjang_pendidikan" class="form-label">Jenjang Pendidikan Dosen</label>
                            <select multiple class="form-select" name="jenjang_pendidikan[]" id="jenjang_pendidikan">
                                @foreach ($jenjangPendidikan as $j)
                                <option value="{{$j->id_jenjang_pendidikan}}" {{ in_array($j->id_jenjang_pendidikan, old('jenjang_pendidikan', request()->get('jenjang_pendidikan', []))) ? 'selected' : '' }}>
                                    {{$j->nama_jenjang_pendidikan}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="jabatan_fungsional" class="form-label">Jabatan Fungsional</label>
                            <select multiple class="form-select" name="jabatan_fungsional[]" id="jabatan_fungsional">
                                @foreach ($jabatanFungsional as $jf)
                                <option value="{{$jf->jabatan_fungsional}}" {{ in_array($jf->jabatan_fungsional, old('jabatan_fungsional', request()->get('jabatan_fungsional', []))) ? 'selected' : '' }}>
                                    {{$jf->jabatan_fungsional}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="submit" class="btn btn-primary" id="apply-filter">Apply</button>
            </div>
            </form>
        </div>
    </div>
</div>