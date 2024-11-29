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
                            <h3 class="fw-500 text-dark mt-0">Komponen Evaluasi Kelas ({{$kelas->matkul->nama_mata_kuliah}} - {{$kelas->nama_kelas_kuliah}})</h3>
                            <p class="mb-0 text-fade fs-18">Total Keseluruhan Komponen Evaluasi Sama Dengan 100 %</p>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                @if($data->isEmpty())
                    <form class="form" method="POST" id="komponen-evaluasi-store" action="{{ route('dosen.penilaian.komponen-evaluasi.store', ['kelas' => $kelas->id_kelas_kuliah]) }}">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-1 text-center align-middle">
                                    <h4>No</h4>
                                </div>
                                <div class="col-md-5 text-center align-middle">
                                    <h4>Nama Komponen Evaluasi</h4>
                                </div>
                                <div class="col-md-6 text-center align-middle">
                                    <h4>Bobot %</h4>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-1 text-center align-middle">
                                    <h4>1.</h4>
                                </div>
                                <div class="col-md-5">
                                    <h4>Aktivitas Partisipatif (Participatory Activity)</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input name="participatory" type="number" class="form-control" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 text-center align-middle">
                                    <h4>2.</h4>
                                </div>
                                <div class="col-md-5">
                                    <h4>Hasil Proyek (Project Outcomes)</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input name="project_outcomes" type="number" class="form-control" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 text-center align-middle">
                                    <h4>3.</h4>
                                </div>
                                <div class="col-md-5">
                                    <h4>Tugas (Assignment)</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input name="assignment" type="number" class="form-control" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 text-center align-middle">
                                    <h4>4.</h4>
                                </div>
                                <div class="col-md-5">
                                    <h4>Kuis (Quiz)</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input name="quiz" type="number" class="form-control" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 text-center align-middle">
                                    <h4>5.</h4>
                                </div>
                                <div class="col-md-5">
                                    <h4>Ujian Tengah Semester (Midterm Exam)</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input name="midterm_exam" type="number" class="form-control" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 text-center align-middle">
                                    <h4>6.</h4>
                                </div>
                                <div class="col-md-5">
                                    <h4>Ujian Akhir Semester (Finalterm Exam)</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input name="finalterm_exam" type="number" class="form-control" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row p-5">
                            <strong>
                                <p class="text-danger p-5">Untuk Point 1 dan 2 disarankan memiliki total minimum 50%</p>
                            </strong>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-end">
                            <a class="btn btn-warning me-1" href="{{route('dosen.penilaian.penilaian-perkuliahan')}}">
                                <i class="ti-trash"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" @if(date("Y-m-d") > $batas_pengisian) disabled @endif>
                                <i class="ti-save-alt"></i> Save
                            </button>
                        </div>
                    </form>
                @else
                    <form class="form" method="POST" id="komponen-evaluasi-update" action="{{ route('dosen.penilaian.komponen-evaluasi.update', ['kelas' => $kelas->id_kelas_kuliah]) }}">
                        @csrf
                        <div class="box-body">
                        <div class="row">
                                <div class="col-md-1 text-center align-middle">
                                    <h4>No</h4>
                                </div>
                                <div class="col-md-5 text-center align-middle">
                                    <h4>Nama Komponen Evaluasi</h4>
                                </div>
                                <div class="col-md-6 text-center align-middle">
                                    <h4>Bobot %</h4>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-1 text-center align-middle">
                                    <h4>1.</h4>
                                </div>
                                <div class="col-md-5">
                                    <h4>Aktivitas Partisipatif (Participatory Activity)</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <input name="participatory" type="number" class="form-control" value="{{ $participatory * 100 }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 text-center align-middle">
                                    <h4>2.</h4>
                                </div>
                                <div class="col-md-5">
                                    <h4>Hasil Proyek (Project Outcomes)</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <input name="project_outcomes" type="number" class="form-control" value="{{ $project_outcomes * 100 }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 text-center align-middle">
                                    <h4>3.</h4>
                                </div>
                                <div class="col-md-5">
                                    <h4>Tugas (Assignment)</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <input name="assignment" type="number" class="form-control" value="{{ $assignment * 100 }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 text-center align-middle">
                                    <h4>4.</h4>
                                </div>
                                <div class="col-md-5">
                                    <h4>Kuis (Quiz)</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <input name="quiz" type="number" class="form-control" value="{{ $quiz * 100 }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 text-center align-middle">
                                    <h4>5.</h4>
                                </div>
                                <div class="col-md-5">
                                    <h4>Ujian Tengah Semester (Midterm Exam)</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <input name="midterm_exam" type="number" class="form-control" value="{{ $midterm_exam * 100 }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 text-center align-middle">
                                    <h4>6.</h4>
                                </div>
                                <div class="col-md-5">
                                    <h4>Ujian Akhir Semester (Finalterm Exam)</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <input name="finalterm_exam" type="number" class="form-control" value="{{ $finalterm_exam * 100 }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row p-5">
                            <strong>
                                <p class="text-danger p-5">Untuk Point 1 dan 2 disarankan memiliki total minimum 50%</p>
                            </strong>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-end">
                            <a class="btn btn-warning me-1" href="{{route('dosen.penilaian.penilaian-perkuliahan')}}">
                                <i class="ti-trash"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" @if(date("Y-m-d") > $batas_pengisian) disabled @endif>
                                <i class="ti-save-alt"></i> Save
                            </button>
                        </div>
                    </form>
                @endif

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

    $('#komponen-evaluasi-update').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Mengubah Bobot Komponen Evaluasi',
            text: "Apakah anda yakin ingin?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#komponen-evaluasi-update').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });

</script>
@endpush

