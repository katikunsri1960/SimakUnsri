@extends('layouts.mahasiswa')
@section('title')
Kartu Rencana Studi
@endsection
@section('content')
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
                style="background-image: url(../images/svg-icon/color-svg/st-1.svg); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">IPS | IPK</p>
                        <h4 class="mt-5 mb-0" style="color:#0052cc">{{$akm->ips}} | {{$akm->ipk}}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-12">
            <div class="box bs-5 border-warning rounded mb-10 pull-up"
                style="background-image: url(../images/svg-icon/color-svg/st-3.svg); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">SKS Maksimum</p>
                        <h4 class="mt-5 mb-0" style="color:#0052cc">24</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-12">
            <div class="box bs-5 border-success rounded mb-10 pull-up"
                style="background-image: url(../images/svg-icon/color-svg/st-4.svg); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <p class="mt-5 mb-5 text-fade fs-12">Dosen PA</p>
                        @if (!empty($riwayat_pendidikan->nama_dosen))
                            <h4 class="mt-5 mb-0" style="color:#0052cc">{{ $riwayat_pendidikan->nama_dosen }}</h4>
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
				<!-- Nav tabs -->
                <ul class="nav nav-pills justify-content-left" role="tablist">
                    <li class="nav-item bg-secondary-light rounded10"> <a class="nav-link active" data-bs-toggle="tab" href="#krs" role="tab"><span><i class="fa-solid fa-file-invoice"></i></span> <span class="hidden-xs-down ms-15">KRS</span></a> </li>
                    <li class="nav-item bg-secondary-light rounded10"> <a class="nav-link " data-bs-toggle="tab" href="#data-kelas-kuliah" role="tab"><span><i class="fa-solid fa-graduation-cap"></i></span> <span class="hidden-xs-down ms-15">Data Kelas Kuliah</span></a> </li>
                </ul>
                <ul class="box-controls pull-right d-md-flex d-none">
                    <!-- <li> -->
                    <div class="clearfix">
                        <a class="waves-effect waves-light btn btn-app btn-success mb-20" href="#">
                            <i class="fa fa-print"></i> Print
                        </a>
                    </div>
                    <div class="form-group m-10">
                        <select class="form-select">
                            <option>2023/2024 Genap</option>
                            <option>2023/2024 Ganjil</option>
                            <option>2022/2023 Genap</option>
                            <option>2022/2023 Ganjil</option>
                            <option>2021/2022 Genap</option>
                            <option>2021/2022 Ganjil</option>
                        </select>
                    </div>
                </ul>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
    $(document).ready(function() {
        // Menggunakan class untuk mendapatkan semua tombol "Lihat Kelas Kuliah"
            $('.lihat-kelas-kuliah').click(function() {
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
                    displayData(data, resultContainerId);

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
                table += '<td>' + kelas.peserta_kelas_count + '</td>';
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
                        console.log(response.message);
                        // Tambahkan logika atau feedback sesuai kebutuhan

                        // Refresh halaman setelah berhasil mengambil atau mengubah kelas kuliah
                        location.reload();
                    },
                    error: function(error) {
                        console.error('Error storing data:', error);
                    }
                });
            });


            // Tambahkan event listener untuk tombol "Ubah Kelas Kuliah"
            $('.btn-ubah-kelas').click(function() {
                var idKelas = $(this).data('id-kelas');

                // Lakukan AJAX request untuk meng-update kelas kuliah
                $.ajax({
                    url: '{{ route("mahasiswa.krs.update_kelas_kuliah") }}',
                    type: 'POST',
                    data: {
                        id_matkul: idMatkul,
                        id_kelas_kuliah: idKelas,
                        _token: csrfToken  // Sertakan CSRF token di sini
                    },
                    success: function(response) {
                        console.log(response.message);
                        // Tambahkan logika atau feedback sesuai kebutuhan
                    },
                    error: function(error) {
                        console.error('Error updating data:', error);
                    }});
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

    $(function() {
        "use strict";
        $('#krs-regular').DataTable({
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

    $(document).ready(function() {
        // Pengecekan tanggal
        @php
            $today = \Carbon\Carbon::now();
            $deadline = \Carbon\Carbon::parse($semester_aktif->krs_selesai);
        @endphp

        // Jika periode pengisian KRS telah berakhir, tampilkan SweetAlert
        @if($today->greaterThan($deadline))
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Periode pengisian KRS telah berakhir. Anda tidak Dapat Menghapus atau Menambahkan Mata Kuliah',
                showCancelButton: false,
                showConfirmButton: true,
                timer: false // Set waktu tampilan SweetAlert
            });
        @endif
    });

</script>
@endpush
