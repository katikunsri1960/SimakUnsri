@extends('layouts.dppm')
@section('title')
KUISIONER
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Daftar Program Studi</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dppm.dashboard')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Daftar Program Studi</li>
                        <li class="breadcrumb-item active" aria-current="page">Daftar</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
@include('swal')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                {{-- <div class="box-header with-border">
                    <div class="d-flex justify-content-end">
                        <div class="d-flex justify-content-start">
                            <!-- Modal trigger button -->
                            <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                                data-bs-target="#filter-button">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                            <span class="divider-line mx-1"></span>
                            <a href="{{route('bak.beasiswa')}}" class="btn btn-warning waves-effect waves-light" >
                                <i class="fa fa-rotate"></i> Reset Filter
                            </a>
                            @include('bak.beasiswa.filter')
                        </div>
                    </div>
                </div> --}}
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-hover margin-top-10 w-p100" style="font-size: 10pt">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Fakultas</th>
                                    <th class="text-center align-middle">Kode Program Studi</th>
                                    <th class="text-center align-middle">Jenjang Pendidikan</th>
                                    <th class="text-center align-middle">Program Studi</th>
                                    <th class="text-center align-middle">Status</th>
                                    <th class="text-center align-middle">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prodi as $index => $p)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $p->fakultas->nama_fakultas ?? '-' }}</td>
                                        <td>{{ $p->kode_program_studi ?? '-' }}</td>
                                        <td>{{ $p->nama_jenjang_pendidikan }}</td>
                                        <td>{{ $p->nama_program_studi }}</td>
                                        <td>{{ $p->status }}</td>
                                        <td class="text-center">
                                            {{-- <a href="{{ route('dppm.kuisioner.kelas-penjadwalan', $p->id_prodi) }}"  --}}
                                            <a href="{{ route('dppm.kuisioner.kelas-penjadwalan', $p->id_prodi) }}" 
                                            class="btn btn-sm btn-primary">
                                            <i class="fa fa-chart-bar"></i> Kelas Kuliah
                                            </a>
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
<link rel="stylesheet" href="{{asset('assets/js/flatpickr/flatpickr.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/js/flatpickr/flatpickr.js')}}"></script>
<script>
$(document).ready(function () {
    $('#data').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        ordering: true,
        columnDefs: [
            { orderable: false, targets: -1 }, // kolom aksi tidak bisa di-sort
            { className: "text-center", targets: [0, -1] } // center untuk kolom No dan Aksi
        ],
        language: {
            url: "{{ asset('assets/vendor_components/datatable/i18n/id.json') }}" // kalau mau bahasa Indonesia
        }
    });
});
</script>
@endpush
