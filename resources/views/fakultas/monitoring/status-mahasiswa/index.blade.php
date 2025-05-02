@extends('layouts.fakultas')
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
                        <li class="breadcrumb-item"><a href="{{route('fakultas')}}"><i class="mdi mdi-home-outline"></i></a>
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

                    {{-- <div class="progress mt-3">
                        <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;"
                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div> --}}
                    <div class="table-responsive mt-5">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100 table-sm">
                            <thead>
                                <tr>
                                    {{-- <th class="text-center align-middle">-</th>
                                    <th class="text-center align-middle">Fakultas</th> --}}
                                    <th class="text-center align-middle">Kode Prodi</th>
                                    <th class="text-center align-middle" >Prodi</th>
                                    <th class="text-center align-middle" >Lewat Masa Studi <br> (10 Semester)</th>
                                    <th class="text-center align-middle" >Lewat Masa Studi</th>
                                    <th class="text-center align-middle" >Terakhir Update</th>
                                    <th class="text-center align-middle" >ACT</th>
                                </tr>

                            </thead>
                            <tbody>
                                @php
                                    $total_lewat_10 = 0;
                                @endphp
                                @foreach ($data as $item)
                                <tr>
                                    {{-- <td class="text-end align-middle">
                                        {{$item->prodi->fakultas->id}}
                                    </td>
                                    <td class="text-start align-middle">
                                        {{$item->prodi->fakultas->nama_fakultas}}
                                    </td> --}}
                                    <td class="text-center align-middle">
                                        {{$item->prodi->kode_program_studi}}
                                    </td>
                                    <td class="text-start align-middle">
                                        {{$item->prodi->nama_jenjang_pendidikan}} - {{$item->prodi->nama_program_studi}}
                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($item->prodi->nama_jenjang_pendidikan == 'S1' && $item->lewat_10_semester > 0)
                                        <a
                                            href="{{route('fakultas.monitoring.status-mahasiswa.detail-prodi', ['id' => $item->id, 'status' => 'lewat_10_semester'])}}">
                                            {{$item->lewat_10_semester}}
                                            @php
                                                $total_lewat_10 += $item->lewat_10_semester;
                                            @endphp
                                        </a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($item->mahasiswa_lewat_semester > 0)
                                        <a
                                            href="{{route('fakultas.monitoring.status-mahasiswa.detail-prodi', ['id' => $item->id, 'status' => 'mahasiswa_lewat_semester'])}}">
                                            {{$item->mahasiswa_lewat_semester}}
                                        </a>
                                        @else
                                        {{$item->mahasiswa_lewat_semester}}
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        {{$item->updated_at}}
                                    </td>
                                    <td class="text-center align-middle">

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">Total</td>
                                    <td class="text-center align-middle">
                                        @if ($total_lewat_10 > 0)
                                        <a
                                        href="{{route('fakultas.monitoring.status-mahasiswa.detail-total',['semester' => $data->first()->id_semester, 'status' => 'lewat_10_semester'])}}" target="_blank">
                                        @endif
                                        {{$total_lewat_10}}
                                    </td>
                                    <td class="text-center">
                                        @if ($data->sum('mahasiswa_lewat_semester') > 0)
                                        <a target="_blank"
                                            href="{{route('fakultas.monitoring.status-mahasiswa.detail-total',['semester' => $data->first()->id_semester, 'status' => 'mahasiswa_lewat_semester'])}}">{{$data->sum('mahasiswa_lewat_semester')}}</a>
                                        @else
                                        {{$data->sum('mahasiswa_lewat_semester')}}
                                        @endif
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
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
