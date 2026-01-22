@extends('layouts.prodi')
@section('title')
Gelar Dosen
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Gelar Dosen</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Master</li>
                        <li class="breadcrumb-item" aria-current="page">Dosen</li>
                        <li class="breadcrumb-item active" aria-current="page">Gelar Dosen</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
                </div>
                <div class="box-body">
                    @include('prodi.data-master.dosen.edit')
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">NAMA</th>
                                <th class="text-center align-middle">NAMA & GELAR</th>
                                <th class="text-center align-middle">NIDK/NIDN</th>
                                <th class="text-center align-middle">NUPTK</th>
                                <th class="text-center align-middle">E-MAIL</th>
                                <th class="text-center align-middle">HOMEBASE</th>
                             </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-start align-middle">{{$d->nama_dosen}}</td>
                                    <td class="text-start align-middle">
                                        <span style="display:block;">
                                            @php
                                                $gelar = $d->gelar;

                                                $gelarDepan = $gelar
                                                    ? collect([
                                                        $gelar->gelar_depan_gb,
                                                        $gelar->gelar_depan_s3,
                                                        $gelar->gelar_depan_s2,
                                                        $gelar->gelar_depan_s1,
                                                    ])->filter()->implode(' ')
                                                    : null;

                                                $gelarBelakang = $gelar
                                                    ? collect([
                                                        $gelar->gelar_belakang_s1,
                                                        $gelar->gelar_belakang_s2,
                                                        $gelar->gelar_belakang_s3,
                                                    ])->filter()->implode(' ')
                                                    : null;
                                            @endphp

                                            <div class="row px-3">
                                                <div class="col-md-8">
                                                    {{ trim(($gelarDepan ? $gelarDepan.' ' : '').$d->nama_dosen) }}@if($gelarBelakang), {{ $gelarBelakang }}@endif
                                                </div>
                                                <div class="col-md-4">
                                                    <button class="btn btn-warning btn-sm" type="button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editModal"
                                                        onclick='edit(@json($d))'>
                                                        <i class="fa fa-pencil"></i> Edit Gelar
                                                    </button>
                                                </div>
                                            </div>
                                        </span>
                                    </td>
                                    <!-- <td class="text-center align-middle">
                                        @if($d->gelar)
                                            {{$d->gelar->gelar_depan_gb}}
                                            {{$d->gelar->gelar_depan_s3}}
                                            {{$d->gelar->gelar_depan_s2}}
                                            {{$d->gelar->gelar_depan_s1}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($d->gelar)
                                            {{$d->gelar->gelar_belakang_s1}}
                                            {{$d->gelar->gelar_belakang_s2}}
                                            {{$d->gelar->gelar_belakang_s3}}
                                        @else
                                            -
                                        @endif
                                    </td> -->
                                    <td class="text-center align-middle">{{$d->nidn ?? '-'}}</td>
                                    <td class="text-center align-middle">{{$d->nuptk ?? '-'}}</td>
                                    <td class="text-start align-middle">{{$d->email ?? '-'}}</td>
                                    <td class="text-center align-middle">
                                        {{ $d->penugasan_terbaru->a_sp_homebase === '1' ? '√' : '-' }}
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
    $(function() {
        "use strict";

        $('#data').DataTable({
            paging: false,
            ordering: true,
            searching: true,
            scrollCollapse: false,
            // scrollX: true,
            scrollY: window.innerHeight * 0.6 + "px",
        });
    });

    $('#editForm').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Simpan Data',
            text: "Apakah anda yakin ingin menyimpan data?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#editForm').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });

    function edit(data) {
        document.getElementById('editForm').reset();

        document.getElementById('id_dosen').value = data.id_dosen;
        document.getElementById('nama').value = data.nama_dosen ?? '';

        const gelar = data.gelar ?? {};

        document.getElementById('gelar_depan_s1').value = gelar.gelar_depan_s1 ?? '';
        document.getElementById('gelar_depan_s2').value = gelar.gelar_depan_s2 ?? '';
        document.getElementById('gelar_depan_s3').value = gelar.gelar_depan_s3 ?? '';
        document.getElementById('gelar_depan_gb').value = gelar.gelar_depan_gb ?? '';

        document.getElementById('gelar_belakang_s1').value = gelar.gelar_belakang_s1 ?? '';
        document.getElementById('gelar_belakang_s2').value = gelar.gelar_belakang_s2 ?? '';
        document.getElementById('gelar_belakang_s3').value = gelar.gelar_belakang_s3 ?? '';
    }

</script>

@endpush
