@extends('layouts.bak')
@section('title')
Monitoring Pengisian KRS
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Monitoring Pengisian KRS</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('bak')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Monitoring</li>
                        <li class="breadcrumb-item active" aria-current="page">Pengisian KRS</li>
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
                    <div class="table-responsive mt-5">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">Nama Fakultas</th>
                                <th class="text-center align-middle">Nama Program Studi</th>
                                <th class="text-center align-middle">Mahasiswa Aktif</th>
                                <th class="text-center align-middle">Mahasiswa Aktif {{date('Y') - 7}} - {{date('Y')}}</th>
                                <th class="text-center align-middle">Mahasiswa > 7 Thn</th>
                                <th class="text-center align-middle">Mahasiswa (Yang melakukan pengisian KRS)</th>
                                <th class="text-center align-middle">Mahasiswa (Tidak isi KRS)</th>
                                <th class="text-center align-middle">Mahasiswa Sudah di Setujui</th>
                                <th class="text-center align-middle">Mahasiswa Belum di Setujui</th>
                                <th class="text-center align-middle">Persentase Pengisian KRS</th>
                             </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $d)
                            @php
                                $persentase_approval = 0;
                                if($d->isi_krs > 0) {
                                    $persentase_approval = ($d->isi_krs / $d->mahasiswa_aktif) * 100;
                                }
                            @endphp
                                <tr class="@if ($persentase_approval < 50) table-danger @endif">
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-start align-middle">{{$d->id}} - {{$d->nama_fakultas}}</td>
                                    <td class="text-start align-middle">{{$d->nama_jenjang_pendidikan}} {{$d->nama_program_studi}}</td>
                                    <td class="text-center align-middle">
                                        <a href="{{route('bak.monitoring.pengisian-krs.detail-mahasiswa-aktif', ['prodi' => $d->prodi->id])}}">
                                            {{$d->mahasiswa_aktif}}
                                        </a>

                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{route('bak.monitoring.pengisian-krs.detail-aktif-min-tujuh', ['prodi' => $d->prodi->id])}}">
                                            {{$d->mahasiswa_aktif_min_7}}
                                        </a>
                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($d->mahasiswa_aktif - $d->mahasiswa_aktif_min_7 > 0)
                                        <a href="{{route('bak.monitoring.pengisian-krs.mahasiswa-up-tujuh', ['prodi' => $d->prodi->id])}}"  target="_blank">
                                            {{$d->mahasiswa_aktif - $d->mahasiswa_aktif_min_7}}
                                        </a>
                                        @else
                                            0
                                        @endif

                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($d->isi_krs > 0)
                                        <a href="{{route('bak.monitoring.pengisian-krs.detail-isi-krs', ['prodi' => $d->prodi->id])}}">
                                            {{$d->isi_krs}}
                                        </a>
                                        @else
                                            0
                                        @endif

                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($d->mahasiswa_aktif_min_7 - $d->isi_krs > 0)
                                        <a href="{{route('bak.monitoring.pengisian-krs.tidak-isi-krs', ['prodi' => $d->prodi->id])}}"  target="_blank">
                                            {{$d->mahasiswa_aktif_min_7 - $d->isi_krs}}
                                        </a>
                                        @else
                                            0
                                        @endif

                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($d->krs_approved > 0)
                                        <a href="{{route('bak.monitoring.pengisian-krs.detail-approved-krs', ['prodi' => $d->prodi->id])}}">
                                        {{$d->krs_approved}}
                                        </a>
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($d->krs_not_approved > 0)
                                        <a href="{{route('bak.monitoring.pengisian-krs.detail-not-approved-krs', ['prodi' => $d->prodi->id])}}">
                                            {{$d->krs_not_approved}}
                                            </a>
                                        @else
                                            0
                                        @endif

                                    </td>
                                    <td class="text-center align-middle">{{number_format($persentase_approval, 2, ',','.')}}%</td>
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
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#data').DataTable({
            "paging": false,
            "scrollX": true,
            "scrollY": "45vh",
            "dom": 'Bfrtip', // Add buttons for export
            "buttons": [
                {
                    extend: 'excelHtml5',
                    title: 'Monev Pengisian KRS',
                    text: '<i class="fa fa-file-excel-o"></i> Download Excel', // Add Excel icon
                    className: 'btn btn-success', // Optional: Add custom styling
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5,6,7,8,9,10] // Tentukan kolom yang ingin diekspor
                    },
                }
            ],
        });
    });

</script>
@endpush
