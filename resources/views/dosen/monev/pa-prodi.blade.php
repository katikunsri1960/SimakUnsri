@extends('layouts.dosen')
@section('title')
Monev Pembimbing Akademik
@endsection
@section('content')
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-12">
            <div class="box pull-up">
                <div class="box-body bg-img bg-primary-light">
                    <div class="d-lg-flex align-items-center justify-content-between">
                        <div class="d-lg-flex align-items-center mb-30 mb-xl-0 w-p100">
                            <img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}"
                                class="img-fluid max-w-250" alt="" />
                            <div class="ms-30">
                                <h2 class="mb-10">Monev Pembimbing Akademik</h2>
                                <p class="mb-0 text-fade fs-18">Universitas Sriwijaya</p>
                            </div>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
            </div>

            @include('dosen.monev.pa-prodi-anggota')
            <div class="row">
                <div class="col-xxl-12">
                    <div class="box box-body mb-0 ">
                        {{-- <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <h3 class="fw-500 text-dark mt-0">Daftar Penelitian Dosen</h3>
                            </div>
                        </div> --}}
                        <div class="row">
                            <div class="col-md-6">
                                <label for="nim" class="form-label">Program Studi</label>
                                <div class="input-group mb-3">
                                    <select name="id_prodi" id="id_prodi" class="form-select">
                                        <option value="">-- Pilih Prodi --</option>
                                        @foreach ($prodi as $p)
                                        <option value="{{$p->id_prodi}}">{{$p->nama_jenjang_pendidikan}} -
                                            {{$p->nama_program_studi}}</option>
                                        @endforeach
                                    </select>
                                    <button class="input-group-button btn btn-primary btn-sm" id="basic-addon1"
                                        onclick="getMonev()"><i class="fa fa-search"></i> Proses</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="data-div"></div>
                        </div>
                    </div>
                </div>
            </div>
</section>
@endsection
@push('js')
<script>
    function getMonev() {
        var id_prodi = $('#id_prodi').val();
        $.ajax({
            url: "{{route('dosen.monev.pa-prodi.get-monev')}}",
            type: "GET",
            data: {
                id_prodi: id_prodi
            },
            success: function (data) {
            //    empty data-div
                $('#data-div').empty();

                if(data.status == 0)
                {
                    $('#data-div').html(`
                        <div class="alert alert-danger" role="alert">
                            <h4>${data.message}</h4>
                        </div>
                    `);
                }
                else
                {
                    // make table from data and append to data-div
                    var table = `
                        <div class="table-responsive">
                            <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">No</th>
                                        <th class="text-center align-middle">NIDN</th>
                                        <th class="text-center align-middle">Dosen</th>
                                        <th class="text-center align-middle">Jumlah<br>Bimbingan</th>
                                        <th class="text-center align-middle">No SK</th>
                                        <th class="text-center align-middle">Tgl Sk Tugas</th>

                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    data.data.forEach((d, i) => {
                        var sk_tugas = d.sk_tugas == null ? 'Belum diisi' : d.sk_tugas;
                        var tanggal_sk_tugas = d.tanggal_sk_tugas == null ? 'Belum diisi' : d.tanggal_sk_tugas;
                        table += `
                                <tr>
                                    <td class="text-center align-middle">${i + 1}</td>
                                    <td class="text-center align-middle">${d.nidn}</td>
                                    <td class="text-start align-middle">${d.nama_dosen}</td>
                                    <td class="text-center align-middle">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalAnggota" onclick="getAnggota(${d.id})">
                                            ${d.jumlah_anggota}
                                        </a>
                                    </td>
                                    <td class="text-center align-middle">${sk_tugas}</td>
                                    <td class="text-center align-middle">${tanggal_sk_tugas}</td>

                                </tr>
                            `;
                        });
                    table += `
                                </tbody>
                            </table>
                        </div>
                    `;
                    $('#data-div').html(table);

                    $('#data').DataTable();


                }
            }
        });
    }

    function getAnggota(id) {
        $.ajax({
            url: "{{route('dosen.monev.pa-prodi.get-anggota-monev')}}",
            type: "GET",
            data: {
                id: id
            },
            success: function (data) {
                // empty anggota-div
                if ($.fn.DataTable.isDataTable('#data-anggota')) {
                    $('#data-anggota').DataTable().clear().destroy();
                }
                $('#anggota-div').empty();

                if (data.status == 0) {
                    $('#anggota-div').html(`
                        <div class="alert alert-danger" role="alert">
                            <h4>${data.message}</h4>
                        </div>
                    `);
                } else {
                    // make table from data and append to anggota-div
                    var table = `
                        <div class="table-responsive">
                            <table id="data-anggota" class="table table-hover table-bordered margin-top-10 w-p100" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">No</th>
                                        <th class="text-center align-middle">NIM</th>
                                        <th class="text-center align-middle">Mahasiswa</th>
                                        <th class="text-center align-middle">Angkatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    data.data.forEach((d, i) => {
                        table += `
                                <tr>
                                    <td class="text-center align-middle">${i + 1}</td>
                                    <td class="text-center align-middle">${d.nim}</td>
                                    <td class="text-start align-middle">${d.nama_mahasiswa}</td>
                                    <td class="text-center align-middle">${d.angkatan}</td>
                                </tr>
                            `;
                    });
                    table += `
                                </tbody>
                            </table>
                        </div>
                    `;
                    $('#anggota-div').html(table);

                    $('#data-anggota').DataTable({
                        "paging": false,

                    });

                }
            }
        });
    }


</script>
@endpush
