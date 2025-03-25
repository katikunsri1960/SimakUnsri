@extends('layouts.universitas')
@section('title')
Monev Status Mahasiswa
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Monev Status Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Monitoring</li>
                        <li class="breadcrumb-item active" aria-current="page">Status Mahasiswa</li>
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
                <div class="box-body">
                    <div class="mb-5">
                        <form action="{{route('univ.monitoring.status-mahasiswa.generate-data')}}" method="post" id="postForm">
                            @csrf
                            <button id="start-process" class="btn btn-primary">Mulai Proses</button>
                        </form>
                    </div>
                    {{-- <div class="progress mt-3">
                        <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;"
                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div> --}}


                    <div class="table-responsive mt-5">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Nama Fakultas</th>
                                    <th class="text-center align-middle">Nama Program Studi</th>
                                    <th class="text-center align-middle">Jumlah Mahasiswa Lewat Semester</th>

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
<script src="{{asset('assets/js/confirmSwal.js')}}"></script>
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>

    confirmSubmit('postForm');

    $(document).ready(function(){
        $('#data').DataTable();

    });

</script>
@endpush
