@if ($aktivitas !== Null)
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
            <form action="{{route('mahasiswa.bimbingan.bimbingan-tugas-akhir.asistensi.store', $aktivitas)}}" method="post"
                id="asistensiForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
						<label class="col-form-label col-md-2">Tanggal</label>
						<div class="col-md-4">
							<input class="form-control" type="date" name="tanggal">
							{{-- <span class="form-text text-muted">Using <code>input type="date"</code></span> --}}
						</div>
					</div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Dosen Pembimbing</label>
                        <div class="col-md-10">
                            <select class="form-control select2" name="dosen_pembimbing" id="dosen_pembimbing" required>
                                <option value="">-- Pilih Dosen Pembimbing --</option>
                                @foreach($dosen_pembimbing->bimbing_mahasiswa as $dosen)
                                    <option value="{{ $dosen->id_dosen }}" {{$dosen->approved==0 ? 'disabled': ''}}><li>Pembimbing {{$dosen->pembimbing_ke}} - {{ $dosen->nama_dosen }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Uraian Asistensi</label>
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
@endif

@push('css')
<link rel="stylesheet" href="{{asset('assets/js/flatpickr/flatpickr.min.css')}}">
@endpush
@push('js')
{{-- <script src="{{asset('assets/vendor_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('assets/vendor_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script> --}}
<script src="{{asset('assets/js/flatpickr/flatpickr.js')}}"></script>
<script>
    // flatpickr("#tanggal", {
    //         dateFormat: "d-m-Y",
    //         maxDate: "today",
    //     });

    $(document).ready(function() {
        $('.select2').select2();

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

        $('#btnTambahAsistensi').click(function(e) {
            e.preventDefault();  // Prevent the default action

            // Check if there are any pending approvals
            let bimbinganApproved = true;
            

            @foreach ($aktivitas->bimbing_mahasiswa as $bimbingan)
                if ({{ $bimbingan->approved }} === 0) {
                    bimbinganApproved = false;
                    break;  // Exit the loop early if any approval is pending
                }
            @endforeach

            if (!bimbinganApproved) {
                swal({
                    title: 'Dosen Pembimbing Belum Disetujui!',
                    text: 'Dosen pembimbing Anda belum disetujui oleh Koordinator Program Studi.',
                    type: 'warning',
                    confirmButtonText: 'OK'
                });
            } 
            
            else {
                $('#tambahAsistensiModal').modal('show');
            }
        });
    });
</script>
@endpush
