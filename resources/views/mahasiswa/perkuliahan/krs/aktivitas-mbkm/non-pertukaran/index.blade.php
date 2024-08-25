@extends('layouts.mahasiswa')
@section('title')
Aktivitas MBKM - Non Pertukaran Pelajar
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
								<h2 class="mb-10">Aktivitas MBKM Non Pertukaran Pelajar,  
                                    {{-- {{auth()->user()->name}}</h2> --}}
                                <p class="text-dark mb-0 fs-16">
                                    SIMAK Universitas Sriwijaya
                                </p>
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
                <div class="row mx-20 " >
                    <div class="col-12 px-25">
                        <div class="box no-shadow mb-0 bg-transparent">
                            <div class="box-header no-border px-0" style="text-align-last: left; padding-bottom:20px">
                            <a type="button" href="{{route('mahasiswa.perkuliahan.mbkm.view')}}" class="btn btn-warning btn-rounded waves-effect waves-light">
                            <i class="fa-solid fa-arrow-left"></i>
                            </a>
                            <h3 class="box-title px-3">Daftar Aktivitas MBKM Non Pertukaran Pelajar</h3>
                            </div>
                        </div>
                    </div>
                <div>
                <div class="row mb-5">
                    <div class="col-xl-12 col-lg-12 text-end">
                        <div class="btn-group">
                            <a class="btn btn-rounded bg-success-light " href="{{route('mahasiswa.perkuliahan.mbkm.tambah-non-pertukaran')}}"><i class="fa fa-plus"><span class="path1"></span><span class="path2"></span></i> Tambah Aktivitas MBKM</a>
                        </div>   
                    </div>                           
                </div><br>
                <div class="row">
                    <div class="table-responsive">
                        <table id="aktivitas-mbkm" class="table table-bordered table-striped text-left">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Nama Aktivitas</th>
                                    <th class="text-center align-middle">Judul</th>
                                    <th class="text-center align-middle">Lokasi</th>
                                    <th class="text-center align-middle">SKS Konversi</th>
                                    <th class="text-center align-middle">Dosen Pembimbing</th>
                                    <th class="text-center align-middle">Status</th>
                                    <th class="text-center align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                
                                @foreach ($data as $data)
                                    <tr>
                                        <td class="text-center align-middle" style="width:2%">{{ $no++ }}</td>
                                        <td class="text-center align-middle" style="width:5%" style="white-space: nowrap;">{{ $data->nama_jenis_aktivitas }}</td>
                                        <td class="text-start align-middle" style="white-space: nowrap;">{{ $data->judul }}</td>
                                        <td class="text-center align-middle" style="white-space: nowrap;">{{ $data->lokasi }}</td>
                                        <td class="text-center align-middle" style="width:10%">
                                            <div>
                                                {{ $data->sks_aktivitas== NULL ? 'Tidak Diisi' : $data->sks_aktivitas }}
                                            </div>
                                        </td>
                                        <td class="text-start align-middle"  style="white-space: nowrap; width:20%">
                                            @foreach($data->bimbing_mahasiswa as $dosen_bimbing)
                                                <ul>
                                                    <li>
                                                        {{$dosen_bimbing->nama_dosen}} 
                                                        {{-- <p>{{$dosen_bimbing->pembimbing_ke == 1 ? '(Pembimbing Utama)' : '(Pembimbing Pendamping)'}}</p> --}}
                                                    </li>
                                                </ul> 
                                            @endforeach
                                        </td>
                                        <td class="text-center align-middle" style="width:10%">
                                            @if ($data->approve_krs == 0)
                                                <span class="badge badge-lg badge-danger-light">Belum Disetujui</span>
                                            @elseif ($data->bimbing_mahasiswa->first()->approved == 0)
                                                <span class="badge badge-lg badge-warning-light">Menunggu konfirmasi Koprodi</span>
                                            @elseif ($data->approve_krs == 1 && $data->bimbing_mahasiswa->first()->approved_dosen == 0)
                                                <span class="badge badge-lg badge-warning-light">Menunggu konfirmasi dosen</span>
                                            @elseif ($data->approve_krs == 1 && $data->bimbing_mahasiswa->first()->approved_dosen == 2)
                                                <span class="badge badge-lg badge-danger-light">Ditolak dosen pembimbing</span>
                                            @else
                                                <span class="badge badge-lg badge-success-light">Disetujui</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle" style="width:3%">
                                            <form action="{{route('mahasiswa.perkuliahan.mbkm.hapus-aktivitas',['id'=>$data->id])}}" method="post" class="delete-form" data-id="{{$data->id}}" id="deleteForm{{$data->id}}">
                                                @csrf
                                                @method('delete')
                                                {{-- <button type="submit" class="btn btn-danger" data-id="{{ $data->id }}" title="Hapus Data" {{ $today>$deadline || $data->approved == 1 ?  'disabled' : '' }}> 
                                                    <i class="fa fa-trash"></i>
                                                </button> --}}
                                                <button type="submit" class="btn btn-danger rounded-10" data-id="{{ $data->id }}" title="Hapus Data" {{ ($today <= $batas_isi_krs && $data->approve_krs == 0) ? '' : 'disabled' }}>
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{-- <tfoot>
                                <tr>
                                    <td class="text-center align-middle" colspan="4"><strong>Total SKS Diambil</strong></td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot> --}}
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
      $(document).ready(function() {
        $('#aktivitas-mbkm').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
            // "scrollCollapse": true,
            // "scrollY": "550px",
        });

    });
</script>

@endpush

