@extends('layouts.bak')
@section('title')
Daftar Album Wisudawan
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
                            <h2>Daftar Album Wisudawan</h2>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Predikat Kelulusan</label>
                        <div class="col-md-6">
                            <select name="predikat" id="predikat" class="form-select">
                                <option value="12">Non Dengan Pujian</option>
                                <option value="3">Dengan Pujian</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Fakultas</label>
                        <div class="col-md-8">
                            <select name="fakultas" id="fakultas" required class="form-select" onchange="filterProdi()" >
                                @foreach ($fakultas as $f)
                                <option value="{{$f->id}}">{{$f->nama_fakultas}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" id="rowProdi">
                        <label class="col-form-label col-md-2">Prodi</label>
                        <div class="col-md-8">
                            <select name="prodi" id="prodi" class="form-select">
                                <option value="">-- Pilih Prodi --</option>
                                @foreach ($prodi as $p)
                                    <option value="{{$p->id_prodi}}">
                                        ({{$p->kode_program_studi}}) -
                                        {{$p->nama_jenjang_pendidikan}}
                                        {{$p->nama_program_studi}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Periode</label>
                        <div class="col-md-3">
                            <select name="periode" id="periode" required class="form-select">
                                @foreach ($periode as $per)
                                <option value="{{$per->periode}}">{{$per->periode}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">&nbsp;</label>
                        <div class="col-md-8">
                           <div class="row mx-2">
                            <button class="btn btn-sm btn-primary" onclick="getData()">Tampilkan <i class="fa fa-magnifying-glass ms-2"></i></button>
                           </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <div class="mb-3">
                            <button class="btn btn-outline btn-danger btn-sm me-2" onclick="downloadPdf()"><i class="fa fa-file-pdf me-2"></i> DOWNLOAD ALBUM (PDF)</button>
                            <!-- <button class="btn btn-outline btn-danger btn-sm me-2" onclick="downloadPdf()"><i class="fa fa-file-pdf me-2"></i> DOWNLOAD ALBUM (PPT)</button> -->
                        </div>

                        <table id="data" class="table table-bordered table-hover margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">NO</th>
                                    <th class="text-center align-middle">NO URUT</th>
                                    <th class="text-center align-middle">PREDIKAT KELULUSAN</th>
                                    <th class="text-center align-middle">FOTO</th>
                                    <th class="text-center align-middle">NOMOR IJAZAH NASIONAL</th>
                                    <th class="text-center align-middle">PERIODE</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">NAMA</th>
                                    <th class="text-center align-middle">NIK</th>
                                    <th class="text-center align-middle">TEMPAT LAHIR</th>
                                    <th class="text-center align-middle">TANGGAL LAHIR</th>
                                    <th class="text-center align-middle">TANGGAL WISUDA</th>
                                    <th class="text-center align-middle">IPK</th>
                                    <th class="text-center align-middle">JENIS KELAMIN</th>
                                    <th class="text-center align-middle">PROGRAM STUDI</th>
                                    <th class="text-center align-middle">GELAR</th>
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
    <div class="modal fade" id="modalNoUrut" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="modalNoUrutTitle">
                        Input Nomor Urut Album
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="wisuda_id">

                    <div class="form-group">
                        <label>Nomor Urut</label>
                        <input
                            type="number"
                            min="1"
                            class="form-control"
                            id="no_urut"
                            placeholder="Masukkan nomor urut">
                    </div>

                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-primary"
                        onclick="simpanNoUrut()">
                        Simpan
                    </button>
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

$(document).ready(function () {
    filterProdi();
    toggleProdi();
});

$('#predikat').on('change', function () {
    toggleProdi();
});

function toggleProdi()
{
    var predikat = $('#predikat').val();

    if (predikat == '3') {
        $('#rowProdi').hide();
        $('#prodi').val('').trigger('change');
    } else {
        $('#rowProdi').show();
    }
}

function getData()
{
    var fakultas = $('#fakultas').val();
    var predikat = $('#predikat').val();
    var prodi = $('#prodi').val();
    var periode = $('#periode').val();

    if (fakultas == '') {
        swal('Peringatan', 'Silahkan pilih fakultas terlebih dahulu', 'warning');
        return;
    }

    if (predikat == '') {
        swal('Peringatan', 'Silahkan pilih predikat kelulusan terlebih dahulu', 'warning');
        return;
    }

    // Non DP wajib pilih prodi
    if (predikat == '12' && prodi == '') {
        swal('Peringatan', 'Silahkan pilih program studi terlebih dahulu', 'warning');
        return;
    }

    if (periode == '') {
        swal('Peringatan', 'Silahkan pilih periode wisuda terlebih dahulu', 'warning');
        return;
    }

    $.ajax({
        url: '{{ route("bak.wisuda.album.peserta") }}',
        type: 'GET',
        data: {
            fakultas: fakultas,
            predikat: predikat,
            prodi: prodi,
            periode: periode
        },

        success: function (response) {

            if (response.status === 'success') {
                // console.log(response.data);
                var table = $('#data').DataTable();
                table.clear().draw();
                $.each(response.data, function (index, item) {
                    var url_berkas = '{{route('bak.wisuda.peserta.formulir', ['id' => 'ID'])}}';
                    url_berkas = url_berkas.replace('ID', item.id);
                    var berkasButton = '<a class="btn btn-sm btn-primary" href="' + url_berkas + '" target="_blank"><i class="fa fa-file me-2"></i>Unduh Berkas Registrasi</a>';
                    var spanStatus = item.approved > 5 ? '<span class="badge badge-danger">' + item.approved_text +'</span>' : '<span class="badge badge-success">' + item.approved_text +'</span>';
                    var foto = item.pas_foto
                        ? '<img src="{{ asset("storage") }}/' + item.pas_foto + '" class="img-fluid" style="max-width:300px; max-height:500px;">'
                        : '';
                    var jenis_kelamin = item.jenis_kelamin === 'L' ? 'Laki-laki' : (item.jenis_kelamin === 'P' ? 'Perempuan' : '-');
                    // ===============================
                    // KONDISI KHUSUS NO IJAZAH
                    // ===============================
                    var nomor_ijazah = item.jenjang === 'Profesi'
                        ? (item.no_ijazah ?? item.no_sertifikat ?? '-')
                        : (item.no_ijazah ?? '-');

                    var noUrutButton = `
                            <div class="text-center">
                                <div class="fw-bold mb-1">
                                    ${item.no_urut ?? '-'}
                                </div>

                                <button
                                    type="button"
                                    class="btn btn-sm ${item.no_urut ? 'btn-info' : 'btn-warning'}"
                                    onclick="editNoUrut(
                                        '${item.id}',
                                        '${item.no_urut ?? ''}',
                                        '${item.nama_mahasiswa}'
                                    )">
                                    ${item.no_urut ? 'Edit' : 'Input'}
                                </button>
                            </div>
                        `;

                    table.row.add([
                        index + 1,
                        noUrutButton,
                        item.nama_predikat_kelulusan,
                        foto,
                        nomor_ijazah ,
                        item.wisuda_ke,
                        item.nim,
                        item.nama_mahasiswa,
                        item.nik,
                        item.tempat_lahir,
                        item.tanggal_lahir,
                        item.tanggal_wisuda,
                        item.ipk,
                        jenis_kelamin,
                        item.jenjang + ' - ' + item.nama_prodi ,
                        item.gelar ?? '-',
                    ]).draw(false);
                });

            } else if(response.status === 'error') {
                swal('Error', response.message, 'error');
            } else {
                swal('Error', 'Gagal mengambil data peserta wisuda', 'error');
            }

        }
    });
}

function downloadPdf()
{
    var fakultas = $('#fakultas').val();
    var predikat = $('#predikat').val();
    var prodi = $('#prodi').val();
    var periode = $('#periode').val();

    if (!fakultas || !predikat || !periode) {
        swal(
            'Peringatan',
            'Silahkan pilih fakultas, predikat dan periode wisuda terlebih dahulu',
            'warning'
        );
        return;
    }

    if (predikat == '12' && prodi == '') {
        swal(
            'Peringatan',
            'Silahkan pilih program studi terlebih dahulu',
            'warning'
        );
        return;
    }

    var baseUrl = '{{ route("bak.wisuda.album.download-pdf") }}';

    var url = baseUrl +
    '?fakultas=' + encodeURIComponent(fakultas) +
    '&predikat=' + encodeURIComponent(predikat) +
    '&prodi=' + encodeURIComponent(prodi) +
    '&periode=' + encodeURIComponent(periode);

    window.open(url, '_blank');
}

function filterProdi()
{
    var prodi = @json($prodi);
    var fakultas = $('#fakultas').val();

    var filteredProdi = prodi.filter(function (p) {
        return p.fakultas_id == fakultas;
    });

    $('#prodi').empty();

    // option kosong → supaya dianggap belum pilih
    $('#prodi').append('<option value="">-- Pilih Prodi --</option>');

    $.each(filteredProdi, function (i, p) {
        $('#prodi').append('<option value="'+p.id_prodi+'">('+p.kode_program_studi+') - '+p.nama_jenjang_pendidikan+' '+p.nama_program_studi+'</option>');
    });

}

function editNoUrut(id, noUrut)
{
    $('#wisuda_id').val(id);
    $('#no_urut').val(noUrut);

    $('#modalNoUrut').modal('show');
}

function simpanNoUrut()
{
    $.ajax({
        url: '{{ route("bak.wisuda.album.update-no-urut") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            id: $('#wisuda_id').val(),
            no_urut: $('#no_urut').val()
        },
        success: function(response){

            if(response.status == 'success')
            {
                $('#modalNoUrut').modal('hide');

                swal(
                    'Berhasil',
                    'Nomor urut berhasil disimpan',
                    'success'
                );

                getData();
            }
            else
            {
                swal('Error', response.message, 'error');
            }
        }
    });
}

$(function () {
    // "use strict";
    $('#data').DataTable();

    $('#fakultas').select2();
    $('#predikat').select2();
    $('#prodi').select2();
    $('#periode').select2();
});
</script>
@endpush
