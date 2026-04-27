@extends('layouts.bak')
@section('title')
Monitoring CPL Kurikulum
@endsection

@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Monitoring CPL Kurikulum per Program Studi</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('bak')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">SKPI</li>
                        <li class="breadcrumb-item active" aria-current="page">CPL Kurikulum</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="box box-outline-success">
        <div class="box-body">
            <div class="table-responsive">
                <table id="table-monitoring" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Fakultas</th>
                            <th class="text-center">Program Studi</th>
                            <th class="text-center">Jumlah Kurikulum</th>
                            <th class="text-center">Dengan CPL</th>
                            <th class="text-center">Tanpa CPL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $i => $d)
                        <tr>
                            <td class="text-center">{{ $i+1 }}</td>

                            <td>{{ $d->nama_fakultas }}</td>

                            <td>
                                {{ $d->nama_jenjang_pendidikan }} - {{ $d->nama_program_studi }}
                            </td>

                            {{-- TOTAL --}}
                            <td class="text-center">
                                <p class="mb-0">
                                    {{ $d->total_kurikulum }}
                                </p>
                            </td>

                            {{-- PUNYA CPL --}}
                            <td class="text-center">
                                <a href="{{ route('bak.monitoring.cpl-kurikulum.detail', ['prodi'=>$d->id_prodi, 'mode'=>'punya']) }}">
                                    {{ $d->punya_cpl }}
                                </a>
                            </td>

                            {{-- TANPA CPL --}}
                            <td class="text-center">
                                <a href="{{ route('bak.monitoring.cpl-kurikulum.detail', ['prodi'=>$d->id_prodi, 'mode'=>'tidak']) }}">
                                    {{ $d->tanpa_cpl }}
                                </a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>

@endsection

@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>

<script>
$('#table-monitoring').DataTable({
    paging: true,
    // scrollX: true,
    // scrollY: "50vh",
    dom: 'Bfrtip',

    buttons: [
        {
            extend: 'excelHtml5',
            title: 'Monitoring CPL Kurikulum',
            text: '<i class="fa fa-file-excel-o"></i> Export Excel',
            className: 'btn btn-success',
            exportOptions: {
                columns: [0,1,2,3,4,5] // sesuai jumlah kolom
            }
        }
    ],

    rowCallback: function(row){

        let total = parseInt($(row).find('td:eq(3)').text()) || 0;
        let punya = parseInt($(row).find('td:eq(4)').text()) || 0;

        if(total > 0 && total === punya){
            $(row).css('background-color', '#d4edda'); // hijau
        } else {
            $(row).css('background-color', '#f8d7da'); // merah
        }
    }
});
</script>
@endpush