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

                            <div class="row">
                                <div class="col-md-3 pt-2">
                                    <label for="semester_view" class="form-label">Semester</label>
                                </div>
                                <div class="col-md-8 ms-4">
                                    <select class="form-select" name="id_semester" id="id_semester"
                                        onchange="document.getElementById('semesterForm').submit();">
                                        <option value="" selected disabled>-- Pilih Semester --</option>
                                        @foreach ($semester as $p)
                                        <option value="{{$p->id_semester}}" @if (request()->get('id_semester') &&
                                            request()->get('id_semester') ==
                                            $p->id_semester) selected @elseif ($p->id_semester == $semester_aktif)
                                            selected @endif
                                            >{{$p->nama_semester}}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="box">
                                <div class="row g-0 py-2">
                                    @foreach ($count as $c)
                                    <div class="col-12 col-lg-4">
                                        <div class="box-body be-1 border-light">
                                            <div class="flexbox mb-1">
                                                <span>
                                                    <i class="fa {{$c['class']}} fs-30"></i><br>
                                                    {{$c['status']}}
                                                </span>
                                                <span class="text-primary fs-40">{{$c['jumlah']}}</span>
                                            </div>
                                            <div class="progress progress-xxs mt-10 mb-0">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{$c['persen']}}%; height: 5px;" aria-valuenow="{{$c['jumlah']}}"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach






                                </div>
                            </div>
                        </div>
                    </div>
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

    $('#id_semester').select2({
        width: '100%'
    });

});

</script>
@endpush
