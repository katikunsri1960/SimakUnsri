@extends('layouts.bak')
@section('title')
Daftar Pengajuan Cuti
@endsection
@section('content')
<section class="content">
    <div class="row align-items-end">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3"><img
                                src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h2>Daftar Pengajuan Cuti Mahasiswa</h2>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-body py-10">
                    <div class="col-md-6 mt-5">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">NIM</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nim" placeholder="Masukan NIM mahasiswa">
                                    <button class="btn btn-primary" id="btnCari"><i class="fa fa-search"></i> Cari</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-5">
                        <table class="table no-border mb-0" hidden id="tableTranskrip">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle"></th>
                                </tr>
                            </thead>
                            <tbody id="bodyTranskrip">

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
        $(document).ready(function () {
            $('#btnCari').click(function () {
                var nim = $('#nim').val();
                if (nim == '') {
                    swal('Peringatan', 'NIM tidak boleh kosong', 'warning');
                } else {
                    $.ajax({
                        url: '{{route('bak.transkrip-nilai.get')}}',
                        type: 'GET',
                        data: {
                            nim: nim
                        },
                        success: function (data) {
                            if (data.status == 'success') {
                                $('#tableTranskrip').removeAttr('hidden');
                                $('#bodyTranskrip').html(data.html);
                            } else {
                                swal('Peringatan', data.message, 'warning');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
