<div class="modal fade" id="approveModal{{$d->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">
                    Persetujuan Pendaftaran Wisuda
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('fakultas.wisuda.approve', $d->id)}}" method="post" id="approve-class-{{$d->id}}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="no_sk_yudisium" class="form-label">SK Yudisium</label>
                            <input type="text" class="form-control" name="no_sk_yudisium" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tgl_sk_yudisium" class="form-label">Tanggal Yudisium</label>
                            <input type="date" class="form-control" name="tgl_sk_yudisium" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('js')
<script>
    $('#approve-class-{{$d->id}}').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Melakukan persetujuan pendaftaran wisuda',
            text: "Apakah anda yakin ingin?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#approve-class-{{$d->id}}').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });
</script>
@endpush
