@extends('layouts.bak')
@section('title')
Gelar Lulusan
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Gelar Lulusan</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Gelar Lulusan</li>
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
                    @include('bak.gelar-lulusan.edit')
                    <div class="table-responsive">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Fakultas</th>
                                    <th class="text-center align-middle">Prodi</th>
                                    <th class="text-center align-middle">Gelar</th>
                                    <th class="text-center align-middle">ACT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle"></td>
                                    <td class="text-start align-middle">{{$d->fakultas->nama_fakultas}}</td>
                                    <td class="text-start align-middle">{{$d->nama_jenjang_pendidikan}} - {{$d->nama_program_studi}}</td>
                                    <td class="text-center align-middle">
                                        @if ($d->gelar_lulusan)
                                        <ul>
                                            @foreach ($d->gelar_lulusan as $gelar)
                                            <li>{{$gelar->gelar_panjang}} ({{$gelar->gelar}})</li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="row px-3">
                                            <a class="btn btn-warning btn-sm" href="{{route('bak.gelar-lulusan.edit', ['prodi' => $d->id])}}"><i class="fa fa-pencil"></i></a>
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

    $('#data').DataTable({
        paging: false,
        scrollY: "400px",
        columnDefs: [{
            searchable: false,
            orderable: false,
            targets: 0
        }],
    }).on('order.dt search.dt', function () {
        $('#data').DataTable().column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();


</script>
@endpush
