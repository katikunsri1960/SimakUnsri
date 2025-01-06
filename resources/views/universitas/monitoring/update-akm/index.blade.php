@extends('layouts.universitas')
@section('title')
Update IPS
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Update AKM</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Akademik</li>
                        <li class="breadcrumb-item active" aria-current="page">Kartu Hasil Studi</li>
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
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="semester" class="form-label">Tahun Akademik</label>
                                <select class="form-select" name="semester" id="semester">
                                    <option value="" disabled selected>-- Pilih Tahun Akademik --</option>
                                    @foreach ($semesters as $semester)
                                    <option value="{{$semester->id_semester}}" {{request()->semester == '' && $semester->id_semester == $semesterAktif->id_semester ? 'selected' : ''}}>{{$semester->nama_semester}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <button class="btn btn-primary btn-md mt-30" id="basic-addon1" onclick="getKrs()"><i class="fa fa-search"></i> Tampilkan Data</button>
                            <button id="btnHitungIPS" class="btn btn-success btn-md mt-30 mx-10">Hitung & Update IPS</button>
                        </div>
                    </div>
                </div>
                <div id="loading" style="display: none; text-align: center; padding-inline:10px">
                    <div id="progress-bar-container" style="width: 100%; background-color: #f3f3f3; margin-bottom: 10px;">
                        <div id="progress-bar" style="height: 10px; width: 0%; background-color: #4caf50;"></div>
                    </div>
                    <p id="loading-percentage" style="color: #4caf50;">0%</p>
                    <p style="color: #4caf50;">Sedang menghitung IPS...</p>
                </div>
                <div class="box-body text-center">
                    <div class="table-responsive">
                        <div id="khsDiv" hidden>
                            <h3 class="text-center">Data AKM </h3>
                            <div class="d-flex justify-content-end align-middle"></div>
                            <div class="row mt-5" id="transferDiv" hidden>
                                <h3>Nilai Transfer</h3>
                                <table class="table table-bordered table-hover mt-2" id="transferTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle">No</th>
                                            <th class="text-center align-middle">Kode Mata Kuliah</th>
                                            <th class="text-center align-middle">Nama Mata Kuliah</th>
                                            <th class="text-center align-middle">Semester</th>
                                            <th class="text-center align-middle">SKS Diakui</th>
                                            <th class="text-center align-middle">Nilai Index Diakui</th>
                                            <th class="text-center align-middle">Nilai Huruf Diakui</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-5" id="akmDiv"></div>
                        {{-- <div id="paginationDiv" class="mt-3"></div> --}}
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
<script>
function getKrs(page = 1) {
    var semester = $('#semester').val();
    console.log(semester);
    // Validasi input semester
    if (semester == '') {
        swal({
            title: "Peringatan!",
            text: "Tahun Akademik harus diisi!",
            type: "warning",
            buttons: {
                confirm: {
                    className: 'btn btn-warning'
                }
            }
        });
        return;
    }

    // AJAX request untuk mendapatkan data
    $.ajax({
        url: '{{route('univ.monitoring.update-akm.data')}}',
        type: 'GET',
        data: {
            semester: semester,
            page: page,
            per_page: 10 // Number of items per page
        },
        success: function(response) {
            // Jika ada error di response
            if (response.status == 'error') {
                swal({
                    title: "Peringatan!",
                    text: response.message,
                    type: "warning",
                    buttons: {
                        confirm: {
                            className: 'btn btn-warning'
                        }
                    }
                });
                return;
            }

            // Reset div
            $('#akmDiv').empty();

            // Cek jika ada data dalam response
            if (response.data && response.data.length > 0) {
                // Tambahkan tabel
                let tableContent = `
                <table id="akmTable" class="table p-20">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">No</th>
                            <th class="text-center align-middle">Nama Program Studi</th>
                            <th class="text-center align-middle">Nama Mahasiswa</th>
                            <th class="text-center align-middle">NIM</th>
                            <th class="text-center align-middle">SKS Total</th>
                            <th class="text-center align-middle">IPK</th>
                            <th class="text-center align-middle">SKS Semester</th>
                            <th class="text-center align-middle">IPS</th>
                            <th class="text-center align-middle">Biaya UKT</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                // Loop melalui data response
                response.data.forEach(function(akm, index) {
                    tableContent += `
                        <tr>
                            <td class="text-center align-middle">${akm.DT_RowIndex}</td>
                            <td class="text-center align-middle">${akm.nama_program_studi}</td>
                            <td class="text-start align-middle">${akm.nama_mahasiswa}</td>
                            <td class="text-center align-middle">${akm.nim}</td>
                            <td class="text-center align-middle">${akm.sks_total}</td>
                            <td class="text-center align-middle">${akm.ipk}</td>
                            <td class="text-center align-middle">${akm.sks_semester}</td>
                            <td class="text-center align-middle">${akm.ips}</td>
                            <td class="text-center align-middle">${akm.biaya_kuliah_smt}</td>
                        </tr>
                    `;
                });

                // Tutup tabel
                tableContent += `
                    </tbody>
                </table>`;

                // Tambahkan tabel ke div
                $('#akmDiv').append(tableContent);

                // Inisialisasi DataTables
                $('#akmTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    serverSide: true,
                    processing: true,
                    ajax: {
                        url: '{{route('univ.monitoring.update-akm.data')}}',
                        data: function(d) {
                            d.semester = $('#semester').val();
                            d.page = d.start / d.length + 1;
                            d.per_page = d.length;
                        },
                        dataSrc: function(json) {
                            json.recordsTotal = json.recordsTotal;
                            json.recordsFiltered = json.recordsFiltered;
                            return json.data;
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'nama_program_studi', name: 'nama_program_studi' },
                        { data: 'nama_mahasiswa', name: 'nama_mahasiswa' },
                        { data: 'nim', name: 'nim' },
                        { data: 'sks_total', name: 'sks_total' },
                        { data: 'ipk', name: 'ipk' },
                        { data: 'sks_semester', name: 'sks_semester' },
                        { data: 'ips', name: 'ips' },
                        { data: 'biaya_kuliah_smt', name: 'biaya_kuliah_smt' }
                    ],
                    drawCallback: function(settings) {
                        var api = this.api();
                        var info = api.page.info();
                        var page = info.page + 1;
                        var perPage = info.length;
                        var total = info.recordsTotal;
                        var currentPage = info.page + 1;
                        var totalPages = info.pages;

                        // Render pagination
                        renderPagination(total, perPage, currentPage, totalPages);
                    }
                });
            } else {
                // Jika tidak ada data
                $('#akmDiv').html('<p class="text-center">Tidak ada data yang ditemukan.</p>');
            }
        },
        error: function(xhr, status, error) {
            // Handling error dari AJAX
            console.log(xhr);
            console.log(status);
            console.log(error);
            swal({
                title: "Error!",
                text: "Terjadi kesalahan saat mengambil data. Silakan coba lagi.",
                type: "error",
                buttons: {
                    confirm: {
                        className: 'btn btn-danger'
                    }
                }
            });
        }
    });
}

// Fungsi untuk merender pagination
function renderPagination(total, perPage, currentPage, totalPages) {
    let paginationContent = '<ul class="pagination justify-content-center">';
    for (let i = 1; i <= totalPages; i++) {
        paginationContent += `<li class="page-item ${i === currentPage ? 'active' : ''}">
            <a class="page-link" href="#" onclick="getKrs(${i})">${i}</a>
        </li>`;
    }
    paginationContent += '</ul>';
    $('#paginationDiv').html(paginationContent);
}

$(document).ready(function() {
    $('#btnHitungIPS').click(function() {
        // Tampilkan indikator loading
        $('#loading').show();
        let progressBar = $('#progress-bar');
        let percentageText = $('#loading-percentage');

        // Mulai progress bar
        let width = 0;
        let progressInterval = setInterval(function() {
            if (width < 100) {
                width++;
                progressBar.css('width', width + '%');
                percentageText.text(width + '%');
            }
        }, 50); // Update setiap 50ms untuk animasi progress

        // Ambil data semester (atau data lain jika perlu)
        var semester = $('#semester').val();

        if (semester === '') {
            swal({
                title: "Peringatan!",
                text: "Tahun Akademik harus diisi!",
                type: "warning",
                buttons: {
                    confirm: {
                        className: 'btn btn-warning'
                    }
                }
            });
            $('#loading').hide(); // Sembunyikan loading jika ada error
            clearInterval(progressInterval); // Hentikan progress bar
            return;
        }

        $.ajax({
            url: '{{route('univ.monitoring.update-akm.hitung-ips')}}', // Ganti dengan route Anda
            type: 'POST',
            data: {
                semester: semester,
                _token: '{{ csrf_token() }}' // Tambahkan CSRF token jika diperlukan
            },
            success: function(response) {
                $('#loading').hide(); // Sembunyikan loading setelah selesai
                clearInterval(progressInterval); // Hentikan progress bar
                progressBar.css('width', '100%'); // Pastikan progress mencapai 100%
                percentageText.text('100%');

                if (response.status === 'success') {
                    swal({
                        title: "Berhasil!",
                        text: "Nilai IPS berhasil dihitung dan diperbarui.",
                        type: "success",
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        }
                    }).then(function() {
                        location.reload(); // Reload halaman setelah konfirmasi
                    });
                } else {
                    swal({
                        title: "Gagal!",
                        text: response.message,
                        type: "error",
                        buttons: {
                            confirm: {
                                className: 'btn btn-danger'
                            }
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                $('#loading').hide(); // Sembunyikan loading jika ada error
                clearInterval(progressInterval); // Hentikan progress bar
                swal({
                    title: "Error!",
                    text: "Terjadi kesalahan saat menghitung IPSxxx.",
                    type: "error",
                    buttons: {
                        confirm: {
                            className: 'btn btn-danger'
                        }
                    }
                });
            }
        });
    });
});
</script>
@endpush
