@extends('layouts.dosen')
@section('title')
Bimbingan Tugas Akhir Dosen
@endsection
@section('content')
@push('header')
<div class="mx-4">
    <a href="{{route('dosen.pembimbing.bimbingan-tugas-akhir')}}"
        class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush
@include('swal')
@include('dosen.pembimbing.tugas-akhir.asistensi-tambah')
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-md-12">
            <div class="box box-widget widget-user-2">
                <div class="widget-user-header bg-gradient-secondary">
                    <div class="widget-user-image">
                        @php
                        $imagePath =
                        public_path('storage/'.$aktivitas->anggota_aktivitas_personal->mahasiswa->angkatan.'/'.$aktivitas->anggota_aktivitas_personal->mahasiswa->nim.'.jpg');
                        @endphp
                        <img class="rounded bg-success-light"
                            src="{{file_exists($imagePath) ? asset('storage/'.$aktivitas->anggota_aktivitas_personal->mahasiswa->angkatan.'/'.$aktivitas->anggota_aktivitas_personal->mahasiswa->nim.'.jpg') : asset('images/images/avatar/avatar-15.png')}}"
                            alt="User Avatar">
                    </div>
                    <h3 class="widget-user-username">{{$aktivitas->anggota_aktivitas_personal->nama_mahasiswa}} </h3>
                    <h4 class="widget-user-desc">NIM: {{$aktivitas->anggota_aktivitas_personal->nim}}<br
                            class="mb-1">ANGKATAN: {{$aktivitas->anggota_aktivitas_personal->mahasiswa->angkatan}}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-12">
            <div class="box box-body mb-0">
                <div class="row mb-2">
                    <div class="col-xl-12 col-lg-12 d-flex justify-content-between">
                        <div class="d-flex justify-content-start">
                            <table class="table">
                                <tr>
                                    <td class="text-left">Judul</td>
                                    <td class="text-center">:</td>
                                    <td class="text-left" style="text-align: justify">{{$aktivitas->judul}}</td>
                                </tr>
                                <tr>
                                    <td class="text-left">Link Repository</td>
                                    <td class="text-center">:</td>
                                    <td class="text-left" style="text-align: justify">
                                        @if(!$repository)
                                            <span class="badge bg-danger">Belum Upload Repositroy</span>
                                        @else
                                            <a class="btn btn-sm btn-info" href="{{$repository->link_repo}}" type="button" title="Lihat Repository" target="_blank">{{$repository->link_repo}}</a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left text-nowrap">No. SK</td>
                                    <td class="text-center">:</td>
                                    <td class="text-left" style="text-align: justify">{{$aktivitas->sk_tugas}}</td>
                                </tr>
                                <tr>
                                    <td class="text-left text-nowrap">Tanggal Mulai</td>
                                    <td class="text-center">:</td>
                                    <td class="text-left" style="text-align: justify">{{$aktivitas->id_tanggal_mulai}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left text-nowrap">Tanggal Selesai</td>
                                    <td class="text-center">:</td>
                                    <td class="text-left" style="text-align: justify">{{$aktivitas->id_tanggal_selesai}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left text-nowrap align-middle">Pembimbing</td>
                                    <td class="text-center align-middle">:</td>
                                    <td class="text-left align-middle">
                                        <ul style="padding: 0; padding-left:0.8rem">
                                            @foreach ($aktivitas->bimbing_mahasiswa as $p)
                                            <li>Pembimbing {{$p->pembimbing_ke}} : {{$p->nama_dosen}}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-xl-12 col-lg-12 text-end">
                        @if($aktivitas->approve_sidang != 1)
                            <div class="btn-group">
                                <a class="btn btn-rounded bg-success-light" href="#" data-bs-toggle="modal" data-bs-target="#tambahAsistensiModal"><i class="fa fa-plus"><span class="path1"></span><span class="path2"></span></i> Tambah Asistensi</a>
                                @if($aktivitas->id_jenis_aktivitas == 2 && $penilaian_langsung->penilaian_langsung == 1)
                                    <a class="btn btn-rounded bg-warning-light" href="{{route('dosen.pembimbing.bimbingan-tugas-akhir.penilaian-langsung', $aktivitas->id)}}"><i class="fa fa-list"></i> Penilaian Langsung</a>
                                @else
                                    <a class="btn btn-rounded bg-primary-light" href="{{route('dosen.pembimbing.bimbingan-tugas-akhir.ajuan-sidang', ['aktivitas' => $aktivitas])}}"><i class="fa fa-check-circle-o"></i> Ajukan Sidang</a>
                                @endif
                            </div>
                        @else
                            <a href="{{route('dosen.pembimbing.bimbingan-tugas-akhir.penilaian-sidang', $aktivitas->id)}}" class="btn btn-primary btn-rounded waves-effect waves-light"><i class="fa fa-play"></i> Mulai Sidang</a>
                            {{--@if($aktivitas->jadwal_ujian == date('Y-m-d'))
                                <a href="{{route('dosen.pembimbing.bimbingan-tugas-akhir.penilaian-sidang', $aktivitas->id)}}" class="btn btn-primary btn-rounded waves-effect waves-light"><i class="fa fa-play"></i> Mulai Sidang</a>
                            @else
                                <button type="submit" id="submit-button" class="btn btn-primary btn-rounded waves-effect waves-light" disabled><i class="fa fa-play"></i> Mulai Sidang</button>
                            @endif--}}
                        @endif
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="dt" class="table table-bordered table-striped" style="font-size: 12px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle" style="width: 5%">No</th>
                                    <th class="text-center align-middle">Tanggal</th>
                                    <th class="text-center align-middle">Keterangan</th>
                                    <th class="text-center align-middle">Pembimbing</th>
                                    <th class="text-center align-middle">Status</th>
                                    <th class="text-center align-middle">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center align-middle">{{$d->id_tanggal}}</td>
                                    <td class="text-left align-middle" style="text-align: justify">{{$d->uraian}}</td>
                                    <td class="text-center align-middle">{{$d->dosen ? $d->dosen->nama_dosen : '-'}}</td>
                                    <td class="text-center align-middle">
                                        @if ($d->approved == 0)
                                            <span class="badge bg-warning">Menunggu Persetujuan</span>
                                        @elseif ($d->approved == 1)
                                            <span class="badge bg-success">Disetujui</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($d->approved == 0)
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-sm btn-rounded bg-info-light" onclick="approveAsistensi({{$d}})">
                                                <i class="fa fa-check-circle-o">
                                                </i> Approve</a>
                                        </div>
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
    @if($aktivitas->approve_sidang == 1)
        <div class="row mt-5">
            <div class="col-12">
                <div class="box">
                    <div class="box-body">
                        <form class="form" action="#" id="update-detail-sidang" method="POST">
                            <h3 class="text-info mb-0"><i class="fa fa-user"></i> Detail Sidang Mahasiswa</h3>
                            <hr class="my-15">
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
                                                value="{{$aktivitas->jadwal_ujian}}"
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
                                                value="{{$aktivitas->jadwal_jam_mulai}}"
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
                                                value="{{$aktivitas->jadwal_jam_selesai}}"
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
                                            <th>Nilai Proses Bimbingan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($aktivitas->bimbing_mahasiswa as $b)
                                            <tr>
                                                <td>{{$b->pembimbing_ke}}</td>
                                                <td>{{$b->nama_dosen}}<br>({{$b->nidn}})</td>
                                                <td>{{$b->nama_kategori_kegiatan}}</td>
                                                <td>{{$b->nilai_proses_bimbingan == '' ? 0 : $b->nilai_proses_bimbingan}}</td>
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
                                        @foreach ($aktivitas->uji_mahasiswa as $u)
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
                                            <th>Nilai Kualitas Karya Ilmiah</th>
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
                </div>
            </div>
        </div>
    @endif
</section>
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('assets/vendor_components/select2/dist/css/select2.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>
    function approveAsistensi(data)
    {
        swal({
            title: "Apakah anda yakin?",
            text: "Data tidak bisa diubah lagi setelah disimpan!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#spinner').show();
                $.ajax({
                    url: "{{route('dosen.pembimbing.bimbingan-tugas-akhir.asistensi.approve', '')}}/" + data.id,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: data.id
                    },
                    success: function (response) {
                        $('#spinner').hide();
                        alert(response.message);
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        $('#spinner').hide();
                        alert(xhr.responseJSON.message);
                    }
                });
            }
        });
        // ajax request form post approve asistensi

    }

    $('#dt').DataTable({
        "stateSave": true,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
    });

</script>
@endpush
