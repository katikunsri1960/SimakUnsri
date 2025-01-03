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
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
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
                                    <option value="{{$semester->id_semester}}" {{request()->semester == '' &&
                                        $semester->id_semester ==
                                        $semesterAktif->id_semester ? 'selected' : ''}}>{{$semester->nama_semester}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            {{-- <label for="nim" class="form-label">Nomor Induk Mahasiswa</label> --}}
                            {{-- <div class="input-group mb-3"> --}}
                                {{-- <input type="text" class="form-control" name="nim" id="nim" required /> --}}
                                <button class="btn btn-primary btn-md mt-30" id="basic-addon1"
                                    onclick="getKrs()"><i class="fa fa-search"></i> Tampilkan Data</button>
                                
                                <button id="btnHitungIPS" class="btn btn-success btn-md mt-30">Hitung & Update IPS</button>
                                    <div id="loading" style="display: none;">Menghitung...</div>
                                    
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
                <div class="box-body text-center">
                    <div class="table-responsive">
                        <div id="khsDiv" hidden>
                            {{-- <div class="row mb-20">
                                <form action="{{route('fakultas.data-akademik.khs.download')}}" method="get" id="cetakForm" target="_blank">
                                    <input type="hidden" name="nim" id="nimCetak">
                                    <input type="hidden" name="id_semester" id="idSemesterCetak">
                                    <button class="btn btn-success" type="submit"><i class="fa fa-print"></i> Cetak KHS</button>
                                </form>
                            </div> --}}
                            <h3 class="text-center">Data AKM </h3>
                            {{-- <table style="width:100%" class="mb-3">
                                <tr>
                                    <td class="text-start align-middle" style="width: 12%">NIM</td>
                                    <td>:</td>
                                    <td class="text-start" id="nimKrs" style="width: 45%; padding-left: 10px"></td>
                                    <td class="text-start align-middle" style="width: 18%">FAKULTAS</td>
                                    <td>:</td>
                                    <td class="text-start align-middle" id="fakultasKrs"
                                        style="width: 30%; padding-left: 10px"></td>
                                </tr>
                                <tr>
                                    <td class="text-start align-middle" style="width: 12%">NAMA</td>
                                    <td>:</td>
                                    <td class="text-start" id="namaKrs" style="width: 45%; padding-left: 10px"></td>
                                    <td class="text-start align-middle" style="width: 18%">JURUSAN</td>
                                    <td>:</td>
                                    <td class="text-start align-middle" id="jurusanKrs"
                                        style="width: 30%; padding-left: 10px"></td>
                                </tr>
                                <tr>
                                    <td class="text-start align-middle" style="width: 12%">NIP PA</td>
                                    <td>:</td>
                                    <td class="text-start" id="nippaKrs" style="width: 45%; padding-left: 10px"></td>
                                    <td class="text-start align-middle" style="width: 18%">PROGRAM STUDI</td>
                                    <td>:</td>
                                    <td class="text-start align-middle" id="prodiKrs"
                                        style="width: 30%; padding-left: 10px"></td>
                                </tr>
                                <tr>
                                    <td class="text-start align-middle" style="width: 12%">DOSEN PA</td>
                                    <td>:</td>
                                    <td class="text-start" id="dosenpaKrs" style="width: 45%; padding-left: 10px"></td>
                                    <td class="text-start align-middle" style="width: 18%">SEMESTER</td>
                                    <td>:</td>
                                    <td class="text-start align-middle" id="semesterKrs"
                                        style="width: 30%; padding-left: 10px"></td>
                                </tr>
                            </table> --}}
                            <div class="d-flex justify-content-end align-middle">

                            </div>
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
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        <div class="row mt-5" id="akmDiv">

                        </div>
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
    function getKrs() {
        var semester = $('#semester').val();
    
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
                semester: semester
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
                if (response.akm && response.akm.length > 0) {
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
                    response.akm.forEach(function(akm, index) {
                        tableContent += `
                            <tr>
                                <td class="text-center align-middle">${index + 1}</td>
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
                    });
                } else {
                    // Jika tidak ada data
                    $('#akmDiv').html('<p class="text-center">Tidak ada data yang ditemukan.</p>');
                }
            },
            error: function(xhr, status, error) {
                // Handling error dari AJAX
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
    
        $(document).ready(function() {
        $('#btnHitungIPS').click(function() {
            // Tampilkan indikator loading
            $('#loading').show();

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
                        });

                        // Reload tabel atau perbarui data
                        // var table = $('#akmTable').DataTable();
                        // table.ajax.reload(null, false); // false untuk mempertahankan halaman

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
                    swal({
                        title: "Error!",
                        text: "Terjadi kesalahan saat menghitung IPS.",
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
}
</script>
@endpush
