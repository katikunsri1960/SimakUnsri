@extends('layouts.bak')
@section('title')
Daftar Peserta Wisuda
@endsection
@section('content')
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
                            <h2>Daftar Peserta Wisuda</h2>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                {{-- <div class="box-header with-border">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                        data-bs-target="#createModal">
                            <i class="fa fa-plus"></i> Tambah Periode
                        </button>
                        <span class="divider-line mx-1"></span>
                    </div>
                </div> --}}
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-hover margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Periode</th>
                                    <th class="text-center align-middle">Tanggal Wisuda</th>
                                    <th class="text-center align-middle">Tanggal Mulai Pendaftaran</th>
                                    <th class="text-center align-middle">Tanggal Akhir Pendaftaran</th>
                                    <th class="text-center align-middle">Apa Aktif</th>
                                    <th class="text-center align-middle">ACT</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
@push('js')
<script>

</script>
@endpush
