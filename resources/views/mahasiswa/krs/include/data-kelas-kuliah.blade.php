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
                                                <table id="data-matkul" class="table table-bordered text-center">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center align-middle">No</th>
                                                            <th class="text-center align-middle">Kode Mata Kuliah</th>
                                                            <th class="text-center align-middle">Nama Mata Kuliah</th>                                    
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
                                                            <tr class="{{ in_array($data->id_matkul, array_column($krs->toArray(), 'id_matkul')) ? 'bg-success-light' : '' }}">
                                                                <td class="text-center align-middle">{{ $no_a++ }}</td>
                                                                <td class="text-start align-middle">{{ $data->kode_mata_kuliah }}</td>
                                                                <td class="text-start align-middle">{{ $data->nama_mata_kuliah }}</td>
                                                                <td class="text-center align-middle">{{ $data->sks_mata_kuliah }}</td>
                                                                <td>
                                                                    <button class="waves-effect rounded waves-light btn btn-success-light mb-5 no-caret" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample{{$data->id_matkul}}" aria-expanded="false" aria-controls="collapseExample">
                                                                        Lihat Kelas Kuliah
                                                                    </button>
                                                                    <div class="collapse" id="collapseExample{{$data->id_matkul}}">
                                                                        <div class="card card-body">
                                                                            <table id="example3" class="table table-bordered table-striped text-center">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="text-center align-middle">No</th>
                                                                                        <th class="text-center align-middle">Kelas Kuliah</th>
                                                                                        <th class="text-center align-middle">Dosen Pengajar</th>
                                                                                        <th class="text-center align-middle">Jadwal Kuliah</th>
                                                                                        <th class="text-center align-middle">Peserta</th>
                                                                                        <th class="text-center align-middle">Action</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @php $no=1; @endphp
                                                                                    <!-- Penggunaan Variabel Blade -->
                                                                                    @foreach ($kelas_kuliah as $d)
                                                                                        @if($d->id_matkul == $data->id_matkul)
                                                                                            <tr>
                                                                                                <td class="text-center align-middle">{{ $no++ }}</td>
                                                                                                <td class="text-center align-middle">{{ $d->nama_kelas_kuliah }}</td>

                                                                                                <td class="text-start align-middle w-300">
                                                                                                    <ul>
                                                                                                        @foreach ($d->dosen_pengajar as $dosen)
                                                                                                            <li>{{ $dosen->nama_dosen }}</li>
                                                                                                        @endforeach
                                                                                                    </ul>
                                                                                                </td>

                                                                                                <td class="text-start align-middle">
                                                                                                    @if($d->jadwal_hari == NULL)
                                                                                                        Jadwal Tidak Diisi
                                                                                                    @else
                                                                                                        {{ $d->jadwal_hari }}, {{ $d->jadwal_jam_mulai }} - {{ $d->jadwal_jam_selesai }}
                                                                                                    @endif
                                                                                                </td>

                                                                                                <td class="text-center align-middle">
                                                                                                    {{ $d->peserta_kelas_count }}
                                                                                                </td>

                                                                                                <td>
                                                                                                    <div class="box-footer text-end">
                                                                                                        <form method="post" action="{{ route('ambil-krs', $d->id_kelas_kuliah) }}">
                                                                                                            @csrf
                                                                                                            <input type="hidden" name="id_matkul" value="{{ $data->id_matkul }}">
                                                                                                            <button type="submit" class="btn btn-primary-light">
                                                                                                                <i class="ti-save-alt"></i> Ambil
                                                                                                            </button>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <!-- Checkbox dicentang jika kelas sudah diambil -->
                                                                    <input type="checkbox" id="md_checkbox_{{ $no_a }}" class="filled-in chk-col-success" {{ in_array($data->id_matkul, array_column($krs->toArray(), 'id_matkul')) ? 'checked' : '' }} />
                                                                    <label for="md_checkbox_{{ $no_a }}"></label>
                                                                </td>
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
