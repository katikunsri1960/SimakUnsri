@foreach($data as $row)
<div class="modal fade" id="modalSkpiDecline{{$bidang->id}}-{{$row->id}}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Decline SKPI
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{route('prodi.data-skpi.decline',$row->id)}}"
                    method="POST"
                    enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Alasan Pembatalan</label>

                        <input type="text"
                            name="alasan_pembatalan"
                            class="form-control"
                            value="{{$row->alasan_pemmbatalan}}"
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                        class="btn btn-primary">
                        Decline
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach