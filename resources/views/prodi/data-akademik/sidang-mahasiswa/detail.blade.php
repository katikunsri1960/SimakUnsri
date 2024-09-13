@extends('layouts.prodi')
@section('title')
Dashboard
@endsection
@section('content')
@include('swal')
<section class="content">
    <div class="row mt-5">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-body">
                    <form class="form" action="#" id="update-detail-sidang" method="POST">
                        <a href="{{route('prodi.data-akademik.sidang-mahasiswa')}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
                        <h3 class="text-info mb-0 mt-40"><i class="fa fa-user"></i> Detail Sidang Mahasiswa</h3>
                        <hr class="my-15">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="mb-4">
                                        <label for="nim" class="form-label">NIM</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="nim"
                                            id="nim"
                                            aria-describedby="helpId"
                                            value="{{$data->anggota_aktivitas_personal->nim}}"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="mb-4">
                                        <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="nama_mahasiswa"
                                            id="nama_mahasiswa"
                                            aria-describedby="helpId"
                                            value="{{$data->anggota_aktivitas_personal->nama_mahasiswa}}"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-4">
                                <label for="judul" class="form-label">Judul</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="judul"
                                    id="judul"
                                    aria-describedby="helpId"
                                    value="{{$data->judul}}"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="mb-4">
                                        <label for="semester" class="form-label">Angkatan</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="semester"
                                            id="semester"
                                            aria-describedby="helpId"
                                            value="{{substr($data->anggota_aktivitas_personal->mahasiswa->id_periode_masuk,0,4)}}"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="mb-4">
                                        <label for="semester" class="form-label">Semester</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="semester"
                                            id="semester"
                                            aria-describedby="helpId"
                                            value="{{$data->nama_semester}}"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="mb-4">
                                        <label for="jenis_aktivitas" class="form-label">Jenis Aktivitas (MK Konversi)</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="jenis_aktivitas"
                                            id="jenis_aktivitas"
                                            aria-describedby="helpId"
                                            value="{{$data->nama_jenis_aktivitas}} ({{$data->konversi->kode_mata_kuliah}} - {{$data->konversi->nama_mata_kuliah}})"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="mb-4">
                                        <label for="sk_tugas" class="form-label">SK Tugas Aktivitas</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="sk_tugas"
                                            id="sk_tugas"
                                            aria-describedby="helpId"
                                            value="{{$data->sk_tugas}}"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="mb-4">
                                        <label for="jenis_aktivitas" class="form-label">Jadwal Sidang</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="jenis_aktivitas"
                                            id="jenis_aktivitas"
                                            aria-describedby="helpId"
                                            value="{{$data->jadwal_ujian}}"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="mb-4">
                                        <label for="sk_tugas" class="form-label">Jam Mulai Sidang</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="sk_tugas"
                                            id="sk_tugas"
                                            aria-describedby="helpId"
                                            value="{{$data->jadwal_jam_mulai}}"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="mb-4">
                                        <label for="sk_tugas" class="form-label">Jam Selesai Sidang</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="sk_tugas"
                                            id="sk_tugas"
                                            aria-describedby="helpId"
                                            value="{{$data->jadwal_jam_selesai}}"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr class="my-15">
                    <h4>Dosen Pembimbing<h4>
                    <div class="row mt-10">
                        <div class="table-responsive">
                            <table id="dt" class="table table-bordered table-striped text-center" style="font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th>Pembimbing Ke -</th>
                                        <th>Nama Dosen</th>
                                        <th>Kategori Pembimbing</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->bimbing_mahasiswa as $b)
                                        <tr>
                                            <td>{{$b->pembimbing_ke}}</td>
                                            <td>{{$b->nama_dosen}}<br>({{$b->nidn}})</td>
                                            <td>{{$b->nama_kategori_kegiatan}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <h4>Dosen Penguji<h4>
                    <div class="row mt-10">
                        <div class="table-responsive">
                            <table id="dt" class="table table-bordered table-striped text-center" style="font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th>Penguji Ke -</th>
                                        <th>Nama Dosen</th>
                                        <th>Kategori Penguji</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->uji_mahasiswa as $u)
                                        <tr>
                                            <td>{{$u->penguji_ke}}</td>
                                            <td>{{$u->nama_dosen}}<br>({{$u->nidn}})</td>
                                            <td>{{$u->nama_kategori_kegiatan}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="my-15">
                    <h4>Notulensi Sidang Mahasiswa<h4>
                    <div class="row mt-10">
                        <div class="table-responsive">
                            <table id="dt" class="table table-bordered table-striped text-center" style="font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Lokasi</th>
                                        <th>Tanggal Pelaksanaan</th>
                                        <th>Jam Mulai Sidang</th>
                                        <th>Jam Selesai Sidang</th>
                                        <th>Jam Mulai Presentasi</th>
                                        <th>Jam Selesai Presentasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_pelaksanaan->notulensi_sidang as $ns)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$ns->lokasi}}</td>
                                            <td>{{$ns->tanggal_sidang}}</td>
                                            <td>{{$ns->jam_mulai_sidang}}</td>
                                            <td>{{$ns->jam_selesai_sidang}}</td>
                                            <td>{{$ns->jam_mulai_presentasi}}</td>
                                            <td>{{$ns->jam_selesai_presentasi}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Notulensi : </td>
                                            <td colspan="6">{{$ns->uraian}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <h4>Catatan Perbaikan Mahasiswa<h4>
                    <div class="row mt-10">
                        <div class="table-responsive">
                            <table id="dt" class="table table-bordered table-striped text-center" style="font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Dosen</th>
                                        <th>Catatan Perbaikan</th>
                                        <th>Batas Akhir Perbaikan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_pelaksanaan->revisi_sidang as $r)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$r->dosen->nama_dosen}}<br>({{$r->dosen->nidn}})</td>
                                            <td>{{$r->uraian}}</td>
                                            <td>{{$r->tanggal_batas_revisi}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <h4>Nilai Sidang Mahasiswa<h4>
                    <div class="row mt-10">
                        <div class="table-responsive">
                            <table id="dt" class="table table-bordered table-striped text-center" style="font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Dosen</th>
                                        <th>Tanggal Penilaian</th>
                                        <th>Nilai Kualitas Skripsi</th>
                                        <th>Nilai Presentasi dan Diskusi</th>
                                        <th>Nilai Performansi</th>
                                        <th>Nilai Akhir Dosen (Jumlah dari BxN)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_pelaksanaan->penilaian_sidang as $p)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$p->dosen->nama_dosen}}<br>({{$p->dosen->nidn}})</td>
                                            <td>{{$p->tanggal_penilaian_sidang}}</td>
                                            <td>{{$p->nilai_kualitas_skripsi}}</td>
                                            <td>{{$p->nilai_presentasi_dan_diskusi}}</td>
                                            <td>{{$p->nilai_performansi}}</td>
                                            <td>{{$p->nilai_akhir_dosen}}</td>
                                            <td>
                                                @if ($p->approved_prodi == 0)
                                                    <span class="badge bg-warning">Menunggu Persetujuan Prodi</span>
                                                @elseif ($p->approved_prodi == 1)
                                                    <span class="badge bg-success">Disetujui</span>
                                                @else
                                                    <span class="badge bg-danger">Dibatalkan</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="box-footer text-end">
                    <form action="{{route('prodi.data-akademik.sidang-mahasiswa.approve-hasil-sidang', $data->id)}}"
                        method="post" id="approve-hasil-sidang">
                        @csrf
                        <a type="button" href="{{route('prodi.data-akademik.sidang-mahasiswa')}}" class="btn btn-danger waves-effect waves-light">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Approve Hasil</button>
                        {{--@if($data->jadwal_ujian == date('Y-m-d'))
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Approve Hasil</button>
                        @else
                            <button type="submit" id="submit-button" class="btn btn-primary waves-effect waves-light" disabled> Approve Hasil</button>
                        @endif--}}
                    </form>
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

    $(function() {
        "use strict";

        // Form submission with SweetAlert confirmation
        $('#approve-hasil-sidang').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Persetujuan Hasil Sidang Mahasiswa',
                text: "Apakah anda yakin ingin melanjutkan?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            },function(isConfirmed){
                if (isConfirmed) {
                    $('#approve-hasil-sidang').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });
</script>
@endpush
