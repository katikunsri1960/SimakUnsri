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
</section>
<section class="content" style="text-align-last: center; padding-bottom:20px">
    <div class="row px-20">
      <div class="col-12 col-lg-6 px-50">
        <div class="box ribbon-box">
          <div class="ribbon-two ribbon-two-primary"><span>KSM</span></div>
          <div class="box-header no-border p-0">				
<<<<<<< HEAD
            <a href="{{route('mahasiswa.krs.index')}}">
=======
            <a href="#">
>>>>>>> f1985c162a610e19a27b82d0fe490682e5053c6d
              <img class="img-fluid" src="{{asset('images/images/avatar/ksm_regular.png')}}" alt="">
            </a>
          </div>
          <div class="box-body">
              <div class="text-center">
<<<<<<< HEAD
                <h3 class="my-10"><a href="{{route('mahasiswa.krs.index')}}">Kartu Studi Mahasiswa</a></h3>
=======
                <h3 class="my-10"><a href="#">Kartu Studi Mahasiswa</a></h3>
>>>>>>> f1985c162a610e19a27b82d0fe490682e5053c6d
                {{-- <h6 class="user-info mt-0 mb-10 text-fade">Designer</h6> --}}
                <p class="text-fade w-p85 mx-auto">KSM Regular, KSM Kampus Merdeka, Aktivitas Regular </p>
              </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6 px-50">
        <div class="box ribbon-box">
          <div class="ribbon-two ribbon-two-danger"><span>Magang</span></div>
          <div class="box-header no-border p-0">				
<<<<<<< HEAD
            <a href="{{route('mahasiswa.aktivitas.magang')}}">
=======
            <a href="#">
>>>>>>> f1985c162a610e19a27b82d0fe490682e5053c6d
              <img class="img-fluid" src="{{asset('images/images/avatar/magang.png')}}" alt="">
            </a>
          </div>
          <div class="box-body">
              <div class="text-center">
<<<<<<< HEAD
                <h3 class="my-10"><a href="{{route('mahasiswa.aktivitas.magang')}}">Aktivitas Magang</a></h3>
=======
                <h3 class="my-10"><a href="#">Aktivitas Magang</a></h3>
>>>>>>> f1985c162a610e19a27b82d0fe490682e5053c6d
                {{-- <h6 class="user-info mt-0 mb-10 text-fade">Full Stack Developer</h6> --}}
                <p class="text-fade w-p85 mx-auto">Aktivitas Magang Kampus Merdeka</p>
              </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection
<<<<<<< HEAD
=======
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
                    console.log(response)
                    var mkMerdeka = response.mk_merdeka;
                    var krsMerdeka = response.krs_merdeka.map(krs => krs.id_matkul); // Extract id_matkul from krs_merdeka
                    var tbody = $('#mk-merdeka-tbody');
                    tbody.empty(); // Kosongkan tabel sebelum menambahkan data baru

                    if (mkMerdeka.length > 0) {
                        $.each(mkMerdeka, function(index, data) {
                            var isDisabled = krsMerdeka.includes(data.id_matkul);
                            var isEmptyClass = data.jumlah_kelas == 0
                            var isEmptyRps = data.jumlah_rpss == 0
                            var row = '<tr class="' + (isDisabled ? 'bg-success-light disabled-row' : 'disabled-row') + '">' +
                                '<td class="text-center align-middle">' + (index + 1) +'. '+ '</td>' +
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
                                    '<button class="btn btn-success-light lihat-kelas-kuliah" title="Lihat kelas kuliah" data-id-matkul="'+ data.id_matkul +'"' + (isEmptyClass || isEmptyRps || isDisabled ? ' disabled' : '') + '><i class="fa fa-eye"></i> </button>' +
                                    '<div class="result-container" id="result-container_'+ data.id_matkul +'" style="margin-top: 5px"></div>' +
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
                    table += '<td>' + kelas.peserta_kelas_count + '/' + '-' + '</td>';
                } else {
                    // Jika Kapasitas Kelas !=  0, tampilkan Kapasitas peserta
                    table += '<td>' + kelas.peserta_kelas_count + '/' + kelas.kapasitas + '</td>';
                }

                if (kelas.kelas_Enrolled) {
                    // Jika sudah terdaftar, tombol "Ambil" dinonaktifkan
                    table += '<td><button class="btn btn-primary btn-ambil-kelas" data-id-kelas="' + kelas.id_kelas_kuliah + '" disabled>Ambil</button></td>';
                } else {
                    // Jika belum terdaftar, tombol "Ambil" aktif
                    table += '<td><button class="btn btn-primary btn-ambil-kelas" data-id-kelas="' + kelas.id_kelas_kuliah + '" data-id-matkul="' + kelas.id_matkul + '" data-nama-matkul="' + kelas.nama_mata_kuliah + '">Ambil</button></td>';
                }
                table += '</tr>';
            });

            table += '</tbody></table>';
            $(resultContainerId).append(table).collapse('toggle');

            // Tambahkan event listener untuk tombol "Ambil"
            $('.btn-ambil-kelas').click(function() {
                var idKelas = $(this).data('id-kelas');
                var idMatkul = $(this).data('id-matkul');
                var id_reg = '{{ auth()->user()->fk_id }}';  // Pastikan user ID sudah terisi

                // Dapatkan CSRF token dari meta tag
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                // Cek prasyarat sebelum mengirimkan request
                cekPrasyarat(idMatkul, id_reg, csrfToken, function(response) {
                    if (response.prasyarat_dipenuhi) {
                        // Jika prasyarat terpenuhi, lanjutkan dengan proses AJAX request untuk menyimpan kelas kuliah
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
                                }, function(result) {
                                    if (result) {
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
                    } else {
                        // Jika prasyarat tidak terpenuhi, tampilkan pesan peringatan
                        swal({
                            title: 'Prasyarat Tidak Terpenuhi',
                            text: 'Anda belum menyelesaikan mata kuliah prasyarat yang diperlukan ' +  ': ' + response.mata_kuliah_syarat,
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        }


        // Fungsi untuk mengecek prasyarat mata kuliah
        function cekPrasyarat(idMatkul, id_reg, csrfToken, callback) {
            $.ajax({
                url: '{{ route("mahasiswa.krs.cek_prasyarat") }}',  // Pastikan rute ini sesuai dengan rute yang Anda gunakan
                type: 'GET',
                data: {
                    id_matkul: idMatkul,
                    id_reg: id_reg,
                    _token: csrfToken
                },
                success: function(response) {
                    callback(response);  // Callback untuk melanjutkan proses setelah pengecekan
                },
                error: function(error) {
                    console.error('Error fetching prasyarat:', error);
                }
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
                    type: 'warning',
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
                type: 'warning',
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
                        
                    });
                    window.location.reload();
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
>>>>>>> f1985c162a610e19a27b82d0fe490682e5053c6d