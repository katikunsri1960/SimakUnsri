@extends('layouts.mahasiswa')
@section('title')
Dashboard
@endsection
@section('content')
@include('swal')
<section class="content">
    <div class="row align-items-end">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3"><img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h2 class="mb-10">Halaman Nilai Perkuliahan,  {{auth()->user()->name}}</h2>
                            <p class="text-dark mb-0 fs-16">
                                SIMAK Universitas Sriwijaya
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-10">
        <div class="col-lg-12 col-xl-12 mt-0">
            <!-- Nav tabs -->
            <ul class="nav nav-pills justify-content-left" role="tablist">
                <li class="nav-item bg-secondary-light"> <a class="nav-link active" data-bs-toggle="tab" href="#khs" role="tab"><span><i class="fa-solid fa-file-invoice"></i></span> <span class="hidden-xs-down ms-15">Kartu Hasil Studi</span></a> </li>
                <li class="nav-item bg-secondary-light"> <a class="nav-link" data-bs-toggle="tab" href="#transkrip-mahasiswa" role="tab"><span><i class="fa-solid fa-graduation-cap"></i></span> <span class="hidden-xs-down ms-15">Transkrip Mahasiswa</span></a> </li>
            </ul>
            <div class="box box-outline-success bs-3 border-success">
                <!-- Tab panes -->
                <div class="tab-content tabcontent">
                    @include('mahasiswa.nilai-perkuliahan.include.khs')
                    @include('mahasiswa.nilai-perkuliahan.include.transkrip-mahasiswa')
                </div>
				<!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var statusSync = @json($statusSync);
        var idBatch = @json($id_batch); // Assuming you have $idBatch available in your Blade template

        if (statusSync == 1) {
            checkSync(idBatch);
        }
    });

    function checkSync(id_batch) {
        $.ajax({
            url: '{{ route('mahasiswa.check-sync') }}',
            type: 'GET',
            data: {
                id_batch: id_batch
            },
            success: function(response) {
                console.log('Sync response:', response);
                // Handle the response here


                // Update the progress bar
                var progressBar = document.getElementById('sync-progress-bar');
                progressBar.style.width = response.progress + '%';
                progressBar.setAttribute('aria-valuenow', response.progress);
                // set text percentage
                progressBar.innerHTML = response.progress + '%';

                if (response.progress < 100) {
                    setTimeout(function() {
                        checkSync(id_batch);
                    }, 3000); // Request every 2 seconds
                } else {
                
                    // reload page
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error checking sync:', error);
            }
        });
    }
</script>
@endpush