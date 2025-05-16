@extends('layouts.bak')
@section('title')
Daftar Peserta Wisuda
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
                            <h2>Daftar Peserta Wisuda</h2>

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
                        <label class="col-form-label col-md-2">Fakultas</label>
                        <div class="col-md-8">
                            <select name="fakultas" id="fakultas" required class="form-select" onchange="filterProdi()">
                                <option value="*">-- Semua Fakultas --</option>
                                @foreach ($fakultas as $f)
                                <option value="{{$f->id}}">{{$f->nama_fakultas}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Prodi</label>
                        <div class="col-md-8">
                            <select name="prodi" id="prodi" required class="form-select">
                                <option value="*">-- Semua Prodi --</option>
                                @foreach ($prodi as $p)
                                <option value="{{$p->id_prodi}}">({{$p->kode_program_studi}}) - {{$p->nama_jenjang_pendidikan}} {{$p->nama_program_studi}}</option>
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
                        <table id="data" class="table table-bordered table-hover margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">NO</th>
                                    <th class="text-center align-middle">PERIODE</th>
                                    <th class="text-center align-middle">STATUS</th>
                                    <th class="text-center align-middle">IJAZAH TERAKHIR</th>
                                    <th class="text-center align-middle">SK YUDISIUM</th>
                                    <th class="text-center align-middle">BERKAS REGISTRASI WISUDA</th>
                                    <th class="text-center align-middle">NOMOR REGISTRASI</th>
                                    <th class="text-center align-middle">FOTO</th>
                                    <th class="text-center align-middle">FAKULTAS</th>
                                    <th class="text-center align-middle">PROGRAM STUDI</th>
                                    <th class="text-center align-middle">JENJANG</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">NAMA</th>
                                    <th class="text-center align-middle">NIK</th>
                                    <th class="text-center align-middle">TEMPAT KULIAH</th>
                                    <th class="text-center align-middle">JALUR MASUK</th>
                                    <th class="text-center align-middle">TEMPAT LAHIR</th>
                                    <th class="text-center align-middle">TANGGAL LAHIR</th>
                                    <th class="text-center align-middle">IPK</th>
                                    <th class="text-center align-middle">ALAMAT</th>
                                    <th class="text-center align-middle">TELP</th>
                                    <th class="text-center align-middle">EMAIL</th>
                                    <th class="text-center align-middle">NAMA ORANG TUA</th>
                                    <th class="text-center align-middle">ALAMAT ORANG TUA</th>
                                    <th class="text-center align-middle">TANGGAL MASUK</th>
                                    <th class="text-center align-middle">TANGGAL YUDISIUM</th>
                                    <th class="text-center align-middle">MASA STUDI</th>
                                    <th class="text-center align-middle">JUDUL TUGAS AKHIR / THESIS / DISERTASI</th>
                                    <th class="text-center align-middle">SCOR USEPT</th>
                                    <th class="text-center align-middle">AKSI</th>
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
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>
function getData()
{
    var fakultas = $('#fakultas').val();
    var prodi = $('#prodi').val();
    var periode = $('#periode').val();

    // console.log(fakultas, prodi, periode);

    if (fakultas == '' || prodi == '' || periode == '') {
        swal('Peringatan', 'Silahkan pilih fakultas, prodi, dan periode wisuda terlebih dahulu', 'warning');
        return;

    }

    $.ajax({
        url: `{{route('bak.wisuda.peserta.data')}}`,
        type: 'GET',
        data: {
            fakultas: fakultas,
            prodi: prodi,
            periode: periode
        },
        success: function (response) {

            if (response.status === 'success') {
                console.log(response.data);
                var table = $('#data').DataTable();
                table.clear().draw();
                $.each(response.data, function (index, item) {
                    console.log(item.id);
                    var url_berkas = '{{route('bak.wisuda.peserta.formulir', ['id' => 'ID'])}}';
                    url_berkas = url_berkas.replace('ID', item.id);
                    var berkasButton = '<a class="btn btn-sm btn-success" href="' + url_berkas + '" target="_blank"><i class="fa fa-file me-2"></i>Unduh Berkas Registrasi</a>';
                    
                    var url_ijazah = item.ijazah_terakhir_file ? '{{ asset('') }}' + item.ijazah_terakhir_file : NULL;
                    var ijazahButton = item.ijazah_terakhir_file ? 
                        '<a class="btn btn-sm btn-success" href="' + url_ijazah + '" target="_blank"><i class="fa fa-file me-2"></i>Lihat Ijazah</a>' : 
                        '<span class="badge badge-warning text-center"><i class="fa fa-exclamation-circle me-1"></i>Belum Upload SK Yudisium</span>';

                    var url_sk_yudisium = item.sk_yudisium_file ? '{{ asset('') }}' + item.sk_yudisium_file : null;
                    var skYudisiumButton = url_sk_yudisium ? 
                        '<a class="btn btn-sm btn-success" href="' + url_sk_yudisium + '" target="_blank"><i class="fa fa-file me-2"></i>Lihat SK Yudisium</a>' : 
                        '<span class="badge badge-warning text-center"><i class="fa fa-exclamation-circle me-1"></i>Belum Upload<br>SK Yudisium</span>';

                    var spanStatus = '';
                        if (item.approved === 0 || item.approved > 3) {
                            spanStatus = '<span class="badge badge-danger">' + item.approved_text + '</span>';
                        } else if (item.approved > 0 && item.approved < 3) {
                            spanStatus = '<span class="badge badge-primary">' + item.approved_text + '</span>';
                        } else {
                            spanStatus = '<span class="badge badge-success">' + item.approved_text + '</span>';
                        };

                    var namaOrtu = '';
                                if (item.nama_ayah && item.nama_ibu_kandung) {
                                    namaOrtu = item.nama_ayah + ' & ' + item.nama_ibu_kandung;
                                } else if (item.nama_ayah) {
                                    namaOrtu = item.nama_ayah;
                                } else if (item.nama_ibu_kandung) {
                                    namaOrtu = item.nama_ibu_kandung;
                                }
                    var alamat = 'RT ' + item.rt + ' RW ' + item.rw + ', ' + item.dusun + ', ' + item.kelurahan + ', ' + item.jalan + ', ' + item.nama_wilayah;
                    var foto = item.pas_foto ? `
                        <td class="text-center align-middle text-nowrap">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#fotoModal${item.id}">
                                <img src="{{ asset('') }}${item.pas_foto}" alt="Pas Foto" style="width: 150px;" title="Lihat Foto">
                            </a>
                            <!-- Modal -->
                            <div class="modal fade" id="fotoModal${item.id}" tabindex="-1" aria-labelledby="fotoModalLabel${item.id}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-3">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="fotoModalLabel${item.id}">FOTO ${item.nama_mahasiswa}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center m-20">
                                            <img src="{{ asset('') }}${item.pas_foto}" alt="Foto" style="width: 100%; max-width: 500px;" class="rounded-3">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    ` : '';

                    var aksi = 
                        `<td class="text-center align-middle text-nowrap">
                            <div class="row">
                                ${item.approved == 2 ? `
                                    <button onclick="approvePeserta(${item.id})" class="btn btn-success btn-sm my-2" title="Setujui Pengajuan">
                                        <i class="fa fa-check"> </i> Approve
                                    </button>` : ''}
                                ${(item.approved == 2 || item.approved == 3) ? `
                                    <button onclick="declinePeserta(${item.id})" class="btn btn-danger btn-sm my-2" title="Tolak Pengajuan">
                                        <i class="fa fa-ban"> </i> Decline
                                    </button>` : ''}
                            </div>
                        </td>`;

                    table.row.add([
                        index + 1,
                        item.wisuda_ke,
                        spanStatus,
                        ijazahButton,
                        skYudisiumButton,
                        berkasButton,
                        item.nomor_registrasi ?? '-',
                        foto,
                        item.nama_fakultas,
                        item.nama_prodi,
                        item.jenjang,
                        item.nim,
                        item.nama_mahasiswa,
                        item.nik,
                        item.lokasi_kuliah,
                        item.jalur_masuk,
                        item.tempat_lahir,
                        item.tanggal_lahir,
                        item.ipk ?? '-',
                        alamat,
                        item.handphone,
                        item.email,
                        namaOrtu,
                        item.alamat_orang_tua ?? '-',
                        item.tanggal_daftar,
                        item.tgl_sk_yudisium ?? spanStatus,
                        item.lama_studi ? item.lama_studi + ' Bulan' : spanStatus,
                        item.judul,
                        item.scor_usept ?? '-',
                        aksi,
                        
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

function filterProdi()
{
    var prodi = @json($prodi);
    var fakultas = $('#fakultas').val();

    if (fakultas == '*') {
        $('#prodi').empty();
        $('#prodi').append('<option value="*">-- Semua Prodi --</option>');
        $.each(prodi, function (i, p) {
            $('#prodi').append('<option value="'+p.id_prodi+'">('+p.kode_program_studi+') - '+p.nama_jenjang_pendidikan+' '+p.nama_program_studi+'</option>');
        });
        return;

    }

    var filteredProdi = prodi.filter(function (p) {
        return p.fakultas_id == fakultas;
    });

    $('#prodi').empty();

    $('#prodi').append('<option value="*">-- Semua Prodi --</option>');
    $.each(filteredProdi, function (i, p) {
        $('#prodi').append('<option value="'+p.id_prodi+'">('+p.kode_program_studi+') - '+p.nama_jenjang_pendidikan+' '+p.nama_program_studi+'</option>');
    });

}

function approvePeserta(id) {
    swal({
        title: "Apakah Anda yakin?",
        text: `Peserta dengan ID ${id} akan disetujui.`,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Lanjutkan',
        cancelButtonText: 'Batal',
    }, function(isConfirmed) {
        if (isConfirmed) {
            $.ajax({
                url: `{{route('bak.wisuda.peserta.approve', ['id' => 'ID'])}}`.replace('ID', id),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: 1
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // console.log('Approval successful:', response.data);
                        swal('Berhasil', response.message, 'success');
                        getData();
                    } else {
                        // console.log('Approval failed:', response.data);
                        swal('Gagal', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    // console.log('Approval failed:', response.data);
                    swal('Gagal', 'Terjadi kesalahan saat menyetujui peserta.', 'error');
                }
            });
        }
    })
}

function declinePeserta(id) {
    swal({
        title: "Apakah Anda yakin?",
        text: `Peserta dengan ID ${id} akan ditolak.`,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Lanjutkan',
        cancelButtonText: 'Batal',
    }, function(isConfirmed) {
        if (isConfirmed) {
            $.ajax({
                url: `{{route('bak.wisuda.peserta.decline', ['id' => 'ID'])}}`.replace('ID', id),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: 0
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // console.log('Decline successful:', response.data);
                        swal('Berhasil', response.message, 'success');
                        getData();
                    } else {
                        // console.log('Decline failed:', response.data);
                        swal('Gagal', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    // console.log('Decline failed:', response.data);
                    swal('Gagal', 'Terjadi kesalahan saat menolak peserta.', 'error');
                }
            });
        }
    })
}

$(function () {
    // "use strict";
    $('#data').DataTable();

    $('#fakultas').select2();
    $('#prodi').select2();
    $('#periode').select2();
});
</script>
@endpush
