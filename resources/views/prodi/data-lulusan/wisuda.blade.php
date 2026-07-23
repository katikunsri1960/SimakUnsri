@extends('layouts.prodi')
@section('title')
Pendaftaran Wisuda Program Studi
@endsection
@section('content')
@include('swal')
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-12">
			<div class="box pull-up">
				<div class="box-body bg-img bg-primary-light">
					<div class="d-lg-flex align-items-center justify-content-between">
						<div class="d-lg-flex align-items-center mb-30 mb-xl-0 w-p100">
			    			<img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" class="img-fluid max-w-250" alt="" />
							<div class="ms-30">
								<h2 class="mb-10">Pendaftaran Wisuda Program Studi</h2>
								<p class="mb-0 text-fade fs-18">Universitas Sriwijaya</p>
							</div>
						</div>
					<div>
				</div>
			</div>							
		</div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
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
                        <table id="data" class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">NO</th>
                                    <th class="text-center align-middle">PERIODE</th>
                                    <th class="text-center align-middle">STATUS</th>
                                    <th class="text-center align-middle">PREDIKAT KELULUSAN</th>
                                    <th class="text-center align-middle">IJAZAH TERAKHIR</th>
                                    <th class="text-center align-middle">SK YUDISIUM</th>
                                    <!-- <th class="text-center align-middle">BERKAS REGISTRASI WISUDA</th> -->
                                    <th class="text-center align-middle">NOMOR REGISTRASI</th>
                                    <th class="text-center align-middle">FOTO</th>
                                    <th class="text-center align-middle">JENJANG</th>
                                    <th class="text-center align-middle">PROGRAM STUDI</th>
                                    <th class="text-center align-middle">GELAR</th>
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
                                    <th class="text-center align-middle">TGL SK YUDISIUM</th>
                                    <th class="text-center align-middle">TGL YUDISIUM</th>
                                    <th class="text-center align-middle">MASA STUDI</th>
                                    <th class="text-center align-middle">JUDUL ID TUGAS AKHIR / THESIS / DISERTASI</th>
                                    <th class="text-center align-middle">JUDUL EN TUGAS AKHIR / THESIS / DISERTASI</th>
                                    
                                    <th class="text-center align-middle">SKOR USEPT</th>
                                    <!-- <th class="text-center align-middle">AKSI</th> -->
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
<div class="modal fade" id="modalJudulEng" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-3">

            <div class="modal-header">
                <h5 class="modal-title">Ubah Judul Bahasa Inggris</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="edit_id">

                <div class="mb-3">
                    <label class="fw-bold">Judul Bahasa Inggris</label>

                    <textarea
                        class="form-control"
                        id="edit_judul_eng"
                        rows="6"></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Batal
                </button>

                <button class="btn btn-success"
                        onclick="updateJudulEng()">
                    <i class="fa fa-save"></i>
                    Simpan
                </button>
            </div>

        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>

function formatTanggal(tanggal) {
    if (!tanggal) return '-';

    let d = new Date(tanggal);

    let day = String(d.getDate()).padStart(2, '0');
    let month = String(d.getMonth() + 1).padStart(2, '0');
    let year = d.getFullYear();

    return `${day}-${month}-${year}`;
}

function getData()
{
    // var fakultas = $('#fakultas').val();
    // var prodi = $('#prodi').val();
    var periode = $('#periode').val();

    // console.log(fakultas, prodi, periode);

    if (periode == '') {
        swal('Peringatan', 'Silahkan pilih prodi, dan periode wisuda terlebih dahulu', 'warning');
        return;

    }

    $.ajax({
        url: `{{route('prodi.data-lulusan.wisuda.peserta.data')}}`,
        type: 'GET',
        data: {
            // prodi: prodi,
            periode: periode
        },
        success: function (response) {

            if (response.status === 'success') {
                // console.log(response.data);
                var table = $('#data').DataTable();
                table.clear().draw();
                $.each(response.data, function (index, item) {
                    // console.log(item.id);
                    
                    var url_ijazah = item.ijazah_terakhir_file ? '{{ asset('') }}' + item.ijazah_terakhir_file : NULL;
                    var ijazahButton = item.ijazah_terakhir_file ? 
                        '<a class="btn btn-sm btn-success" href="' + url_ijazah + '" target="_blank"><i class="fa fa-file me-2"></i>Lihat Ijazah</a>' : 
                        '<span class="badge badge-warning text-center"><i class="fa fa-exclamation-circle me-1"></i>Belum Upload SK Yudisium</span>';

                    // var skYudisium = (item.no_sk_yudisium && item.tgl_sk_yudisium)
                    //     ? item.no_sk_yudisium + '<br>( ' + moment(item.tgl_sk_yudisium).format('D MMMM YYYY') + ' )'
                    //     : '-';

                    var jsonData = encodeURIComponent(JSON.stringify(response.data).replace(/'/g, '&#39;'));
                    var url_sk_yudisium = item.sk_yudisium_file ? '{{ asset('') }}' + item.sk_yudisium_file : null;
                    var skYudisiumButton = url_sk_yudisium ? 
                        '<a class="btn btn-sm btn-success" href="' + url_sk_yudisium + '" target="_blank"><i class="fa fa-file me-2"></i>Lihat SK Yudisium</a>' : 
                        '<span class="badge badge-warning text-center"><i class="fa fa-exclamation-circle me-1"></i>Belum Upload<br>SK Yudisium</span>';


                    var spanStatus = '';
                    if (item.approved_wisuda === 0 ) {
                        spanStatus = '<span class="badge badge-warning text-center d-block">' + item.approved_wisuda_text + '</span>';
                    } else if (item.approved_wisuda > 3) {
                        let alasan = item.alasan_pembatalan ? '<span class="badge badge-danger mt-2">Alasan ditolak :<br>' + item.alasan_pembatalan + '</span>' : '';
                        spanStatus = '<span class="badge badge-danger text-center d-block">' + item.approved_wisuda_text + '</span>' + alasan;
                    } else if (item.approved_wisuda > 0 && item.approved_wisuda < 3) {
                        spanStatus = '<span class="badge badge-primary text-center d-block">' + item.approved_wisuda_text + '</span>';
                    } else {
                        spanStatus = '<span class="badge badge-success text-center d-block">' + item.approved_wisuda_text + '</span>';
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
                                <img src="{{ asset('storage') }}/${item.pas_foto}" alt="Pas Foto" style="width: 150px;" title="Lihat Foto">
                            </a>
                            <!-- Modal -->
                            <div class="modal fade" id="fotoModal${item.id}" tabindex="-1" aria-labelledby="fotoModalLabel${item.id}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-3">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="fotoModalLabel${item.id}">FOTO ${item.nama_riwayat}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center m-20">
                                            <img src="{{ asset('storage') }}/${item.pas_foto}" alt="Foto" style="width: 100%; max-width: 500px;" class="rounded-3">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    ` : '';

                    var aksi = `
                        <td class="align-middle text-nowrap">
                            <div class="row">

                                ${
                                    item.approved == 1
                                    ? (
                                        !item.file_bebas_pustaka
                                        ? `
                                            <span class="badge badge-lg bg-danger mb-2 rounded">
                                                Ditangguhkan
                                            </span>
                                            <p class="text-danger mb-0">
                                                <strong>
                                                    Mahasiswa belum Mengumpulkan Bundle Skripsi/Tesis/Disertasi ke UPT Perpustakaan!
                                                </strong>
                                            </p>
                                        `
                                        : !item.link_repo
                                        ? `
                                            <span class="badge badge-lg bg-danger mb-2 rounded">
                                                Ditangguhkan
                                            </span>
                                            <p class="text-danger mb-0">
                                                <strong>
                                                    Mahasiswa belum Upload Repository!
                                                </strong>
                                            </p>
                                        `
                                        : `
                                            <button onclick="showApproveModal(${item.id})" class="btn btn-success btn-sm my-2">
                                                <i class="fa fa-check"></i> Approve
                                            </button>

                                            <div class="modal fade" id="approveModal${item.id}" tabindex="-1">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Approve Fakultas</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <div class="modal-body row">
                                                            <div class="col-md-12 mb-3">
                                                                <label>Gelar Lulusan</label>
                                                                <select class="form-select" id="gelar_${item.id}">
                                                                    <option value="">-- Pilih Gelar --</option>
                                                                    @foreach($gelar_lulusan as $g)
                                                                        <option value="{{ $g->id }}">
                                                                            {{ $g->gelar }} ({{ $g->prodi->nama_jenjang_pendidikan }} - {{ $g->prodi->nama_program_studi }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-12 mb-3">
                                                                <label>Predikat Lulusan</label>
                                                                <select class="form-select" id="predikat_${item.id}">
                                                                    <option value="">-- Pilih Predikat --</option>
                                                                    @foreach($predikat_lulusan as $p)
                                                                        <option value="{{ $p->id }}">{{ $p->indonesia }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                            <button class="btn btn-success" onclick="submitApprove(${item.id})">
                                                                Setujui
                                                            </button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <button onclick="showDeclineModal(${item.id})"
                                                    class="btn btn-danger btn-sm my-2"
                                                    title="Tolak Pengajuan">
                                                <i class="fa fa-ban"></i> Decline
                                            </button>

                                            <div class="modal fade" id="declineModal${item.id}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Pembatalan Pendafataran Yudisium</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Alasan Pembatalan</label>
                                                                <input class="form-control"
                                                                    id="alasan_pembatalan${item.id}"
                                                                    placeholder="Masukkan alasan pembatalan">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button class="btn btn-danger" onclick="submitDecline(${item.id})">
                                                                Tolak
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        `
                                    )
                                    : (item.approved == 2
                                        ? `
                                            <button onclick="showDeclineModal(${item.id})"
                                                    class="btn btn-danger btn-sm my-2"
                                                    title="Tolak Pengajuan">
                                                <i class="fa fa-ban"></i> Decline
                                            </button>

                                            <div class="modal fade" id="declineModal${item.id}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Alasan Penolakan</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Alasan Penolakan</label>
                                                                <input class="form-control"
                                                                    id="alasan_pembatalan${item.id}"
                                                                    placeholder="Masukkan alasan penolakan">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button class="btn btn-danger" onclick="submitDecline(${item.id})">
                                                                Tolak
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        `
                                        : ''
                                    )
                                }

                            </div>
                        </td>
                        `;

                    var judulEng = `
                        <div style="min-width:150px">
                            <div id="judul_eng_${item.id}" class="mb-2">
                                ${item.judul_eng ?? '-'}
                            </div>

                            <div class="text-center">
                                <button
                                    type="button"
                                    class="btn btn-warning btn-xs mt-2"
                                    data-id="${item.id}"
                                    data-judul="${$('<div>').text(item.judul_eng ?? '').html()}"
                                    onclick="editJudulEng(this)">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                            </div>
                        </div>
                    `;

                    var useptData = item.useptdata
                        ? `
                            <div class="text-center">
                                <span class="text-${item.useptdata.class}">
                                    <strong>${item.useptdata.score}</strong>
                                </span>
                                <br>
                                <span class="badge ${
                                    item.useptdata.class === 'success'
                                        ? 'bg-success'
                                        : 'bg-danger'
                                }">
                                    ${item.useptdata.status}
                                </span>
                            </div>
                        `
                        : '-';

                    table.row.add([
                        index + 1,
                        item.wisuda_ke,
                        spanStatus,
                        item.predikat_kelulusan ?? '-',
                        ijazahButton,
                        skYudisiumButton, 
                        // skYudisium,
                        item.nomor_registrasi ?? '-',
                        foto,
                        item.jenjang,
                        item.nama_prodi,
                        item.gelar ?? '-',
                        item.nim,
                        item.nama_riwayat,
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
                        item.tgl_masuk,
                        item.tgl_sk_yudisium ?? spanStatus,
                        item.sk_tgl_kegiatan ?? spanStatus,
                        item.lama_studi ? item.lama_studi + ' Bulan' : spanStatus,
                        item.judul,
                        judulEng,
                        useptData,
                        // aksi,
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

function editJudulEng(btn)
{
    let id = $(btn).data('id');
    let judul = $(btn).data('judul');

    $('#edit_id').val(id);

    $('#edit_judul_eng').val(judul);

    $('#modalJudulEng').modal('show');
}

function updateJudulEng()
{
    let id = $('#edit_id').val();
    let judul = $('#edit_judul_eng').val();

    if (judul.trim() == '') {
        swal('Peringatan', 'Judul Bahasa Inggris tidak boleh kosong', 'warning');
        return;
    }

    swal({
        title: "Konfirmasi",
        text: "Apakah Anda yakin ingin menyimpan perubahan Judul Bahasa Inggris?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#04a08b",
        confirmButtonText: "Ya, Simpan",
        cancelButtonText: "Batal",
        closeOnConfirm: false
    }, function () {

        $.ajax({
            url: "{{ route('prodi.data-lulusan.wisuda.update-judul-eng') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id,
                judul_eng: judul
            },
            success: function(response){

                if(response.status == 'success'){

                    $('#judul_eng_' + id).html(judul);

                    $('#modalJudulEng').modal('hide');

                    swal(
                        "Berhasil",
                        response.message,
                        "success"
                    );

                }else{

                    swal(
                        "Gagal",
                        response.message,
                        "error"
                    );

                }

            },
            error:function(){

                swal(
                    "Error",
                    "Terjadi kesalahan server.",
                    "error"
                );

            }
        });

    });
}

function setDosenPa(data, id) {
    // data di sini adalah array, cari objek dengan id yang sesuai
    // data = data.replace(/&#39;/g, "'");
    data = JSON.parse(data);
    var peserta = Array.isArray(data) ? data.find(function(item) { return item.id == id; }) : data;
    if (!peserta) {
        console.log('Data peserta tidak ditemukan untuk id:', id);
        return;
    }
    // console.log(peserta, peserta.no_sk_yudisium, peserta.tgl_sk_yudisium, peserta.tgl_keluar);
    if (peserta.no_sk_yudisium) {
        $('#no_sk_yudisium').val(peserta.no_sk_yudisium);
    }
    if (peserta.tgl_sk_yudisium) {
        $('#tgl_sk_yudisium').val(peserta.tgl_sk_yudisium);
    }
    if (peserta.tgl_keluar) {
        $('#tgl_yudisium').val(peserta.tgl_keluar);
    }

    // Populate other fields...
    // document.getElementById('editForm').action = '/fakultas/pendaftaran-wisuda/' + id;
}


$(function () {
    // "use strict";
    $('#data').DataTable();

    // $('#fakultas').select2();
    // $('#prodi').select2();
    // $('#periode').select2();
});

</script>

@endpush

