@extends('layouts.mahasiswa')
@section('title')
Biaya Kuliah
@endsection
@section('content')
<section class="content">
    <div class="row align-items-end">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3"><img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <!-- <h2>Halaman KRS {{auth()->user()->name}}</h2> -->
                            <h2>Biaya Kuliah Mahasiswa</h2>
                            <p class="text-dark mb-0 fs-16">
                                Universitas Sriwijaya
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-20">
        <div class="col-lg-12 col-xl-12 mt-5">
            <div class="box">
				<!-- Nav tabs -->
                <ul class="nav nav-pills justify-content-left" role="tablist">
                    <li class="nav-item bg-secondary-light rounded10"> <a class="nav-link active" data-bs-toggle="tab" href="#tagihan" role="tab"><span><i class="fa fa-money"></i></span> <span class="hidden-xs-down ms-15">Tagihan</span></a> </li>
                    <li class="nav-item bg-secondary-light rounded10"> <a class="nav-link " data-bs-toggle="tab" href="#riwayat-pembayaran" role="tab"><span><i class="fa-solid fa-refresh"></i></span> <span class="hidden-xs-down ms-15">Riwayat Pembayaran</span></a> </li>
                    {{-- <li class="nav-item bg-secondary-light rounded10"> <a class="nav-link " data-bs-toggle="tab" href="#rekap-pembayaran" role="tab"><span><i class="fa-solid fa-tasks"></i></span> <span class="hidden-xs-down ms-15">Rekap Pembayaran</span></a> </li> --}}
                </ul>
                
                <!-- Tab panes -->
                <div class="tab-content tabcontent">
                    @include('mahasiswa.biaya-kuliah.include.tagihan')
                    @include('mahasiswa.biaya-kuliah.include.riwayat-pembayaran')
                    {{-- @include('mahasiswa.biaya-kuliah.include.rekap-pembayaran') --}}
                </div>
				<!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>	
</section>
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert@1.1.3/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert@1.1.3/dist/sweetalert.css">

<!-- Kode HTML dan Blade Anda di sini -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mengambil data dari Blade
        var statusBayar = @json($tagihan->status_pembayaran);

        // Mengecek apakah status bayar adalah NULL
        if (statusBayar === null) {
            // Menampilkan SweetAlert jika status bayar adalah NULL
            swal({
                title: "Tagihan Belum Dibayar",
                text: "Anda belum membayar tagihan UKT. Silakan bayar untuk dapat mengisi KRS.",
                type: "warning",
                confirmButtonText: "OK",
                closeOnConfirm: false
            }, function() {
                // Kembali ke halaman index atau halaman lain jika tombol OK diklik
                window.location.href = "{{ route('mahasiswa.dashboard') }}"; // Ganti dengan rute yang sesuai
            });
        }
    });
</script>