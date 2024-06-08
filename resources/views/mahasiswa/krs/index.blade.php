@extends('layouts.mahasiswa')
@section('title')
Kartu Rencana Studi
@endsection
@section('content')
@include('swal')
<section class="content">
    <div class="row align-items-end">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3"><img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h2>Kartu Rencana Studi Mahasiswa</h2>
                            <p class="text-dark align-middle mb-0 fs-16">
                                Universitas Sriwijaya
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box no-shadow mb-0 bg-transparent">
                <div class="box-header no-border px-0">
                    <h4 class="box-title"><i class="fa fa-file-invoice"></i> KRS</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-12">
            <div class="box bs-5 border-primary rounded mb-10 pull-up"
                style="background-image: url({{asset('images/images/svg-icon/color-svg/st-1.svg')}}); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">IPS | IPK</p>
                        <h4 class="mt-5 mb-0" style="color:#0052cc">{{$akm->isEmpty() ? '0 | 0' : $akm[0]->ips .' | '. $akm[0]->ipk }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-12">
            <div class="box bs-5 border-warning rounded mb-10 pull-up"
                style="background-image: url({{asset('images/images/svg-icon/color-svg/st-3.svg')}}); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">SKS Maksimum</p>
                        <h4 class="mt-5 mb-0" style="color:#0052cc">{{$sks_max}}</h4>
                        {{-- <p class="text-fade mb-0 fs-12 text-white">Sisa SKS : ({{$sks_max}}-{{$sks_mk}})</p> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-12">
            <div class="box bs-5 border-success rounded mb-10 pull-up"
                style="background-image: url({{asset('images/images/svg-icon/color-svg/st-4.svg')}}); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">Dosen PA</p>
                        @if (!empty($riwayat_pendidikan->pembimbing_akademik->nama_dosen))
                            <h4 class="mt-5 mb-0" style="color:#0052cc">{{ $riwayat_pendidikan->pembimbing_akademik->nama_dosen }}</h4>
                        @else
                            <h4 class="mt-5 mb-0" style="color:#0052cc">Tidak Diisi</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-20">
        <div class="col-lg-12 col-xl-12 mt-5">
            <div class="box">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 d-flex justify-content-between mb-10">
                        <div class="d-flex justify-content-start px-3">
                            <!-- Nav tabs -->
                            <ul class="nav nav-pills justify-content-start" role="tablist">
                                <li class="nav-item rounded10 mb-0"> <a class="nav-link active" data-bs-toggle="tab" href="#krs" role="tab"><span><i class="fa-solid fa-file-invoice"></i></span> <span class="hidden-xs-down ms-15">KRS</span></a> </li>
                                <li class="nav-item rounded10 mb-0"> <a class="nav-link " data-bs-toggle="tab" href="#data-kelas-kuliah" role="tab"><span><i class="fa-solid fa-graduation-cap"></i></span> <span class="hidden-xs-down ms-15">Data Kelas Kuliah</span></a> </li>
                            </ul>
                        </div>
                        <div class="d-flex justify-content-end px-3">
                            <div class="row">
                                <div class="col-xl-7 mt-10">
                                    <select name="semester" id="semester_select" class="form-select form-select-lg mb-10">
                                        <option value="">-- Pilih Semester --</option>
                                        @foreach ($semester as $s)
                                            <option value="{{$s->id_semester}}" @if ($s->id_semester == $semester_select) selected @endif>{{$s->nama_semester}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-xl-5 mt-10">
                                    <td>
                                        <a href="{{route('mahasiswa.krs.print', ['id_semester' => $semester_select])}}"  target="_blank" class="waves-effect waves-light btn btn-sm btn-success mb-5 float-end"><i class="fa fa-print"></i> Cetak</a> 
                                    </td>
                                </div> --}}
                                <div class="col-xl-5 mt-10">
                                    <td>
                                        <a href="#" id="print-krs-btn" class="waves-effect waves-light btn btn-sm btn-success mb-5 float-end">
                                            <i class="fa fa-print"></i> Cetak
                                        </a> 
                                    </td>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab panes -->
                <div class="tab-content tabcontent">
                    @include('mahasiswa.krs.include.krs')
                    @include('mahasiswa.krs.include.data-kelas-kuliah')
                </div>
				<!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>

</section>
@endsection
@push('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>

    $(document).ready(function() {
        $('#semester_select').select2({
            placeholder: '-- Pilih Semester --',
            width: '100%',
        });

        $('#semester_select').on('change', function (e) {
            var id = $(this).val();
            window.location.href = "{{route('mahasiswa.krs')}}?semester=" + id;
        });

        $(document).ready(function() {
            $('#print-krs-btn').on('click', function(e) {
                e.preventDefault(); // Mencegah link untuk langsung mengarahkan

                $.ajax({
                    url: '{{ route("mahasiswa.krs.print.checkDosenPA", ["id_semester" => $semester_select]) }}', // Buat route khusus untuk pengecekan
                    type: 'GET',
                    success: function(response) {
                        if (response.error) {
                            swal("Perhatian", response.error, "warning").then(() => {
                                window.location.href = '{{ url()->previous() }}'; // Redirect ke halaman sebelumnya
                            });
                        } else {
                            window.open('{{ route("mahasiswa.krs.print", ["id_semester" => $semester_select]) }}', '_blank'); // Jika tidak ada error, buka halaman print di tab baru
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching data:', xhr);
                    }
                });
            });
        });

        // Select Fakultas Kampus Merdeka
        $('#select-fakultas').change(function() {
            // Ambil nilai fakultas_id yang dipilih
            var selectedFakultasId = $(this).val();

            // Kirim permintaan Ajax untuk mengambil program studi berdasarkan fakultas_id yang dipilih
            $.ajax({
                url: '{{ route("mahasiswa.krs.pilih_prodi") }}',
                method: 'GET',
                data: {
                    id: selectedFakultasId
                },
                success: function(response) {
                    // Update select prodi dengan opsi baru
                    $('#select-prodi').empty(); // Kosongkan opsi yang ada sebelumnya
                    $.each(response.prodi, function(index, prodi) {
                        $('#select-prodi').append('<option value="' + prodi.id_prodi + '">' + prodi.nama_jenjang_pendidikan +' - '+ prodi.nama_program_studi + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    // Tangani kesalahan jika ada
                    console.error(error);
                }
            });
        });


        // Event handler untuk saat pergantian prodi dipilih
        $('#select-prodi').change(function() {
            var selectedProdiId = $(this).val();

            $.ajax({
                url: '{{ route("mahasiswa.krs.pilih_mk_merdeka") }}',
                method: 'GET',
                data: {
                    id_prodi: selectedProdiId
                },
                success: function(response) {
                    console.log(response.mk_merdeka.id_semester)
                    var mkMerdeka = response.mk_merdeka;
                    var krsMerdeka = response.krs_merdeka.map(krs => krs.id_matkul); // Extract id_matkul from krs_merdeka
                    var tbody = $('#mk-merdeka-tbody');
                    tbody.empty(); // Kosongkan tabel sebelum menambahkan data baru

                    if (mkMerdeka.length > 0) {
                        $.each(mkMerdeka, function(index, data) {
                            var isDisabled = krsMerdeka.includes(data.id_matkul);
                            var isEmpty = data.jumlah_kelas_kuliah == 0
                            var row = '<tr class="' + (isDisabled ? 'bg-success-light disabled-row' : 'disabled-row') + '">' +
                                '<td class="text-center align-middle">' + (index + 1) + '</td>' +
                                '<td class="text-start align-middle">' + data.kode_mata_kuliah + '</td>' +
                                '<td class="text-start align-middle" style="white-space: nowrap;">' + data.nama_mata_kuliah + '</td>' +
                                '<td class="text-center align-middle" style="white-space: nowrap;">' +
                                '<button type="button" class="btn btn-warning-light lihat-rps" data-bs-toggle="modal" data-id-matkul="'+ data.id_matkul +'">' +
                                        '<i class="fa fa-newspaper-o"></i> Lihat RPS' +
                                    '</button>' +
                                '</td>' +
                                '<td class="text-center align-middle">' + data.matkul_kurikulum.semester + '</td>' +
                                '<td class="text-center align-middle">' + data.sks_mata_kuliah + '</td>' +
                                '<td class="text-center align-middle">' + data.jumlah_kelas + '</td>' +
                                '<td class="text-center align-middle">' +
                                    '<button class="btn btn-success-light lihat-kelas-kuliah" title="Lihat kelas kuliah" data-id-matkul="'+ data.id_matkul +'"' + (isEmpty || isDisabled ? ' disabled' : '') + '><i class="fa fa-eye"></i> </button>' +
                                    '<div class="result-container" id="result-container_'+ data.id_matkul +'" style="margin-top: 20px"></div>' +
                                '</td>' +
                                '</tr>';
                            tbody.append(row);
                        });
                    } else {
                        var row = '<tr><td colspan="8" class="text-center">Tidak ada data mata kuliah merdeka yang tersedia.</td></tr>';
                        tbody.append(row);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        // Event listener untuk tombol "Lihat Kelas Kuliah"
        $(document).on('click', '.lihat-kelas-kuliah', function() {
            var idMatkul = $(this).data('id-matkul');
            var resultContainerId = '#result-container_' + idMatkul;

            // Dapatkan CSRF token dari meta tag
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Lakukan AJAX request ke endpoint yang sesuai dengan menyertakan CSRF token
            $.ajax({
                url: '{{ route("mahasiswa.krs.get_kelas_kuliah") }}',
                type: 'GET',
                data: {
                    id_matkul: idMatkul,
                    _token: csrfToken  // Sertakan CSRF token di sini
                },
                success: function(data) {
                    // Cek apakah data kelas kuliah kosong
                    if (data.length === 0) {
                        // Jika kelas kuliah kosong, tampilkan pesan peringatan menggunakan SweetAlert
                        Swal.fire({
                            icon: 'warning',
                            title: 'Kelas Kuliah Kosong',
                            text: 'Tidak ada kelas kuliah yang tersedia untuk mata kuliah ini.'
                        });
                    } else {
                        // Jika ada data kelas kuliah, tampilkan data seperti biasa
                        displayData(data, resultContainerId);
                    }
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        });




        function displayData(data, resultContainerId) {
            $(resultContainerId).empty();

            var table = '<table class="table table-bordered table-striped text-center">';
            table += '<thead><tr><th>No</th><th>Kelas Kuliah</th><th>Dosen Pengajar</th><th style="width: 400px;">Jadwal Kuliah</th><th>Peserta</th><th>Action</th></tr></thead>';
            table += '<tbody>';

            $.each(data, function(index, kelas) {
                table += '<tr>';
                table += '<td>' + (index + 1) + '</td>';
                table += '<td>' + kelas.nama_kelas_kuliah + '</td>';
                if (kelas.dosen_pengajar.length > 0) {
                    // Jika dosen_pengajar tidak kosong, tampilkan nama dosen
                    table += '<td class="text-start align-middle">' + formatDosenPengajar(kelas.dosen_pengajar) + '</td>';
                } else {
                    // Jika dosen_pengajar kosong, tampilkan pesan "Nama Dosen Tidak Diisi"
                    table += '<td>Nama Dosen Tidak Diisi</td>';
                }
                table += '<td>' + formatJadwalKuliah(kelas.jadwal_hari, kelas.jadwal_jam_mulai, kelas.jadwal_jam_selesai) + '</td>';
                if (kelas.kapasitas == null) {
                    // Jika Kapasitas Kelas = 0, tampilkan "-"
                    table += '<td>' + kelas.peserta_kelas_count +'/'+ '-' +'</td>';;
                } else {
                    // Jika Kapasitas Kelas !=  0, tampilkan Kapasitas peserta
                    table += '<td>' + kelas.peserta_kelas_count +'/'+ kelas.kapasitas +'</td>';;
                }

                if (kelas.kelas_Enrolled) {
                    // Jika sudah terdaftar, tombol "Ambil" dinonaktifkan
                    table += '<td><button class="btn btn-primary btn-ambil-kelas" data-id-kelas="' + kelas.id_kelas_kuliah + '" disabled>Ambil</button></td>';
                } else {
                    // Jika belum terdaftar, tombol "Ambil" aktif
                    table += '<td><button class="btn btn-primary btn-ambil-kelas" data-id-kelas="' + kelas.id_kelas_kuliah + '">Ambil</button></td>';
                }
                table += '</tr>';
            });

            table += '</tbody></table>';
            $(resultContainerId).append(table).collapse('toggle');

            // Tambahkan event listener untuk tombol "Ambil"
            $('.btn-ambil-kelas').click(function() {
                var idKelas = $(this).data('id-kelas');

                // Dapatkan CSRF token dari meta tag
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                // Lakukan AJAX request untuk menyimpan kelas kuliah
                $.ajax({
                    url: '{{ route("mahasiswa.krs.store_kelas_kuliah") }}',
                    type: 'POST',
                    data: {
                        id_kelas_kuliah: idKelas,
                        _token: csrfToken  // Sertakan CSRF token di sini
                    },
                    success: function(response) {
                        swal({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        },function(result){
                            if(result){
                                console.log(response.message);
                                // Lakukan refresh halaman atau aksi lainnya jika diperlukan
                                location.reload();
                            }
                        });

                    },
                    error: function(response) {
                        console.log(response);
                        var errorMessage = response.responseJSON.message;
                        swal({
                            title: 'Gagal!',
                            text: errorMessage,
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        }

        function formatDosenPengajar(dosenPengajar) {
            // Format dosen pengajar sesuai kebutuhan
            var formattedString = '<ul>';
            $.each(dosenPengajar, function(index, dosen) {
                formattedString += '<li>' + dosen.dosen.nama_dosen + '</li>';
            });
            formattedString += '</ul>';

            return formattedString;
        }

        function formatJadwalKuliah(hari, jamMulai, jamSelesai) {
            if (hari && jamMulai && jamSelesai) {
                return hari + ', ' + jamMulai + ' - ' + jamSelesai;
            } else {
                return 'Jadwal Tidak Diisi';
            }
        }
    });

    // TOMBOL 
    $('.delete-form').submit(function(e){
        e.preventDefault();
        var formId = $(this).data('id');
        swal({
            title: 'Apakah Anda Yakin??',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $(`#deleteForm${formId}`).unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });

    // HAPUS MK KRS
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-button');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const form = document.getElementById(`deleteForm${id}`);

                swal({
                    title: "Apakah Anda yakin?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    } else {
                        swal("Data aman!");
                    }
                });
            });
        });
    });

    $(function() {
        "use strict";

        $('.hapus-aktivitas').click(function() {
            var idAktivitas = $(this).data('id');
            console.log(idAktivitas)
            
            swal({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            },function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        url: '/mahasiswa/krs/hapus-aktivitas/' + idAktivitas,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            swal("Aktivitas telah dihapus.", {
                                icon: "success",
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            swal({
                                title:"Gagal Menghapus Aktivitas",
                                text: xhr,
                                icon: "error",
                            });
                        }
                    });
                }
            });
        });

        $('#krs-regular').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
            "pageLength": 10,
            // "scrollCollapse": false,
            // "scrollX": true,
            "columnDefs": [
                { "width": "700px", "targets": 6 }, // Kolom lebar 700px
            ]
        });

        $('#krs-akt').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
            "pageLength": 10,
            // "scrollCollapse": false,
            // "scrollY": "450px",
            "columnDefs": [
                { "width": "700px", "targets": 6 }, // Kolom lebar 700px
            ]
        });

        $('#krs-merdeka').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
            "pageLength": 10,
            // "scrollCollapse": false,
            // "scrollY": "450px",
            "columnDefs": [
                { "width": "700px", "targets": 6 }, // Kolom lebar 700px
            ]
        });

        $('#data-matkul-regular').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
            "pageLength": 10,
            "autoWidth": false,
            // "scrollCollapse": false,
            // "scrollY": "450px",
            "columnDefs": [
                { "width": "700px", "targets": 6 }, // Kolom lebar 700px
            ]
        });

        $('#data-matkul-aktivitas').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
            "pageLength": 10,
            "autoWidth": false,
            // "scrollCollapse": false,
            // "scrollY": "450px",
            "columnDefs": [
                { "width": "700px", "targets": 6 }, // Kolom lebar 700px
            ]
        });

        $('#data-matkul-merdeka').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
            "pageLength": 10,
            "autoWidth": false,
            // "scrollCollapse": false,
            // "scrollY": "450px",
            "columnDefs": [
                { "width": "700px", "targets": 6 }, // Kolom lebar 700px
            ]
        });
    });
    
    //  PENGECEKAN PERIODE KRS
    $(document).ready(function() {
        // Pengecekan tanggal
        @php
            $today = \Carbon\Carbon::now();
            $deadline = \Carbon\Carbon::parse($semester_aktif->krs_selesai);
        @endphp

        // Jika periode pengisian KRS telah berakhir, tampilkan SweetAlert
        @if($today->greaterThan($deadline) || $semester_aktif->id_semester > $semester_select)
            swal(
                "Perhatian", 
                "Periode pengisian KRS pada Semester yang Anda pilih telah berakhir. Anda tidak Dapat Menghapus atau Menambahkan Mata Kuliah", 
                "warning"
            ); 
        @endif
    });

    // AMBIL AKTIVITAS
    $('.ambil-aktivitas').click(function() {
        var idMatkul = $(this).data('id-matkul');
        window.location.href = '/mahasiswa/krs/ambil-aktivitas/' + idMatkul;
    });


    // LIHAT RPS
    $(document).on('click', '.lihat-rps', function() {
        var idMatkul = $(this).data('id-matkul');
        var resultContainerId = '#data-rencana-pembelajaran tbody';

        // Dapatkan CSRF token dari meta tag
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Lakukan AJAX request ke endpoint yang sesuai dengan menyertakan CSRF token
        $.ajax({
            url: '{{ route("mahasiswa.lihat-rps", ["id_matkul" => ":id_matkul"]) }}'.replace(':id_matkul', idMatkul),
            type: 'GET',
            data: {
                _token: csrfToken  // Sertakan CSRF token di sini
            },
            success: function(data) {
                if (data.length === 0) {
                    // Tampilkan pesan error jika data RPS kosong
                    swal({
                        icon: 'warning',
                        title: 'Tidak ada data RPS',
                        text: 'Rencana Pembelajaran Semester tidak ditemukan untuk mata kuliah ini.',
                    });

                } else {
                    displayData(data, resultContainerId);
                    $('#rpsModal').modal('show'); // Tampilkan modal setelah data dimuat
                }
            },
            error: function(error) {
                console.error('Error fetching data:', error);
                swal({
                    icon: 'error',
                    title: 'Terjadi kesalahan',
                    text: 'Tidak dapat mengambil data RPS. Silakan coba lagi nanti.',
                });
            }
        });
    });

    // MENAMPILKAN DATA RPS
    function displayData(data, resultContainerIdModal) {
        $(resultContainerIdModal).empty();

        $.each(data, function(index, rps) {
            var row = '<tr>';
            row += '<td class="text-center align-middle">' + rps.pertemuan + '</td>';
            row += '<td class="text-start align-middle">' + rps.materi_indonesia + '</td>';
            row += '<td class="text-start align-middle">' + rps.materi_inggris + '</td>';
            row += '</tr>';
            $(resultContainerIdModal).append(row);
        });
    }
    
    
        
    

</script>
@endpush
