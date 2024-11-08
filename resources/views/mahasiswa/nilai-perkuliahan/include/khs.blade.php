<div class="tab-pane active" id="khs" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light big-side-section shadow-lg rounded10">
            <div class="row">
                <div class="row">
                    <div class="col-xxl-12 py-10 mx-10">
                        @if ($data_aktivitas->isEmpty())
                        <div class="row ">
                            <div class="col-lg-12 col-lg-12 col-lg-12 p-20 m-0">
                                <div class="box box-body bg-warning-light">
                                    <div class="row" style="align-items: center;">
                                        <div class="col-lg-1 text-right" style="text-align-last: end;">
                                            <i class="fa-solid fa-2xl fa-circle-exclamation fa-danger" style="color: #d10000;"></i></i>
                                        </div>
                                        <div class="col-lg-10 text-left text-danger">
                                            <label>
                                                Anda belum memiliki Aktivitas Kuliah Mahasiswa!
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                            @foreach($data_aktivitas as $d)
                                <div class="box">
                                    <div class="box-body mb-0 bg-white">
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped text-left">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center align-middle">{{$d->nama_semester}}</th>
                                                            <th class="text-center align-middle">SKS Semester</th>
                                                            <th class="text-center align-middle">SKS Total</th>
                                                            <th class="text-center align-middle">IP Semester</th>
                                                            <th class="text-center align-middle">IP Komulatif</th>
                                                            <th class="text-center align-middle">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            @if($d->id_status_mahasiswa == 'A')
                                                                <td class="text-center align-middle"><span class="badge badge-primary me-15">{{$d->nama_status_mahasiswa}}</span></td>
                                                            @else
                                                                <td class="text-center align-middle">{{$d->nama_status_mahasiswa}}</td>
                                                            @endif
                                                            <td class="text-center align-middle">{{$d->sks_semester}}</td>
                                                            <td class="text-center align-middle">{{$d->sks_total}}</td>
                                                            <td class="text-center align-middle">{{$d->ips}}</td>
                                                            <td class="text-center align-middle">{{$d->ipk}}</td>
                                                            <td class="text-center align-middle">
                                                                <a type="button" href="{{route('mahasiswa.perkuliahan.nilai-perkuliahan.lihat-khs', ['id_semester' => $d->id_semester])}}" class="btn btn-success waves-effect waves-light">
                                                                <i class="fa-solid fa-eye"></i> Lihat KHS
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            @endforeach	
                        @endif
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>
