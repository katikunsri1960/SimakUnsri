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
                <div class="box-header with-border d-flex justify-content-between">
                    <div class="d-flex justify-content-start">
                        <button type="button" class="btn btn-secondary waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#filter-button">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                        <span class="divider-line mx-1"></span>
                        <a href="{{route('univ.perkuliahan.aktivitas-kuliah')}}"
                            class="btn btn-warning waves-effect waves-light">
                            <i class="fa fa-refresh"></i> Reset Filter
                        </a>
                    </div>
                    <div class="d-flex justify-content-end">
                        <form action="{{route('univ.perkuliahan.aktivitas-kuliah.sync')}}" method="get" id="sync-form">
                            <button class="btn btn-primary waves-effect waves-light" type="submit"><i
                                    class="fa fa-refresh"></i> Sinkronisasi
                            </button>
                        </form>
                        <span class="divider-line mx-1"></span>
                        <button class="btn btn-success waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#createModal"><i class="fa fa-plus"></i> Tambah Data
                        </button>
                    </div>
                </div>
                @include('universitas.perkuliahan.aktivitas-kuliah.filter')
                @include('universitas.perkuliahan.aktivitas-kuliah.create')
                @include('universitas.perkuliahan.aktivitas-kuliah.edit')
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-hover margin-top-10 w-p100" style="font-size: 10pt">
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

        $("#id_registrasi_mahasiswa").select2({
            placeholder: '-- Masukan NIM / Nama Mahasiswa --',
            dropdownParent: $('#createModal'),
            width: '100%',
            minimumInputLength: 3,
            ajax: {
                url: "{{route('univ.pengaturan.akun.get-mahasiswa')}}",
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function (data) {
                    // console.log(data);
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: "("+item.nim+") "+item.nama_mahasiswa,
                                id: item.id_registrasi_mahasiswa
                            }
                        })
                    };
                },
            }
        });
        

        document.addEventListener('DOMContentLoaded', function () {
            const formatToTwoDecimal = (value) => {
                if (!value) return '';
                const number = parseFloat(value);
                return isNaN(number) ? '' : number.toFixed(2);
            };

            // Event Listener untuk IPS
            const ipsInput = document.getElementById('ips');
            ipsInput.addEventListener('blur', function () {
                this.value = formatToTwoDecimal(this.value);
            });

            // Event Listener untuk IPK
            const ipkInput = document.getElementById('ipk');
            ipkInput.addEventListener('blur', function () {
                this.value = formatToTwoDecimal(this.value);
            });
        });

        function editData(data) {
            console.log(data); // Pastikan data yang diterima valid
            console.log(JSON.stringify(data));
            // Isi data ke dalam form modal edit
            $('#edit_id').val(data.id);
            $('#edit_nama_mahasiswa').val(data.nama_mahasiswa);

            // Isi dropdown status mahasiswa
            let statusOptions = `
                <option value="" disabled>-- Pilih Status --</option>
                <option value="A" ${data.id_status_mahasiswa == 'A' ? 'selected' : ''}>Aktif</option>
                <option value="M" ${data.id_status_mahasiswa == 'M' ? 'selected' : ''}>Kampus Merdeka</option>
                <option value="C" ${data.id_status_mahasiswa == 'C' ? 'selected' : ''}>Cuti</option>
                <option value="N" ${data.id_status_mahasiswa == 'N' ? 'selected' : ''}>Non-Aktif</option>
            `;
            $('#edit_status_mahasiswa').html(statusOptions);

            $('#edit_ips').val(data.ips);
            $('#edit_sks_semester').val(data.sks_semester);
            $('#edit_ipk').val(data.ipk);
            $('#edit_sks_total').val(data.sks_total);

            // Isi dropdown jenis pembiayaan
            let pembiayaanOptions = `
                <option value="" disabled>-- Pilih Jenis Pembiayaan --</option>
                <option value="1" ${data.id_pembiayaan == 1 ? 'selected' : ''}>Mandiri</option>
                <option value="2" ${data.id_pembiayaan == 2 ? 'selected' : ''}>Beasiswa Tidak Penuh</option>
                <option value="3" ${data.id_pembiayaan == 3 ? 'selected' : ''}>Beasiswa Penuh</option>
            `;
            $('#edit_id_pembiayaan').html(pembiayaanOptions);

            // Isi dropdown semester
            let semesterOptions = '<option value="">-- Pilih Semester --</option>';
            data.semesters.forEach(function (semester) {
                semesterOptions += `<option value="${semester.id_semester}" ${
                    semester.id_semester == data.id_semester ? 'selected' : ''
                }>${semester.nama_semester}</option>`;
            });
            $('#edit_id_semester').html(semesterOptions);
        }

        $('#data').DataTable({
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
                {
                    data: null,
                    searchable: false,
                    class: "text-center align-middle mx-5",
                    sortable: false,
                    render: function(data, type, row) {
                        var jsonData = JSON.stringify(data).replace(/"/g, '&quot;');
                        var button = `
                            <button class="btn btn-secondary btn-sm hitung-ips my-5" data-id-reg="${data.id_registrasi_mahasiswa}" data-id-semester="${data.id_semester}">
                                Hitung IPS
                            </button>
                            <button class="btn btn-primary btn-sm btn-edit my-5" data-bs-toggle="modal" data-bs-target="#editModal" 
                                onclick="editData(${jsonData})">
                                Edit
                            </button>
                        `;
                        return button;
                    }
                },
                {data: null, searchable: false, class: "text-center align-middle text-nowrap", sortable: false, render: function(data, type, row) {
                    var button = '<button class="btn btn-secondary btn-sm hitung-ips" data-id-reg="' + data.id_registrasi_mahasiswa + '" data-id-semester="' + data.id_semester + '"><i class="fa fa-retweet"></i> Hitung IPS</button>';
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
                    $('#spinner').show();
                    console.log('masuk 1');
                    $.ajax({
                        url: '{{ route("univ.perkuliahan.aktivitas-kuliah.hitung-ips") }}',
                        type: 'POST',
                        data: {
                            id_reg: idReg,
                            id_semester: idSemester,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('masuk success');
                            console.log(response);
                            if (String(response.status) === "success") {
                                swal({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    type: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                }, function(isConfirm){
                                    if (isConfirm) {
                                        window.location.reload();
                                    }
                                });
                            } else {
                                swal("Gagal!", response.message, "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('masuk error');
                            swal("Error!", "Terjadi kesalahan saat menghitung IPS.", "error");
                        }
                    });
                }
            });
        });




        // $(document).on('click', '.btn-edit', function () {
        //     var id = $(this).data('id'); // Ambil ID dari tombol edit

        //     $.ajax({
        //         url: `/universitas/perkuliahan/aktivitas-kuliah/${id}/edit`,
        //         method: 'GET',
        //         success: function (response) {
        //             if (response.success) {
        //                 // Isi data ke dalam form modal edit
        //                 $('#edit_id').val(response.data.id);
        //                 $('#edit_nama_mahasiswa').val(response.data.nama_mahasiswa);

        //                 // Isi dropdown status mahasiswa
        //                 let statusOptions = `
        //                     <option value="" disabled>-- Pilih Status --</option>
        //                     <option value="A" ${response.data.id_status_mahasiswa == 'A' ? 'selected' : ''}>Aktif</option>
        //                     <option value="M" ${response.data.id_status_mahasiswa == 'M' ? 'selected' : ''}>Kampus Merdeka</option>
        //                     <option value="C" ${response.data.id_status_mahasiswa == 'C' ? 'selected' : ''}>Cuti</option>
        //                     <option value="N" ${response.data.id_status_mahasiswa == 'N' ? 'selected' : ''}>Non-Aktif</option>
        //                 `;
        //                 $('#edit_status_mahasiswa').html(statusOptions);

        //                 $('#edit_ips').val(response.data.ips);
        //                 $('#edit_sks_semester').val(response.data.sks_semester);
        //                 $('#edit_ipk').val(response.data.ipk);
        //                 $('#edit_sks_total').val(response.data.sks_total);
                        
        //                 // Isi dropdown jenis pembiayaan
        //                 let pembiayaanOptions = `
        //                     <option value="" disabled>-- Pilih Jenis Pembiayaan --</option>
        //                     <option value="1" ${response.data.id_pembiayaan == 1 ? 'selected' : ''}>Mandiri</option>
        //                     <option value="2" ${response.data.id_pembiayaan == 2 ? 'selected' : ''}>Beasiswa Tidak Penuh</option>
        //                     <option value="3" ${response.data.id_pembiayaan == 3 ? 'selected' : ''}>Beasiswa Penuh</option>
        //                 `;
        //                 $('#edit_id_pembiayaan').html(pembiayaanOptions);

        //                 // Isi dropdown semester
        //                 let semesterOptions = '<option value="">-- Pilih Semester --</option>';
        //                 response.semesters.forEach(function (semester) {
        //                     semesterOptions += `<option value="${semester.id_semester}" ${
        //                         semester.id_semester == response.data.id_semester ? 'selected' : ''
        //                     }>${semester.nama_semester}</option>`;
        //                 });
        //                 $('#edit_id_semester').html(semesterOptions);

        //                 // Tampilkan modal
        //                 $('#editModal').modal('show');
        //             } else {
        //                 alert(response.message);
        //             }
        //         },
        //         error: function (xhr) {
        //             alert('Terjadi kesalahan saat mengambil data!');
        //         }
        //     });
        // });

        $('#editForm').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: `/universitas/perkuliahan/aktivitas-kuliah/${$('#edit_id').val()}/update`,
                method: 'PATCH', // Sesuai dengan method route
                data: $(this).serialize(), // Mengirimkan seluruh data dari form
                success: function (response) {
                    if (response.success) {
                        $('#editModal').modal('hide');
                        alert('Data berhasil diperbarui!');
                        $('#data').DataTable().ajax.reload(); // Refresh DataTable
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr) {
                    alert('Terjadi kesalahan saat menyimpan data!');
                }
            });
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
