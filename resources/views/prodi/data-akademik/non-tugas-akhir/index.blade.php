@extends('layouts.prodi')
@section('title')
Aktivitas Non @if (Auth::user()->fk->nama_jenjang_pendidikan == 'S1')
Skripsi
@elseif (Auth::user()->fk->nama_jenjang_pendidikan == 'S2')
Tesis
@elseif (Auth::user()->fk->nama_jenjang_pendidikan == 'S3')
Disertasi
@else
Tugas Akhir
@endif
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">
                Aktivitas Non @if (Auth::user()->fk->nama_jenjang_pendidikan == 'S1')
                Skripsi
                @elseif (Auth::user()->fk->nama_jenjang_pendidikan == 'S2')
                Tesis
                @elseif (Auth::user()->fk->nama_jenjang_pendidikan == 'S3')
                Disertasi
                @else
                Tugas Akhir
                @endif
            </h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Akademik</li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Aktivitas Non @if (Auth::user()->fk->nama_jenjang_pendidikan == 'S1')
                            Skripsi
                            @elseif (Auth::user()->fk->nama_jenjang_pendidikan == 'S2')
                            Tesis
                            @elseif (Auth::user()->fk->nama_jenjang_pendidikan == 'S3')
                            Disertasi
                            @else
                            Tugas Akhir
                            @endif
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
@include('prodi.data-akademik.non-tugas-akhir.detail')
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
                                    <th class="text-center align-middle">JENIS AKTIVITAS</th>
                                    <th class="text-center align-middle">NAMA AKTIVITAS</th>
                                    <th class="text-center align-middle">NO SK<br>(Tanggal SK)</th>
                                    <th class="text-center align-middle">Pembimbing</th>
                                    <th class="text-center align-middle">Status Pembimbing</th>
                                    <th class="text-center align-middle">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle"></td>
                                    <td class="text-center align-middle">
                                        {{$d->anggota_aktivitas_personal ? $d->anggota_aktivitas_personal->nim : "-"}}
                                    </td>
                                    <td class="text-start align-middle" style="width: 15%">
                                        {{$d->anggota_aktivitas_personal ? $d->anggota_aktivitas_personal->nama_mahasiswa : "-"}}
                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($d->id_jenis_aktivitas == '5' || $d->id_jenis_aktivitas == '6')
                                            <div class="row my-10">
                                                <span class="badge badge-lg badge-warning">Aktivitas Konversi</span>
                                            </div>
                                            @if($d->count_nilai > 0)
                                                <div class="row">
                                                    <span class="badge badge-lg badge-success">Sudah di Nilai</span>
                                                </div>
                                            @endif
                                        @else
                                            <div class="row my-10">
                                                <span class="badge badge-lg badge-success me-10">MBKM ({{$d->sks_aktivitas}} SKS)</span>
                                            </div>
                                            @if($d->count_nilai > 0)
                                                <div class="row">
                                                    <span class="badge badge-lg badge-success">Sudah di Nilai</span>
                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">{{$d->nama_jenis_aktivitas}}</td>
                                    <td class="text-center align-middle">
                                        {{$d->sk_tugas}}<br>({{$d->id_tanggal_sk_tugas}})
                                    </td>
                                    <td class="text-start align-middle">
                                        <ul>
                                            @foreach ($d->bimbing_mahasiswa as $p)
                                            <li>Pembimbing {{$p->pembimbing_ke}} :<br>{{$p->nama_dosen}}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($d->approved > 0)
                                            <span class="badge badge-lg badge-danger">Belum Disetujui</span>
                                        @elseif ($d->approved == 0 && $d->approved_dosen > 0)
                                            <span class="badge badge-lg badge-warning">Menunggu konfirmasi dosen</span>
                                        @elseif ($d->approved == 0 && $d->decline_dosen > 0)
                                            <span class="badge badge-lg badge-danger">Bimbingan dibatalkan dosen</span>
                                        @else
                                            <span class="badge badge-lg badge-success">Approved</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="row d-flex justify-content-center">
                                            @if ($d->approved > 0 || $d->decline_dosen > 0)
                                            <form
                                                action="{{route('prodi.data-akademik.non-tugas-akhir.approve-pembimbing', $d)}}"
                                                method="post" id="approveForm{{$d->id}}" data-id="{{$d->id}}"
                                                class="approve-class">
                                                @csrf
                                                <div class="row">
                                                    <button type="submit" class="btn btn-sm my-2 btn-success ">Approve
                                                        pembimbing</button>
                                                </div>
                                            </form>
                                            @endif
                                            @if(($d->approved == 0 && $d->approved_dosen == 0) && ($d->id_jenis_aktivitas != '5' && $d->id_jenis_aktivitas != '6'))
                                                @if((strtotime(date('Y-m-d')) < strtotime($pengisian_nilai->mulai_isi_nilai)) || (strtotime(date('Y-m-d')) > strtotime($pengisian_nilai->batas_isi_nilai)))
                                                    <button type="submit" class="btn btn-primary btn-sm my-2" title="Nilai Konversi" disabled>
                                                        <i class="fa fa-pencil-square-o"></i> Nilai Konversi
                                                    </button>
                                                @else
                                                    <a href="{{ route('prodi.data-akademik.non-tugas-akhir.nilai-konversi', $d->id_aktivitas) }}" class="btn btn-success btn-sm my-2" title="Nilai Konversi">
                                                        <i class="fa fa-pencil-square-o"></i> Nilai Konversi
                                                    </a>
                                                @endif
                                            @endif
                                            <a href="{{route('prodi.data-akademik.non-tugas-akhir.edit-detail', $d->id_aktivitas)}}" class="btn btn-warning btn-sm my-2" title="Edit"><i class="fa fa-edit"></i> Edit</a>
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
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
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
            "stateSave": true,
            "order": [[ 5, "desc" ]],
            "dom": '<"top"lf<"dt-center"B>>rt<"bottom"ip><"clear">', // Place buttons (B) at the top center
            "buttons": [
                {
                    "extend": 'excelHtml5',
                    "text": 'Download Excel',
                    "className": 'btn btn-primary mt-10'
                }
            ],
            "lengthMenu": [10, 25, 50, 75, 100], // Include the length changing control
            "pageLength": 10, // Set the default number of rows to display
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
