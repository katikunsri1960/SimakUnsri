@extends('layouts.prodi')
@section('title')
Ruang Perkuliahan
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Master Ruang Perkuliahan</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Ruang Perkuliahan</li>
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
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-success waves-effect waves-light" data-bs-toggle="modal"
                        data-bs-target="#tambahRuangKuliah"><i class="fa fa-plus"></i> Tambah Ruang Kuliah</button>
                    </div>
                </div>
                @include('prodi.data-master.ruang-perkuliahan.create')
                @include('prodi.data-master.ruang-perkuliahan.update')
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">NAMA RUANGAN</th>
                                <th class="text-center align-middle">LOKASI</th>
                                <th class="text-center align-middle">AKSI</th>
                             </tr>
                          </thead>
                          <tbody>
                            @php
                                $row = 0;
                            @endphp
                            @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$row = $row + 1}}</td>
                                    <td class="text-center align-middle">{{$d->nama_ruang}}</td>
                                    <td class="text-center align-middle">{{$d->lokasi}}</td>
                                    <td class="text-center align-middle">
                                        <button class="btn btn-rounded bg-warning" title="Edit Data" data-bs-toggle="modal" data-bs-target="#editRuangKuliah" onclick="editRuang({{$d}}, {{$d->id}})"> 
                                            <i class="fa fa-pencil-square-o"><span class="path1"></span><span class="path2"></span></i>
                                        </button>
                                        <form action="{{route('prodi.data-master.ruang-perkuliahan.delete', $d)}}" method="POST" id="delete-ruang-{{$d->id}}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-rounded bg-danger" title="Delete Data">
                                                <i class="fa fa-trash"><span class="path1"></span><span class="path2"></span></i>
                                            </button>
                                        </form>
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
    function editRuang(data, id) {
        document.getElementById('edit_nama_ruang').value = data.nama_ruang;
        document.getElementById('edit_lokasi').value = data.lokasi;
        // Populate other fields...
        document.getElementById('edit-ruang').action = '/prodi/data-master/ruang-perkuliahan/' + id + '/update';
    }

    $('#tambah-ruang').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Pembuatan Ruang Kuliah',
            text: "Apakah anda yakin ingin menambahkan ruang?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#tambah-ruang').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });

    $('#edit-ruang').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Edit Data Ruang Kuliah',
            text: "Apakah anda yakin ingin merubah ruang?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#edit-ruang').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });

    $('#delete-ruang-{{$d->id}}').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Delete Data Ruang Kuliah',
            text: "Apakah anda yakin ingin menghapus ruang?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#delete-ruang-{{$d->id}}').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });
</script>
@endpush
