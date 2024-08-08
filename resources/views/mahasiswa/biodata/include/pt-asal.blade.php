<div class="tab-pane " id="pt-asal" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light rounded20 big-side-section">
            <div class="row">
                <div class="row">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                        <div class="box">
                            <div class="box-body  mb-0 bg-white">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12">
                                        <h3 class="fw-500 text-dark mt-0 mb-20">Perguruan Tinggi Asal</h3>
                                    </div>                             
                                </div>
                                @foreach ($riwayat_pendidikan as $data)
                                    @if ($data->id_jenis_daftar == 1 )
                                    <div class="row ">
                                        <div class="col-lg-12 col-lg-12 col-lg-12 p-20 m-0">
                                            <div class="box box-body bg-warning-light">
                                                <div class="row" style="align-items: center;">
                                                    <div class="col-lg-1 text-right" style="text-align-last: end;">
                                                        <i class="fa-solid fa-2xl fa-circle-exclamation fa-danger" style="color: #d10000;"></i></i>
                                                    </div>
                                                    <div class="col-lg-10 text-left text-danger">
                                                        <label>
                                                            Data Perguruan Tinggi Asal hanya untuk mahasiswa dengan Jenis Pendaftaran selain  Peserta Didik Baru !
                                                        </label>
                                                    </div>
                                                    
                                                </div>                       
                                            </div>
                                        </div>
                                    </div>  
                                    @else
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table id="example1" class="table table-bordered table-striped text-center">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">No</th>
                                                        <th class="text-center">Nama Jenis Daftar</th>
                                                        <th class="text-center">Perguruan Tinggi Asal</th>
                                                        <th class="text-center">Program Studi Asal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $no=1;
                                                    @endphp
                                                    {{-- @foreach ($pt_asal as $data) --}}
                                                        <tr>
                                                            <td class="text-center">{{ $no++ }}</td>
                                                            <td class="text-center">{{$data->nama_jenis_daftar}}</td>
                                                            <td class="text-start">{{$data->nama_perguruan_tinggi_asal}}</td>
                                                            <td class="text-start">{{$data->nama_program_studi_asal}}</td>
                                                        </tr>
                                                    {{-- @endforeach                                                --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach  
                            </div>
                            <div class="box-footer text-end">
                                <a class="btn btn-rounded bg-primary" href="#"><i class="fa-solid fa-plus"> <span class="path1"></span><span class="path2"></span></i> Tambah</a>
                            </div>  
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>
    