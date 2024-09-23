@extends('layouts.universitas')
@section('title')
Program Studi
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Program Studi</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Referensi</li>
                        <li class="breadcrumb-item active" aria-current="page">Program Studi</li>
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
                    <div class="d-flex justify-content-end">
                        <form action="{{route('univ.referensi.prodi.sync')}}" method="get" id="sync-form">
                            <button class="btn btn-primary waves-effect waves-light" type="submit"><i class="fa fa-refresh"></i> Sinkronisasi</button>
                        </form>
                        <span class="divider-line mx-1"></span>
                        <form action="{{route('univ.referensi.sync')}}" method="get" id="sync-ref">
                            <button class="btn btn-success waves-effect waves-light" type="submit"><i class="fa fa-refresh"></i> Sinkronisasi Referensi</button>
                        </form>
                        <span class="divider-line mx-1"></span>
                        <form action="{{route('univ.referensi.sync-all-pt')}}" method="get" id="sync-all-pt">
                            <button class="btn btn-warning waves-effect waves-light" type="submit"><i class="fa fa-refresh"></i> Sinkronisasi All PT</button>
                        </form>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100">
                          <thead class="table-primary">
                              <tr>
                                <th class="text-center align-middle">KODE PRODI</th>
                                <th class="text-center align-middle">NAMA PRODI</th>
                                <th class="text-center align-middle">JENJANG</th>
                                <th class="text-center align-middle">STATUS</th>
                              </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $d)
                            <tr>
                                <td class="text-center align-middle">{{$d->kode_program_studi}}</td>
                                <td class="text-start align-middle">{{$d->nama_program_studi}}</td>
                                <td class="text-center align-middle">{{$d->nama_jenjang_pendidikan}}</td>
                                <td class="text-center align-middle">{{$d->status}}</td>
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
    $(function () {
        "use strict";

        $('#data').DataTable({
            pageLength: 25,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        });

        // sweet alert sync-form
        $('#sync-form').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Sinkronisasi Data',
                text: "Apakah anda yakin ingin melakukan sinkronisasi data?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sinkronkan!',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $('#sync-form').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });

        $('#sync-ref').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Sinkronisasi Data',
                text: "Apakah anda yakin ingin melakukan sinkronisasi data?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sinkronkan!',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $('#sync-ref').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });

        $('#sync-all-pt').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Sinkronisasi Data',
                text: "Apakah anda yakin ingin melakukan sinkronisasi data?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sinkronkan!',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $('#sync-all-pt').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });

    });
</script>
@endpush
