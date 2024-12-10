@extends('layouts.fakultas')
@section('title')
Tugas Akhir
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Aktivitas Tugas Akhir</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('fakultas')}}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Akademik</li>
                        <li class="breadcrumb-item active" aria-current="page">Aktivitas Tugas Akhir</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
@include('fakultas.data-akademik.tugas-akhir.detail')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
                    <div class="d-flex justify-content-end">
                        <div class="d-flex justify-content-start">
                            <!-- Modal trigger button -->
                            <button type="button" class="btn btn-primary waves-effect waves" data-bs-toggle="modal"
                                data-bs-target="#filter-button">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                            <span class="divider-line mx-1"></span>
                            <a href="{{route('fakultas.data-akademik.tugas-akhir')}}" class="btn btn-warning waves-effect waves" >
                                <i class="fa fa-rotate"></i> Reset Filter
                            </a>
                            @include('fakultas.data-akademik.tugas-akhir.filter')
                        </div>
                    </div>
                </div>
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
                                    <th class="text-center align-middle">NO SK<br>(Tanggal SK)</th>
                                    <th class="text-center align-middle">NAMA<br>PROGRAM STUDI</th>
                                    <th class="text-center align-middle">PEMBIMBING</th>
                                    <th class="text-center align-middle">STATUS PEMBIMBING</th>
                                    <th class="text-center align-middle">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle"></td>
                                    <td class="text-center align-middle">
                                        {{$d->anggota_aktivitas_personal ? $d->anggota_aktivitas_personal->nim : '-'}}
                                    </td>
                                    <td class="text-start align-middle" style="width: 15%">
                                        {{$d->anggota_aktivitas_personal ? $d->anggota_aktivitas_personal->nama_mahasiswa : '-'}}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ $d->nama_jenis_aktivitas}}<br>({{$d->konversi ? $d->konversi->kode_mata_kuliah - $d->konversi->nama_mata_kuliah : '-'}})
                                    </td>
                                    <td class="text-center align-middle">
                                        {{$d->sk_tugas}}<br>({{$d->id_tanggal_sk_tugas}})
                                    </td>
                                    <td class="text-center align-middle">
                                        {{$d->nama_prodi}}
                                    </td>
                                    <td class="text-start align-middle">
                                        <ul>
                                            @if($d->bimbing_mahasiswa)
                                                @foreach ($d->bimbing_mahasiswa as $p)
                                                <li>Pembimbing {{$p->pembimbing_ke}} :<br>{{$p->nama_dosen}}</li>
                                                @endforeach
                                            @endif

                                        </ul>
                                    </td>
                                    <td class="text-center align-middle" >
                                        @if($d->bimbing_mahasiswa)
                                            @foreach ($d->bimbing_mahasiswa as $p)
                                                @if ($d->approve_krs == 0 && $p->approved == 0)
                                                    <span class="badge badge-lg badge-danger mb-10">Belum Disetujui</span><br>
                                                @elseif ($p->approved == 0)
                                                    <span class="badge badge-lg badge-warning mb-10">Menunggu konfirmasi Koprodi</span><br>
                                                @elseif ($d->approve_krs == 1 && $p->approved_dosen == 0)
                                                    <span class="badge badge-lg badge-warning mb-10">Menunggu konfirmasi dosen</span><br>
                                                @elseif ($d->approve_krs == 1 && $p->approved_dosen == 2)
                                                    <span class="badge badge-lg badge-danger mb-10">Ditolak dosen pembimbing</span><br>
                                                @elseif ($d->approve_krs == 0 && $p->approved == 1)
                                                    <span class="badge badge-lg badge-warning mb-10">Dibatalkan Dosen PA</span><br>
                                                @else
                                                    <span class="badge badge-lg badge-success mb-10">Disetujui</span><br>
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="row d-flex justify-content-center">
                                            <a href="#" class="btn btn-info btn-sm my-2" title="Detail"
                                                data-bs-toggle="modal" data-bs-target="#detailModal"
                                                onclick="detailFunc({{$d}})">
                                                <i class="fa fa-eye"></i> Detail
                                            </a>
                                        </div>
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
@push('css')
<link rel="stylesheet" href="{{asset('assets/vendor_components/select2/dist/css/select2.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>
    function detailFunc(data) {
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
                text: "Setelah disetujui, pembimbing tidak bisa diubah lagi!",
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
