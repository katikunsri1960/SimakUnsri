@extends('layouts.dosen')
@section('title')
Sidang Mahasiswa
@endsection
@section('content')
@include('swal')
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-12">
			<div class="box pull-up">
				<div class="box-body bg-img bg-primary-light">
					<div class="d-lg-flex align-items-center justify-content-between">
						<div class="d-lg-flex align-items-center mb-30 mb-xl-0 w-p100">
			    			<img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" class="img-fluid max-w-250" alt="" />
							<div class="ms-30">
								<h2 class="mb-10">Penguji Sidang Mahasiswa</h2>
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
                            <h4 class="fw-500 text-dark mt-0">Daftar Sidang Mahasiswa</h4>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="dt" class="table table-bordered table-striped text-center" style="font-size: 11px;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NAMA AKTIVITAS<br>(MK Konversi)</th>
                                    <th>Mahasiswa</th>
                                    <th>Judul</th>
                                    <th>Penguji</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>
                                        {{ strtoupper($d->nama_jenis_aktivitas)}}<br>({{$d->konversi->kode_mata_kuliah}} - {{$d->konversi->nama_mata_kuliah}})
                                    </td>
                                    <td class="text-start align-middle">
                                        <ul>
                                        @foreach ($d->anggota_aktivitas as $m)
                                            <li>
                                                {{$m->mahasiswa->nama_mahasiswa}}
                                                ({{$m->mahasiswa->nim}})
                                            </li>
                                        @endforeach
                                        </ul>
                                    </td> 
                                    <td class="text-start align-middle">
                                        <p style="text-align: justify">
                                            {{$d->judul}}
                                        </p>
                                    </td>
                                    <td class="text-start align-middle">
                                        <ul>
                                        @foreach ($d->uji_mahasiswa as $u)
                                            <li>{{$u->nama_kategori_kegiatan}} : {{$u->dosen->nama_dosen}}</li>
                                        @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-start align-middle" style="width: 10%">
                                        <ul>
                                            @foreach($d->uji_mahasiswa as $db)
                                                @if ($db->status_uji_mahasiswa == 1)
                                                    <li>Penguji {{$db->penguji_ke}} : <br><span class="badge bg-warning">Menunggu Persetujuan Dosen</span></li>
                                                @elseif ($db->status_uji_mahasiswa == 2)
                                                    <li>Penguji {{$db->penguji_ke}} : <br><span class="badge bg-success">Disetujui</span></li>
                                                @else
                                                    <li>Penguji {{$db->penguji_ke}} : <br><span class="badge bg-danger">Dibatalkan</span></li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        @if($d->count_approved > 0)
                                            <form action="{{route('dosen.penilaian.sidang-mahasiswa.approve-penguji', $d)}}" method="post" id="approveForm{{$d->id_aktivitas}}" class="approve-class" data-id='{{$d->id_aktivitas}}'>
                                                @csrf
                                                <div class="row">
                                                    <button type="submit" class="btn btn-sm btn-primary" title="Setujui Pengujian"><i class="fa fa-thumbs-up"></i> Approve</button>
                                                </div>
                                            </form>
                                        @endif
                                        <form action="{{route('dosen.penilaian.sidang-mahasiswa.decline-penguji', $d)}}" method="post" id="declineForm{{$d->id_aktivitas}}" class="decline-class" data-id='{{$d->id_aktivitas}}'>
                                            @csrf
                                            <div class="row">
                                                <button type="submit" class="btn btn-sm btn-danger my-2" title="Tolak Pengujian"><i class="fa fa-ban"></i> Decline</button>
                                            </div>
                                        </form>
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
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
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

        $('.approve-class').on('submit', function(e) {
            e.preventDefault();
            var formId = $(this).data('id');
            swal({
                title: 'Apakah Anda Yakin??',
                text: "Setelah disetujui, penguji tidak bisa diubah lagi!",
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

        $('.decline-class').on('submit', function(e) {
            e.preventDefault();
            var formId = $(this).data('id');
            swal({
                title: 'Apakah Anda Yakin??',
                text: "Setelah dibatalkan, penguji tidak bisa diubah lagi!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $(`#declineForm${formId}`).unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });
</script>
@endpush

