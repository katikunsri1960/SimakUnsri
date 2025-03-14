<div class="modal fade" id="pembatalanModal{{$d->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">
                    Pembatalan Pengajuan Cuti
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('fakultas.pengajuan-cuti.decline', $d->id_cuti)}}" method="post" id="decline-class-{{$d->id}}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="alasan_pembatalan" class="form-label">Alasan Pembatalan</label>
                            <textarea class="form-control form-group" name="alasan_pembatalan" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('js')
<script>
    $('#decline-class-{{$d->id}}').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Apakah anda yakin?',
            text: "Anda akan membatalkan pengajuan cuti ini.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#decline-class-{{$d->id}}').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });
</script>
@endpush
