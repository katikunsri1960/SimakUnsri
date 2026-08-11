@extends('layouts.bak')
@section('title')
List Mahasiswa Tidak KRS
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">List Mahasiswa Tidak KRS</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('bak')}}"><i class="mdi mdi-home-outline"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Monitoring</li>
                        <li class="breadcrumb-item" aria-current="page"><a
                                href="{{route('bak.monitoring.pengisian-krs')}}">Pengisian KRS</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List Mahasiswa Tidak KRS</li>
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
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <td>Fakultas</td>
                                    <td>:</td>
                                    <td>{{$prodi->fakultas->nama_fakultas}}</td>
                                </tr>
                                <tr>
                                    <td>Program Studi</td>
                                    <td>:</td>
                                    <td>{{$prodi->nama_jenjang_pendidikan}} - {{$prodi->nama_program_studi}}
                                        ({{$prodi->kode_program_studi}})</td>
                                </tr>
                            </table>
                        </div>

                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <label for="statusFilter">Filter by Status Pembayaran:</label>
                            <select id="statusFilter" class="form-control">
                                <option value="">All</option>
                                <option value="Lunas">Lunas</option>
                                <option value="Penundaan Bayar">Penundaan Bayar</option>
                                <option value="Belum Bayar">Belum Bayar</option>
                                <option value="KIP-K">KIP-K</option>
                                <option value="BBP">BBP</option>
                                <option value="Bantuan UKT Unsri">Bantuan UKT Unsri</option>
                                <option value="Afirmasi Pendidikan Tinggi">Afirmasi Pendidikan Tinggi</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive">

                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Angkatan</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">Nama</th>
                                    <th class="text-center align-middle">Dosen PA</th>
                                    <th class="text-center align-middle">Status Pembayaran</th>
                                    <th class="text-center align-middle">Nominal UKT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center align-middle">{{$d->angkatan}}</td>
                                    <td class="text-center align-middle">{{$d->nim}}</td>
                                    <td class="text-start align-middle">{{$d->nama_mahasiswa}}</td>
                                    <td class="text-start align-middle">{{$d->pembimbing_akademik ?
                                        $d->pembimbing_akademik->nama_dosen : '-'}}</td>
                                    <td class="text-center align-middle">
                                        @if ($d->beasiswa)
                                        <h5><span
                                                class="badge bg-primary">{{$d->beasiswa->jenis_beasiswa->nama_jenis_beasiswa}}</span>
                                        </h5>
                                        @else
                                            @if ($d->tagihan)
                                                @if ($d->tagihan->pembayaran)
                                                <h5><span class="badge bg-success">Lunas</span></h5>
                                                @else
                                                    @if ($d->penundaan_bayar == 1)
                                                    <h5><span class="badge bg-warning">Penundaan Bayar</span></h5>
                                                    @else
                                                    <h5><span class="badge bg-danger">Belum Bayar</span></h5>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-end align-middle">
                                        @if ($d->tagihan)
                                        {{number_format($d->tagihan->total_nilai_tagihan, 0, ',', '.')}}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
@push('css')
<style>
    .dt-center {
        text-align: center;
    }
    </style>
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>
   $(document).ready(function() {
        var table = $('#data').DataTable({
            dom: '<"top"lf<"dt-center"B>>rt<"bottom"ip><"clear">', // Place buttons (B) at the top center
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Download Excel',
                    className: 'btn btn-primary'
                }
            ],
            lengthMenu: [10, 25, 50, 75, 100], // Include the length changing control
            pageLength: 10 // Set the default number of rows to display
        });

        // Custom filter for Status Pembayaran
        $('#statusFilter').on('change', function() {
            var filterValue = this.value;
            table.column(5).search(filterValue).draw();
        });
    });
</script>
@endpush
