@extends('layouts.dosen')
@section('title')
Komponen Evaluasi Kelas Perkuliahan
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
								<h2 class="mb-10">Komponen Evaluasi Kelas Perkuliahan</h2>
								<p class="mb-0 text-fade fs-18">Universitas Sriwijaya</p>
							</div>
						</div>
					<div>
				</div>
			</div>							
		</div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-12">
            <div class="box bg-light">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-xl-4 col-lg-12 pb-20">
                            <a class="btn btn-rounded bg-warning-light" href="{{route('dosen.penilaian.penilaian-perkuliahan')}}"><i class="fa fa-chevron-left"><span class="path1"></span><span class="path2"></span></i> Kembali</a>
                        </div>                             
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <h3 class="fw-500 text-dark mt-0">Komponen Evaluasi Kelas ({{$kelas[0]['nama_mata_kuliah']}} - {{$kelas[0]['nama_kelas_kuliah']}})</h3>
                            <p class="mb-0 text-fade fs-18">Total Keseluruhan Komponen Evaluasi Sama Dengan 100 %</p>
                        </div>                             
                    </div>
                </div>
                <!-- /.box-header -->
                <form class="form" method="POST" id="komponen-evaluasi-store" action="{{ route('dosen.penilaian.komponen-evaluasi.store', ['kelas' => $kelas[0]['id_kelas_kuliah']]) }}">
                    @csrf
                    @if($data->isEmpty())
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Participatory Activity</label>
                                        <input name="participatory" type="number" class="form-control" value="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Project Outcomes</label>
                                        <input name="project_outcomes" type="number" class="form-control" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Assignment</label>
                                        <input name="assignment" type="number" class="form-control" value="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Quiz</label>
                                        <input name="quiz" type="number" class="form-control" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Midterm Exam</label>
                                        <input name="midterm_exam" type="number" class="form-control" value="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Finalterm Exam</label>
                                        <input name="finalterm_exam" type="number" class="form-control" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-end">
                            <button type="button" class="btn btn-warning me-1">
                                <i class="ti-trash"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti-save-alt"></i> Save
                            </button>
                        </div> 
                    @else
                        @foreach($data as $d)
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Participatory Activity</label>
                                            <input name="participatory" type="number" class="form-control" value="{{ $d->id_jenis_evaluasi == '2' ? $d->bobot_evaluasi : '0' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Project Outcomes</label>
                                            <input name="project_outcomes" type="number" class="form-control" value="{{ $d->id_jenis_evaluasi == '3' ? $d->bobot_evaluasi : '0' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Assignment</label>
                                            <input name="assignment" type="number" class="form-control" value="{{ $d->id_jenis_evaluasi == '4' && $d->nomor_urut == '3' ? $d->bobot_evaluasi : '0' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Quiz</label>
                                            <input name="quiz" type="number" class="form-control" value="{{ $d->id_jenis_evaluasi == '4' && $d->nomor_urut == '4' ? $d->bobot_evaluasi : '0' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Midterm Exam</label>
                                            <input name="midterm_exam" type="number" class="form-control" value="{{ $d->id_jenis_evaluasi == '4' && $d->nomor_urut == '5' ? $d->bobot_evaluasi : '0' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Finalterm Exam</label>
                                            <input name="finalterm_exam" type="number" class="form-control" value="{{ $d->id_jenis_evaluasi == '4' && $d->nomor_urut == '6' ? $d->bobot_evaluasi : '0' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <!-- /.box-body -->
                        <div class="box-footer text-end">
                            <button type="button" class="btn btn-warning me-1">
                                <i class="ti-trash"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" disabled>
                                <i class="ti-save-alt"></i> Save
                            </button>
                        </div> 
                    @endif    
                </form>

            </div>
            <!-- /.box -->			
        </div>
    </div>			
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>

    $('#komponen-evaluasi-store').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Mengatur Bobot Komponen Evaluasi',
            text: "Apakah anda yakin ingin?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#komponen-evaluasi-store').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });

</script>
@endpush
