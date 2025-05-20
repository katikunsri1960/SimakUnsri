 <div class="modal fade" id="editModal{{$d->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editModalLabel{{$d->id}}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{$d->id}}">Edit SK Yudisium</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('fakultas.wisuda.edit-sk-yudisium', $d->id) }}" method="POST" id="edit-class-{{$d->id}}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="no_sk_yudisium{{$d->id}}" class="form-label">No SK Yudisium</label>
                        <input type="text" class="form-control" id="no_sk_yudisium{{$d->id}}" name="no_sk_yudisium" value="{{ $d->no_sk_yudisium }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_sk_yudisium{{$d->id}}" class="form-label">Tanggal SK Yudisium</label>
                        <input type="date" class="form-control" id="tgl_sk_yudisium{{$d->id}}" name="tgl_sk_yudisium" value="{{ $d->tgl_sk_yudisium }}" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="col-md-12">Cari atau Upload File SK Yudisium</label>
                        <div class="row">
                            <div class="col-md-8 mb-2">
                                <select name="id_edit" id="nama_file_edit{{$d->id}}" class="form-select">
                                    <option value="">-- Cari File SK Yudisium --</option>
                                </select>
                                <small class="text-muted">Cari file SK Yudisium yang sudah ada</small>
                            </div>
                            <div class="col-md-4 mb-2 d-flex align-items-end">
                                <button type="button" class="btn btn-primary btn-sm" id="btnCari"><i class="fa fa-search"></i> Cari</button>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="editBaru{{$d->id}}">
                                    <label class="form-check-label" for="editBaru{{$d->id}}">
                                        Upload file SK Yudisium baru
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3" id="editFileSection{{$d->id}}" style="display:none;">
                        <label for="sk_yudisium_file_{{$d->id}}" class="form-label">File SK Yudisium (.pdf)</label>
                        <input type="file" class="form-control" name="sk_yudisium_file" id="sk_yudisium_file_{{$d->id}}"
                            aria-describedby="fileHelpId" accept=".pdf" />
                        <small class="text-muted">Upload file baru jika belum ada di daftar</small><br>
                        <small class="text-muted text-danger">Kosongkan jika tidak ingin merubah file</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>
    $(function(){
        $('#editBaru{{$d->id}}').on('change', function(){
            if($(this).is(':checked')){
                $('#editFileSection{{$d->id}}').show();
                $('#sk_yudisium_file_{{$d->id}}').attr('required', true);
                $('#nama_file_edit').attr('disabled', true);
            } else {
                $('#editFileSection{{$d->id}}').hide();
                $('#sk_yudisium_file_{{$d->id}}').attr('required', false);
                $('#nama_file_edit').attr('disabled', false);
            }
        });
    });

    $('#editModal{{$d->id}}').on('shown.bs.modal', function () {
        $("#nama_file_edit{{$d->id}}").select2({
            dropdownParent: $('#editModal{{$d->id}}'),
            placeholder : '-- Masukan No SK Yudisium / Nama File --',
            width: '100%',
            minimumInputLength: 3,
            ajax: {
                url: "{{route('fakultas.wisuda.search-sk-yudisium')}}",
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama_file + " ("+item.fakultas.nama_fakultas+")",
                                id: item.id
                            }
                        })
                    };
                },
            }
        });
    });

    $('#edit-class-{{$d->id}}').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Upload SK Yudisium',
            text: "Apakah anda yakin upload file?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#edit-class-{{$d->id}}').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });
</script>
@endpush
