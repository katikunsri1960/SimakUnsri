@extends('layouts.prodi')
@section('title')
KHS Mahasiswa
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Kartu Hasil Studi</h3>
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
                        <div class="col-md-4">
                            <label for="nim" class="form-label">Nomor Induk Mahasiswa</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="nim" id="nim" required />
                                <button class="input-group-button btn btn-primary btn-sm" id="basic-addon1"
                                    onclick="getKrs()"><i class="fa fa-search"></i> Proses</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body text-center">
                    <div class="table-responsive">
                        <div id="krsDiv" hidden>
                            <h3 class="text-center">Kartu Hasil Studi (KHS)</h3>
                            <table style="width:100%" class="mb-3">
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
                            </table>
                            <div class="d-flex justify-content-end align-middle">

                            </div>
                            <table class="table table-bordered mt-4" id="krs-regular">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">No</th>
                                        <th class="text-center align-middle">Kode Mata Kuliah</th>
                                        <th class="text-center align-middle">Nama Mata Kuliah</th>
                                        <th class="text-center align-middle">Nama Kelas</th>
                                        <th class="text-center align-middle">SKS</th>
                                        <th class="text-center align-middle">Nilai Angka</th>
                                        <th class="text-center align-middle">Nilai Index</th>
                                        <th class="text-center align-middle">Nilai Huruf</th>
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
    </div>
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>
 function getKrs()
    {
        var nim = $('#nim').val();
        var semester = $('#semester').val();
        if(nim == '' || semester == ''){
            swal({
                title: "Peringatan!",
                text: "Nomor Induk Mahasiswa dan Tahun Akademik harus diisi!",
                type: "warning",
                buttons: {
                    confirm: {
                        className : 'btn btn-warning'
                    }
                }
            });
        }else{
            $.ajax({
                url: '{{route('prodi.data-akademik.khs.data')}}',
                type: 'GET',
                data: {
                    nim: nim,
                    semester: semester
                },
                success: function(response){
                    if (response.status == 'error') {
                        swal({
                            title: "Peringatan!",
                            text: response.message,
                            type: "warning",
                            buttons: {
                                confirm: {
                                    className : 'btn btn-warning'
                                }
                            }
                        });
                        return false;
                    }

                    $('#krsDiv').removeAttr('hidden');
                    // append response.krs to table of krs-regular
                    $('#nimKrs').text(response.riwayat.nim);
                    // remove "Fakultas " from nama_fakultas
                    var fakultas = response.riwayat.prodi.fakultas.nama_fakultas.replace('Fakultas ', '');
                    $('#fakultasKrs').text(fakultas);
                    $('#namaKrs').text(response.riwayat.nama_mahasiswa);
                    var jurusan = response.riwayat.prodi.jurusan.nama_jurusan_id ?? '-';
                    $('#jurusanKrs').text(jurusan);
                    var nip_pa = response.riwayat.dosen_pa.nip ?? '-';
                    $('#nippaKrs').text(nip_pa);
                    var dosen_pa = response.riwayat.dosen_pa.nama_dosen ?? '-';
                    $('#dosenpaKrs').text(dosen_pa);
                    $('#prodiKrs').text(response.riwayat.prodi.nama_program_studi);
                    var semesterText =  $('#semester option:selected').text();
                    $('#semesterKrs').text(semesterText);
                    $('#krs-regular tbody').empty();

                    // count response.krs.approved
                    var approved = 0;
                    var no = 1;

                    response.nilai.forEach(function(krs, index){
                        var trClass = '';
                        if(krs.nilai_huruf == 'F' || krs.nilai_huruf == null)
                        {
                            trClass = 'bg-danger';
                        }
                        $('#krs-regular tbody').append(`
                            <tr class="${trClass}">
                                <td class="text-center align-middle">${no}</td>
                                <td class="text-center align-middle">${krs.kode_mata_kuliah}</td>
                                <td class="text-start align-middle">${krs.nama_mata_kuliah}</td>
                                <td class="text-center align-middle">${krs.nama_kelas_kuliah}</td>
                                <td class="text-center align-middle">${krs.sks_mata_kuliah}</td>
                                <td class="text-center align-middle">${krs.nilai_angka ?? '-'}</td>
                                <td class="text-center align-middle">${krs.nilai_indeks}</td>
                                <td class="text-center align-middle">${krs.nilai_huruf}</td>
                            </tr>
                        `);
                        no++;
                    });

                }
            });
        }
    }
</script>
@endpush
