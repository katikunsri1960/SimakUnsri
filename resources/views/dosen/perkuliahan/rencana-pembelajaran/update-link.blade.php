@extends('layouts.dosen')
@section('title')
Pengisian Nilai Perkuliahan
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
								<h2 class="mb-10">Rencana Pembelajaran Semester</h2>
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
                            <a class="btn btn-rounded bg-warning-light" href="{{route('dosen.perkuliahan.rencana-pembelajaran.detail', ['matkul' => $matkul->id_matkul])}}"><i class="fa fa-chevron-left"><span class="path1"></span><span class="path2"></span></i> Kembali</a>
                        </div>                             
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <h3 class="fw-500 text-dark mt-0">Update Link Repository Rencana Pembelajaran Semester</h3>
                        </div>                             
                    </div>
                </div>
                <!-- /.box-header -->
                <form class="form" method="POST" id="update-link-rps" action="{{ route('dosen.perkuliahan.rencana-pembelajaran.update-link', ['matkul' => $matkul->id_matkul]) }}">
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="link_rps" class="form-label">Link Repository RPS</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="link_rps"
                                        id="link_rps"
                                        aria-describedby="helpId"
                                        value="{{$matkul->link_rps}}"
                                        required
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-end">
                        <a class="btn btn-warning me-1" href="{{route('dosen.perkuliahan.rencana-pembelajaran.detail', ['matkul' => $matkul->id_matkul])}}">
                            <i class="ti-trash"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti-save-alt"></i> Save
                        </button>
                    </div> 
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

    $('#update-link-rps').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Perubahan Link Repository Rencana Pembelajaran Semester',
            text: "Apakah anda yakin ingin?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#update-link-rps').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });

</script>
@endpush

