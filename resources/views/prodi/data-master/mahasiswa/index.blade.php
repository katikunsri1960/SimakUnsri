@extends('layouts.prodi')
@section('title')
Mahasiswa Prodi
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Master Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Mahasiswa</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
@include('swal')
@include('prodi.data-master.mahasiswa.set-pa')
@include('prodi.data-master.mahasiswa.set-kurikulum')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border d-flex justify-content-between">
                    <div class="d-flex justify-content-start">
                        <!-- Modal trigger button -->
                        <button type="button" class="btn btn-secondary waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#filter-button">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                        <span class="divider-line mx-1"></span>
                        <a href="{{route('prodi.data-master.mahasiswa')}}" class="btn btn-warning waves-effect waves-light"><i class="fa fa-refresh"></i> Reset Filter</a>
                        @include('prodi.data-master.mahasiswa.filter')
                    </div>
                    <div class="d-flex justify-content-end">
                        <span class="divider-line mx-1"></span>
                        <button class="btn btn-success waves-effect waves-light" href="#" data-bs-toggle="modal" data-bs-target="#setAngkatanModal"><i class="fa fa-plus"></i>
                            Set Kurikulum Angkatan</button>
                        @include('prodi.data-master.mahasiswa.set-angkatan')
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100 table-bordered" style="font-size: 11px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle" style="width: 5%">No</th>
                                    <th class="text-center align-middle">FOTO</th>
                                    <th class="text-center align-middle">AKT</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">NAMA</th>
                                    <th class="text-center align-middle">KURIKULUM</th>
                                    <th class="text-center align-middle">DOSEN P.A.</th>
                                    <th class="text-center align-middle">STATUS</th>
                                    <th class="text-center align-middle">STATUS<br>PEMBAYARAN</th>
                                    <th class="text-center align-middle">AKSI</th>
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
<link rel="stylesheet" href="{{asset('assets/vendor_components/select2/dist/css/select2.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>



    $(function() {
        "use strict";



        $('#data').DataTable({
            // dom: 'Bfrtip',
            // buttons: [
            //     'copy', 'csv', 'excel', 'pdf', 'print'
            // ],
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('prodi.data-master.mahasiswa.data')}}',
                type: 'GET',
                data: function (d) {
                    d.angkatan = $('#angkatanFilter').val();
                    d.status = $('#statusFilter').val();
                },
                error: function (xhr, error, thrown) {
                    alert('An error occurred. ' + thrown);
                }
            },
            order: [[2, 'desc']],
            columns: [
                {
                    data: null,
                    searchable: false,
                    class: "text-center align-middle",
                    sortable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                }},
                {
                    data: null,
                    name: 'image',
                    render: function(data, type, row) {
                        var imagePath = '{{ asset('storage') }}' + '/' + data.angkatan + '/' + data.nim + '.jpg';
                        return '<img class="rounded20 bg-light img-fluid w-80" src="' + imagePath + '" alt="" onerror="this.onerror=null;this.src=\'{{ asset('images/images/avatar/avatar-15.png') }}\';">';
                    },
                    orderable: false,
                    searchable: false
                },
                {data: 'angkatan', name: 'angkatan', class: "text-center align-middle", searchable: true, orderData: [0]},
                {data: 'nim', name: 'nim', class: 'text-center', searchable: true, orderData: [1]},
                {data: 'nama_mahasiswa', name: 'nama_mahasiswa', class: 'text-start', searchable: true, orderData: [2]},
                {
                    data: 'kurikulum.nama_kurikulum',
                    name: 'kurikulum.nama_kurikulum',
                    class: 'text-start',
                    searchable: false,
                    sortable:false,
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'pembimbing_akademik.nama_dosen',
                    name: 'pembimbing_akademik.nama_dosen',
                    class: 'text-start',
                    searchable: false,
                    sortable:false,
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'keterangan_keluar',
                    name: 'keterangan_keluar',
                    searchable: true,
                    class: "text-center align-middle",
                    sortable: false,
                    render: function(data, type, row) {
                        return data ? data : 'Aktif';
                    }
                },
                {data: null, searchable: false, sortable:false, class:"text-center align-middle", render: function(data, type, row, meta) {
                        return "";
                }},
                {
                    data: null,
                    render: function(data, type, row) {
                        var buttonClass = data.dosen_pa ? 'warning' : 'primary';
                        var buttonText = data.dosen_pa ? 'Ubah' : 'Assign';
                        var buttonKurClass = data.id_kurikulum ? 'secondary' : 'primary';
                        var buttonKurText = data.id_kurikulum ? 'Ubah' : 'Set';
                        var urlUsept = "/prodi/data-master/mahasiswa/nilai-usept/" + data.id_registrasi_mahasiswa;
                        var jsonData = encodeURIComponent(JSON.stringify(data).replace(/'/g, '&#39;'));
                        return `
                        <div class="row justify-content-center px-2">
                            <button class="m-2 btn btn-sm btn-rounded btn-${buttonClass} text-nowrap"
                                    data-bs-toggle="modal"
                                    data-bs-target="#assignDosenPa"
                                    onclick="setDosenPa(decodeURIComponent('${jsonData}'), ${data.id})">
                                <i class="fa fa-user-graduate"></i> ${buttonText} PA
                            </button>
                            <button class="m-2 btn btn-sm btn-rounded btn-${buttonKurClass} text-nowrap"
                                    data-bs-toggle="modal"
                                    data-bs-target="#setKurilukumModal"
                                    onclick="setKurikulum(decodeURIComponent('${jsonData}'), ${data.id})">
                                <i class="fa fa-plus"></i> ${buttonKurText} Kurikulum
                            </button>
                            <a href="${urlUsept}" class="m-2 btn btn-sm btn-rounded btn-success text-nowrap">
                                <i class="fa fa-list-ol"></i> Nilai Usept
                            </a>
                        </div>
                        `;
                    },
                    orderable: false,
                    searchable: false
                }
            ],
        });


    });

    function setDosenPa(data, id) {
        data = data.replace(/&#39;/g, "'");
        // console.log('setDosenPa called with data:', data, 'and id:', id);
        $('#edit_id_dosen').val(data.dosen_pa).trigger('change');
        // Populate other fields...
        document.getElementById('editForm').action = '/prodi/data-master/mahasiswa/set-pa/' + id;
    }

    function setKurikulum(data, id){
        data = data.replace(/&#39;/g, "'");
        parserData = JSON.parse(data);

        // console.log('setKurikulum called with data:', data, 'and id:', id);
        $('#edit_set_id_kurikulum').val(parserData.id_kurikulum).trigger('change');
        $('#edit_nim').val(parserData.nim);
        $('#edit_nama_mahasiswa').val(parserData.nama_mahasiswa);
        // Populate other fields...
        document.getElementById('kurForm').action = '/prodi/data-master/mahasiswa/set-kurikulum/' + id;
    }

    $('#kurForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        var form = $(this);
        var url = form.attr('action');
        var formData = form.serialize();

        // Show confirmation dialog
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
                // If confirmed, proceed with AJAX request
                $('#setKurilukumModal').modal('hide');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    success: function(response) {
                    
                        if (response.status === 'success') {
                            $('#data').DataTable().draw(false); // Redraw DataTable without resetting pagination
                            alert(response.message);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Gagal menyimpan data!!. ' + xhr.responseJSON.message);
                    }
                });
            }
        });
    });
</script>
@endpush
