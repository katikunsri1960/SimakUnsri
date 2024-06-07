<div class="modal fade" id="tambahAsistensiModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="asistensiTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="asistensiTitle">
                    Asistensi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('dosen.pembimbing.bimbingan-tugas-akhir.asistensi.store', $aktivitas)}}" method="post" id="asistensiForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
						<label class="col-form-label col-md-2">Tanggal</label>
						<div class="col-md-10">
							<input type="text" class="form-control" name="tanggal" id="tanggal"
                                aria-describedby="helpId" placeholder="" />
						</div>
					</div>
                    <div class="form-group row">
						<label class="col-form-label col-md-2">Keterangan</label>
						<div class="col-md-10">
							<textarea class="form-control" name="uraian" id="uraian" rows="3"></textarea>
						</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batalkan
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('css')
<link rel="stylesheet" href="{{asset('assets/js/flatpickr/flatpickr.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/js/flatpickr/flatpickr.js')}}"></script>
<script>
   flatpickr("#tanggal", {
            dateFormat: "d-m-Y",
        });

    $('#asistensiForm').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Apakah anda yakin?',
            text: "Data tidak bisa diubah lagi setelah disimpan!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#spinner').show();
                $('#asistensiForm').unbind('submit').submit();
            }
        });
    });

</script>
@endpush
