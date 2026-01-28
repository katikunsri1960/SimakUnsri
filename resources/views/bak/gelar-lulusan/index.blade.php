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
                    <div class="box-header with-border">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-success waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#createModal" onclick="store()><i class="fa fa-plus"></i> Tambah Data</button>
                        </div>
                    </div>
                    @include('bak.gelar-lulusan.create')
                    @include('bak.gelar-lulusan.edit')
                    <div class="table-responsive">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Fakultas</th>
                                    <th class="text-center align-middle">Prodi</th>
                                    <th class="text-center align-middle">Gelar Panjang</th>
                                    <th class="text-center align-middle">Gelar Singkatan</th>
                                    <th class="text-center align-middle">ACT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                    <td class="text-start align-middle">{{$d->prodi->fakultas ? $d->prodi->fakultas->nama_fakultas : 'Belum di Assign'}}</td>
                                    <td class="text-start align-middle">{{$d->prodi->nama_jenjang_pendidikan}} - {{$d->prodi->nama_program_studi}}</td>
                                    <td class="text-center align-middle">{{$d ? $d->gelar_panjang : ''}}</td>
                                    <td class="text-center align-middle">{{$d ? $d->gelar : ''}}</td>
                                    <td class="text-center align-middle">
                                        <div class="row px-3">
                                            <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal"
                                            data-bs-target="#editModal" onclick="edit({{$d}})"><i class="fa fa-pencil"></i></button>
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
    $(document).ready(function () {

        $('#createModal').on('shown.bs.modal', function () {

            if ($('#id_prodi').hasClass("select2-hidden-accessible")) {
                $('#id_prodi').select2('destroy');
            }

            $('#id_prodi').select2({
                dropdownParent: $('#createModal'),
                placeholder: '-- Pilih Program Studi --',
                minimumInputLength: 3,
                width: '100%',
                ajax: {
                    url: "{{ route('bak.gelar-lulusan.get-prodi') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(item => ({
                                id: item.id_prodi,
                                text: item.nama_jenjang_pendidikan + ' - ' + item.nama_program_studi
                            }))
                        };
                    }
                }
            });

        });
    });


    $('#createForm').submit(function(e){
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
                $('#createForm').unbind('submit').submit();
                $('#spinner').show();
            }
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

    const table = $('#data').DataTable({
        paging: true,
        pageLength: 25,
        scrollY: "600px",
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0 // kolom nomor
            }
        ],
        order: [[1, 'asc']] // optional: default order kolom ke-2
    });

    table.on('order.dt search.dt', function () {
        table
            .column(0, { search: 'applied', order: 'applied' })
            .nodes()
            .each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
    }).draw();


    function edit(data) {
        document.getElementById('editForm').reset();

        $('#id_gelar').val(data.id);

        document.getElementById('fakultas').value = data.prodi.fakultas ? data.prodi.fakultas.nama_fakultas : '';
        document.getElementById('prodi').value = data.prodi.nama_jenjang_pendidikan + " - " + data.prodi.nama_program_studi;
        document.getElementById('gelar_panjang').value = data ? data.gelar_panjang : '';
        document.getElementById('gelar').value = data ? data.gelar : '';

    }

    function store(data) {
        document.getElementById('createForm').reset();

        $('#id_prodi').val(data.id_prodi);
        document.getElementById('gelar_panjang_new').value = data ? data.gelar_panjang : '';
        document.getElementById('gelar_new').value = data ? data.gelar : '';

    }

</script>
@endpush
