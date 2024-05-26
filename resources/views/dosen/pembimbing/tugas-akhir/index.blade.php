@extends('layouts.dosen')
@section('title')
Bimbingan Tugas Akhir Dosen
@endsection
@section('content')
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-12">
			<div class="box pull-up">
				<div class="box-body bg-img bg-primary-light">
					<div class="d-lg-flex align-items-center justify-content-between">
						<div class="d-lg-flex align-items-center mb-30 mb-xl-0 w-p100">
			    			<img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" class="img-fluid max-w-250" alt="" />
							<div class="ms-30">
								<h2 class="mb-10">Bimbingan Tugas Akhir Dosen</h2>
								<p class="mb-0 text-fade fs-18">Universitas Sriwijaya</p>
							</div>
						</div>
					<div>
				</div>
			</div>
		</div>
    </div>
    <div class="row">
        <div class="col-xxl-12">
            <div class="box box-body mb-0">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 d-flex justify-content-between">
                        <div class="d-flex justify-content-start">
                            <h4 class="fw-500 text-dark mt-0">Daftar Bimbingan Tugas Akhir Dosen</h4>
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
                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="dt" class="table table-bordered table-striped" style="font-size: 11px">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">JUDUL</th>
                                    <th class="text-center align-middle">PRODI</th>
                                    <th class="text-center align-middle">MAHASISWA</th>
                                    <th class="text-center align-middle">NO SK<br>(Tanggal SK)</th>
                                    <th class="text-center align-middle">Tanggal Mulai</th>
                                    <th class="text-center align-middle">Tanggal Selesai</th>
                                    <th class="text-center align-middle">Pembimbing</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td class="text-start align-middle" style="width: 25%">{{$d->judul}}</td>
                                    <td class="text-center align-middle" style="width: 10%">{{$d->nama_prodi}}</td>
                                    <td class="text-center align-middle" style="width: 15%">{{$d->anggota_aktivitas_personal->nim}}<br>{{$d->anggota_aktivitas_personal->nama_mahasiswa}}</td>
                                    <td class="text-center align-middle">
                                        {{$d->sk_tugas}}<br>({{$d->id_tanggal_sk_tugas}})
                                    </td>
                                    <td class="text-center align-middle">
                                        {{$d->tanggal_mulai ? $d->id_tanggal_mulai : '-'}}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{$d->tanggal_selesai ? $d->id_tanggal_selesai : '-'}}
                                    </td>
                                    <td class="text-start align-middle text-nowrap">
                                        <ul>
                                        @foreach ($d->bimbing_mahasiswa as $p)
                                            <li>Pembimbing {{$p->pembimbing_ke}} :<br>{{$p->nama_dosen}}</li>
                                        @endforeach
                                        </ul>
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
    $(function () {
        $('#dt').DataTable({
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
            window.location.href = "{{route('dosen.pembimbing.bimbingan-tugas-akhir')}}?semester=" + id;
        });
    });
</script>
@endpush

