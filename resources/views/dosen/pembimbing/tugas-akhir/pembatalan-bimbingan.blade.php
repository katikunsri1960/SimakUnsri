<div class="modal fade" id="pembatalanModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">
                    Pembatalan Bimbingan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('dosen.pembimbing.bimbingan-tugas-akhir.decline-pembimbing', $d->id_aktivitas)}}" method="post" id="decline-class">
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
    $('#decline-class').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Melakukan pembatalan bimbingan mahasiswa',
            text: "Apakah anda yakin ingin?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#decline-class').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });
</script>
@endpush
