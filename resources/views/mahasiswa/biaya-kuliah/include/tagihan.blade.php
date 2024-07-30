<div class="tab-pane active" id="tagihan" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light rounded20 big-side-section">
            <div class="row">
                <div class="row">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                        <div class="box">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12">
                                        <h3 class="fw-500 text-dark mt-0 mb-20">Tagihan</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="box bs-5 border-primary rounded mb-10 pull-up"
                                            style="background-image: url(../images/svg-icon/color-svg/st-1.svg); background-position: right bottom; background-repeat: no-repeat;">
                                            <div class="box-body">
                                                <div class="flex-grow-1">
                                                    <p class="mt-5 mb-5 text-fade fs-12">UKT</p>
                                                    <h4 class="mt-5 mb-0" style="color:#0052cc">Rp  {{number_format($tagihan->total_nilai_tagihan, 2, ',', '.') }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="box bs-5 border-warning rounded mb-10 pull-up"
                                            style="background-image: url(../images/svg-icon/color-svg/st-2.svg); background-position: right bottom; background-repeat: no-repeat;">
                                            <div class="box-body">
                                                <div class="flex-grow-1">
                                                    <p class="mt-5 mb-5 text-fade fs-12">UKT yang Belum Dibayar</p>
                                                    <h4 class="mt-5 mb-0" style="color:#0052cc">Rp  {{number_format($tagihan->total_nilai_tagihan, 2, ',', '.') }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="box bs-5 border-danger rounded mb-10 pull-up"
                                            style="background-image: url({{asset('images/images/svg-icon/color-svg/st-3.svg')}}); background-position: right bottom; background-repeat: no-repeat;">
                                            <div class="box-body">
                                                <div class="flex-grow-1">
                                                    <p class="mt-5 mb-5 text-fade fs-12">Batas Akhir Pembayaran</p>
                                                    <h4 class="mt-5 mb-0" style="color:#0052cc">{{$tagihan->waktu_berakhir}}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-xxl-12">
                                        <div class="box box-body mb-0 bordered">
                                            <div class="row">
                                                <div class="col-xl-12 col-lg-12">
                                                    <h3 class="fw-500 text-dark mt-0">Daftar Tagihan Uang Kuliah Tunggal</h3>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <table id="example1" class="table table-bordered table-striped text-center">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">No</th>
                                                                <th class="text-center">Nama Tagihan</th>
                                                                <th class="text-center">Periode</th>
                                                                <!-- <th class="text-center">Tahun Ajaran</th>                                     -->
                                                                <th class="text-center">Nominal Tagihan</th>
                                                                <th class="text-center">Batas Akhir Tagihan</th>
                                                                <th class="text-center">Status Tagihan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center">1</td>
                                                                <td class="text-start">UKT {{$tagihan->formatted_kode_periode }}</td>
                                                                <td class="text-start">{{$tagihan->formatted_kode_periode}}</td>
                                                                <!-- <td class="text-start">2023/2024</td> -->
                                                                <td class="text-start">Rp  {{number_format($tagihan->total_nilai_tagihan, 2, ',', '.') }}</td>
                                                                <td class="text-start">{{$tagihan->waktu_berakhir}}</td>
                                                                <td class="text-start">
                                                                    <div>
                                                                        <span class="badge badge-xl {{ $tagihan->pembayaran->status_pembayaran == NULL ? 'badge-danger-light' : 'badge-success-light' }} mb-5">
                                                                            {{ $tagihan->pembayaran->status_pembayaran == NULL ? 'Belum Bayar' : 'Lunas' }}
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="box-footer text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-money"> </i>  Bayar Tagihan
                                </button>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

