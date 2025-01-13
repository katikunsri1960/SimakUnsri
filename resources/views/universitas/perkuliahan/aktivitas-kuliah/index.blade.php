@extends('layouts.universitas')
@section('title')
Aktivitas Kuliah Mahasiswa
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Aktivitas Kuliah Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Perkuliahan</li>
                        <li class="breadcrumb-item active" aria-current="page">Aktivitas Perkuliahan</li>
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
                        <div class="col-md-6 text-start">
                            <button type="button" class="btn btn-success waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#filter-button">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                        @include('universitas.perkuliahan.aktivitas-kuliah.filter')
                        <span class="divider-line mx-1"></span>
                        <a href="{{route('univ.perkuliahan.aktivitas-kuliah')}}"
                            class="btn btn-warning waves-effect waves-light">
                            <i class="fa fa-refresh"></i> Reset Filter
                        </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <form action="{{route('univ.perkuliahan.aktivitas-kuliah.sync')}}" method="get" id="sync-form">
                                <button class="btn btn-primary waves-effect waves-light" type="submit"><i
                                        class="fa fa-refresh"></i> Sinkronisasi</button>
                            </form>
                            <span class="divider-line mx-1"></span>
                        </div>
                    </div>

                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table  table-hover margin-top-10 w-p100" style="font-size: 10pt">
                            <thead>
                                <tr>
                                    <th class="text-center-align-middle">No</th>
                                    <th class="text-center-align-middle">NIM</th>
                                    <th class="text-center-align-middle">Nama</th>
                                    <th class="text-center-align-middle">Prodi</th>
                                    <th class="text-center-align-middle">Angkatan</th>
                                    <th class="text-center-align-middle">Semester</th>
                                    <th class="text-center-align-middle">Status</th>
                                    <th class="text-center-align-middle">IPS</th>
                                    <th class="text-center-align-middle">IPK</th>
                                    <th class="text-center-align-middle">SKS Semester</th>
                                    <th class="text-center-align-middle">SKS Total</th>
                                    <th class="text-center-align-middle">Jenis Pembiayaan</th>
                                    <th class="text-center-align-middle">ACT</th>
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
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>
    $(function () {
        // "use strict";

        $('#id_prodi').select2({
            placeholder: 'Pilih Program Studi',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#filter-button')
        });

        $('#angkatan').select2({
            placeholder: 'Pilih Angkatan',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#filter-button')
        });

        $('#semester').select2({
            placeholder: 'Pilih Semester',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#filter-button')
        });
        $('#status_mahasiswa').select2({
            placeholder: 'Pilih Status',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#filter-button')
        });

        $('#data').DataTable({
            // dom: 'Bfrtip',
            // buttons: [
            //     'copy', 'csv', 'excel', 'pdf', 'print'
            // ],
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('univ.perkuliahan.aktivitas-kuliah.data')}}',
                type: 'GET',
                data: function (d) {
                    d.id_prodi = $('#id_prodi').val();
                    d.semester = $('#semester').val();
                    d.angkatan = $('#angkatan').val();
                    d.status_mahasiswa = $('#status_mahasiswa').val();
                },
                error: function (xhr, error, thrown) {
                    alert('An error occurred. ' + thrown);
                }
            },
            columns: [
                {
                    data: null,
                    searchable: false,
                    class: "text-center align-middle",
                    sortable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                }},
                {data: 'nim', name: 'nim', class: 'text-center', searchable: true},
                {data: 'nama_mahasiswa', name: 'nama_mahasiswa', class: 'text-start', searchable: true},
                {data: 'nama_program_studi', name: 'nama_program_studi', searchable: true},
                {data: 'angkatan', name: 'angkatan', class: "text-center align-middle", searchable: true},
                {data: 'nama_semester', name: 'nama_semester', class: "text-center align-middle", searchable: true},
                {data: 'nama_status_mahasiswa', name: 'nama_status_mahasiswa', class: "text-center align-middle", searchable: true},
                {data: 'ips', name: 'ips', class: "text-center align-middle", searchable: true},
                {data: 'ipk', name: 'ipk', class: "text-center align-middle", searchable: true},
                {data: 'sks_semester', name: 'sks_semester', class: "text-center align-middle", searchable: true},
                {data: 'sks_total', name: 'sks_total', class: "text-center align-middle", searchable: true},
                {data: 'nama_pembiayaan', name: 'nama_pembiayaan', class: "text-center align-middle", searchable: false},
                {data: null, searchable: false, class: "text-center align-middle", sortable: false, render: function(data, type, row) {
                    var button = '<button class="btn btn-secondary btn-sm hitung-ips" data-id-reg="' + data.id_registrasi_mahasiswa + '" data-id-semester="' + data.id_semester + '">Hitung IPS</button>';
                    return button;
                }},
            ],
        });

         // Event listener untuk tombol hitung IPS
         $('#data').on('click', '.hitung-ips', function() {
            var idReg = $(this).data('id-reg');
            var idSemester = $(this).data('id-semester');

            swal({
                title: "Apakah Anda yakin?",
                text: "Anda akan menghitung IPS untuk mahasiswa ini.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hitung!',
                cancelButtonText: 'Batal'
            }, function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: '{{ route("univ.perkuliahan.aktivitas-kuliah.hitung-ips") }}',
                        type: 'POST',
                        data: {
                            id_reg: idReg,
                            id_semester: idSemester,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status === 'success') {
                                swal("Berhasil!", response.message, "success");
                            } else {
                                swal("Gagal!", response.message, "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            swal("Error!", "Terjadi kesalahan saat menghitung IPS.", "error");
                        }
                    });
                }
            });
            // }).then((willCalculate) => {
            //     if (willCalculate) {
            //         $.ajax({
            //             url: '{{ route("univ.perkuliahan.aktivitas-kuliah.hitung-ips") }}',
            //             type: 'POST',
            //             data: {
            //                 id_reg: idReg,
            //                 id_semester: idSemester,
            //                 _token: '{{ csrf_token() }}'
            //             },
            //             success: function(response) {
            //                 console.log(response);
            //                 if (response.status === 'success') {
            //                     swal("Berhasil!", response.message, "success");
            //                 } else {
            //                     swal("Gagal!", response.message, "error");
            //                 }
            //             },
            //             error: function(xhr, status, error) {
            //                 swal("Error!", "Terjadi kesalahan saat menghitung IPS.", "error");
            //             }
            //         });
            //     }
            // });
        });

        // sweet alert sync-form
        $('#sync-form').submit(function(e){
            e.preventDefault();
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
                    $('#spinner').show();
                    $('#sync-form').unbind('submit').submit();
                }
            });
        });

    });
</script>
@endpush
