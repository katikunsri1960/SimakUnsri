@extends('layouts.bak')
@section('title')
Perbaikan Data Mahasiswa
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
                            <h2>Perbaikan Data Mahasiswa</h2>
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
                    <div class="col-md-8 mt-5">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Cari</label>
                            <div class="col-md-8">
                                <input type="hidden" name="nim">
                                <select name="id_registrasi_mahasiswa" id="id_registrasi_mahasiswa" required class="form-select">
                                    <option value=""></option>
                                </select>

                            </div>
                            <div class="col-md-2 pt-1">
                                <div class="row">
                                    <button class="btn btn-primary btn-sm" id="btnCari"><i class="fa fa-search"></i>
                                        Cari</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="box-body text-center">
                            <div id="formDiv" hidden>
                                <form id="formPerbaikanData">
                                    @csrf
                                    <input type="hidden" id="id_registrasi_mahasiswa_hidden">

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label>NIM</label>
                                            <input type="text" class="form-control" id="nim" disabled>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Nama Mahasiswa</label>
                                            <input type="text" class="form-control" id="nama" disabled>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Nama Perbaikan</label>
                                            <input type="text" class="form-control" id="nama_perbaikan"
                                                placeholder="Contoh: Nama Mahasiswa">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Tempat Perbaikan</label>
                                            <input type="text" class="form-control" id="tmpt_perbaikan"
                                                placeholder="Contoh: Palembang">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Tanggal Perbaikan</label>
                                            <input type="date" class="form-control" id="tgl_perbaikan">
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <button type="button" class="btn btn-success btn-sm" id="btnSimpan">
                                            <i class="fa fa-save"></i> Simpan Perbaikan
                                        </button>
                                    </div>

                                    <small id="saveStatus" class="text-muted d-block mt-2"></small>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('assets/vendor_components/select2/dist/css/select2.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>
$('#btnSimpan').on('click', function (e) {

    e.preventDefault();

    let id = $('#id_registrasi_mahasiswa_hidden').val();
    let nama = $('#nama_perbaikan').val();
    let tempat = $('#tmpt_perbaikan').val();
    let tanggal = $('#tgl_perbaikan').val();

    if (!id) {
        swal('Peringatan', 'Data mahasiswa belum dipilih', 'warning');
        return;
    }

    if (!nama && !tempat && !tanggal) {
        swal('Peringatan', 'Minimal satu data perbaikan harus diisi', 'warning');
        return;
    }

    swal({
        title: 'Simpan Data',
        text: "Apakah anda yakin ingin menyimpan data?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Lanjutkan',
        cancelButtonText: 'Batal'
    }, function (isConfirm) {

        if (!isConfirm) return;

        $('#spinner').show();
        $('#saveStatus').text('Menyimpan data...');

        $.ajax({
            url: "{{ route('bak.wisuda.perbaikan-data.store') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id_registrasi_mahasiswa: id,
                nama_perbaikan: nama ? nama : null,
                tmpt_perbaikan: tempat ? tempat : null,
                tgl_perbaikan: tanggal || null,
            },
            success: function (res) {

                $('#spinner').hide();

                if (res.status === 'success') {

                    swal('Berhasil', res.message, 'success');

                    $('#saveStatus')
                        .removeClass('text-danger')
                        .addClass('text-success')
                        .text('✔ Data perbaikan tersimpan');

                } else {

                    swal('Gagal', res.message ?? 'Gagal menyimpan data', 'error');

                    $('#saveStatus')
                        .removeClass('text-success')
                        .addClass('text-danger')
                        .text('❌ Gagal menyimpan');
                }

            },
            error: function (xhr) {

                $('#spinner').hide();

                let msg = xhr.responseJSON?.message ?? 'Terjadi kesalahan sistem';
                swal('Gagal', msg, 'error');
                $('#saveStatus').text('❌ Gagal menyimpan');
            }
        });
    });
});

function toDateInputFormat(dateStr) {
    if (!dateStr) return null;

    // asumsi format: DD-MM-YYYY
    let parts = dateStr.split('-');
    if (parts.length !== 3) return null;

    return `${parts[2]}-${parts[1]}-${parts[0]}`;
}


$("#id_registrasi_mahasiswa").select2({
    placeholder : '-- Masukan NIM / Nama Mahasiswa --',
    width: '100%',
    minimumInputLength: 3,
    ajax: {
        url: "{{ route('bak.wisuda.registrasi-ijazah.get-mahasiswa') }}",
        type: "GET",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return { q: params.term };
        },
        processResults: function (data) {
            return {
                results: $.map(data, function (item) {
                    return {
                        id: item.id_registrasi_mahasiswa,
                        text: item.nama_mahasiswa + " (" + item.nim + " - " + item.nama_program_studi + ")"
                    }
                })
            };
        }
    }
});

$(document).ready(function () {

    function loadMahasiswa(nim) {

        if (!nim) {
            swal('Peringatan', 'NIM / Nama tidak boleh kosong', 'warning');
            return;
        }

        $("#spinner").show();

        $.ajax({
            url: "{{ route('bak.wisuda.perbaikan-data.get') }}",
            type: 'GET',
            data: { nim: nim },
            success: function (response) {

                $("#spinner").hide();

                if (response.status === 'error') {
                    swal("Peringatan!", response.message, "warning");
                    return;
                }

                // tampilkan form
                $('#formDiv').prop('hidden', false);

                // =============================
                // DATA UTAMA
                // =============================
                $('#nim').val(response.riwayat.nim);
                $('#nama').val(response.riwayat.nama_mahasiswa);
                $('#id_registrasi_mahasiswa_hidden')
                    .val(response.riwayat.id_registrasi_mahasiswa);

                // =============================
                // DATA PERBAIKAN
                // =============================
                if (response.riwayat.data_perbaikan) {

                    $('#nama_perbaikan').val(response.riwayat.data_perbaikan.nama_perbaikan);
                    $('#tmpt_perbaikan').val(response.riwayat.data_perbaikan.tmpt_perbaikan);
                    $('#tgl_perbaikan').val(response.riwayat.data_perbaikan.tgl_perbaikan);

                    swal(
                        'Info',
                        'Data perbaikan sebelumnya ditemukan dan ditampilkan',
                        'info'
                    );

                } else {

                    $('#nama_perbaikan').val(response.riwayat.nama_mahasiswa);
                                    $('#tmpt_perbaikan').val(response.riwayat.biodata.tempat_lahir);
                                    $('#tgl_perbaikan').val(toDateInputFormat(response.riwayat.biodata.tanggal_lahir));
                }
            },
            error: function () {
                $("#spinner").hide();
                swal("Error", "Gagal mengambil data mahasiswa", "error");
            }
        });
    }

    // =============================
    // AUTO LOAD SETELAH RELOAD
    // =============================
    let lastId = localStorage.getItem('last_id_registrasi_mahasiswa');

    if (lastId) {

        let option = new Option('Memuat data...', lastId, true, true);
        $('#id_registrasi_mahasiswa').append(option).trigger('change');

        loadMahasiswa(lastId);

        localStorage.removeItem('last_id_registrasi_mahasiswa');
    }

    // =============================
    // MANUAL CARI
    // =============================
    $('#btnCari').on('click', function () {
        loadMahasiswa($('#id_registrasi_mahasiswa').val());
    });

});


</script>

@endpush
