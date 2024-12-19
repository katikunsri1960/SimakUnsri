@extends('layouts.fakultas')
@section('title')
Monitoring Pengisian Nilai
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Monitoring Pengisian Nilai</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('fakultas')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Monitoring</li>
                        <li class="breadcrumb-item active" aria-current="page">Pengisian Nilai</li>
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
                        <div class="col-md-11 mt-5">
                            <div class="form-group row">
                                <label class="col-form-label col-md-3">Program Studi</label>
                                <div class="col-md-9">
                                    <select name="prodi" id="prodi" class="form-select">
                                        <option value="">Pilih Prodi</option>
                                        @foreach($prodi as $p)
                                            <option value="{{ $p->id }}">{{ $p->nama_jenjang_pendidikan }} - {{ $p->nama_program_studi }} ({{ $p->kode_program_studi }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-10 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" onclick="getData()">Proses <i class="fa fa-magnifying-glass ms-1"></i></button>
                            </div>
                        </div>
                        <hr>
                        <div class="mt-3" id="divData" hidden>
                            <table class="table table-bordered table-hover" id="data-monitoring">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">No</th>
                                        <th class="text-center align-middle">NIDN</th>
                                        <th class="text-center align-middle">NAMA DOSEN</th>
                                        <th class="text-center align-middle">TOTAL KELAS AJAR</th>
                                        <th class="text-center align-middle">SUDAH DINILAI</th>
                                        <th class="text-center align-middle">BELUM DINILAI</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
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
        $('#data').DataTable();
    });

    function getData(){
        var prodi = $('#prodi').val();
        if(prodi == ''){
            swal('Peringatan', 'Pilih Prodi terlebih dahulu', 'warning');
            return;
        }
        // remove table tbody
        $('#data-monitoring').DataTable().clear().draw();
        $.ajax({
            url: '{{route('fakultas.monitoring.pengisian-nilai.data')}}',
            type: 'GET',
            data: {
                prodi: prodi
            },
            success: function(response){
                if(response.status == 'success'){
                    $('#divData').removeAttr('hidden');

                    var data = response.data;
                    var table = $('#data-monitoring').DataTable({
                        data: data,
                        destroy: true,
                        columns: [
                            {data: null, className: 'text-center align-middle'}, // Kolom untuk nomor urut
                            {data: 'nidn', className: 'text-center align-middle'},
                            {data: 'nama_dosen', className: 'text-start align-middle'},
                            {
                                data: 'total_kelas',
                                className: 'text-center align-middle',
                                render: function(data, type, row, meta) {
                                    // Ganti URL dengan URL yang sesuai

                                    var url = '{{ route("fakultas.monitoring.pengisian-nilai.detail", ["mode"=> ":mode","dosen" => ":id_dosen", "prodi" => ":prodi"]) }}';
                                    url = url.replace(':id_dosen', row.id_dosen);
                                    url = url.replace(':prodi', prodi);
                                    url = url.replace(':mode', '1');
                                    return '<a href="' + url + '">' + data + '</a>';
                                }
                            },
                            {
                                data: 'total_kelas_dinilai', className: 'text-center align-middle',
                                render: function(data, type, row, meta) {
                                    // Ganti URL dengan URL yang sesuai

                                    var url = '{{ route("fakultas.monitoring.pengisian-nilai.detail", ["mode"=> ":mode","dosen" => ":id_dosen", "prodi" => ":prodi"]) }}';
                                    url = url.replace(':id_dosen', row.id_dosen);
                                    url = url.replace(':prodi', prodi);
                                    url = url.replace(':mode', '2');
                                    return '<a href="' + url + '">' + data + '</a>';
                                }
                            },
                            {
                                data: 'total_kelas_belum_dinilai', className: 'text-center align-middle',
                                render: function(data, type, row, meta) {
                                    // Ganti URL dengan URL yang sesuai

                                    var url = '{{ route("fakultas.monitoring.pengisian-nilai.detail", ["mode"=> ":mode","dosen" => ":id_dosen", "prodi" => ":prodi"]) }}';
                                    url = url.replace(':id_dosen', row.id_dosen);
                                    url = url.replace(':prodi', prodi);
                                    url = url.replace(':mode', '3');
                                    return '<a href="' + url + '">' + data + '</a>';
                                }
                            },
                        ],
                        columnDefs: [
                            {
                                targets: 0, // Kolom pertama
                                orderable: false, // Kolom ini tidak bisa diurutkan
                                searchable: false, // Kolom ini tidak bisa dicari
                                render: function (data, type, row, meta) {
                                    return meta.row + 1; // Menambahkan nomor urut
                                }
                            }
                        ],
                        order: [[1, 'asc']], // Mengatur urutan default berdasarkan kolom kedua (nidn)
                        rowCallback: function(row, data, index){
                            // Menambahkan nomor urut yang tetap
                            $('td:eq(0)', row).html(index + 1);
                        }
                    });
                }
            }
        });

    }

</script>
@endpush
