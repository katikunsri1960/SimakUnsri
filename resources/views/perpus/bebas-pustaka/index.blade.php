@extends('layouts.perpus')
@section('title')
BEBAS PUSTAKA
@endsection
@section('content')
<section class="content">
    <div class="row align-items-end">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3"><img
                                src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h2>Bebas Pustaka Mahasiswa</h2>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-body py-10">

                    <div class="col-md-6 mt-5">

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">NIM</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nim"
                                        placeholder="Masukan NIM mahasiswa">
                                    <button class="btn btn-primary" id="btnCari"><i class="fa fa-search"></i>
                                        Cari</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-5">
                        <div class="box-body">
                            <div class="table-responsive">
                                <div id="krsDiv" hidden>
                                    <div class="row mb-3">
                                        <div class="col-md-2" id="foto">

                                        </div>
                                        <div class="col-md-10 p-2">
                                            <table style="width:100%" class="mb-3">
                                                <tr>
                                                    <td class="text-start align-middle" style="width: 12%">NIM</td>
                                                    <td>:</td>
                                                    <td class="text-start" id="nimKrs"
                                                        style="width: 45%; padding-left: 10px"></td>
                                                    <td class="text-start align-middle" style="width: 18%">FAKULTAS</td>
                                                    <td>:</td>
                                                    <td class="text-start align-middle" id="fakultasKrs"
                                                        style="width: 30%; padding-left: 10px"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start align-middle" style="width: 12%">NAMA</td>
                                                    <td>:</td>
                                                    <td class="text-start" id="namaKrs"
                                                        style="width: 45%; padding-left: 10px"></td>
                                                    <td class="text-start align-middle" style="width: 18%">JURUSAN</td>
                                                    <td>:</td>
                                                    <td class="text-start align-middle" id="jurusanKrs"
                                                        style="width: 30%; padding-left: 10px"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start align-middle" style="width: 12%">NIP PA</td>
                                                    <td>:</td>
                                                    <td class="text-start" id="nippaKrs"
                                                        style="width: 45%; padding-left: 10px"></td>
                                                    <td class="text-start align-middle" style="width: 18%">PROGRAM STUDI
                                                    </td>
                                                    <td>:</td>
                                                    <td class="text-start align-middle" id="prodiKrs"
                                                        style="width: 30%; padding-left: 10px"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start align-middle" style="width: 12%">DOSEN PA</td>
                                                    <td>:</td>
                                                    <td class="text-start" id="dosenpaKrs"
                                                        style="width: 45%; padding-left: 10px"></td>
                                                    {{-- <td class="text-start align-middle" style="width: 18%">SEMESTER
                                                    </td>
                                                    <td>:</td>
                                                    <td class="text-start align-middle" id="semesterKrs"
                                                        style="width: 30%; padding-left: 10px"></td> --}}
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <hr>
                                    <form action="{{route('perpus.bebas-pustaka.store')}}" method="post" id="storeForm" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" name="id_registrasi_mahasiswa"
                                                id="id_registrasi_mahasiswa">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="file_bebas_pustaka" class="form-label">File Bebas Pustaka <span class="text-danger">(PDF, Max 1 Mb!)</span></label>
                                                    <input type="file" class="form-control" name="file_bebas_pustaka" id="file_bebas_pustaka" placeholder="Max 1MB!" aria-describedby="fileHelpId" />
                                                    <div id="fileError" class="text-danger" style="display: none;">File size exceeds 1MB!</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="link_repo" class="form-label">Link Repo
                                                        TA/Skripsi/Thesis/Disertasi</label>
                                                    <input type="url" class="form-control" name="link_repo"
                                                        id="link_repo" aria-describedby="helpId" placeholder="" />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row p-4">
                                                    <button class="btn btn-primary">Simpan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
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

<script>

    $(document).ready(function () {
            $('#btnCari').click(function () {
                var nim = $('#nim').val();
                $('#krsDiv').attr('hidden', 'hidden');
                if (nim == '') {
                    swal('Peringatan', 'NIM tidak boleh kosong', 'warning');
                } else {
                    $.ajax({
                        url: '{{route('perpus.bebas-pustaka.get-data')}}',
                        type: 'GET',
                        data: {
                            nim: nim
                        },
                        success: function (response) {
                            // clear krsDiv

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
                            $('#nimCetak').val(nim);
                            $('#krsDiv').removeAttr('hidden');
                            // append response.krs to table of krs-regular
                            $('#nimKrs').text(response.riwayat.nim);
                            // remove "Fakultas " from nama_fakultas
                            var fakultas = response.riwayat.prodi.fakultas.nama_fakultas.replace('Fakultas ', '');
                            $('#fakultasKrs').text(fakultas);
                            $('#namaKrs').text(response.riwayat.nama_mahasiswa);
                            var jurusan = response.riwayat.prodi.jurusan.nama_jurusan_id ?? '-';
                            $('#jurusanKrs').text(jurusan);
                            var nip_pa = response.riwayat.pembimbing_akademik ? response.riwayat.pembimbing_akademik.nip : '-';
                            $('#nippaKrs').text(nip_pa);
                            var dosen_pa = response.riwayat.pembimbing_akademik ? response.riwayat.pembimbing_akademik.nama_dosen : '-';
                            $('#dosenpaKrs').text(dosen_pa);
                            var prodi = response.riwayat.prodi.nama_jenjang_pendidikan + ' ' + response.riwayat.prodi.nama_program_studi;
                            $('#prodiKrs').text(prodi);
                            // var semesterText =  $('#semester option:selected').text();
                            // $('#semesterKrs').text(semesterText);
                            // $('#krs-regular tbody').empty();

                            // append foto
                            var imagePath = '{{ asset('storage') }}' + '/' + response.riwayat.angkatan + '/' + response.riwayat.nim + '.jpg';
                            $('#foto').html(`
                                <img class="rounded20 bg-light img-fluid w-80" src="` + imagePath + `" alt="" onerror="this.onerror=null;this.src='{{ asset('images/images/avatar/avatar-15.png') }}';">
                            `);

                            $('#id_registrasi_mahasiswa').val(response.riwayat.id_registrasi_mahasiswa);

                        }
                    });

                }
            });

            $('#storeForm').submit(function(e){

                var fileInput = document.getElementById('file_bebas_pustaka');
                var fileError = document.getElementById('fileError');
                var maxSize = 1024 * 1024; // 1MB in bytes

                if (fileInput.files.length > 0) {
                    var file = fileInput.files[0];
                    if (file.size > maxSize) {
                        fileError.style.display = 'block';
                        e.preventDefault(); // Prevent form submission
                        swal({
                            title: 'Peringatan',
                            text: 'File melebihi 1MB!',
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                        return false;
                    } else {
                        fileError.style.display = 'none';
                    }
                }
                e.preventDefault();
                swal({
                    title: 'Simpan Data',
                    text: "Pastikan Data yang anda masukan sudah benar?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }, function(isConfirm){
                    if (isConfirm) {
                        $('#spinner').show();
                        $('#storeForm').unbind('submit').submit();
                    }
                });
            });
        });
</script>
@endpush
