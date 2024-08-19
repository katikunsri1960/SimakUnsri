@extends('layouts.universitas')
@section('title')
BEASISWA
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Daftar Beasiswa Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Beasiswa Mahasiswa</li>
                        <li class="breadcrumb-item active" aria-current="page">Daftar</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
@include('swal')
@include('universitas.beasiswa.create')
{{-- @include('universitas.beasiswa.edit') --}}
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-success waves-effect waves-light" data-bs-toggle="modal"
                        data-bs-target="#createModal"><i class="fa fa-plus"></i> Tambah Data</button>
                        <span class="divider-line mx-1"></span>
                        {{-- <form action="{{route('univ.mahasiswa.sync-prestasi')}}" method="get" id="sync-form-2">
                            <button class="btn btn-success waves-effect waves-light" type="submit"><i class="fa fa-refresh"></i> Sinkronisasi Prestasi</button>
                        </form> --}}
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table  table-hover margin-top-10 w-p100" style="font-size: 10pt">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Program Studi</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">Nama</th>
                                    <th class="text-center align-middle">Angkatan</th>
                                    <th class="text-center align-middle">Jenis Beasiswa</th>
                                    <th class="text-center align-middle">Pembiayaan</th>
                                    <th class="text-center align-middle">Tanggal Mulai</th>
                                    <th class="text-center align-middle">Tanggal Selesai</th>
                                    <th class="text-center align-middle">Action</th>
                                </tr>
                            </thead>
                          <tbody>

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
<link rel="stylesheet" href="{{asset('assets/js/flatpickr/flatpickr.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/js/flatpickr/flatpickr.js')}}"></script>
<script>
    function deleteRuang(id) {
        swal({
            title: 'Delete Data',
            text: "Apakah anda yakin ingin menghapus data?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                document.getElementById('delete-form-' + id).submit();
                $('#spinner').show();
            }
        });
    }

    function editRuang(data, id) {
        data = data.replace(/&#39;/g, "'");
        console.log(data);
        document.getElementById('edit_mahasiswa').value = data.nim;
        // Populate other fields...
        document.getElementById('editForm').action = '/universitas/beasiswa/update/' + id;
    }

     $(document).ready(function() {
        // "use strict";
        flatpickr("#tanggal_mulai_beasiswa", {
            dateFormat: "d-m-Y",
        });

        flatpickr("#tanggal_akhir_beasiswa", {
            dateFormat: "d-m-Y",
        });

        $('#data').DataTable({
            // dom: 'Bfrtip',
            // buttons: [
            //     'copy', 'csv', 'excel', 'pdf', 'print'
            // ],
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('univ.beasiswa.data')}}',
                type: 'GET',
                data: function (d) {
                    d.prodi = $('#prodi').val();
                },
                error: function (xhr, error, thrown) {
                    alert('An error occurred. ' + thrown);
                }
            },
            columns: [
                {
                    data: null,
                    searchable: false,
                    class: "text-center align-middle",
                    sortable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                }},
                {data: 'nama_program_studi', name: 'nama_program_studi', class: 'text-start', searchable: true, orderData: [0]},
                {data: 'nim', name: 'nim', class: 'text-center', searchable: true, orderData: [1]},
                {data: 'nama_mahasiswa', name: 'nama_mahasiswa', class: 'text-start', searchable: true, orderData: [2]},
                {data: 'mahasiswa.angkatan', name: 'mahasiswa.angkatan', class: "text-center align-middle", searchable: true, orderData: [3]},
                {data: 'jenis_beasiswa.nama_jenis_beasiswa', name: 'jenis_beasiswa.nama_jenis_beasiswa', class: 'text-center', searchable: false, sortable:false},
                {data: 'nama_pembiayaan', name: 'nama_pembiayaan', class: 'text-center', searchable: false, sortable:false},
                {data: 'id_tanggal_mulai_beasiswa', name: 'id_tanggal_mulai_beasiswa', searchable: true, orderData: [4]},
                {data: 'id_tanggal_akhir_beasiswa', name: 'id_tanggal_akhir_beasiswa', searchable: true, orderData: [5]},
                {data: 'null', searchable: false, class:"text-center align-middle", sortable:false,
                    render: function(data, type, row) {
                        return `
                            <button type="button" class="btn bg-danger my-2 btn-sm" title="Delete Data" onclick="deleteRuang(${row.id})">
                                <i class="fa fa-trash"><span class="path1"></span><span class="path2"></span></i>
                            </button>
                            <form action="{{ route('univ.beasiswa.delete', '') }}/${row.id}" method="POST" id="delete-form-${row.id}">
                                @csrf
                                @method('delete')
                            </form>
                        `;
                    }
                },

            ],
        });



        $("#id_registrasi_mahasiswa").select2({
            placeholder : '-- Masukan NIM / Nama Mahasiswa --',
            dropdownParent: $('#createModal'),
            width: '100%',
            minimumInputLength: 3,
            ajax: {
                url: "{{route('univ.pengaturan.akun.get-mahasiswa')}}",
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function (data) {
                    // console.log(data);
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: "("+item.nim+") "+item.nama_mahasiswa,
                                id: item.id_registrasi_mahasiswa
                            }
                        })
                    };
                },
            }
        });

        $('#storeForm').submit(function(e){
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
                    $('#storeForm').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });



    });
</script>
@endpush
