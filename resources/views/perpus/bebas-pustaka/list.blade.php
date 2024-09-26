@extends('layouts.perpus')
@section('title')
BEBAS PUSTAKA - LIST
@endsection
@section('content')
@include('perpus.bebas-pustaka.filter')
<section class="content">
    <div class="row align-items-end">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3"><img
                                src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h2>List Bebas Pustaka Mahasiswa</h2>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="d-flex justify-content-end">
                        <div class="d-flex justify-content-start">
                            <!-- Modal trigger button -->
                            <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                                data-bs-target="#filter-button">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                            <span class="divider-line mx-1"></span>
                            <a href="{{route('perpus.bebas-pustaka.list')}}" class="btn btn-warning waves-effect waves-light" >
                                <i class="fa fa-rotate"></i> Reset Filter
                            </a>
                        </div>
                    </div>
                </div>
                <div class="box-body py-10">
                    <div class="table-responsive">
                        <table id="data" class="table  table-hover margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Program Studi</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">Nama</th>
                                    <th class="text-center align-middle">File Bebas Pustaka</th>
                                    <th class="text-center align-middle">Link Repo</th>
                                    <th class="text-center align-middle">Verified By</th>
                                    <th class="text-center align-middle">Act</th>
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
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>
$(function () {


        var table = $('#data').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('perpus.bebas-pustaka.list-data') }}',
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
                    }
                },
                {
                    data: 'null',
                    name: 'null',
                    class: 'text-start',
                    searchable: false,
                    orderData: [0],

                    render: function(data, type, row) {
                        return row.nama_jenjang_pendidikan + ' - ' + row.nama_program_studi;
                    }
                },
                { data: 'nim', name: 'nim', class: 'text-center', searchable: true, orderData: [1] },
                { data: 'nama_mahasiswa', name: 'nama_mahasiswa', class: 'text-start', searchable: true, orderData: [2] },
                {
                    data: 'file_bebas_pustaka',
                    name: 'file_bebas_pustaka',
                    class: 'text-center',
                    searchable: false,
                    sortable: false,
                    render: function(data, type, row) {
                        return '<a href="{{ asset('storage') }}/' + data + '" target="_blank" class="btn btn-primary btn-outline btn-sm"><i class="fa fa-file pe-1"></i> View</a>';
                    }
                },
                {
                    data: 'link_repo',
                    name: 'link_repo',
                    class: 'text-start text-no-wrap',
                    searchable: false,
                    sortable: false,
                    render: function(data, type, row) {
                        return '<a href="' + data + '" target="_blank" class="btn btn-primary btn-outline btn-sm"><i class="fa fa-link pe-1"></i> Link Repo </a>';
                    }
                },
                { data: 'verifikator', name: 'verifikator', class: 'text-center', searchable: true,  sortable: false,},
                {
                    data: 'id',
                    name: 'id',
                    class: 'text-center',
                    searchable: false,
                    sortable: false,
                    render: function(data, type, row) {
                        return `
                            <form id="delete-form-${data}" action="{{ route('perpus.bebas-pustaka.delete', '') }}/${data}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-button" data-id="${data}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        `;
                    }
                }
            ],
            drawCallback: function() {
                // Attach event listeners to delete buttons after table draw
                document.querySelectorAll('.delete-button').forEach(function(button) {
                    button.addEventListener('click', function(event) {
                        event.preventDefault();
                        var dataId = this.getAttribute('data-id');
                        var form = document.getElementById('delete-form-' + dataId);

                        swal({
                            title: 'Hapus Data',
                            text: "Apakah anda yakin ingin menghapus data ini?",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal'
                        }, function(isConfirm){
                            if (isConfirm) {
                                $('#spinner').show();
                                form.submit();
                            }
                        });
                    });
                });
            }
        });

    });
</script>
@endpush
