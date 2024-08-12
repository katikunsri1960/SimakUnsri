<div class="tab-pane" id="riwayat-pembayaran" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light rounded20 big-side-section">
            <div class="row">
                <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                    <div class="box box-body">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <h3 class="fw-500 text-dark mt-0 mb-20">Riwayat Pembayaran</h3>
                            </div>
                        </div>
                        @if(!empty($tagihan))
                            <div class="row">
                                <div class="col-xxl-12">
                                    <div class="box box-body mb-0 bg-white">
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table id="pembayaran" class="table table-bordered table-striped text-center">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">No</th>
                                                            <th class="text-center">Nama Tagihan</th>
                                                            <th class="text-center">Tanggal Bayar</th>
                                                            <th class="text-center">Jumlah Bayar</th>
                                                            <th class="text-center">Status Bayar</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($pembayaran as $p)
                                                            <tr>
                                                                <td class="text-center align-middle" style="width:5%">{{ $loop->iteration }}</td>
                                                                @if($p->pembayaran == null)
                                                                    <td colspan="4" class="text-center align-middle" style="width:10%">
                                                                        <div>
                                                                            <span class="badge badge-xl badge-danger-light mb-5">
                                                                                Belum Bayar
                                                                            </span>
                                                                        </div>
                                                                    </td>
                                                                @else
                                                                    <td class="text-start align-middle">UKT {{ $p->formatted_kode_periode }}</td>
                                                                    <td class="text-start align-middle">{{ $p->pembayaran->waktu_transaksi }}</td>
                                                                    <td class="text-end align-middle">Rp. {{ number_format($p->pembayaran->total_nilai_pembayaran, 2, ',', '.') }}</td>
                                                                    <td class="text-center align-middle" style="width:10%">
                                                                        <div>
                                                                            <span class="badge badge-xl {{ $p->pembayaran->status_pembayaran == 0 ? 'badge-danger-light' : 'badge-success-light' }} mb-5">
                                                                                {{ $p->pembayaran->status_pembayaran == 0 ? 'Belum Bayar' : 'Lunas' }}
                                                                            </span>
                                                                        </div>
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
                        @else
                            <div class="row mb-20">
                                <div class="col-xxl-12">
                                    <div class="box box-body mb-0 bg-white">
                                        <div class="row ">
                                            <div class="col-lg-12 col-lg-12 col-lg-12 p-20 m-0">
                                                <div class="box box-body bg-primary-light">
                                                    <div class="row" style="align-items: center;">
                                                        <div class="col-lg-1 text-right" style="text-align-last: end;">
                                                            <i class="fa-solid fa-2xl fa-circle-exclamation fa-success" style="color: #00d173;"></i></i>
                                                        </div>
                                                        <div class="col-lg-10 text-left text-success">
                                                            <label>
                                                                Anda tidak memiliki riwayat pembayaran UKT!
                                                            </label><br>
                                                            @if($beasiswa!=NULL)
                                                                <label>
                                                                    Anda Penerima Beasiswa!
                                                                </label>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>
