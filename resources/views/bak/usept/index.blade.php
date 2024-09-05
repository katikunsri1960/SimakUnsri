@extends('layouts.bak')
@section('title')
Daftar Nilai untuk Lulus USEPT Program Studi
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
                            <h2>Daftar Nilai untuk Lulus USEPT Program Studi</h2>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('bak.usept.modal-create')
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-body py-10">
                    <div class="col-md-6 mt-5 mb-5">
                        <div class="d-flex justify-content-start">
                            <!-- Modal trigger button -->
                            <button type="button" class="btn btn-success waves-effect waves-light"
                                data-bs-toggle="modal" data-bs-target="#filter-button">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                            @include('bak.usept.filter')
                            <span class="divider-line mx-1"></span>
                            <a href="{{route('bak.usept-prodi')}}" class="btn btn-warning waves-effect waves-light">
                                <i class="fa fa-refresh"></i> Reset Filter
                            </a>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="table-responsive mt-5">
                        <table id="data" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Program Studi</th>
                                    <th>Kurikulum</th>
                                    <th>Nilai USEPT</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-start align-middle">{{$d->nama_jenjang_pendidikan}}
                                        {{$d->nama_program_studi}}</td>
                                    <td class="text-start align-middle">{{$d->nama_kurikulum}}</td>
                                    <td class="text-center align-middle">{{$d->nilai_usept}}</td>
                                    <td class="text-start align-middle">
                                        <div class="row mx-3">
                                            <button class="btn btn-{{$d->nilai_usept ? 'warning' : 'primary'}} btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalCreate" onclick="tambahData({{$d}})">
                                            {{$d->nilai_usept ? "Ubah" : "Tambah"}} Nilai
                                        </button>
                                        </div>

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
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>

    function tambahData(data){
        if (data.nilai_usept) {
            // add text to modalCreateTitle
            $('#modalCreateTitle').text('Ubah Nilai USEPT');
            $('#nilai_usept').val(data.nilai_usept);
        } else {
            // add text to modalCreateTitle
            $('#modalCreateTitle').text('Masukan Nilai USEPT');
        }

        // add action form
        document.getElementById('storeForm').action = '/bak/usept-prodi/store/' + data.id;

    }

    $('#storeForm').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Apakah Anda Yakin??',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $('#storeForm').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });

    $(document).ready(function () {
            $('#data').DataTable();


            $('#id_prodi').select2({
                placeholder: 'Pilih Program Studi',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#filter-button')
            });
        });
</script>
@endpush
