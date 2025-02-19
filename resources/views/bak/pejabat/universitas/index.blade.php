@extends('layouts.bak')
@section('title')
Pejabat Universitas
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Pejabat Universitas</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a>
                        </li>
                        <li class="breadcrumb-item">Pejabat</li>
                        <li class="breadcrumb-item active" aria-current="page">Universitas</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-body">
                    @include('bak.pejabat.universitas.edit')
                    <div class="table-responsive">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">JABATAN</th>
                                    <th class="text-center align-middle">NAMA</th>
                                    <th class="text-center align-middle">NIP</th>
                                    <th class="text-center align-middle">ACT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jabatan as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-start align-middle">{{$d->nama}}</td>
                                    @if ($d->pejabat)
                                    <td class="text-start align-middle">{{$d->pejabat->gelar_depan}}
                                        {{$d->pejabat->nama}}, {{$d->pejabat->gelar_belakang}}</td>
                                    <td class="text-center align-middle">{{$d->pejabat->nip}} </td>
                                    <td class="text-center align-middle">
                                        <div class="row px-3">
                                            <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal"
                                            data-bs-target="#editModal" onclick="edit({{$d}})"><i class="fa fa-pencil"></i> Edit Data</button>
                                        </div>
                                    </td>
                                    @else
                                    <td class="text-center align-middle" colspan="3">
                                        <div class="row px-3">
                                            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                                            data-bs-target="#editModal" onclick="edit({{$d}})"><i class="fa fa-plus"></i> Isi Data</button>
                                        </div>
                                    </td>
                                    @endif
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
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>

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

    $('#data').DataTable();

    function edit(data) {
        document.getElementById('editForm').reset();

        $('#jabatan_id').val(data.id);
        $('#jabatan').val(data.nama);

        document.getElementById('gelar_depan').value = data.pejabat ? data.pejabat.gelar_depan : '';
        document.getElementById('nama').value = data.pejabat ? data.pejabat.nama : '';
        document.getElementById('gelar_belakang').value = data.pejabat ? data.pejabat.gelar_belakang : '';
        document.getElementById('nip').value = data.pejabat ? data.pejabat.nip : '';

    }





</script>
@endpush
