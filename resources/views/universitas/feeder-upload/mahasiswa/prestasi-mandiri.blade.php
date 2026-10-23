@extends('layouts.universitas')

@section('title')
SINKRONISASI PRESTASI MANDIRI (SIMKATMAWA, PDUNSRI & FEEDER DIKTI)
@endsection

@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">SINKRONISASI PRESTASI MANDIRI</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('univ') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Feeder Upload</li>
                        <li class="breadcrumb-item active" aria-current="page">Prestasi Mandiri</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

@include('swal')

<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-12 mb-5">
                            <form id="uploadSimkatmawaForm">
                                @csrf
                                <input type="hidden" name="id_prodi" id="form_id_prodi" required>
                                <div class="col-md-8">
                                    <div class="row">
                                        <button type="submit" class="btn btn-primary btn-sm" disabled id="buttonSubmitForm">
                                            <i class="fa fa-upload me-3"></i> Upload Data (FEEDER DIKTI, SIMKATMAWA & PDUNSRI)
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="row mt-3">
                                <div id="progressContainer" style="display: none;">
                                    <!-- Header Status & Spinner -->
                                    <div class="d-flex align-items-center mb-3" id="syncStatusHeader">
                                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status" id="syncSpinner">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <strong class="text-primary" id="syncStatusText">Proses sinkronisasi sedang berjalan, mohon tunggu...</strong>
                                    </div>

                                    <div class="progress mb-2">
                                        <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                    </div>
                                    
                                    <div class="row text-center mt-3">

                                        <div class="col-md-4 border-end">
                                            <h6 class="fw-bold text-warning"><i class="fa fa-database"></i> FEEDER DIKTI</h6>
                                            <p class="mb-0">
                                                Berhasil: <span id="feederBerhasil" class="badge badge-success fs-14">0</span> | 
                                                Gagal: <span id="feederGagal" class="badge badge-danger fs-14">0</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4 border-end">
                                            <h6 class="fw-bold text-primary"><i class="fa fa-cloud-upload"></i> SIMKATMAWA</h6>
                                            <p class="mb-0">
                                                Berhasil: <span id="simkatmawaBerhasil" class="badge badge-success fs-14">0</span> | 
                                                Gagal: <span id="simkatmawaGagal" class="badge badge-danger fs-14">0</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="fw-bold text-info"><i class="fa fa-server"></i> PDUNSRI</h6>
                                            <p class="mb-0">
                                                Berhasil: <span id="pdunsriBerhasil" class="badge badge-success fs-14">0</span> | 
                                                Gagal: <span id="pdunsriGagal" class="badge badge-danger fs-14">0</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="form-group row">
                            <label for="id_prodi" class="col-sm-2 col-form-label">Program Studi</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="id_prodi" id="id_prodi">
                                    <option value="all">Semua Program Studi</option>
                                    @foreach ($prodi as $p)
                                        <option value="{{ $p->id_prodi }}">
                                            {{ $p->kode_program_studi }} - {{ $p->nama_program_studi }} ({{ $p->nama_jenjang_pendidikan }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">&nbsp;</label>
                            <div class="col-sm-4">
                                <div class="row mx-1">
                                    <button type="button" class="btn btn-secondary btn-sm form-control" onclick="getData()">
                                        <i class="fa fa-filter me-2"></i> Filter Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="loadingData" class="text-center py-5" style="display:none;">
                        <div class="spinner-border text-primary" role="status" style="width:3rem;height:3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-3">
                            <strong>Sedang mengambil data prestasi...</strong>
                        </div>
                    </div>

                    <div id="tableContainer">
                        <div class="table-responsive mt-5">
                            <table id="dataPrestasi" class="table table-bordered table-hover margin-top-10 w-p100" style="font-size: 10pt">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">No</th>
                                        <th class="text-center align-middle">Status Feeder</th>
                                        <th class="text-center align-middle">Status SIMKATMAWA</th>
                                        <th class="text-center align-middle">Status PDUNSRI</th>
                                        <th class="text-center align-middle">NIM</th>
                                        <th class="text-center align-middle">Nama Mahasiswa</th>
                                        <th class="text-center align-middle">Program Studi</th>
                                        <th class="text-center align-middle">Nama Prestasi</th>
                                        <th class="text-center align-middle">Penyelenggara</th>
                                        <th class="text-center align-middle">Tahun</th>
                                        <th class="text-center align-middle">ID SIMKATMAWA</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
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
<script src="{{ asset('assets/vendor_components/select2/dist/js/select2.min.js') }}"></script>

<script>
    function getData(){
        var id_prodi = $('#id_prodi').val();

        if ($.fn.DataTable.isDataTable('#dataPrestasi')) {
            $('#dataPrestasi').DataTable().clear().destroy();
        }

        $('#dataPrestasi tbody').empty();
        $('#loadingData').show();
        $('#tableContainer').hide();
        $('#buttonSubmitForm').prop('disabled', true);

        $.ajax({
            url: "{{ route('univ.feeder-upload.prestasi.mandiri.data') }}",
            type: 'GET',
            data: { id_prodi: id_prodi },
            success: function(response) {
                $('#loadingData').hide();
                $('#tableContainer').show();

                var html = '';
                var no = 1;
                var hasUnsynced = false;

                if(response.length > 0){
                    $('#form_id_prodi').val(id_prodi);
                } else {
                    $('#form_id_prodi').val('');
                }

                $.each(response, function(i, item) {
                    var statusSimkatmawa = item.simkatmawa == 1 
                        ? '<span class="badge badge-success">Sudah Sync</span>' 
                        : '<span class="badge badge-secondary">Belum Sync</span>';

                    var statusPdunsri = (item.pdunsri == 1) 
                        ? '<span class="badge badge-success">Sudah Sync</span>' 
                        : '<span class="badge badge-secondary">Belum Sync</span>';

                    var statusFeeder = (item.feeder == 1) 
                        ? '<span class="badge badge-success">Sudah Sync</span>' 
                        : '<span class="badge badge-secondary">Belum Sync</span>';

                    if (item.simkatmawa == 0 || (item.pdunsri ?? 0) == 0 || (item.feeder ?? 0) == 0) {
                        hasUnsynced = true;
                    }

                    html += '<tr>';
                    html += '<td class="text-center">' + no + '</td>';
                    html += '<td class="text-center">' + statusFeeder + '</td>';
                    html += '<td class="text-center">' + statusSimkatmawa + '</td>';
                    html += '<td class="text-center">' + statusPdunsri + '</td>';
                    html += '<td class="text-center">' + item.nim + '</td>';
                    html += '<td class="text-start">' + item.nama_mahasiswa + '</td>';
                    html += '<td class="text-start">' + item.prodi + '</td>';
                    html += '<td class="text-start">' + item.nama_prestasi + '</td>';
                    html += '<td class="text-center">' + item.penyelenggara + '</td>';
                    html += '<td class="text-center">' + item.tahun + '</td>';
                    html += '<td class="text-center">' + (item.id_simkatmawa ?? '-') + '</td>';
                    html += '</tr>';

                    no++;
                });

                $('#dataPrestasi tbody').html(html);

                if (hasUnsynced) {
                    $('#buttonSubmitForm').prop('disabled', false);
                } else {
                    $('#buttonSubmitForm').prop('disabled', true);
                }

                $('#dataPrestasi').DataTable();
            },
            error: function(xhr, status, error) {
                $('#loadingData').hide();
                $('#tableContainer').show();
                swal('Error', 'Terjadi kesalahan saat mengambil data.', 'error');
            }
        });
    }

    $(function () {
        $('#id_prodi').select2({
            placeholder: 'Pilih Prodi',
            allowClear: true,
            width: '100%',
        });

        $('#uploadSimkatmawaForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var submitButton = form.find('button[type="submit"]');

            swal({
                title: 'Sinkronisasi Prestasi',
                text: "Apakah Anda yakin ingin mengirim data Prestasi Mandiri ke SIMKATMAWA, PDUNSRI, dan FEEDER DIKTI?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sinkronkan All!',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    // Tampilkan spinner dan status loading
                    $('#progressContainer').show();
                    $('#syncSpinner').show();
                    $('#syncStatusText').text('Proses sinkronisasi sedang berjalan, mohon tunggu...').removeClass('text-success').addClass('text-primary');
                    submitButton.prop('disabled', true);

                    var id_prodi = $('#id_prodi').val();
                    var eventSourceUrl = "{{ route('univ.feeder-upload.prestasi.mandiri.upload') }}?id_prodi=" + id_prodi;
                    var source = new EventSource(eventSourceUrl);

                    source.onmessage = function(event) {
                        var data = JSON.parse(event.data);
                        var progress = data.progress.toFixed(2) + '%';

                        // Update Progress Bar
                        $('#progressBar').css('width', progress).attr('aria-valuenow', data.progress).text(progress);

                        // Update Statistik Terpisah REAL-TIME
                        $('#simkatmawaBerhasil').text(data.simkatmawaBerhasil);
                        $('#simkatmawaGagal').text(data.simkatmawaGagal);
                        $('#pdunsriBerhasil').text(data.pdunsriBerhasil);
                        $('#pdunsriGagal').text(data.pdunsriGagal);
                        $('#feederBerhasil').text(data.feederBerhasil);
                        $('#feederGagal').text(data.feederGagal);

                        if (data.progress >= 100) {
                            source.close();

                            // Sembunyikan spinner setelah selesai
                            $('#syncSpinner').hide();
                            $('#syncStatusText').text('Proses sinkronisasi selesai!').removeClass('text-primary').addClass('text-success');

                            swal({
                                title: 'Sinkronisasi Selesai',
                                text: "Proses sinkronisasi ke SIMKATMAWA, PDUNSRI, dan Feeder Dikti telah selesai!",
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            }, function() {
                                getData(); // Refresh datatable
                            });
                            submitButton.prop('disabled', true);
                        }
                    };

                    source.onerror = function(event) {
                        source.close();
                        $('#syncSpinner').hide();
                        $('#syncStatusText').text('Proses terhenti akibat kendala koneksi.').removeClass('text-primary').addClass('text-danger');
                        getData();
                        swal({
                            title: 'Sinkronisasi Terhenti',
                            text: 'Gagal menghubungkan atau koneksi terputus.',
                            type: 'error',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    };
                }
            });
        });
    });
</script>
@endpush