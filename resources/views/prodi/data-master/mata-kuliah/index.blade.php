@extends('layouts.prodi')
@section('title')
Mata Kuliah
@endsection
@section('content')
@include('prodi.data-master.mata-kuliah.edit-nama')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Master Mata Kuliah</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Mata Kuliah</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>
@include('swal')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                {{-- <div class="box-header with-border">

                </div> --}}
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100" style="font-size: 11px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Kurikulum</th>
                                    <th class="text-center align-middle">KODE MK</th>
                                    <th class="text-center align-middle">NAMA MK</th>
                                    <th class="text-center align-middle">NAMA MK (ENGLISH)</th>
                                    <th class="text-center align-middle">SKS</th>
                                    <th class="text-center align-middle">PRASYARAT</th>
                                    <th class="text-center align-middle" style="width: 10%">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $number = 1;
                                @endphp
                                @foreach ($data as $a)
                                @if ($a->mata_kuliah)
                                @foreach ($a->mata_kuliah as $d)
                                <tr>
                                    <td class="text-center align-middle">{{ $number++ }}</td>
                                    <td class="text-start align-middle" style="width: 15%">{{$a->nama_kurikulum}}</td>
                                    <td class="text-center align-middle">{{$d->kode_mata_kuliah}}</td>
                                    <td class="text-start align-middle">{{$d->nama_mata_kuliah}}</td>
                                    <td class="text-center align-middle">
                                        @if ($d->nama_mata_kuliah_english == null)
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalEditNama" onclick="editNama({{$d}})">
                                            <i class="fa fa-plus me-1"></i> Tambah Nama MK (EN)
                                        </button>
                                        @else
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditNama" onclick="editNama({{$d}})" title="Click to update">
                                            {{$d->nama_mata_kuliah_english}}  <i class="fa fa-pencil ms-1"></i>
                                        </a>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">{{$d->sks_mata_kuliah}}</td>
                                    <td class="text-start align-middle">
                                        @if ($d->prasyarat_matkul)
                                        <ul>
                                            @foreach ($d->prasyarat_matkul as $item)
                                            <li>{{$item->matkul_prasyarat->kode_mata_kuliah}} -
                                                {{$item->matkul_prasyarat->nama_mata_kuliah}}</li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle text-nowrap">
                                        <div class="row">
                                            @if ($d->prasyarat_matkul->count() > 0)
                                            <div class="col-md-12 mb-2">
                                                <form
                                                    action="{{route('prodi.data-master.mata-kuliah.delete-prasyarat', ['matkul' => $d] )}}"
                                                    method="post" id="delete-prasyarat-{{$d->id}}">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-trash"></i> Hapus Prasyarat
                                                    </button>
                                                </form>
                                            </div>
                                            @else
                                            <div class="col-md-12 mb-2">
                                                <a class="btn btn-primary btn-sm w-100"
                                                    href="{{route('prodi.data-master.mata-kuliah.tambah-prasyarat', ['kurikulum' => $a,'matkul' => $d])}}">
                                                    <i class="fa fa-plus"></i> Prasyarat
                                                </a>
                                            </div>
                                            @endif
                                            <div class="col-md-12">
                                                <a class="btn btn-warning btn-sm w-100"
                                                    href="{{route('prodi.data-master.mata-kuliah.lihat-rps', ['matkul' => $d->id_matkul])}}">
                                                    <i class="fa fa-search"></i> Lihat RPS
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @if ($d->prasyarat_matkul->count() > 0)
                                <script>
                                    $('#delete-prasyarat-{{$d->id}}').submit(function(e){
                                        e.preventDefault();
                                        swal({
                                            title: 'Apakah Anda Yakin?',
                                            type: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'Simpan',
                                            cancelButtonText: 'Batal'
                                        }, function(isConfirm){
                                            if (isConfirm) {
                                                $('#spinner').show();
                                                $('#delete-prasyarat-{{$d->id}}').unbind('submit').submit();
                                            }
                                        });
                                    });
                                </script>
                                @endif
                                @endforeach
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>

<script>
    $(function() {
        "use strict";

        $('#data').DataTable({
            "stateSave": true,
        });
    });

    function editNama(data) {

        document.getElementById('formEditNama').reset();
        document.getElementById('kode_mk').value = data.kode_mata_kuliah;
        document.getElementById('nama_mk_id').value = data.nama_mata_kuliah;
        document.getElementById('nama_mata_kuliah_english').value = data.nama_mata_kuliah_english ?? '';

        document.getElementById('formEditNama').action = "{{route('prodi.data-master.mata-kuliah.edit-nama', ['matkul' => ':id'])}}".replace(':id', data.id);

    }

    $('#formEditNama').submit(function(e) {
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
            }, function(isConfirm) {
                if (isConfirm) {
                    $('#spinner').show();
                    $('#formEditNama').unbind('submit').submit();
                }
            });
        });
</script>
@endpush
