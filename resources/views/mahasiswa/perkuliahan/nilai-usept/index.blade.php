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
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header">
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
                <div class="box-body">
                    <h3 class="text-info mb-25"><i class="fa fa-book"></i> Daftar Nilai Tes USEPT</h3>
                    <hr class="my-15">
                    <div class="table-responsive">
                        <table id="test" class="table table-bordered table-striped text-center">
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
                <div class="box-footer">
                    <h3 class="text-info mb-25"><i class="fa fa-book"></i> Daftar Nilai Course USEPT</h3>
                    {{-- <hr class="my-15"> --}}
                    <div class="table-responsive">
                        <table id="course" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Tanggal Upload</th>                                    
                                    <th>Nilai Angka</th>
                                    <th>Nilai Huruf</th>
                                    <th>Nilai Konversi USEPT</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($course_data as $c)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td class="text-center align-middle" style="white-space:nowrap;">{{$mahasiswa->nim}}</td>
                                        <td class="text-start align-middle">{{$mahasiswa->nama_mahasiswa}}</td>
                                        <td>{{ date('d M Y', strtotime($c->tgl_upload)) }}</td>
                                        @if($c->total_score < 71)
                                            <td class="text-center align-middle" colspan="4"> 
                                                <span class="badge bg-danger">Belum Lulus</span>
                                            </td> 
                                        @else
                                            <td>{{$c->total_score}}</td>
                                            <td>{{$c->grade}}</td>
                                            <td>{{$c->konversi}}</td>
                                            <td class="text-center align-middle"> 
                                                @if ($c->konversi < $usept_prodi->nilai_usept)
                                                    <span class="badge bg-danger">Belum Lulus</span>
                                                @elseif ($usept_prodi->nilai_usept == NULL)
                                                    <span class="badge bg-danger">Nilai Kelulusan Belum diatur</span>
                                                @elseif ($c->konversi >= $usept_prodi->nilai_usept)
                                                    <span class="badge bg-success">Lulus</span>
                                                @endif
                                            </td>
                                        @endif
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
        $('#test').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
            // "scrollCollapse": true,
            // "scrollY": "550px",
        });

        $('#course').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
            // "scrollCollapse": true,
            // "scrollY": "550px",
        });

    });
</script>

@endpush

