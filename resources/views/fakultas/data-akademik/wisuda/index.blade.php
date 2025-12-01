@extends('layouts.fakultas')
@section('title')
Pendaftaran Wisuda Fakultas
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
								<h2 class="mb-10">Pendaftaran Wisuda Fakultas</h2>
								<p class="mb-0 text-fade fs-18">Universitas Sriwijaya</p>
							</div>
						</div>
					<div>
				</div>
			</div>							
		</div>
    </div>
    @include('fakultas.data-akademik.wisuda.upload-sk')
    @include('fakultas.data-akademik.wisuda.edit-sk')
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
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
                        <table id="data" class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">NO</th>
                                    <th class="text-center align-middle">AKSI</th>
                                    <th class="text-center align-middle">PERIODE</th>
                                    <th class="text-center align-middle">STATUS</th>
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
    // var fakultas = $('#fakultas').val();
    var prodi = $('#prodi').val();
    var periode = $('#periode').val();

    // console.log(fakultas, prodi, periode);

    if (prodi == '' || periode == '') {
        swal('Peringatan', 'Silahkan pilih prodi, dan periode wisuda terlebih dahulu', 'warning');
        return;

    }

    $.ajax({
        url: `{{route('fakultas.wisuda.peserta.data')}}`,
        type: 'GET',
        data: {
            prodi: prodi,
            periode: periode
        },
        success: function (response) {

            if (response.status === 'success') {
                console.log(response.data);
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
                    var skYudisiumButton = '';
                    if (!item.sk_yudisium_file) {
                        skYudisiumButton = `
                            <td class="text-center align-middle">
                                <button class="btn btn-warning btn-sm pilihSkBtn" data-id="${item.id}">
                                    <i class="fa fa-upload"></i> Pilih SK Yudisium
                                </button>
                            </td>
                        `;
                    } else {
                        skYudisiumButton = `
                            <td class="text-center align-middle">
                                <a href="{{ asset('') }}${item.sk_yudisium_file}" target="_blank" class="btn btn-success btn-sm my-2">
                                    <i class="fa fa-file"></i> Lihat
                                </a>
                                <button type="button" 
                                    class="btn btn-primary btn-sm my-2 btn-edit-sk"
                                    data-id="${item.id}"
                                    data-nosk="${item.sk_nama_file ?? 'Belum Diisi'}"
                                    data-tglsk="${item.sk_tgl_surat ?? 'Belum Diisi'}"
                                    data-tglyudisium="${item.sk_tgl_kegiatan ?? 'Belum Diisi'}"
                                    data-file="{{ asset('') }}${item.sk_yudisium_file}"
                                >
                                    <i class="fa fa-edit"></i> Edit
                                </button>

                                
                                <button type="button" class="btn btn-danger btn-sm my-2 btn-hapus-sk" data-id="${item.id}">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                                <form id="form-hapus-sk-${item.id}" action="{{ route('fakultas.wisuda.hapus-sk-yudisium', '') }}/${item.id}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        `;
                    }

                    var spanStatus = '';
                    if (item.approved === 0 || item.approved > 3) {
                        let alasan = item.alasan_pembatalan ? '<span class="badge badge-danger mt-2">Alasan ditolak :<br>' + item.alasan_pembatalan + '</span>' : '';
                        spanStatus = '<span class="badge badge-danger text-center d-block">' + item.approved_text + '</span>' + alasan;
                    } else if (item.approved > 0 && item.approved < 3) {
                        spanStatus = '<span class="badge badge-primary text-center d-block">' + item.approved_text + '</span>';
                    } else {
                        spanStatus = '<span class="badge badge-success text-center d-block">' + item.approved_text + '</span>';
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
                        `<td class="align-middle text-nowrap">
                            <div class="row">

                                ${item.approved == 1 ? `
                                    <button onclick="showApproveModal(${item.id})" class="btn btn-success btn-sm my-2" title="Setujui Pengajuan">
                                        <i class="fa fa-check"> </i> Approve
                                    </button>

                                    <div class="modal fade" id="approveModal${item.id}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
                                        aria-labelledby="modalLabel${item.id}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLabel${item.id}">
                                                        Approve & Input Gelar + Predikat Lulusan
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <form action="{{route('fakultas.wisuda.peserta.approve', '')}}/${item.id}" 
                                                    method="post" 
                                                    id="upload-class-${item.id}" 
                                                    enctype="multipart/form-data">

                                                    @csrf

                                                    <div class="modal-body">
                                                        <div class="row">

                                                            <!-- Gelar Lulusan -->
                                                            <div class="col-md-12 mb-3">
                                                                <label for="gelar_${item.id}" class="form-label">Gelar Lulusan</label>
                                                                <select class="form-select" name="gelar" id="gelar_${item.id}" required>
                                                                    <option value="">-- Pilih Gelar Lulusan --</option>
                                                                    @foreach($gelar_lulusan as $gelar)
                                                                        <option value="{{ $gelar->id }}">{{ $gelar->gelar }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- Predikat Lulusan -->
                                                            <div class="col-md-12 mb-3">
                                                                <label for="predikat_${item.id}" class="form-label">Predikat Lulusan</label>
                                                                <select class="form-select" name="predikat" id="predikat_${item.id}" required>
                                                                    <option value="">-- Pilih Predikat Lulusan --</option>
                                                                    @foreach($predikat as $p)
                                                                        <option value="{{ $p->id }}">{{ $p->indonesia }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            Tutup
                                                        </button>
                                                        <button type="button" class="btn btn-success" onclick="submitApprove(${item.id})">
                                                            Setuju
                                                        </button>
                                                    </div>

                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                ` : ''}

                                ${(item.approved == 1 || item.approved == 2) ? `
                                    <button onclick="showDeclineModal(${item.id})" class="btn btn-danger btn-sm my-2" title="Tolak Pengajuan">
                                        <i class="fa fa-ban"> </i> Decline
                                    </button>

                                    <div class="modal fade" id="declineModal${item.id}" tabindex="-1" aria-labelledby="declineModalLabel${item.id}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="declineModalLabel${item.id}">Alasan Penolakan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="col-md-12 mb-3">
                                                        <label for="alasan_pembatalan${item.id}" class="form-label">Alasan Penolakan</label>
                                                        <input class="form-control" name="alasan_pembatalan" id="alasan_pembatalan${item.id}" placeholder="Masukkan alasan penolakan">
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="button" class="btn btn-danger" onclick="submitDecline(${item.id})">Tolak</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                ` : ''}

                            </div>
                        </td>`;

                    table.row.add([
                        index + 1,
                        aksi,
                        item.wisuda_ke,
                        spanStatus,
                        ijazahButton,
                        skYudisiumButton, 
                        // skYudisium,
                        item.nomor_registrasi ?? '-',
                        foto,
                        item.jenjang,
                        item.nama_prodi,
                        item.gelar ?? '-',
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
                        item.sk_tgl_kegiatan ?? spanStatus,
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

$(function () {
    $('#data').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        // "responsive": true,
    });

    $('.btn-hapus-sk').click(function(e){
        e.preventDefault();
        var id = $(this).data('id');
        swal({
            title: 'Hapus SK Yudisium?',
            text: "Anda yakin ingin menghapus SK Yudisium ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#form-hapus-sk-' + id).submit();
            }
        });
    });
});

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

//TOMBOL APPROVE
function showApproveModal(id) {
    $('#approveModal' + id).modal('show');
}

function submitApprove(id) {
    var gelar = $('#gelar_' + id).val();
    var predikat = $('#predikat_' + id).val();

    if (!gelar) {
        swal('Peringatan', 'Silakan pilih gelar lulusan.', 'warning');
        return;
    }
    if (!predikat) {
        swal('Peringatan', 'Silakan pilih predikat lulusan.', 'warning');
        return;
    }

    swal({
        title: "Konfirmasi Persetujuan",
        text: "Apakah Anda yakin ingin menyetujui peserta ini?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Setujui',
        cancelButtonText: 'Batal',
    }, function(isConfirmed) {
        if (isConfirmed) {
            $.ajax({
                url: `{{route('fakultas.wisuda.peserta.approve', ['id' => 'ID'])}}`.replace('ID', id),
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: 1,
                    gelar: gelar,
                    predikat: predikat
                },
                success: function(response) {
                    if (response.status === 'success') {

                        // 0. Hilangkan fokus dari tombol modal (FIX untuk warning aria-hidden)
                        $('body').focus();

                        // 1. Tutup modal
                        $('#approveModal' + id).modal('hide');

                        // 2. Tunggu modal selesai menutup
                        setTimeout(function() {

                            // Hapus backdrop kalau masih tersisa
                            $('.modal-backdrop').remove();

                            // 3. Tampilkan swal
                            swal({
                                title: "Berhasil",
                                text: response.message,
                                type: "success"
                            }, function() {
                                getData();
                            });

                        }, 350);

                    } else {
                        swal('Gagal', response.message, 'error');
                    }
                },

                error: function(xhr) {
                    console.log('Approve error:', xhr.responseText);
                    swal('Gagal', 'Terjadi kesalahan saat menyetujui peserta.', 'error');
                }
            });
        }
    });
}





// Tambahkan fungsi berikut agar tombol Decline berfungsi
// Tampilkan Modal Decline
function showDeclineModal(id) {
    $('#declineModal' + id).modal('show');
}

// Submit Penolakan
function submitDecline(id) {
    var alasan = $('#alasan_pembatalan' + id).val();

    if (!alasan) {
        swal('Peringatan', 'Silakan isikan alasan penolakan.', 'warning');
        return;
    }

    swal({
        title: "Konfirmasi Penolakan",
        text: "Apakah Anda yakin ingin menolak peserta ini?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Tolak',
        cancelButtonText: 'Batal'
    }, function(isConfirmed) {

        if (isConfirmed) {
            $.ajax({
                url: `{{ route('fakultas.wisuda.peserta.decline', ['id' => 'ID']) }}`.replace('ID', id),
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: 0,
                    alasan: alasan
                },

                success: function(response) {
                    if (response.status === 'success') {

                        // 🟢 FIX: Hilangkan fokus sebelum modal ditutup
                        $('body').focus();

                        // Tutup modal
                        $('#declineModal' + id).modal('hide');

                        // Tunggu modal selesai ditutup (300–350ms)
                        setTimeout(function() {

                            // FIX: Hapus backdrop jika masih tersisa
                            $('.modal-backdrop').remove();

                            // Tampilkan swal success
                            swal({
                                title: "Berhasil",
                                text: response.message,
                                type: "success"
                            }, function() {
                                getData(); // refresh data tabel
                            });

                        }, 350);

                    } else {
                        console.log('Decline failed:', response);
                        swal('Gagal', response.message, 'error');
                    }
                },

                error: function(xhr) {
                    console.log('Decline error:', xhr.responseText);
                    swal('Gagal', 'Terjadi kesalahan saat menolak peserta.', 'error');
                }
            });
        }

    });
}


</script>

@endpush

