@extends('layouts.universitas')
@section('title')
FEEDER UPLOAD - AKTIVITAS MAHASISWA
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">FEEDER UPLOAD - AKTIVITAS MAHASISWA</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Feeder Upload</li>
                        <li class="breadcrumb-item active" aria-current="page">Aktivitas Mahasiswa</li>
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
                            <form id="uploadAkmForm">
                                @csrf
                                <input type="hidden" name="id_prodi" id="form_id_prodi" required>
                                <input type="hidden" name="id_semester" id="form_id_semester" required>
                                <div class="col-md-6">
                                    <div class="row">
                                        <button type="submit" class="btn btn-primary btn-sm" disabled id="buttonSubmitForm"> <i
                                                class="fa fa-upload me-3"></i>Upload Aktivitas</button>
                                    </div>
                                </div>
                            </form>
                            <div class="row mt-3">
                                <div id="progressContainer" style="display: none;">
                                    <div class="progress">
                                        <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                    </div>
                                    <p>Data Berhasil: <span id="dataBerhasil">0</span></p>
                                    <p>Data Gagal: <span id="dataGagal">0</span></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">Program Studi</label>
                            <div class="col-sm-10">

                                <select class="form-select" name="id_prodi" id="id_prodi">
                                    <option value="" selected>Select one</option>
                                    @foreach ($prodi as $p)
                                    <option value="{{$p->id}}">
                                        {{$p->kode_program_studi}} - {{$p->nama_program_studi}}
                                        ({{$p->nama_jenjang_pendidikan}})
                                    </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-search-input" class="col-sm-2 col-form-label">Semester</label>
                            <div class="col-sm-4">
                                <select class="form-select" name="id_semester" id="id_semester">
                                    <option value="" selected>Select one</option>
                                    @foreach ($semester as $s)
                                    <option value="{{$s->id_semester}}" @if ($s->id_semester == $semesterAktif->id_semester) selected
                                        @endif>
                                        {{$s->nama_semester}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-search-input" class="col-sm-2 col-form-label">&nbsp;</label>
                            <div class="col-sm-4">
                                <div class="row mx-1">
                                    <button type="button" class="btn btn-secondary btn-sm form-control" onclick="getData()"><i class="fa fa-filter me-2"></i> Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-5">
                        <table id="dataAkm" class="table table-bordered table-hover margin-top-10 w-p100"
                            style="font-size: 10pt">
                            <thead>
                                <tr>
                                    <th class="text-center-align-middle">No</th>
                                    <th class="text-center-align-middle">Status Sync</th>
                                    <th class="text-center-align-middle">Semester</th>
                                    <th class="text-center-align-middle">Jenis</th>
                                    <th class="text-center-align-middle">Judul</th>
                                    <th class="text-center-align-middle">Lokasi</th>
                                    <th class="text-center-align-middle">Tanggal SK</th>
                                    <th class="text-center-align-middle">Tanggal Mulai</th>
                                    <th class="text-center-align-middle">Tanggal Selesai</th>
                                    <th class="text-center-align-middle">Prodi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('assets/js/flatpickr/flatpickr.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/js/flatpickr/flatpickr.js')}}"></script>
<script>
    function getData(){
        var id_prodi = $('#id_prodi').val();
        var id_semester = $('#id_semester').val();

        // remove existing rows
        $('#dataAkm tbody').html('');
        $('#dataAkm').DataTable().destroy();

        $.ajax({
            url: "{{ route('univ.feeder-upload.aktivitas.data') }}",
            type: 'GET',
            data: {
                id_prodi: id_prodi,
                id_semester: id_semester
            },
            success: function(response) {
                // console.log(response);
                var data = response;
                var html = '';
                var no = 1;
                // console.log(data);
                if (response.length > 0) {
                    $('#buttonSubmitForm').prop('disabled', false);
                    $('#form_id_prodi').val(id_prodi);
                    $('#form_id_semester').val(id_semester);
                } else {
                    $('#buttonSubmitForm').prop('disabled', true);
                    $('#form_id_prodi').val('');
                    $('#form_id_semester').val('');
                }

                $.each(data, function(i, item) {
                    html += '<tr>';
                    html += '<td class="text-center">' + no + '</td>';
                    html += '<td>' + item.status_sync + '</td>';
                    html += '<td class="text-center">' + item.nama_semester + '</td>';
                    html += '<td>' + item.nama_jenis_aktivitas + '</td>';
                    html += '<td class="text-start">' + item.judul + '</td>';
                    html += '<td class="text-start">' + item.lokasi + '</td>';
                    html += '<td class="text-center">' + item.tanggal_sk_tugas + '</td>';
                    html += '<td class="text-center">' + item.tanggal_mulai + '</td>';
                    html += '<td class="text-center">' + item.tanggal_selesai + '</td>';
                    html += '<td class="text-center">' + item.nama_prodi + '</td>';
                    html += '</tr>';
                    no++;
                });

                $('#dataAkm tbody').html(html);

                $('#dataAkm').DataTable();


            },
            error: function(xhr, status, error) {
                alert('An error occurred: ' + error);
            }
        });
    }

    $(function () {
        // "use strict";
        $('#data').DataTable();


        $('#id_prodi').select2({
            placeholder: 'Pilih Program Studi',
            allowClear: true,
            width: '100%',
        });


        $('#id_semester').select2({
            placeholder: 'Pilih Semester',
            allowClear: true,
            width: '100%',
        });

        $('#uploadAkmForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var submitButton = form.find('button[type="submit"]');

            swal({
                title: 'Sinkronisasi Data',
                text: "Apakah anda yakin ingin melakukan sinkronisasi?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sinkronkan!',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $('#progressContainer').show();
                    submitButton.prop('disabled', true);

                     // Serialize form data and log it to the console
                     var formData = form.serialize();
                    console.log('Serialized form data:', formData);

                    $.ajax({
                        url: "{{ route('univ.feeder-upload.ajax') }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            var id_prodi = $('#id_prodi').val();
                            var id_semester = $('#id_semester').val();

                            var eventSourceUrl = "{{ route('univ.feeder-upload.aktivitas.upload') }}" + "?prodi=" + id_prodi + "&semester=" + id_semester;
                            // console.log('EventSource URL:', eventSourceUrl);

                            // Initialize the EventSource with the constructed URL
                            var source = new EventSource(eventSourceUrl);

                            source.onmessage = function(event) {
                                var data = JSON.parse(event.data);
                                var progress = data.progress.toFixed(2) + '%';
                                $('#progressBar').css('width', progress).attr('aria-valuenow', data.progress).text(progress);
                                $('#dataBerhasil').text(data.dataBerhasil);
                                $('#dataGagal').text(data.dataGagal);

                                if (data.progress >= 100) {
                                    source.close();
                                    swal({
                                        title: 'Sinkronisasi Data',
                                        text: "Sinkronisasi data berhasil!",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'OK'
                                    });
                                    submitButton.prop('disabled', true);
                                }
                            };

                            source.onerror = function(event) {
                                source.close();
                                // trigger getData() to refresh the data
                                getData();

                                swal({
                                    title: 'Sinkronisasi Data',
                                    text: "Sinkronisasi data gagal!",
                                    type: 'error',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK'
                                });
                            };
                        },
                        error: function(xhr, status, error) {
                            alert('An error occurred: ' + error);
                        }
                    });
                }
            });
        });


    });
</script>
@endpush