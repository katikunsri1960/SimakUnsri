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
								<h2 class="mb-10">Pengisian Nilai Perkuliahan Mahasiswa</h2>
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
                            <h3 class="fw-500 text-dark mt-0">Upload DPNA Kelas Kuliah ({{$kelas[0]['nama_mata_kuliah']}} - {{$kelas[0]['nama_kelas_kuliah']}})</h3>
                        </div>                             
                    </div>
                </div>
                <!-- /.box-header -->
                <form class="form" method="POST" id="upload-dpna-store" action="{{ route('dosen.penilaian.penilaian-perkuliahan.upload-dpna.store', ['kelas' => $kelas[0]['id_kelas_kuliah']]) }}">
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="file" name="file" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-end">
                        <a class="btn btn-warning me-1" href="{{route('dosen.penilaian.penilaian-perkuliahan')}}">
                            <i class="ti-trash"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary" {{ $batas_pengisian >= 0 ? '' : 'disabled' }}>
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

    $('#upload-dpna-store').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Pengisian Nilai Perkuliahan',
            text: "Apakah anda yakin ingin?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#upload-dpna-store').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });

</script>
@endpush

