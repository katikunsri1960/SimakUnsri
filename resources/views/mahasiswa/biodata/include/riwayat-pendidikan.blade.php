<div class="tab-pane" id="riwayat-pendidikan" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light rounded20 big-side-section">
            <div class="row">
                <div class="row">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                        <div class="box">
                            <div class="box-body  mb-0 bg-white">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12">
                                        <h3 class="fw-500 text-dark mt-0 mb-20">Riwayat Pendidikan</h3>
                                    </div>                             
                                </div>
                                @if ($riwayat_pendidikan -> isEmpty() )
                                <div class="row ">
                                    <div class="col-lg-12 col-lg-12 col-lg-12 p-20 m-0">
                                        <div class="box box-body bg-warning-light">
                                            <div class="row" style="align-items: center;">
                                                <div class="col-lg-1 text-right" style="text-align-last: end;">
                                                    <i class="fa-solid fa-2xl fa-circle-exclamation fa-danger" style="color: #d10000;"></i></i>
                                                </div>
                                                <div class="col-lg-10 text-left text-danger">
                                                    <label>
                                                        Data <strong>Riwayat Pendidikan</strong> hanya untuk mahasiswa dengan Jenis Keluar <strong>LULUS</strong> !
                                                    </label>
                                                </div>
                                                
                                            </div>                       
                                        </div>
                                    </div>
                                </div>  
                                @else
                                <div class="row">
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-bordered table-striped text-center">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Jenjang Studi</th>
                                                    {{-- <th class="text-center">Gelar Akademik</th>                                     --}}
                                                    <th class="text-center">Nama PT</th>
                                                    <th class="text-center">Bidang Ilmu</th>
                                                    <th class="text-center">Tanggal Ijazah</th>
                                                    <th class="text-center">SKS Lulus</th>
                                                    <th class="text-center">IPK Lulus</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($riwayat_pendidikan as $data)
                                                <tr>
                                                    <td class="text-center">1</td>
                                                    <td class="text-start">{{$data->nama_jenjang_pendidikan}}</td>
                                                    {{-- <td class="text-start"></td> --}}
                                                    <td class="text-start">{{$data->nama_perguruan_tinggi}}</td>
                                                    <td class="text-start">{{$data->nama_program_studi}}</td>
                                                    <td class="text-start">{{date_format(new DateTime($data->tanggal_keluar), "d-m-Y") }}</td>
                                                    <td class="text-center">{{$data->sks_diakui}}</td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center">
                                                        <a class="btn btn-rounded bg-warning-light" href="#" title="Hapus Riwayat"><i class="fa fa-trash"><span class="path1"></span><span class="path2"></span></i></a>
                                                        <a class="btn btn-rounded bg-success-light" href="#" title="Edit Riwayat"><i class="fa fa-pen-to-square"><span class="path1"></span><span class="path2"></span></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <!-- <div class="box-footer text-end">
                                <a class="btn btn-rounded bg-primary" href="#"><i class="fa-solid fa-plus"> <span class="path1"></span><span class="path2"></span></i> Tambah</a>
                            </div>   -->
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>