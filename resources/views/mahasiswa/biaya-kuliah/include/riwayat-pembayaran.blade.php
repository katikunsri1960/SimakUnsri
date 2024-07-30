<div class="tab-pane" id="riwayat-pembayaran" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light rounded20 big-side-section">
            <div class="row">
                <div class="row">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                        <div class="box box-body">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12">
                                    <h3 class="fw-500 text-dark mt-0 mb-20">Riwayat Pembayaran</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xxl-12">
                                    <div class="box box-body mb-0 bg-white">
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table id="example2" class="table table-bordered table-striped text-center">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">No</th>
                                                            <th class="text-center">Tanggal Bayar</th>
                                                            <th class="text-center">Jumlah Bayar</th>
                                                            <th class="text-center">Status Bayar</th>
                                                            <th class="text-center">Kode Bank</th>
                                                            <th class="text-center">Channel Bayar</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($pembayaran as $p)
                                                        <tr>
                                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                            <td class="text-start align-middle">{{ $p->waktu_transaksi }}</td>
                                                            <td class="text-end align-middle">Rp. {{number_format($p->total_nilai_pembayaran, 2, ',', '.') }}</td>
                                                            <td class="text-center align-middle" style="width:10%">
                                                                <div>
                                                                    <span class="badge badge-xl {{ $p->status_pembayaran == 0 ? 'badge-danger-light' : 'badge-success-light' }} mb-5">
                                                                        {{ $p->status_pembayaran == 0 ? 'Belum Bayar' : 'Lunas' }}
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td class="text-center align-middle">{{ $p->kode_bank }}</td>
                                                            <td class="text-center align-middle">{{ $p->kanal_bayar_bank }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>		
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>
