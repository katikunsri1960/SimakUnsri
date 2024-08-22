@extends('layouts.dosen')
@section('title')
Bimbingan Akademik Dosen
@endsection
@section('content')
@push('header')
<div class="mx-4">
    <a href="{{route('dosen.pembimbing.bimbingan-akademik')}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-12">
			<div class="box pull-up">
				<div class="box-body bg-img bg-primary-light">
					<div class="d-lg-flex align-items-center justify-content-between">
						<div class="d-lg-flex align-items-center mb-30 mb-xl-0 w-p100">
			    			<img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" class="img-fluid max-w-250" alt="" />
							<div class="ms-30">
                                <h2 class="mb-10">KRS</h2>
								<p class="mb-0 text-fade fs-18">{{$riwayat->nama_mahasiswa}}</p>
							</div>
						</div>
					<div>
				</div>
			</div>
		</div>
    </div>
    @include('swal')
    <div class="row">
        <div class="col-xxl-12">
            <div class="box box-body mb-0">
                <div class="row mb-10">
                    <p class="text-danger text-end">*Pembatalan KRS hanya bisa dilakukan pada masa KPRS.</p>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12  d-flex justify-content-between">
                        <div class="d-flex justify-content-start">
                            <table>
                                <tr>
                                    <td><h4>Nama</h4></td>
                                    <td class="px-3"><h4>:</h4></td>
                                    <td class="px-3"><h4>{{$riwayat->nama_mahasiswa}}</h4></td>
                                </tr>
                                <tr>
                                    <td><h4>NIM</h4></td>
                                    <td class="px-3"><h4>:</h4></td>
                                    <td class="px-3"><h4>{{$riwayat->nim}}</h4></td>
                                </tr>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end align-middle">
                            <div class="">
                                @if ($data->where('approved', '0')->count()+$aktivitas->where('approve_krs', '0')->count()+$aktivitas_mbkm->where('approve_krs', '0')->count() > 0)
                                    <form action="{{route('dosen.pembimbing.bimbingan-akademik.approve-all', ['riwayat' => $riwayat])}}" method="post" id="approveAll">
                                    @csrf
                                        <button class="btn btn-primary btn-rounded" type="submit" @if ($data->where('approved', '0')->count()+$aktivitas->where('approve_krs', '0')->count() == 0)
                                            disabled
                                        @endif><i class="fa fa-check"></i> Setujui KRS</button>
                                    </form>
                                @endif
                                @if ($data->where('approved', '1')->count()+$aktivitas->where('approve_krs', '1')->count()+$aktivitas_mbkm->where('approve_krs', '1')->count() > 0)
                                    <form action="{{route('dosen.pembimbing.bimbingan-akademik.batal-krs', ['riwayat' => $riwayat])}}" method="post" id="batalKRS">
                                    @csrf
                                        <button class="btn btn-warning btn-rounded" type="submit"
                                            @if ($data->where('approved', '1')->count() + $aktivitas->where('approve_krs', '1')->count() == 0 || date('Y-m-d') < $semester_aktif->tanggal_mulai_kprs)
                                                disabled
                                            @endif>
                                            <i class="fa fa-undo"></i> Batalkan Persetujuan KRS
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="krs" class="table table-bordered table-striped" style="font-size: 12px">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center align-middle">NO</th>
                                    <th class="text-center align-middle">KODE MK</th>
                                    <th class="text-center align-middle">NAMA MK</th>
                                    <th class="text-center align-middle">SKS</th>
                                    <th class="text-center align-middle">KELAS</th>
                                    <th class="text-center align-middle">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalSks = 0;
                                @endphp
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center align-middle">{{$d->kelas_kuliah->matkul->kode_mata_kuliah}}</td>
                                    <td class="text-start align-middle">{{$d->kelas_kuliah->matkul->nama_mata_kuliah}}</td>
                                    <td class="text-center align-middle">{{$d->kelas_kuliah->matkul->sks_mata_kuliah}}
                                        @php
                                            $totalSks += $d->kelas_kuliah->matkul->sks_mata_kuliah;
                                        @endphp
                                    </td>
                                    <td class="text-center align-middle">{{$d->kelas_kuliah->nama_kelas_kuliah}}</td>
                                    <td class="text-center align-middle">
                                        @if ($d->approved == '1')
                                            <span class="badge bg-success rounded">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Belum Disetujui</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @foreach ($aktivitas as $a)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center align-middle">{{$a->konversi ? $a->konversi->kode_mata_kuliah : '-'}}</td>
                                    <td class="text-start align-middle">{{$a->konversi ? $a->konversi->nama_mata_kuliah : '-'}}</td>
                                    <td class="text-center align-middle">{{$a->konversi ? $a->konversi->sks_mata_kuliah : '-'}}
                                        @php
                                            $totalSks += $a->konversi ? $a->konversi->sks_mata_kuliah : 0;
                                        @endphp
                                    </td>
                                    <td class="text-center align-middle"> - </td>
                                    <td class="text-center align-middle">
                                        @if ($a->approve_krs == '1')
                                            <span class="badge bg-success rounded">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Belum Disetujui</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center align-middle" colspan="3">Total</th>
                                    <th class="text-center align-middle">{{$totalSks}}</th>
                                    <th class="text-center align-middle" colspan="2"></th>
                                </tr>
                            </tfoot>
					  </table>
                    </div>
                </div>
                <hr>
                <div class="row my-10">
                    <h4 class="text-center">Aktivitas MBKM Eksternal</h4>
                </div>
                <hr>
                <div class="row">
                    <div class="table-responsive">
                        <table id="krs_mbkm" class="table table-bordered table-striped" style="font-size: 12px">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center align-middle">NO</th>
                                    <th class="text-center align-middle">NAMA AKTIVITAS</th>
                                    <th class="text-center align-middle">JUDUL AKTIVITAS</th>
                                    <th class="text-center align-middle">SEMESTER</th>
                                    <th class="text-center align-middle">LOKASI</th>
                                    <th class="text-center align-middle">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($aktivitas_mbkm as $am)
                                <tr>
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center align-middle">{{$am->nama_jenis_aktivitas}}</td>
                                    <td class="text-start align-middle">{{$am->judul}}</td>
                                    <td class="text-center align-middle">{{$am->nama_semester}}</td>
                                    <td class="text-center align-middle">{{$am->lokasi}}</td>
                                    <td class="text-center align-middle">
                                        @if ($am->approve_krs == '1')
                                            <span class="badge bg-success rounded">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Belum Disetujui</span>
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
    <script>
        $(function () {
            $('#krs').DataTable({
                "paging": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
            });

            $('#krs_mbkm').DataTable({
                "paging": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
            });

            confirmSubmit('approveAll');
            confirmSubmit('batalKRS');
        });
    </script>
@endpush

