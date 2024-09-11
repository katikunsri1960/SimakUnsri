@extends('layouts.mahasiswa')
@section('title')
Nilai USEPT Mahasiswa
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
								<h2 class="mb-10">Histori Nilai USEPT Mahasiswa,  {{auth()->user()->name}}</h2>
								<p class="mb-0 text-fade fs-18">SIMAK Universitas Sriwijaya</p>
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
                <div class="row mb-50">
                    <div class="col-xl-12 col-lg-12">
                        <h3 class="fw-500 text-dark mt-0">Daftar Nilai USEPT Mahasiswa</h3>
                        <h4 class="mb-5">
                            Nilai Kelulusan USEPT Prodi {{$mahasiswa->nama_program_studi}} :
                            @if(empty($usept_prodi->nilai_usept))
                                <span class="badge badge-xl badge-danger mb-5">Nilai Kelulusan Belum diatur</span>
                            @else
                                <span class="badge badge-xl badge-success mb-5 px-20">{{ $usept_prodi->nilai_usept }}</span>
                            @endif
                        </h4>
                    </div>                             
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>                                    
                                    <th>Tanggal Ujian</th>
                                    <th>Skor USEPT</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td class="text-center align-middle" style="white-space:nowrap;">{{$mahasiswa->nim}}</td>
                                        <td class="text-start align-middle">{{$mahasiswa->nama_mahasiswa}}</td>
                                        <td>{{ date('d M Y', strtotime($d->tgl_test)) }}</td>
                                        <td>{{$d->score}}</td>
                                        <td class="text-center align-middle"> 
                                            @if ($d->score < $usept_prodi->nilai_usept)
                                                <span class="badge bg-danger">Belum Lulus</span>
                                            @elseif ($usept_prodi->nilai_usept == NULL)
                                                <span class="badge bg-danger">Nilai Kelulusan Belum diatur</span>
                                            @elseif ($d->score >= $usept_prodi->nilai_usept)
                                                <span class="badge bg-success">Lulus</span>
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
      $(document).ready(function() {
        $('#data').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
            // "scrollCollapse": true,
            // "scrollY": "550px",
        });

    });
</script>

@endpush

