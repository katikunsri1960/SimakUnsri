@extends('layouts.universitas')
@section('title')
Monev Status UKT Mahasiswa
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Monev Status UKT Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Monitoring</li>
                        <li class="breadcrumb-item active" aria-current="page">Status UKT Mahasiswa</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-warning bs-3 border-warning">
                <div class="box-body text-center" style="padding: 60px;">
                    <i class="fa fa-code fa-4x text-warning mb-3"></i>
                    <h2 class="text-warning">Halaman Sedang Dalam Pengembangan</h2>
                    <p class="text-muted">Fitur ini belum tersedia untuk saat ini. Silakan kembali lagi nanti.</p>
                    {{-- <a href="{{ route('bak') }}" class="btn btn-primary mt-3">
                        <i class="mdi mdi-home"></i> Kembali ke Dashboard
                    </a> --}}
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

    $(document).ready(function(){
        $('#data').DataTable({
            "paging": false,
            "info": false,
            "scrollX": true,
            "scrollY": "45vh",
            "dom": 'Bfrtip', // Add buttons for export
            "buttons": [
                {
                    extend: 'excelHtml5',
                    title: 'Monev Status Mahasiswa',
                    text: '<i class="fa fa-file-excel-o"></i> Download Excel', // Add Excel icon
                    className: 'btn btn-success', // Optional: Add custom styling
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5] // Tentukan kolom yang ingin diekspor
                    },
                    customize: function (xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];

                        // Modifikasi header kolom kedua
                        $('row c[r="B2"]', sheet).text('Nama Fakultas (Custom Header)');
                    }
                }
            ],
            "columnDefs": [
                {
                    "targets": 0, // Kolom pertama
                    "type": "num" // Menentukan tipe data sebagai numerik
                },
                {
                    "targets": 5, // Kolom ke-6
                    "orderable": false // Menonaktifkan pengurutan pada kolom ini
                }
            ]
        });

    });

</script>
@endpush
