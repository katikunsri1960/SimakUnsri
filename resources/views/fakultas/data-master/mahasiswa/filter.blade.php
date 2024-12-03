<div class="modal fade" id="filter-button" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="filterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterTitle">
                    {{-- filter icon fa --}}
                    <i class="fa fa-filter"></i> Filter
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('fakultas.data-master.mahasiswa')}}" method="get">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="prodi" class="form-label">Prodi</label>
                            <select multiple class="form-select" name="prodi[]" id="prodi">
                                @foreach ($prodi as $p)
                                <option value="{{$p->id_prodi}}" {{ in_array($p->id_prodi, old('prodi',
                                    request()->get('prodi', []))) ? 'selected' : '' }}>
                                    {{$p->nama_jenjang_pendidikan}} - {{$p->nama_program_studi}} ({{$p->kode_program_studi}})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="angkatan" class="form-label">Angkatan</label>
                            <select multiple class="form-select" name="angkatan[]" id="angkatan">
                                <option value="">
                                    -- Pilih Angkatan --
                                </option>
                                @foreach ($angkatan as $p)
                                    <option value="{{$p->angkatan_raw}}" {{ in_array($p->angkatan_raw, old('angkatan',
                                        request()->get('angkatan', []))) ? 'selected' : '' }}>
                                        {{$p->angkatan_raw}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="status_keluar" class="form-label">Status Keluar</label>
                            <select multiple class="form-select" name="status_keluar[]" id="status_keluar">
                                <option value="" disabled>-- Pilih Status Keluar --</option>
                                <!-- Option manual -->
                                <option value="*" {{ in_array('*', old('status_keluar', request()->get('status_keluar', []))) ? 'selected' : '' }}>Aktif</option>
                                <!-- Options dari database -->
                                @foreach ($status_keluar as $p)
                                    <option value="{{$p->id_jenis_keluar}}" 
                                        {{ in_array($p->id_jenis_keluar, old('status_keluar', request()->get('status_keluar', []))) ? 'selected' : '' }}>
                                        {{$p->keterangan_keluar}}
                                    </option>
                                @endforeach
                            </select>
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
@push('js')

<script>
    $(document).ready(function () {
        $('#prodi').select2({
            placeholder: '-- Pilih Prodi -- ',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#filter-button')
        });

        $('#angkatan').select2({
            placeholder: '-- Pilih Angkatan -- ',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#filter-button')
        });

        $('#status_keluar').select2({
            placeholder: '-- Pilih Status Keluar -- ',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#filter-button')
        });
    });

</script>
@endpush
