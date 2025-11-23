@extends('layouts.universitas')

@section('title', 'Realisasi Pertemuan Dosen')

@section('content')
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h3 class="page-title">Realisasi Pertemuan Dosen</h3>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('univ') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item">Perkuliahan</li>
                        <li class="breadcrumb-item active">Realisasi Pertemuan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box box-outline-success bs-3 border-success">
                    <div class="box-header with-border">
                        <div class="d-flex justify-content-start align-items-center mb-3">
                            <button id="start-sync" class="btn btn-primary waves-effect waves-light"
                                @if ($statusSync == 1) disabled @endif>
                                <i class="fa fa-refresh"></i> Sinkronisasi Realisasi Pertemuan
                            </button>
                        </div>

                        <div class="alert alert-warning mt-4" id="progress-container"
                            @if ($statusSync != 1) style="display:none" @endif>
                            <h3 class="alert-heading">Perhatian!</h3>
                            <hr>
                            <p class="mb-0">Realisasi pertemuan sedang proses sinkronisasi. Harap menunggu terlebih
                                dahulu!</p>
                            <div class="progress mt-3">
                                <div id="sync-progress-bar"
                                    class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                    style="width:0%">0%</div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="data" class="table table-hover w-p100" style="font-size: 10pt">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Dosen</th>
                                        <th>ID Kelas Kuliah</th>
                                        <th>Nama Mata Kuliah</th>
                                        <th>Nama Kelas</th>
                                        <th>Rencana Pertemuan</th>
                                        <th>Realisasi Pertemuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- DataTables otomatis isi --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="{{ asset('assets/vendor_components/datatable/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // === DataTables ===
            var table = $('#data').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('universitas.perkuliahan.realisasi-pertemuan.ajax') }}',
                pageLength: 50,
                lengthMenu: [50, 100],
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'nama_dosen', name: 'nama_dosen', className: 'text-left' },
                    { data: 'id_kelas_kuliah', name: 'id_kelas_kuliah', className: 'text-center' },
                    { data: 'nama_mata_kuliah', name: 'kk.nama_mata_kuliah', className: 'text-left' },
                    { data: 'nama_kelas_kuliah', name: 'nama_kelas_kuliah', className: 'text-center' },
                    { data: 'rencana_minggu_pertemuan', name: 'rencana_minggu_pertemuan', className: 'text-center' },
                    { data: 'realisasi_minggu_pertemuan', name: 'realisasi_minggu_pertemuan', className: 'text-center' }
                ]
            });

            var statusSync = @json($statusSync);
            var idBatch = @json($id_batch);
            var syncCompleted = false;

            // Jika sudah ada batch yang jalan → langsung monitor progress
            if (statusSync == 1 && idBatch) {
                checkSync(idBatch);
            }

            // === Tombol Mulai Sinkronisasi ===
            $('#start-sync').click(function() {
                if ($(this).prop('disabled')) return;

                Swal.fire({
                    title: 'Sinkronisasi Realisasi Pertemuan',
                    text: "Apakah anda yakin ingin melakukan sinkronisasi?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Sinkronkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#start-sync').prop('disabled', true);
                        $('#progress-container').show();

                        $.ajax({
                            url: '{{ route('universitas.perkuliahan.update-realisasi') }}',
                            type: 'POST',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(res) {
                                if (res.success) {
                                    checkSync(res.batch_id);
                                } else {
                                    Swal.fire('Error', res.message || 'Terjadi kesalahan', 'error');
                                    $('#start-sync').prop('disabled', false);
                                    $('#progress-container').hide();
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error', xhr.responseJSON?.message || 'Gagal memulai sinkronisasi', 'error');
                                $('#start-sync').prop('disabled', false);
                                $('#progress-container').hide();
                            }
                        });
                    }
                });
            });

            // === Cek Progress ===
            function checkSync(id_batch) {
                if (syncCompleted) return;

                $.ajax({
                    url: '{{ route('universitas.perkuliahan.update-realisasi.check-sync') }}',
                    type: 'GET',
                    data: { id_batch: id_batch },
                    success: function(response) {
                        var progressBar = document.getElementById('sync-progress-bar');
                        var percent = response.progress ?? 0;
                        progressBar.style.width = percent + '%';
                        progressBar.innerHTML = percent + '%';

                        if (percent < 100) {
                            setTimeout(function() { checkSync(id_batch); }, 3000);
                        } else if (!syncCompleted) {
                            syncCompleted = true;
                            Swal.fire({
                                title: 'Sukses!',
                                text: 'Sinkronisasi berhasil diselesaikan.',
                                icon: 'success'
                            }).then(() => location.reload());
                        }
                    },
                    error: function(xhr) {
                        $('#progress-container').hide();
                        $('#start-sync').prop('disabled', false);
                        Swal.fire('Error', xhr.responseJSON?.message || 'Gagal memeriksa progres sinkronisasi', 'error');
                    }
                });
            }
        });
    </script>
@endpush
