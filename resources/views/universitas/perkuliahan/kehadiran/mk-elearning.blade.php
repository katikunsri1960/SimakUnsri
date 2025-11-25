@extends('layouts.universitas')
@section('title')
    Aktivitas Kuliah Mahasiswa
@endsection
@section('content')
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h3 class="page-title">Kode Mata kuliah dan Kelas di Elearning di Semester Aktif</h3>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('univ') }}"><i class="mdi mdi-home-outline"></i></a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Perkuliahan</li>
                            <li class="breadcrumb-item active" aria-current="page">kode mata kuliah & kelas</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box box-outline-success bs-3 border-success">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="d-flex justify-content-start align-items-center mb-3">
                                <button id="start-sync" class="btn btn-primary waves-effect waves-light"
                                    @if ($statusSync == 1) disabled @endif>
                                    <i class="fa fa-refresh"></i> Sinkronisasi Daftar Mata Kuliah
                                </button>
                            </div>

                            <div class="alert alert-warning mt-4" id="progress-container"
                                @if ($statusSync != 1) style="display:none" @endif>
                                <h3 class="alert-heading">Perhatian!</h3>
                                <hr>
                                <p class="mb-0"> Mata Kuliah sedang proses sinkronisasi. Harap menunggu terlebih
                                    dahulu!</p>
                                <div class="progress mt-3">
                                    <div id="sync-progress-bar"
                                        class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                        style="width:0%">0%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="data" class="table table-hover margin-top-10 w-p100" style="font-size: 10pt">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">No</th>
                                        <th class="text-center align-middle">KODE MATA KULIAH</th>
                                        <th class="text-center align-middle">KELAS KULIAH</th>
                                        <th class="text-center align-middle">ID KELAS KULIAH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data diisi oleh DataTables AJAX --}}
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
    <!-- Ganti ke SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('assets/vendor_components/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/select2/dist/js/select2.min.js') }}"></script>

    <script>
        $(function() {
            // DataTables AJAX
            $('#data').DataTable({
                processing: true,
                serverSide: false,
                ajax: "{{ route('universitas.perkuliahan.mk-elearning.ajax') }}",
                columns: [{
                        data: null,
                        render: (d, t, r, m) => m.row + 1,
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'kode_mata_kuliah',
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'kelas_kuliah',
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'id_kelas_kuliah',
                        className: 'text-center align-middle'
                    }
                ]
            });


            // Ambil status awal dari PHP
            var statusSync = @json($statusSync);
            var idBatch = @json($id_batch);

            if (statusSync == 1) {
                checkSync(idBatch);
            }

            // Klik tombol sinkronisasi
            $('#start-sync').click(function() {
                if ($(this).prop('disabled')) return;

                Swal.fire({
                    title: 'Sinkronisasi Data Mata Kuliah',
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
                            url: '{{ route('universitas.perkuliahan.mk-elearning.sync') }}',
                            type: 'GET',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                if (res.success) {
                                    checkSync(res.batch_id);
                                } else {
                                    Swal.fire('Error', res.message ||
                                        'Terjadi kesalahan', 'error');
                                    $('#start-sync').prop('disabled', false);
                                    $('#progress-container').hide();
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error', 'Gagal memulai sinkronisasi',
                                    'error');
                                $('#start-sync').prop('disabled', false);
                                $('#progress-container').hide();
                            }
                        });
                    }
                });
            });

            // Cek progress
            function checkSync(id_batch) {
                $.ajax({
                    url: '{{ route('universitas.perkuliahan.cek-progres-ambil-mk') }}',
                    type: 'GET',
                    data: {
                        id_batch: id_batch
                    },
                    success: function(response) {
                        var progressBar = document.getElementById('sync-progress-bar');
                        progressBar.style.width = response.progress + '%';
                        progressBar.innerHTML = response.progress + '%';

                        if (response.progress < 100) {
                            setTimeout(function() {
                                checkSync(id_batch);
                            }, 3000);
                        } else {
                            Swal.fire('Sukses!', 'Sinkronisasi berhasil', 'success').then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Error checking sync:', xhr.responseText);
                        Swal.fire('Error', 'Gagal memeriksa progres sinkronisasi', 'error');
                    }
                });
            }
        });
    </script>
@endpush
