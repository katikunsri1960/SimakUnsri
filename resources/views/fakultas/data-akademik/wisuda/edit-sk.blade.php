<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit SK Yudisium</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="edit-class" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="no_sk_yudisium" class="form-label">No SK Yudisium</label>
                            <input type="text" class="form-control" id="no_sk_yudisium" name="no_sk_yudisium" value="{{ old('no_sk_yudisium', $data->no_sk_yudisium ?? '') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tgl_sk_yudisium" class="form-label">Tanggal SK Yudisium</label>
                            <input type="date" class="form-control" id="tgl_sk_yudisium" name="tgl_sk_yudisium" value="{{ old('tgl_sk_yudisium', $data->tgl_sk_yudisium ?? '') }}" required>
                            <span class="badge badge-danger-light mt-2">* Gunakan tanggal tanda tangan SK Yudisium</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tgl_yudisium" class="form-label">Tanggal Yudisium</label>
                            <input type="date" class="form-control" id="tgl_yudisium" name="tgl_yudisium" value="{{ old('tgl_yudisium', $data->tgl_yudisium ?? '') }}" required>
                            <span class="badge badge-danger-light mt-2">* Gunakan tanggal kegiatan Yudisium</span>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="col-md-12">Cari atau Upload File SK Yudisium</label>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <select name="id" id="nama_file_edit" class="form-select">
                                        <option value="">-- Cari File SK Yudisium --</option>
                                    </select>
                                    <small class="text-muted mt-2">Cari file SK Yudisium yang sudah ada</small>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" value="1" id="editBaru" name="edit_baru">
                                        <label class="form-check-label" for="editBaru">
                                            Upload file SK Yudisium baru
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3" id="editFileSection" style="display:none;">
                            <label for="sk_yudisium_file" class="form-label">File SK Yudisium (.pdf)</label>
                            <input type="file" class="form-control" name="sk_yudisium_file" id="sk_yudisium_file"
                                aria-describedby="fileHelpId" accept=".pdf" />
                            <small class="text-muted">Upload file baru jika belum ada di daftar</small><br>
                            <small class="text-muted text-danger">Kosongkan jika tidak ingin merubah file</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
        $('#editBaru').on('change', function(){
            if($(this).is(':checked')){
            $('#editFileSection').show();
            $('#sk_yudisium_file').attr('required', true);
            $('#nama_file_edit').attr('disabled', true);
            } else {
            $('#editFileSection').hide();
            $('#sk_yudisium_file').attr('required', false);
            $('#nama_file_edit').attr('disabled', false);
            }
        });
    });

    $('#editModal').on('shown.bs.modal', function () {
        $("#nama_file_edit").select2({
            dropdownParent: $('#editModal'),
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

    $('#edit-class-').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Edit SK Yudisium',
            text: "Apakah anda yakin simpan perubahan?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#edit-class-').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });
</script>
@endpush
