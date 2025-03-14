@extends('layouts.prodi')
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
                            <h2>Pengajuan Cuti Mahasiswa</h2>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border d-flex justify-content-between">
                    <div class="d-flex justify-content-start">
                        <!-- Modal trigger button -->
                        <form action="{{ route('prodi.report.cuti-mahasiswa') }}" method="get" id="semesterForm">
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-3 pt-2">
                                        <label for="semester_view" class="form-label">Semester</label>
                                    </div>
                                    <div class="col-md-8 ms-4">
                                        <select class="form-select" name="semester_view" id="semester_view"
                                            onchange="document.getElementById('semesterForm').submit();">
                                            <option value="" selected disabled>-- Pilih Semester --</option>
                                            @foreach ($semester as $p)
                                            <option value="{{$p->id_semester}}" @if (request()->get('semester_view') && request()->get('semester_view') ==
                                                $p->id_semester) selected  @elseif ($p->status == $semester_aktif) selected @endif
                                                >{{$p->nama_semester}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive mt-5">
                        <table id="data" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-start align-middle">Nama Mahasiswa</th>
                                    <th class="text-center align-middle">Semester</th>
                                    <th class="text-center align-middle">Alasan Cuti</th>
                                    <th class="text-center align-middle">No. HP</th>
                                    <th class="text-center align-middle">SK</th>
                                    <th class="text-center align-middle">Terakhir Update</th>
                                    <th class="text-center align-middle">Status</th>
                                    <th class="text-center align-middle">File Pendukung</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center align-middle">{{$d->riwayat->nim}}</td>
                                    <td class="text-start align-middle">{{$d->riwayat->nama_mahasiswa}}</td>
                                    <td class="text-center align-middle">{{$d->nama_semester}}</td>
                                    <td class="text-center align-middle">{{$d->alasan_cuti}}</td>
                                    <td class="text-center align-middle">{{$d->handphone ? $d->handphone : '-'}}</td>
                                    <td class="text-center align-middle">{{$d->no_sk ? $d->no_sk : '-'}} <br>
                                        {{$d->tanggal_sk ? date('d-m-Y', strtotime($d->tanggal_sk)) : '-'}}</td>
                                    <td class="text-center align-middle">{{$d->terakhir_update}}</td>
                                    <td class="text-center align-middle" style="width:10%">
                                        @if($d->approved == 0)
                                        <span class="badge badge-xl rounded badge-danger mb-5">Belum Disetujui</span>
                                        @elseif($d->approved == 1)
                                        <span class="badge badge-xl rounded badge-primary mb-5">Disetujui Program
                                            Studi</span>
                                        @elseif($d->approved == 2)
                                        <span class="badge badge-xl rounded badge-primary mb-5">Disetujui
                                            Fakultas</span>
                                        @elseif($d->approved == 3)
                                        <span class="badge badge-xl rounded badge-success mb-5">Disetujui BAK</span>
                                        @elseif($d->approved == 9)
                                        <span class="badge badge-xl rounded badge-danger mb-5">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle" style="width:10%">
                                        @if($d->file_pendukung)
                                        <a href="{{ asset('storage/' . $d->file_pendukung) }}" target="_blank"
                                            class="btn btn-primary" title="Lihat File">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @else
                                        <span class="text-muted">Tidak ada file</span>
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
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>
$(document).ready(function() {


    $('#data').DataTable({
        "paging": true,      // Menampilkan pagination
        "ordering": true,    // Mengizinkan pengurutan kolom
        "searching": true    // Menambahkan kotak pencarian
    });

    $('#semester_view').select2({
        width: '100%'
    });

});

</script>
@endpush
