@extends('layouts.mahasiswa')
@section('title')
Bimbingan Tugas Akhir Mahasiswa
@endsection
@section('content')
@include('swal')
{{-- @include('mahasiswa.bimbingan.bimbingan-tugas-akhir.detail') --}}
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-12">
			<div class="box pull-up">
				<div class="box-body bg-img bg-primary-light">
					<div class="d-lg-flex align-items-center justify-content-between">
						<div class="d-lg-flex align-items-center mb-30 mb-xl-0 w-p100">
			    			<img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" class="img-fluid max-w-250" alt="" />
							<div class="ms-30">
								<h2 class="mb-10">Bimbingan Tugas Akhir Mahasiswa</h2>
								<p class="mb-0 text-fade fs-18">Universitas Sriwijaya</p>
							</div>
						</div>
					<div>
				</div>
			</div>
		</div>
    </div>
    @if (empty($data[0]->id_jenis_aktivitas))
        <!-- Bagian ini ditampilkan jika $aktivitas == NULL -->
        <section class="content bg-primary-light rounded20">
            <div class="row mb-20">
                <div class="col-xxl-12">
                    <div class="box box-body bg-white">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <h3 class="fw-500 text-dark mb-20">Bimbingan Tugas Akhir</h3>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-lg-12 col-lg-12 col-lg-12 p-20 m-0">
                                <div class="box box-body bg-warning-light">
                                    <div class="row" style="align-items: center;">
                                        <div class="col-lg-1 text-right" style="text-align-last: end;">
                                            <i class="fa-solid fa-2xl fa-circle-exclamation fa-danger" style="color: #d10000;"></i></i>
                                        </div>
                                        <div class="col-lg-10 text-left text-danger">
                                            <label>
                                                Anda tidak memiliki Aktivitas!
                                            </label><br>
                                            <label>
                                                Silahkan Ambil Aktivitas di Menu Kartu Rencana Studi!
                                            </label><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @elseif ($statusPembayaran == 0 && $beasiswa == 0)
        <!-- Bagian ini ditampilkan jika $aktivitas == NULL -->
        <section class="content bg-primary-light rounded20">
            <div class="row mb-20">
                <div class="col-xxl-12">
                    <div class="box box-body bg-white">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <h3 class="fw-500 text-dark mb-20">Bimbingan Tugas Akhir</h3>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-lg-12 col-lg-12 col-lg-12 p-20 m-0">
                                <div class="box box-body bg-warning-light">
                                    <div class="row" style="align-items: center;">
                                        <div class="col-lg-1 text-right" style="text-align-last: end;">
                                            <i class="fa-solid fa-2xl fa-circle-exclamation fa-danger" style="color: #d10000;"></i></i>
                                        </div>
                                        <div class="col-lg-10 text-left text-danger">
                                            <label>
                                                Anda Belum Melakukan Pembayaran!
                                            </label><br>
                                            <label>
                                                Silahkan melakukan Pembayaran UKT sebelum periode pembayaran selesai!
                                            </label><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @elseif ($statusPembayaran > 0 || $beasiswa > 0)
        <div class="row">
            <div class="col-12">
                <div class="box box-outline-success bs-3 border-success">
                    <div class="box-header">
                        <div class="col-xl-12 col-lg-12 d-flex justify-content-between">
                            <div class="d-flex justify-content-start">
                                <h3 class="text-info my-0"><i class="fa fa-users"></i> Daftar Bimbingan Tugas Akhir Mahasiswa</h3>
                                <hr class="my-15">
                            </div>
                            <div class="d-flex justify-content-end px-3">
                                <select name="semester" id="semester_select" class="form-select">
                                    <option value="">-- Pilih Semester --</option>
                                    @foreach ($semester as $s)
                                    <option value="{{$s->id_semester}}" @if ($s->id_semester == $id_semester) selected @endif>{{$s->nama_semester}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="data" class="table table-bordered table-hover margin-top-10 w-p100"
                                style="font-size: 11px">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">NO</th>
                                        <th class="text-center align-middle">NAMA AKTIVITAS<br>(MK Konversi)</th>
                                        <th class="text-center align-middle">JUDUL AKTIVITAS</th>
                                        <th class="text-center align-middle">NO SK<br>(Tanggal SK)</th>
                                        <th class="text-center align-middle">PEMBIMBING</th>
                                        <th class="text-center align-middle">STATUS</th>
                                        <th class="text-center align-middle">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                    {{-- @include('mahasiswa.pembimbing.tugas-akhir.pembatalan-bimbingan') --}}
                                        <tr>
                                            <td class="text-center align-middle">{{$loop->iteration}}</td>
                                            <td class="text-center align-middle">
                                                {{ strtoupper($d->nama_jenis_aktivitas)}}<br>({{$d->konversi->kode_mata_kuliah}} - {{$d->konversi->nama_mata_kuliah}})
                                            </td>
                                            <td class="text-center align-middle" style="width: 15%">{{ strtoupper($d->judul)}}</td>
                                            <td class="text-center align-middle">
                                                {{$d->sk_tugas ? $d->sk_tugas : '-' }}<br>({{$d->id_tanggal_sk_tugas}})
                                            </td>
                                            <td class="text-start align-middle text-nowrap">
                                                <ul>
                                                    @foreach ($d->bimbing_mahasiswa as $p)
                                                        <li>Pembimbing {{$p->pembimbing_ke}} :<br>{{$p->nama_dosen}}</li>
                                                    @endforeach
                                                    </ul>
                                            </td>
                                            <td class="text-center align-middle" style="width: 10%">
                                                @if($d->approve_sidang != 1)
                                                    @foreach($d->bimbing_mahasiswa as $db)
                                                        @if ($db->approved == 1 && $db->approved_dosen == 0)
                                                            <li>Pembimbing {{$db->pembimbing_ke}} : <br><span class="badge bg-warning">Menunggu Persetujuan Dosen</span></li>
                                                        @elseif ($db->approved == 1 && $db->approved_dosen == 1)
                                                            <li>Pembimbing {{$db->pembimbing_ke}} : <br><span class="badge bg-success">Disetujui</span></li>
                                                        @elseif ($db->approved == 1 && $db->approved_dosen == 2)
                                                            <li>Pembimbing {{$db->pembimbing_ke}} : <br><span class="badge bg-danger">Dibatalkan</span></li>
                                                        @else
                                                            <li>Pembimbing {{$db->pembimbing_ke}} : <br><span class="badge bg-warning">{{$db->approved_dosen}}</span></li>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <li><span class="badge bg-primary">Diajukan Sidang</span> : {{$d->jadwal_ujian}} ({{$d->jadwal_jam_mulai}} - {{$d->jadwal_jam_selesai}})</li>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle text-nowrap">
                                                <div class="row">
                                                    {{-- <a href="#" class="btn btn-danger btn-sm my-2" title="Tolak Bimbingan" data-bs-toggle="modal" data-bs-target="#pembatalanModal{{$d->id}}"><i class="fa fa-ban"></i> Decline</a>
                                                    <a href="#" class="btn btn-secondary btn-sm my-2" data-bs-toggle="modal" data-bs-target="#detailModal" onclick="detailFunc({{$d}})"><i class="fa fa-eye"></i> Detail</a> --}}
                                                    {{-- <a href="{{route('mahasiswa.bimbingan.bimbingan-tugas-akhir.asistensi', $d)}}" class="btn btn-sm btn-primary my-2" title="Approve Bimbingan"><i class="fa fa-pencil-square-o"></i> Asistensi</a> --}}
                                                    <a href="{{route('mahasiswa.bimbingan.bimbingan-tugas-akhir.asistensi', $d->id)}}" class="btn btn-primary btn-sm my-2" title="Asistensi"><i class="fa fa-pencil-square-o"></i> Asistensi</a>
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
    @endif
</section>
@endsection
@push('css')
    <link rel="stylesheet" href="{{asset('assets/vendor_components/select2/dist/css/select2.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>

    function detailFunc(data) {
        $('#detail_judul').val(data.judul);
        $('#edit_tanggal_mulai').val(data.id_tanggal_mulai);
        $('#edit_tanggal_selesai').val(data.id_tanggal_selesai);
        $('#edit_lokasi').val(data.lokasi);
    }

    $(function () {
        $('#data').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "responsive": true,
        });

        $('#semester_select').select2({
            placeholder: '-- Pilih Semester --',
            width: '100%',
        });

        $('#semester_select').on('change', function (e) {
            var id = $(this).val();
            window.location.href = "{{route('mahasiswa.bimbingan.bimbingan-tugas-akhir')}}?semester=" + id;
        });
    });
</script>
@endpush

