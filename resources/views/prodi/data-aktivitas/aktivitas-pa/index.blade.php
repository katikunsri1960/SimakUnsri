@extends('layouts.prodi')
@section('title')
Aktivitas Pembimbingan Akademik
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Aktivitas Pembimbingan Akademik</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Aktivitas</li>
                        <li class="breadcrumb-item active" aria-current="page">Aktivitas PA</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>
@include('prodi.data-aktivitas.aktivitas-pa.edit')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
                    {{-- information --}}
                    <div
                        class="alert alert-danger"
                        role="alert"
                    >
                        <h4>Silahkan melengkapi No SK Penugasaan Dosen PA dan Tanggal SK Tugas!</h4>
                    </div>

                </div>
                <div class="box-body">
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
                                <th class="text-center align-middle">AKSI</th>
                             </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $d)
                            <tr>
                                <td class="text-center align-middle">{{$loop->iteration}}</td>
                                <td class="text-center align-middle">{{$d->nidn}}</td>
                                <td class="text-start align-middle">{{$d->nama_dosen}}</td>
                                <td class="text-center align-middle">
                                    <a href="{{route('prodi.data-aktivitas.aktivitas-pa.anggota', $d->id)}}">
                                        {{$d->jumlah_anggota}}
                                    </a>
                                </td>
                                <td class="text-center align-middle">{{$d->sk_tugas}}</td>
                                <td class="text-center align-middle">{{$d->tanggal_sk_tugas}}</td>

                                <td class="text-center align-middle">
                                    <button class="btn btn-warning btn-sm" title="Edit"
                                            onclick="edit({{$d}})"
                                            data-bs-toggle="modal" data-bs-target="#modalEdit">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </td>
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
<script src="{{asset('assets/js/flatpickr/flatpickr.js')}}"></script>
<script>
    $(function() {
        "use strict";

        $('#data').DataTable({
            "stateSave": true,
        });

        flatpickr('#tanggal_sk_tugas', {
            enableTime: false,
            dateFormat: "Y-m-d",
        });

        $('#formEdit').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Apakah anda yakin?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $('#spinner').show();
                    $('#formEdit').unbind('submit').submit();
                }
            });
        });
    });

    function edit(data)
    {
        // reset form
        document.getElementById('formEdit').reset();
        document.getElementById('formEdit').action = '/prodi/data-aktivitas/aktivitas-pa/update/' + data.id;

        $('#nidn').val(data.nidn);
        $('#nama_dosen').val(data.nama_dosen);
        $('#sk_tugas').val(data.sk_tugas);
        $('#tanggal_sk_tugas').val(data.tanggal_sk_tugas);
    }


</script>
@endpush
