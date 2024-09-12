@extends('layouts.fakultas')
@section('title')
Sidang Tugas Akhir
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">
                Sidang Tugas Akhir Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('fakultas')}}"><i
                            class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Akademik</li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Sidang Tugas Akhir Mahasiswa
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
@include('fakultas.data-akademik.sidang-mahasiswa.detail')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-hover margin-top-10 w-p100"
                            style="font-size: 11px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">NO</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">NAMA</th>
                                    <th class="text-center align-middle">NAMA AKTIVITAS<br>(MK Konversi)</th>
                                    <th class="text-center align-middle">Pembimbing</th>
                                    <th class="text-center align-middle">Penguji</th>
                                    <th class="text-center align-middle">Status Penguji</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle"></td>
                                    <td class="text-center align-middle">
                                        {{$d->anggota_aktivitas_personal->nim}}
                                    </td>
                                    <td class="text-start align-middle" style="width: 15%">
                                        {{$d->anggota_aktivitas_personal->nama_mahasiswa}}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ strtoupper($d->nama_jenis_aktivitas)}}<br>({{$d->konversi->kode_mata_kuliah}} - {{$d->konversi->nama_mata_kuliah}})
                                    </td>
                                    <td class="text-start align-middle">
                                        <ul>
                                            @foreach ($d->bimbing_mahasiswa as $p)
                                            <li>Pembimbing {{$p->pembimbing_ke}} :<br>{{$p->nama_dosen}}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-start align-middle">
                                        <ul>
                                            @foreach ($d->uji_mahasiswa as $u)
                                            <li>{{$u->nama_kategori_kegiatan}} :<br>{{$u->nama_dosen}}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($d->status_uji > 0)
                                            <span class="badge badge-lg badge-danger">Belum Disetujui</span>
                                        @elseif ($d->approved_prodi > 0)
                                            <span class="badge badge-lg badge-warning">Menunggu konfirmasi dosen</span>
                                        @elseif ($d->decline_dosen > 0)
                                            <span class="badge badge-lg badge-danger">Dibatalkan dosen</span>
                                        @else
                                            <span class="badge badge-lg badge-success">Approved</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
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
<script>
    function detailFunc1(data) {
        $('#detail_judul').val(data.judul);
        $('#edit_tanggal_mulai').val(data.id_tanggal_mulai);
        $('#edit_tanggal_selesai').val(data.id_tanggal_selesai);
        $('#edit_lokasi').val(data.lokasi);
    }

    $(function() {
        "use strict";

        $('#data').DataTable({
            // default sort by column 6 desc
            "order": [[ 5, "desc" ]],
            "columnDefs": [{
                "targets": 0,
                "searchable": false,
                "orderable": false,
                "render": function (data, type, full, meta) {
                    return meta.settings._iDisplayStart + meta.row + 1;
                }
            }],
            "drawCallback": function (settings) {
                var api = this.api();
                var startIndex = api.context[0]._iDisplayStart;
                api.column(0, {page: 'current'}).nodes().each(function (cell, i) {
                    cell.innerHTML = startIndex + i + 1;
                });
            }
        });

        $('.approve-class').on('submit', function(e) {
            e.preventDefault();
            var formId = $(this).data('id');
            swal({
                title: 'Apakah Anda Yakin??',
                text: "Setelah disetujui, penguji tidak bisa diubah lagi!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $(`#approveForm${formId}`).unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });
</script>
@endpush
