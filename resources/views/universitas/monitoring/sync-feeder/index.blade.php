@extends('layouts.universitas')
@section('title')
Sync Feeder
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Daftar Sync Feeder</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Monitoring</li>
                        <li class="breadcrumb-item active" aria-current="page">Sync Feeder</li>
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
                    <div class="d-flex justify-content-start">
                        <!-- Modal trigger button -->
                        <button type="button" class="btn btn-success waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#filter-button">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                        @include('universitas.monitoring.kelulusan.filter')
                        <span class="divider-line mx-1"></span>
                        <a href="{{route('univ.monitoring.lulus-do')}}" class="btn btn-warning waves-effect waves-light" >
                            <i class="fa fa-refresh"></i> Reset Filter
                        </a>
                    </div>
                </div> --}}
                <div class="box-body">
                    <div id="batch-list"></div>
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
    function fetchBatches() {
        fetch('/universitas/monitoring/batch-job/data')
            .then(res => res.json())
            .then(data => {
                let html = '';
                if (data.length === 0) {
                    html = '<p>Tidak ada batch yang sedang berjalan.</p>';
                } else {
                    data.forEach(batch => {
                        html += `
                            <div class="mb-4">
                                <div><strong>${batch.name}</strong> (${batch.status})</div>
                                <div class="progress mb-1">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: ${batch.progress}%;">
                                        ${batch.progress}%
                                    </div>
                                </div>
                                <small>Jobs: ${batch.processed_jobs}/${batch.total_jobs}, Gagal: ${batch.failed_jobs}</small>
                                <hr>
                            </div>`;
                    });
                }

                document.getElementById('batch-list').innerHTML = html;
            });
    }

    fetchBatches();
    setInterval(fetchBatches, 3000); // refresh tiap 3 detik
</script>
@endpush
