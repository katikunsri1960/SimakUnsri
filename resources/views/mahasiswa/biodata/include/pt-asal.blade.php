<div class="tab-pane" id="pt-asal" role="tabpanel">
    <div class="col-xl-12">
        <div class="bg-primary-light rounded20 big-side-section">
            <div class="row">
                <div class="col-12 py-10 mx-10">
                    <div class="box">
                        <div class="box-body mb-0 bg-white">

                            <h3 class="fw-500 text-dark mb-20">
                                Perguruan Tinggi Asal
                            </h3>

                            @if($ptAsal->count() == 0)

                                <div class="box box-body bg-warning-light">
                                    <div class="row align-items-center">
                                        <div class="col-lg-1 text-end">
                                            <i class="fa-solid fa-2xl fa-circle-exclamation text-danger"></i>
                                        </div>
                                        <div class="col-lg-11 text-danger">
                                            Data Perguruan Tinggi Asal hanya untuk mahasiswa dengan Jenis Pendaftaran selain Peserta Didik Baru.
                                        </div>
                                    </div>
                                </div>

                            @else

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th>Nama Jenis Daftar</th>
                                                <th>Perguruan Tinggi Asal</th>
                                                <th>Program Studi Asal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($ptAsal as $key => $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $data->nama_jenis_daftar }}</td>
                                                    <td class="text-start">
                                                        {{ $data->nama_perguruan_tinggi_asal ?? '-' }}
                                                    </td>
                                                    <td class="text-start">
                                                        {{ $data->nama_program_studi_asal ?? '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>