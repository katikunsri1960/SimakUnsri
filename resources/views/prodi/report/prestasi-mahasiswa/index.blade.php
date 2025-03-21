@extends('layouts.prodi')
@section('title')
Prestasi Mahasiswa
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Prestasi Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Report</li>
                        <li class="breadcrumb-item active" aria-current="page">Prestasi Mahasiswa</li>
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
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">NIM</th>
                                <th class="text-center align-middle">NAMA MAHASISWA</th>
                                <th class="text-center align-middle">NAMA PRESTASI</th>
                                <th class="text-center align-middle">JENIS PRESTASI (TINGKAT PRESTASI)</th>
                                <th class="text-center align-middle">TAHUN PRESTASI</th>
                                <th class="text-center align-middle">PERINGKAT</th>
                                <th class="text-center align-middle">PENYELENGGARA</th>
                             </tr>
                          </thead>
                          <tbody>
                                @php
                                    $no_a=1;
                                @endphp
                                @foreach ($data as $d)
                                    <tr>
                                        <td class="text-center align-middle" style="width: 10%">{{$no_a ++}}</td>
                                        <td class="text-center align-middle" style="width: 15%">{{$d->biodata_mahasiswa->riwayat_pendidikan ? $d->biodata_mahasiswa->riwayat_pendidikan[0]->nim : '-'}}</td>
                                        <td class="text-center align-middle" style="width: 15%">{{$d->nama_mahasiswa}}
                                        </td>
                                        <td class="text-center align-middle">
                                            {{$d->nama_prestasi}}
                                        </td>
                                        <td class="text-center align-middle" style="width: 15%">
                                            {{$d->nama_jenis_prestasi}}<br>({{$d->nama_tingkat_prestasi}})
                                        </td>
                                        <td class="text-center align-middle" style="width: 15%">
                                            {{$d->tahun_prestasi}}
                                        </td>
                                        <td class="text-center align-middle" style="width: 15%">
                                            {{$d->peringkat}}
                                        </td>
                                        <td class="text-center align-middle" style="width: 15%">
                                            {{$d->penyelenggara}}
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
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>
    $('#data').DataTable({
        "paging": true, // Make sure this is true
        "stateSave": true,
        "order": [[ 5, "desc" ]],
        "dom": '<"top"lf<"dt-center"B>>rt<"bottom"ip><"clear">', // Place buttons (B) at the top center
        "buttons": [
            {
                "extend": 'excelHtml5',
                "text": 'Download Excel',
                "className": 'btn btn-primary mt-10'
            }
        ],
        "lengthMenu": [10, 25, 50, 75, 100], // Include the length changing control
        "pageLength": 10, // Set the default number of rows to display
        "columnDefs": [{
            "targets": 0,
            "searchable": false,
            "orderable": false,
            "render": function (data, type, full, meta) {
                return meta.settings._iDisplayStart + meta.row + 1;
            }
        }],
        "drawCallback": function (settings) {
            var api = this.api();
            var startIndex = api.context[0]._iDisplayStart;
            api.column(0, {page: 'current'}).nodes().each(function (cell, i) {
                cell.innerHTML = startIndex + i + 1;
            });
        }
    });
</script>
@endpush
