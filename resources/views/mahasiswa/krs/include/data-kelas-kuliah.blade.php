<div class="tab-pane active" id="data-kelas-kuliah" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light rounded20 big-side-section">
            <div class="row">
                <div class="row">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                        <div class="box box-body">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12">
                                    <h3 class="fw-500 text-dark mt-0 mb-20">Data Kelas Kuliah</h3>
                                </div>                             
                            </div>
                            <div class="row">
                                <div class="col-xxl-12">
                                    <div class="box box-body mb-0">
                                        <div class="row">
                                            <div class="col-xl-4 col-lg-12">
                                                <h3 class="fw-500 text-dark mt-0">Semester 6</h3>
                                            </div>                             
                                        </div>
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table id="matkul-krs" class="table table-bordered table-striped text-center">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center align-middle">No</th>
                                                            <th class="text-center align-middle">Kode Mata Kuliah</th>
                                                            <th class="text-center align-middle">Nama Mata Kuliah</th>                                    
                                                            {{-- <th class="text-center align-middle">Semester</th> --}}
                                                            <th class="text-center align-middle">SKS Mata Kuliah</th>
                                                            <th class="text-center align-middle">Jadwal Kuliah</th>
                                                            <th class="text-center align-middle">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no_a=1;
                                                        @endphp

                                                        @foreach ($matakuliah as $data)
                                                            {{-- @foreach ($data->kelas_kuliah as $kelas) --}}
                                                                {{-- @if($kelas->id_semester == $semester_aktif->id_semester) --}}
                                                                
                                                            
                                                                <tr>
                                                                    <td class="text-center align-middle">{{ $no_a++ }}</td>
                                                                    <td class="text-center align-middle">{{$data->kode_mata_kuliah}}</td>
                                                                    <td class="text-start align-middle">{{$data->nama_mata_kuliah}}</td>
                                                                    <td class="text-center align-middle">{{$data->sks_mata_kuliah}}</td>
                                                                    
                                                                    <td>
                                                                        {{-- <button type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"
                                                                                    class="waves-effect waves-light btn btn-success-light mb-5">
                                                                                Lihat Kelas Kuliah
                                                                        </button> --}}
                                                                        {{-- <div class="btn-group">
                                                                            <button class="waves-effect rounded waves-light btn btn-success-light mb-5 no-caret" type="button" data-bs-toggle="dropdown">Lihat Kelas Kuliah</button>
                                                                            <div class="dropdown-menu">
                                                                                <a class="dropdown-item" href="#">Action</a>
                                                                                <a class="dropdown-item" href="#">Another action</a>
                                                                                <a class="dropdown-item" href="#">Something else here</a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="collapse" id="collapseExample">
                                                                            <div class="card card-body">
                                                                                
                                                                            </div>
                                                                        </div> --}}

                                                                        <p>
                                                                            <button class="waves-effect rounded waves-light btn btn-success-light mb-5 no-caret" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample{{$data->id_matkul}}" aria-expanded="false" aria-controls="collapseExample">
                                                                                Lihat Kelas Kuliah
                                                                            </button>
                                                                        </p>
                                                                        <div class="collapse" id="collapseExample{{$data->id_matkul}}">
                                                                            <div class="card card-body">
                                                                                <table id="data-kelas" class="table table-bordered table-striped text-center">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th class="text-center align-middle">No</th>
                                                                                            <th class="text-center align-middle">Kelas Kuliah</th>
                                                                                            <th class="text-center align-middle">Dosen Pengajar</th>
                                                                                            <th class="text-center align-middle">Jadwal Kuliah</th>
                                                                                            <th class="text-center align-middle">Peserta</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @php
                                                                                            $no=1;
                                                                                        @endphp
                                
                                                                                        @foreach ($kelas_kuliah as $d)
                                                                                            @if($d->id_matkul == $data->id_matkul)
                                                                                                <tr>
                                                                                                    <td class="text-center align-middle">{{ $no++ }}</td>
                                                                                                    <td class="text-center align-middle">{{$d->nama_kelas_kuliah}}</td>
                                                                                                    <td class="text-start align-middle w-300 d-xl-inline-block">
                                                                                                        @foreach ($d->dosen_pengajar as $dosen)
                                                                                                            <i class="fa-regular fa-circle-dot"></i> {{$dosen->nama_dosen}}<br>
                                                                                                        @endforeach
                                                                                                    </td>
                                                                                                    <td class="text-start align-middle">{{$d->tanggal_mulai_efektif}}</td>
                                                                                                    <td class="text-center align-middle">-</td>
                                                                                                    <td class="text-center align-middle">
                                                                                                        <input name="group5" type="radio" id="radio_{{$d->id_kelas_kuliah}}" class="with-gap radio-col-success" />
                                                                                                        <label for="radio_{{$d->id_kelas_kuliah}}"></label>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                                </tr>
                                                                {{-- @endif --}}
                                                            {{-- @endforeach --}}
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
