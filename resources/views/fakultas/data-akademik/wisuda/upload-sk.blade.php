{{-- ===================================================== --}}
{{--                MODAL UPLOAD SK YUDISIUM              --}}
{{-- ===================================================== --}}
<div class="modal fade" id="uploadModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="modalLabelUpload" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Upload SK Yudisium</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="" method="POST" id="uploadForm" enctype="multipart/form-data">
                @csrf

                {{-- HIDDEN INPUT UNTUK METADATA --}}
                <input type="hidden" name="no_sk_yudisium" id="no_sk_yudisium_hidden">
                <input type="hidden" name="tgl_sk_yudisium" id="tgl_sk_yudisium_hidden">
                <input type="hidden" name="tgl_yudisium" id="tgl_yudisium_hidden">

                <div class="modal-body">
                    <div class="row">
                        {{-- Toggle Upload Baru --}}
                        <div class="col-md-12 mb-10">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" value="1" id="uploadBaru" name="upload_baru">
                                <label class="form-check-label fw-bold" for="uploadBaru">
                                    Upload file SK Yudisium baru
                                </label>
                            </div>
                        </div>
                        <hr>

                        {{-- Upload File Baru --}}
                        <div class="col-md-12 mb-3" id="uploadFileSection" style="display:none;">
                            <label class="form-label">File SK Yudisium (PDF)</label>
                            <input type="file" class="form-control" name="sk_yudisium_file" id="sk_yudisium_file" accept=".pdf">
                        </div>
                        
                        {{-- Pilih File SK --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Cari File SK Yudisium</label>
                            <select name="id_file" id="nama_file_select" class="form-select">
                                <option value="">-- Cari File SK Yudisium --</option>
                            </select>
                            <small class="text-muted">Cari file SK Yudisium yang sudah ada</small>
                        </div>


                        {{-- DISPLAY No SK --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label">No SK Yudisium</label>
                            <input type="text" class="form-control" id="no_sk_yudisium_display" readonly>
                        </div>

                        {{-- DISPLAY Tanggal SK --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal SK Yudisium</label>
                            <input type="date" class="form-control" id="tgl_sk_yudisium_display" readonly>
                        </div>

                        {{-- DISPLAY Tanggal Yudisium --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Yudisium</label>
                            <input type="date" class="form-control" id="tgl_yudisium_display" readonly>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger">Setuju</button>
                </div>

            </form>

        </div>
    </div>
</div>

@push('js')
<script>
// =============================================================
// RESET MODAL SAAT DIBUKA
// =============================================================
function resetUploadModal() {

    $('#uploadForm')[0].reset();

    // Reset Select2
    $('#nama_file_select').val(null).trigger('change');
    $('#nama_file_select').prop('disabled', false);

    // Reset toggle upload baru
    $('#uploadBaru').prop('checked', false).prop('disabled', false);

    // Hide upload file
    $('#uploadFileSection').hide();
    $('#sk_yudisium_file').prop('required', false);

    // Reset display fields
    $('#no_sk_yudisium_display').val('').prop('readonly', true);
    $('#tgl_sk_yudisium_display').val('').prop('readonly', true);
    $('#tgl_yudisium_display').val('').prop('readonly', true);

    // Reset hidden fields
    $('#no_sk_yudisium_hidden').val('');
    $('#tgl_sk_yudisium_hidden').val('');
    $('#tgl_yudisium_hidden').val('');
}


// =============================================================
// BUKA MODAL + SET ACTION
// =============================================================
$(document).on('click', '.pilihSkBtn', function(e){
    e.preventDefault();

    resetUploadModal();

    let id = $(this).data('id');
    let action = `{{ url('fakultas/pendaftaran-wisuda/upload-sk-yudisium') }}/${id}`;
    $('#uploadForm').attr('action', action);

    $('#uploadModal').modal('show');
});


// =============================================================
// INISIALISASI SELECT2
// =============================================================
$('#uploadModal').on('shown.bs.modal', function () {

    if (!$('#nama_file_select').data('select2')) {

        $('#nama_file_select').select2({
            dropdownParent: $('#uploadModal'),
            placeholder : '-- Masukan No SK / Nama File --',
            width: '100%',
            minimumInputLength: 3,
            ajax: {
                url: "{{ route('fakultas.wisuda.search-sk-yudisium') }}",
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: params => ({ q: params.term }),
                processResults: data => ({
                    results: data.map(item => ({
                        id: item.id,
                        text: item.nama_file + " (" + item.fakultas.nama_fakultas + ")"
                    }))
                })
            }
        });

        $('#nama_file_select').data('select2', true);
    }
});


// =============================================================
// PILIH FILE SK — AUTO-FILL METADATA
// =============================================================
$(document).on('change', '#nama_file_select', function(){
    let fileId = $(this).val();

    if (!fileId) {

        $('#uploadBaru').prop('disabled', false);

        $('#no_sk_yudisium_display').val('');
        $('#tgl_sk_yudisium_display').val('');
        $('#tgl_yudisium_display').val('');

        $('#no_sk_yudisium_hidden').val('');
        $('#tgl_sk_yudisium_hidden').val('');
        $('#tgl_yudisium_hidden').val('');

        return;
    }

    // Disable upload baru
    $('#uploadBaru')
        .prop('checked', false)
        .prop('disabled', true);

    // Ambil metadata dari server
    $.ajax({
        url: "{{ route('fakultas.wisuda.search-sk-yudisium') }}",
        type: "GET",
        data: { id: fileId },
        success: function (res) {

            // Display field
            $('#no_sk_yudisium_display').val(res.no_sk ?? '').prop('readonly', true);
            $('#tgl_sk_yudisium_display').val(res.tgl_sk ?? '').prop('readonly', true);
            $('#tgl_yudisium_display').val(res.tgl_yudisium ?? '').prop('readonly', true);

            // Hidden input (yang dikirim ke backend)
            $('#no_sk_yudisium_hidden').val(res.no_sk ?? '');
            $('#tgl_sk_yudisium_hidden').val(res.tgl_sk ?? '');
            $('#tgl_yudisium_hidden').val(res.tgl_yudisium ?? '');
        }
    });
});


// =============================================================
// UPLOAD FILE BARU — ENABLE MANUAL INPUT
// =============================================================
$(document).on('change', '#uploadBaru', function(){

    if ($(this).is(':checked')) {

        $('#nama_file_select').val(null).trigger('change').prop('disabled', true);

        $('#uploadFileSection').show();
        $('#sk_yudisium_file').prop('required', true);

        // Aktifkan input manual
        $('#no_sk_yudisium_display').prop('readonly', false);
        $('#tgl_sk_yudisium_display').prop('readonly', false);
        $('#tgl_yudisium_display').prop('readonly', false);

    } else {

        $('#nama_file_select').prop('disabled', false);

        $('#uploadFileSection').hide();
        $('#sk_yudisium_file').prop('required', false);
    }
});


// =============================================================
// SYNC INPUT DISPLAY → HIDDEN (Untuk Upload Baru)
// =============================================================
$('#no_sk_yudisium_display').on('input', function(){
    $('#no_sk_yudisium_hidden').val($(this).val());
});

$('#tgl_sk_yudisium_display').on('change', function(){
    $('#tgl_sk_yudisium_hidden').val($(this).val());
});

$('#tgl_yudisium_display').on('change', function(){
    $('#tgl_yudisium_hidden').val($(this).val());
});

</script>
@endpush
