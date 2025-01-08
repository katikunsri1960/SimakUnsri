@extends('layouts.bak')
@section('title')
Daftar Pengajuan Cuti
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
                            <h2>Daftar Pengajuan Cuti Mahasiswa</h2>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-body py-10">
                    <div class="col-md-6 mt-5">
                        <div class="pull-right">
                            <form action="{{ route('bak.pengajuan-cuti') }}" method="get" id="semesterForm">

                            {{-- <p class="mb-0 text-fade fs-18">Semester - </p> --}}
                            <div class="mb-3">
                                <label for="semester_view" class="form-label">Semester</label>
                                <select
                                    class="form-select"
                                    name="semester_view"
                                    id="semester_view"
                                    onchange="document.getElementById('semesterForm').submit();"
                                >
                                    <option value="" selected disabled>-- Pilih Semester --</option>
                                    @foreach ($pilihan_semester as $p)
                                        <option value="{{$p->id_semester}}"
                                            @if ($semester_view != null)
                                            {{$semester_view == $p->id_semester ? 'selected' : ''}}
                                            @else
                                            {{$semester_aktif->id_semester == $p->id_semester ? 'selected' : ''}}
                                            @endif
                                            >{{$p->nama_semester}}</option>
                                    @endforeach
                                </select>
                            </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive mt-5">
                        <table id="data" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Semester</th>
                                    <th>Prodi</th>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Alasan Pengajuan Cuti</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td class="text-start align-middle" style="white-space:nowrap;">{{$d->nama_semester}}</td>
                                        <td class="text-start align-middle">{{$d->prodi->nama_jenjang_pendidikan}} {{$d->prodi->nama_program_studi}}</td>
                                        <td class="text-start align-middle">{{$d->riwayat->nim}}</td>
                                        <td class="text-start align-middle">{{$d->riwayat->nama_mahasiswa}}</td>
                                        <td class="text-start align-middle">{{$d->alasan_cuti}}</td>
                                        <td class="text-center align-middle" style="width:10%">
                                            @if($d->approved == 0)
                                                <span class="badge badge-xl badge-danger-light mb-5">Belum Disetujui</span>
                                            @elseif($d->approved == 1)
                                                <span class="badge badge-xl badge-warning-light mb-5">Disetujui Fakultas</span>
                                            @elseif($d->approved == 2)
                                                <span class="badge badge-xl badge-success-light mb-5">Disetujui BAK</span>
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
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#data').DataTable();
        });
    </script>
@endpush
