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
                        <div class="btn-group">
                            <a class="btn btn-rounded bg-success-light" href="#" data-bs-toggle="modal"
                                data-bs-target="#tambahAsistensiModal"><i class="fa fa-plus"><span
                                        class="path1"></span><span class="path2"></span></i> Tambah Asistensi</a>
                            <a class="btn btn-rounded bg-primary-light" href="{{route('dosen.pembimbing.bimbingan-tugas-akhir.ajuan-sidang', ['aktivitas' => $aktivitas])}}"><i class="fa fa-check-circle-o"></i> Ajukan Sidang</a>
                        </div>
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
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
    });

</script>
@endpush
