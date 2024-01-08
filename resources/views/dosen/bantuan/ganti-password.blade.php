@extends('layouts.dosen')
@section('title')
Perubahan Password Akun
@endsection
@section('content')
@include('swal')
<section class="content bg-light">
    <div class="row items-align">
        <div class="col-lg-12 col-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 pb-10 text-center">
                            <img src="{{asset('images/logo-unsri.png')}}" alt="UNSRI" class="img-responsive w-150">
                        </div>                             
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 text-center">
                            <h3 class="fw-500 text-dark mt-0">Perubahan Password Akun SIAKAD Universitas Sriwijaya</h3>
                        </div>                             
                    </div>
                </div>
                <!-- /.box-header -->
                <form class="form" action="{{route('dosen.bantuan.proses-ganti-password')}}" id="update-password" method="POST">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="ti-lock"></i></span>
                                <input name="new_password" type="password" class="form-control" placeholder="New Password" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="ti-lock"></i></span>
                                <input name="confirm_password" type="password" class="form-control" placeholder="Confirm New Password" required>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-end">
                        <a href="{{route('dosen')}}" type="button" class="btn btn-warning me-1">
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
<script>
    // sweet alert update password
    $('#update-password').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Perubahan Password Akun',
            text: "Apakah anda yakin ingin melakukan perubahan password?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#update-password').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });

</script>
@endpush