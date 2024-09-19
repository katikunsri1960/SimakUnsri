@extends('layouts.universitas')
@section('title')
Transkrip Mahasiswa
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Transkrip Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Perkuliahan</li>
                        <li class="breadcrumb-item active" aria-current="page">Transkrip Mahasiswa</li>
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
                <div class="box-header with-border">
                    @if ($statusSync == 1)
                    <div class="alert alert-warning mt-4">
                        <h3 class="alert-heading">Perhatian!</h3>
                        <hr>
                        <p class="mb-0">Data Transkrip sedang proses sinkronisasi. Harap menunggu terlebih dahulu!</p>
                        {{-- progress bar --}}
                        <div class="progress mt-3">
                            <div id="sync-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                        </div>
                    </div>
                    @else
                    <div class="d-flex justify-content-end">
                        <form action="{{route('univ.perkuliahan.transkrip.sync')}}" method="get" id="sync-form">
                            <button class="btn btn-primary waves-effect waves-light" type="submit"><i class="fa fa-refresh"></i> Sinkronisasi</button>
                        </form>
                        <span class="divider-line mx-1"></span>
                        {{-- <button class="btn btn-success waves-effect waves-light" href="#"><i class="fa fa-plus"></i> Tambah Kurikulum</button> --}}
                    </div>
                    @endif
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table  table-hover margin-top-10 w-p100">

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
            url: '{{ route('univ.check-sync') }}',
            type: 'GET',
            data: {
                id_batch: id_batch
            },
            success: function(response) {
                // Handle the response here
                console.log('Sync check response:', response);

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
                    console.log('Sync completed');
                    // reload page
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error checking sync:', error);
            }
        });
    }

    $(function () {

        // sweet alert sync-form
        $('#sync-form').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Sinkronisasi Data',
                text: "Apakah anda yakin ingin melakukan sinkronisasi?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sinkronkan!',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $('#spinner').show();
                    $('#sync-form').unbind('submit').submit();
                }
            });
        });

    });
</script>
@endpush
