@extends('layouts.dosen')
@section('title')
Pembimbingan Karya Ilmiah Mahasiswa
@endsection
@section('content')
@include('swal')
@include('dosen.pembimbing.tugas-akhir.detail')
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-12">
			<div class="box pull-up">
				<div class="box-body bg-img bg-primary-light">
					<div class="d-lg-flex align-items-center justify-content-between">
						<div class="d-lg-flex align-items-center mb-30 mb-xl-0 w-p100">
			    			<img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" class="img-fluid max-w-250" alt="" />
							<div class="ms-30">
								<h2 class="mb-10">Pembimbingan Karya Ilmiah Mahasiswa</h2>
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
                            <h4 class="fw-500 text-dark mt-0">Daftar Bimbingan Karya Ilmiah Dosen</h4>
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
                                    <th class="text-center align-middle">PRODI</th>
                                    <th class="text-center align-middle">MAHASISWA</th>
                                    <th class="text-center align-middle">NO SK<br>(Tanggal SK)</th>
                                    <th class="text-center align-middle">Pembimbing</th>
                                    <th class="text-center align-middle">Status</th>
                                    <th class="text-center align-middle">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                @include('dosen.pembimbing.tugas-akhir.pembatalan-bimbingan')
                                <tr>

                                    <td class="text-center align-middle" style="width: 10%">{{$d->nama_prodi}}</td>
                                    <td class="text-center align-middle" style="width: 15%">{{$d->anggota_aktivitas_personal->nim}}<br>{{$d->anggota_aktivitas_personal->nama_mahasiswa}}</td>
                                    <td class="text-center align-middle">
                                        {{$d->sk_tugas}}<br>({{$d->id_tanggal_sk_tugas}})
                                    </td>
                                    <td class="text-start align-middle text-nowrap">
                                        <ul>
                                            @foreach ($d->bimbing_mahasiswa as $p)
                                                <li>Pembimbing {{$p->pembimbing_ke}} :<br>{{$p->nama_dosen}}</li>
                                            @endforeach
                                            </ul>
                                    </td>
                                    <td class="text-center align-middle" style="width: 10%">
                                        @foreach($d->bimbing_mahasiswa as $db)
                                            @if ($db->approved == 1 && $db->approved_dosen == 0)
                                                <span class="badge bg-warning">Menunggu Persetujuan Dosen</span>
                                            @elseif ($db->approved == 1 && $db->approved_dosen == 1)
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif ($db->approved == 1 && $db->approved_dosen == 2)
                                                <span class="badge bg-danger">Dibatalkan</span>
                                            @else
                                                <span class="badge bg-warning">{{$db->approved_dosen}}</span>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="text-center align-middle text-nowrap">
                                        <div class="row">
                                            @if($d->count_approved > 0)
                                            <form action="{{route('dosen.pembimbing.bimbingan-tugas-akhir.approve-pembimbing', $d)}}" method="post" id="approveForm{{$d->id_aktivitas}}" class="approve-class" data-id='{{$d->id_aktivitas}}'>
                                                @csrf
                                                <div class="row">
                                                    <button type="submit" class="btn btn-sm btn-primary" title="Setujui Bimbingan"><i class="fa fa-thumbs-up"></i> Approve</button>
                                                </div>
                                            </form>
                                            @endif
                                            <a href="#" class="btn btn-danger btn-sm my-2" title="Tolak Bimbingan" data-bs-toggle="modal" data-bs-target="#pembatalanModal{{$d->id}}"><i class="fa fa-ban"></i> Decline</a>
                                            <a href="#" class="btn btn-secondary btn-sm my-2" data-bs-toggle="modal" data-bs-target="#detailModal" onclick="detailFunc({{$d}})"><i class="fa fa-eye"></i> Detail</a>
                                            <a href="{{route('dosen.pembimbing.bimbingan-tugas-akhir.asistensi', $d)}}" class="btn btn-sm btn-primary my-2" title="Approve Bimbingan"><i class="fa fa-pencil-square-o"></i> Asistensi</a>
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

        $('.approve-class').on('submit', function(e) {
            e.preventDefault();
            var formId = $(this).data('id');
            swal({
                title: 'Apakah Anda Yakin??',
                text: "Setelah disetujui, pembimbing tidak bisa diubah lagi!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $(`#approveForm${formId}`).unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });
</script>
@endpush

