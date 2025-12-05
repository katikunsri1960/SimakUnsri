{{-- ===================================================== --}}
{{--                MODAL EDIT SK YUDISIUM                --}}
{{-- ===================================================== --}}
<div class="modal fade" id="editSkModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editSkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSkModalLabel">Edit SK Yudisium</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" method="POST" id="editSkForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="row">

                        {{-- No SK --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label">No SK Yudisium</label>
                            <input type="text" class="form-control" id="edit_no_sk" name="no_sk_yudisium" required>
                        </div>

                        {{-- tanggal SK --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal SK Yudisium</label>
                            <input type="date" class="form-control" id="edit_tgl_sk" name="tgl_sk_yudisium" required>
                        </div>

                        {{-- tanggal Yudisium --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Yudisium</label>
                            <input type="date" class="form-control" id="edit_tgl_yudisium" name="tgl_yudisium" required>
                        </div>

                        {{-- File lama --}}
                        <div class="col-md-12 mb-2">
                            <label class="form-label">File SK Saat Ini</label>
                            <div id="currentSkFile"></div>
                        </div>

                        {{-- Cari file lama --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Ganti dengan File yang Sudah Ada</label>
                            <select name="id_file" id="edit_file_select" class="form-select">
                                <option value="">-- Pilih file SK Yudisium yang sudah ada --</option>
                            </select>
                        </div>

                        {{-- Toggle Upload baru --}}
                        <div class="col-md-12 mb-2">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="edit_uploadBaru" name="upload_baru" value="1">
                                <label class="form-check-label" for="edit_uploadBaru">
                                    Upload file SK Yudisium baru
                                </label>
                            </div>
                        </div>

                        {{-- Upload File --}}
                        <div class="col-md-12 mb-3" id="editUploadFileSection" style="display:none;">
                            <label class="form-label">Upload File Baru (PDF)</label>
                            <input type="file" class="form-control" id="edit_sk_file" name="sk_yudisium_file" accept=".pdf">
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
<script>

// =====================================================
//    READONLY INPUTS (AGAR TETAP TERKIRIM KE CONTROLLER)
// =====================================================
function toggleMetadataInputs(isUploadNew) {
    if (isUploadNew) {
        $("#edit_no_sk").prop("readonly", false);
        $("#edit_tgl_sk").prop("readonly", false);
        $("#edit_tgl_yudisium").prop("readonly", false);

        $("#editUploadFileSection").show();
        $("#edit_sk_file").attr("required", true);

        $("#edit_file_select").prop("disabled", true).val("").trigger("change");
    } else {
        $("#edit_no_sk").prop("readonly", true);
        $("#edit_tgl_sk").prop("readonly", true);
        $("#edit_tgl_yudisium").prop("readonly", true);

        $("#editUploadFileSection").hide();
        $("#edit_sk_file").attr("required", false);

        $("#edit_file_select").prop("disabled", false);
    }
}


// =====================================================
//  OPEN EDIT MODAL + FILL DATA
// =====================================================
$(document).on("click", ".btn-edit-sk", function () {
    let id = $(this).data("id");
    let noSk = $(this).data("nosk");
    let tglSk = $(this).data("tglsk");
    let tglYudisium = $(this).data("tglyudisium");
    let fileUrl = $(this).data("file");

    let action = `{{ url('fakultas/pendaftaran-wisuda/edit-sk-yudisium') }}/${id}`;
    $("#editSkForm").attr("action", action);

    $("#edit_no_sk").val(noSk);
    $("#edit_tgl_sk").val(tglSk);
    $("#edit_tgl_yudisium").val(tglYudisium);

    $("#currentSkFile").html(`
        <a href="${fileUrl}" target="_blank" class="btn btn-success btn-sm">
            <i class="fa fa-file"></i> Lihat File Lama
        </a>
    `);

    $("#edit_uploadBaru").prop("checked", false);
    toggleMetadataInputs(false);

    $("#editSkModal").modal("show");
});


// =====================================================
//  INIT SELECT2
// =====================================================
$("#editSkModal").on("shown.bs.modal", function () {
    if (!$("#edit_file_select").hasClass("select2-hidden-accessible")) {
        $("#edit_file_select").select2({
            dropdownParent: $("#editSkModal"),
            placeholder : "-- Cari file SK --",
            width: "100%",
            minimumInputLength: 3,
            ajax: {
                url: "{{ route('fakultas.wisuda.search-sk-yudisium') }}",
                type: "GET",
                dataType: "json",
                delay: 250,
                data: params => ({ q: params.term }),
                processResults: data => ({
                    results: data.map(item => ({
                        text: item.nama_file,
                        id: item.id
                    }))
                }),
            }
        });
    }
});


// =====================================================
//  PILIH FILE → AMBIL DETAIL & ISI OTOMATIS
// =====================================================
$(document).on("change", "#edit_file_select", function(){
    let fileId = $(this).val();

    if (!fileId) {
        $("#edit_uploadBaru").prop("disabled", false);
        return;
    }

    $("#edit_uploadBaru").prop("checked", false).prop("disabled", true);
    toggleMetadataInputs(false);

    $.ajax({
        url: "{{ route('fakultas.wisuda.search-sk-yudisium') }}",
        type: "GET",
        data: { id: fileId },
        success: function (res) {
            $("#edit_no_sk").val(res.no_sk);
            $("#edit_tgl_sk").val(res.tgl_sk);
            $("#edit_tgl_yudisium").val(res.tgl_yudisium);

            $("#currentSkFile").html(`
                <a href="${res.file_url}" target="_blank" class="btn btn-success btn-sm">
                    <i class="fa fa-file"></i> Lihat File
                </a>
            `);

        }
    });
});


// =====================================================
//  UPLOAD BARU CHECKBOX
// =====================================================
$(document).on("change", "#edit_uploadBaru", function(){
    toggleMetadataInputs($(this).is(":checked"));
});

</script>
@endpush
