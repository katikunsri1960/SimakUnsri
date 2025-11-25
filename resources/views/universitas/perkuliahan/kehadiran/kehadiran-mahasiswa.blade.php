@extends('layouts.universitas')

@section('title')
    Aktivitas Kuliah Dosen
@endsection

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h3 class="page-title">Data Kehadiran Mahasiswa Di Elearning</h3>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('univ') }}"><i class="mdi mdi-home-outline"></i></a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Perkuliahan</li>
                            <li class="breadcrumb-item active" aria-current="page">Kehadiran mahasiswa</li>
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
                        <div class="d-flex justify-content-start align-items-center mb-3">
                            <button id="start-sync" class="btn btn-primary waves-effect waves-light"
                                @if ($statusSync == 1) disabled @endif>
                                <i class="fa fa-refresh"></i> Sinkronisasi Kehadiran Mahasiswa
                            </button>
                        </div>

                        <div class="alert alert-warning mt-4" id="progress-container"
                            @if ($statusSync != 1) style="display:none" @endif>
                            <h3 class="alert-heading">Perhatian!</h3>
                            <hr>
                            <p class="mb-0">Kehadiran Mahasiswa sedang proses sinkronisasi. Harap menunggu terlebih
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
                            <table id="data" class="table table-hover margin-top-10 w-p100" style="font-size: 10pt">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">No</th>
                                        <th class="text-center align-middle">Kode Mata Kuliah</th>
                                        <th class="text-center align-middle">Nama Mata Kuliah</th>
                                        <th class="text-center align-middle">Nama kelas</th>
                                        <th class="text-center align-middle">NIM Mahasiswa</th>
                                        <th class="text-center align-middle">Nama Mahasiswa</th>
                                        <th class="text-center align-middle">Id Sesi</th>
                                        <th class="text-center align-middle">Tanggal Sesi</th>
                                        <th class="text-center align-middle">Status Mahasiswa</th>
                                        <th class="text-center align-middle">Nama Dosen Pengajar</th>
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
    <script src="{{ asset('assets/vendor_components/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/sweetalert/sweetalert.min.js') }}"></script>
    <script>
        $(function() {
            $('#data').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('universitas.perkuliahan.kehadiran.ajax') }}",
                pageLength: 50,
                lengthMenu: [50, 100],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'kode_mata_kuliah',
                        name: 'kode_mata_kuliah',
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'nama_mk',
                        name: 'nama_mk',
                        className: 'text-start align-middle'
                    },
                    {
                        data: 'nama_kelas',
                        name: 'nama_kelas',
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'username',
                        name: 'username',
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'nama_mahasiswa',
                        name: 'nama_mahasiswa',
                        className: 'text-start align-middle'
                    },
                    {
                        data: 'session_id',
                        name: 'session_id',
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'session_date',
                        name: 'session_date',
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'status_mahasiswa',
                        name: 'status_mahasiswa',
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'nama_dosen',
                        name: 'nama_dosen',
                        className: 'text-center align-middle'
                    }
                ]
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var statusSync = @json($statusSync);
            var idBatch = @json($id_batch);

            if (statusSync == 1) {
                checkSync(idBatch);
            }

            $('#start-sync').click(function() {
                if ($(this).prop('disabled')) return; // cegah klik saat disabled

                swal({
                    title: 'Sinkronisasi Kehadiran Mahasiswa',
                    text: "Apakah anda yakin ingin melakukan sinkronisasi?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Sinkronkan!',
                    cancelButtonText: 'Batal'
                }, function(isConfirm) {
                    if (isConfirm) {
                        $('#start-sync').prop('disabled', true);
                        $('#progress-container').show();

                        $.ajax({
                            url: '{{ route('universitas.perkuliahan.kehadiran.sync') }}',
                            type: 'GET',
                            success: function(res) {
                                if (res.success) {
                                    checkSync(res.data.batch_id);
                                } else {
                                    swal('Error', res.message, 'error');
                                    $('#start-sync').prop('disabled', false);
                                }
                            },
                            error: function() {
                                swal('Error', 'Gagal memulai sinkronisasi', 'error');
                                $('#start-sync').prop('disabled', false);
                            }
                        });
                    }
                });
            });
        });

        function checkSync(id_batch) {
            $.ajax({
                url: '{{ route('universitas.perkuliahan.progres-update-mahasiswa') }}',
                type: 'GET',
                data: {
                    id_batch: id_batch
                },
                success: function(response) {
                    var progressBar = document.getElementById('sync-progress-bar');
                    progressBar.style.width = response.progress + '%';
                    progressBar.setAttribute('aria-valuenow', response.progress);
                    progressBar.innerHTML = response.progress + '%';

                    if (response.progress < 100) {
                        setTimeout(function() {
                            checkSync(id_batch);
                        }, 3000);
                    } else {
                        swal({
                            title: 'Sukses!',
                            text: 'Sinkronisasi berhasil',
                            type: 'success'
                        }, function() {
                            location.reload();
                        });

                    }
                },
                error: function() {
                    console.error('Error checking sync');
                }
            });
        }
    </script>
@endpush
