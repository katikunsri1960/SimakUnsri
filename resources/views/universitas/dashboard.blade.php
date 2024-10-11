@extends('layouts.universitas')
@section('title')
Dashboard
@endsection
@section('content')
<section class="content">
    <div class="row align-items-end">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3"><img src="images/images/svg-icon/color-svg/custom-14.svg" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h2>Hello {{auth()->user()->name}}, Welcome Back!</h2>
                            <p class="text-dark mb-0 fs-16">

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-12">
            <div class="row p-5">
                <table class="table table-hover table-bordered" id="mahasiswaTable">
                    <thead>
                        <tr>
                            <th>Prodi</th>

                            <th>Jumlah Approved</th>
                            <th>Jumlah Not Approved</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <canvas id="myChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</section>
@endsection
@push('js')
{{-- <script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script> --}}
<script>
    $(document).ready(function() {
        // Fetch data via AJAX
        $.ajax({
            url: "{{ route('univ.data-krs') }}",
            method: 'GET',
            success: function(data) {
                // Check if data is an array


                // Clear existing table rows
                $('#mahasiswaTable tbody').empty();

                // Prepare data for table and chart
                let labels = [];
                let approvedData = [];
                let notApprovedData = [];


                // Display the chart using Chart.js

            },
            error: function(error) {
         
            }
        });
    });
</script>
@endpush
