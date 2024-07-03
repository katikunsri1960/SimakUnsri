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
                                <div class="row">
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-bordered table-striped text-center">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Jenjang Studi</th>
                                                    {{-- <th class="text-center">Gelar Akademik</th>                                     --}}
                                                    <th class="text-center">Nama PT</th>
                                                    <th class="text-center">Program Studi</th>
                                                    <th class="text-center">Bidang Minat</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Tanggal Keluar</th>
                                                    <th class="text-center">SKS Diakui</th>
                                                    <th class="text-center">IPK Lulus</th>
                                                    {{-- <th class="text-center">Action</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no=1;
                                                @endphp

                                                @foreach ($riwayat_pendidikan as $data)
                                                    <tr>
                                                        <td class="text-center">{{ $no++ }}</td>
                                                        <td class="text-center">{{$data->prodi->nama_jenjang_pendidikan}}</td>
                                                        {{-- <td class="text-start"></td> --}}
                                                        <td class="text-start">{{$data->nama_perguruan_tinggi}}</td>
                                                        <td class="text-start">{{$data->prodi->nama_program_studi}}</td>
                                                        <td class="text-center">
                                                            {{$data->nm_bidang_minat == Null ? 'Tidak Diisi' : $data->nm_bidang_minat}}
                                                        </td>
                                                        <td class="text-center">
                                                            {{$data->id_jenis_keluar == Null ? 'Aktif' : $data->keterangan_keluar}}
                                                        </td>
                                                        <td class="text-center">
                                                            {{$data->id_jenis_keluar == Null ? '-' : date_format(new DateTime($data->tanggal_keluar), "d-m-Y") }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{$data->sks_diakui == Null ? 'Tidak Diisi' : $data->sks_diakui }}
                                                        </td>
                                                        <td class="text-center"></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                {{-- @endif --}}
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
