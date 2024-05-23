<div class="modal fade" id="setAngkatanModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="setmodalAngkatanTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setmodalAngkatanTitle">
                    Atur Kurikulum Angkatan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('prodi.data-master.mahasiswa.set-kurikulum-angkatan')}}" method="post" id="postForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tahun_angkatan" class="form-label">Angkatan</label>
                            <select class="form-select" name="tahun_angkatan" id="tahun_angkatan">
                                <option value="" selected disabled>-- Pilih Angkatan --</option>
                                @foreach ($angkatan as $a)
                                <option value="{{$a->angkatan}}">
                                    {{$a->angkatan}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_kurikulum" class="form-label">Kurikulum</label>
                            <select class="form-select" name="id_kurikulum" id="id_kurikulum">
                                <option value="" selected disabled>-- Pilih Kurikulum --</option>
                                @foreach ($kurikulum as $k)
                                <option value="{{$k->id_kurikulum}}">
                                    {{$k->nama_kurikulum}}
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
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('js')
    <script>
        confirmSubmit('postForm');
    </script>
@endpush
