<div class="tab-pane" id="data-kelas-kuliah" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light rounded20 big-side-section mb-20 shadow-lg">
            <div class="row">
                <div class="row">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                        @php
                            $no=1;
                            $totalSks = 0;
                            
                            $today = \Carbon\Carbon::now();
                            $deadline = \Carbon\Carbon::parse($semester_aktif->krs_selesai);
                        @endphp
                        @if(!$today->greaterThan($deadline))
                            @if($data_status_mahasiswa == "A" )
                                <div class="row mb-20">
                                    <div class="col-xxl-12">
                                        <div class="box box-body mb-0 bg-white">
                                            <div class="row">
                                                <div class="col-xl-12 col-lg-12">
                                                    <h3 class="fw-500 text-dark mb-20">Daftar Mata Kuliah Regular</h3>
                                                </div>
                                            </div>
                                            {{-- COPY DISINI --}}
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <table id="data-matkul-regular" class="table table-bordered table-striped text-left">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center align-middle">No</th>
                                                                <th class="text-center align-middle">Kode Mata Kuliah</th>
                                                                <th class="text-center align-middle">Nama Mata Kuliah</th>
                                                                <th class="text-center align-middle">Semester Ke</th>
                                                                <th class="text-center align-middle">SKS Mata Kuliah</th>
                                                                <th class="text-center align-middle">Jumlah Kelas Kuliah</th>
                                                                <th class="text-center align-middle">Jadwal Kuliah</th>
                                                                <th class="text-center align-middle">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $no_a=1;
                                                                $isEnrolled = array_column($krs_regular->toArray(), 'id_matkul');
                                                            @endphp
        
                                                            {{-- Tampilkan mata kuliah yang ada di $isEnrolled --}}
                                                            @foreach ($matakuliah->whereIn('id_matkul', $isEnrolled) as $data)
                                                                @php
                                                                    $isDisabled = $data->jumlah_kelas_kuliah == 0;
                                                                @endphp
                                                                <tr class="bg-success-light {{ $isDisabled ? 'disabled-row' : '' }}">
                                                                    <td class="text-center align-middle">{{ $no_a++ }}</td>
                                                                    <td class="text-start align-middle">{{ $data->kode_mata_kuliah }}</td>
                                                                    <td class="text-start align-middle">{{ $data->nama_mata_kuliah }}</td>
                                                                    <td class="text-center align-middle">{{ $data->semester }}</td>
                                                                    <td class="text-center align-middle">{{ $data->sks_mata_kuliah }}</td>
                                                                    <td class="text-center align-middle">{{ $data->jumlah_kelas_kuliah }}</td>
                                                                    
                                                                    {{-- TABEL BERHASIL DAN TERBUKA SESUAI POSISI --}}
                                                                    <td>
                                                                        
                                                                        <button class="btn btn-warning-light lihat-kelas-kuliah" data-id-matkul="{{ $data->id_matkul }}" {{ $isDisabled ? 'disabled' : '' }}>
                                                                            Ubah Kelas Kuliah
                                                                        </button>
                                                                        
                                                                        <!-- Gunakan id_matkul dalam atribut id untuk hasil kontainer -->
                                                                        <div class="result-container" id="result-container_{{ $data->id_matkul }}" style="margin-top: 20px"></div>
                                                                    </td>
                                                                        
                                                                    <td>
                                                                        <input type="checkbox" id="md_checkbox_{{ $no_a }}" class="filled-in chk-col-success" {{ in_array($data->id_matkul, array_column($krs_regular->toArray(), 'id_matkul')) ? 'checked' : '' }} {{ $isDisabled ? 'disabled' : '' }} />
                                                                        <label for="md_checkbox_{{ $no_a }}"></label>
                                                                    </td>
                                                                    
                                                                </tr>
                                                            @endforeach
        
                                                            {{-- Tampilkan mata kuliah yang tidak ada di $isEnrolled --}}
                                                            @foreach ($matakuliah->whereNotIn('id_matkul', $isEnrolled) as $data)
                                                                @php
                                                                    $isDisabled = $data->jumlah_kelas_kuliah == 0;
                                                                @endphp
                                                                <tr class="{{ $isDisabled ? 'disabled-row' : '' }}">
                                                                    <td class="text-center align-middle">{{ $no_a++ }}</td>
                                                                    <td class="text-start align-middle">{{ $data->kode_mata_kuliah }}</td>
                                                                    <td class="text-start align-middle">{{ $data->nama_mata_kuliah }}</td>
                                                                    <td class="text-center align-middle">{{ $data->semester }}</td>
                                                                    <td class="text-center align-middle">{{ $data->sks_mata_kuliah }}</td>
                                                                    <td class="text-center align-middle">{{ $data->jumlah_kelas_kuliah }}</td>
                                                                    
                                                                    {{-- TABEL BERHASIL DAN TERBUKA SESUAI POSISI --}}
                                                                    <td>
                                                                    
                                                                        <button class="btn btn-success-light lihat-kelas-kuliah" data-id-matkul="{{ $data->id_matkul }}" {{ $isDisabled ? 'disabled' : '' }}>
                                                                            Lihat Kelas Kuliah
                                                                        </button>
                                                                        
                                                                        <!-- Gunakan id_matkul dalam atribut id untuk hasil kontainer -->
                                                                        <div class="result-container" id="result-container_{{ $data->id_matkul }}" style="margin-top: 20px"></div>
                                                                    </td>
                                                                        
                                                                    <td>
                                                                        <input type="checkbox" id="md_checkbox_{{ $no_a }}" class="filled-in chk-col-success" {{ in_array($data->id_matkul, array_column($krs_regular->toArray(), 'id_matkul')) ? 'checked' : '' }} {{ $isDisabled ? 'disabled' : '' }} />
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
                            @elseif ($data_status_mahasiswa == "M" )
                                <div>
                                    <div class="row mb-20">
                                        <div class="col-xxl-12">
                                            <div class="box box-body mb-0 bg-white">
                                                <div class="row">
                                                    <div class="col-xl-12 col-lg-12">
                                                        <h3 class="fw-500 text-dark mb-20">Daftar Mata Kuliah Regular</h3>
                                                    </div>
                                                </div>
                                                {{-- COPY DISINI --}}
                                                <div class="row">
                                                    <div class="table-responsive">
                                                        <table id="data-matkul-regular" class="table table-bordered table-striped text-left">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center align-middle">No</th>
                                                                    <th class="text-center align-middle">Kode Mata Kuliah</th>
                                                                    <th class="text-center align-middle">Nama Mata Kuliah</th>
                                                                    <th class="text-center align-middle">Semester Ke</th>
                                                                    <th class="text-center align-middle">SKS Mata Kuliah</th>
                                                                    <th class="text-center align-middle">Jumlah Kelas Kuliah</th>
                                                                    <th class="text-center align-middle">Jadwal Kuliah</th>
                                                                    <th class="text-center align-middle">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $no_a=1;
                                                                    $isEnrolled = array_column($krs_regular->toArray(), 'id_matkul');
                                                                @endphp
            
                                                                {{-- Tampilkan mata kuliah yang ada di $isEnrolled --}}
                                                                @foreach ($matakuliah->whereIn('id_matkul', $isEnrolled) as $data)
                                                                    @php
                                                                        $isDisabled = $data->jumlah_kelas_kuliah == 0;
                                                                    @endphp
                                                                    <tr class="bg-success-light {{ $isDisabled ? 'disabled-row' : '' }}">
                                                                        <td class="text-center align-middle">{{ $no_a++ }}</td>
                                                                        <td class="text-start align-middle">{{ $data->kode_mata_kuliah }}</td>
                                                                        <td class="text-start align-middle">{{ $data->nama_mata_kuliah }}</td>
                                                                        <td class="text-center align-middle">{{ $data->semester }}</td>
                                                                        <td class="text-center align-middle">{{ $data->sks_mata_kuliah }}</td>
                                                                        <td class="text-center align-middle">{{ $data->jumlah_kelas_kuliah }}</td>
                                                                        
                                                                        {{-- TABEL BERHASIL DAN TERBUKA SESUAI POSISI --}}
                                                                        <td>
                                                                            
                                                                            <button class="btn btn-warning-light lihat-kelas-kuliah" data-id-matkul="{{ $data->id_matkul }}" {{ $isDisabled ? 'disabled' : '' }}>
                                                                                Ubah Kelas Kuliah
                                                                            </button>
                                                                            
                                                                            <!-- Gunakan id_matkul dalam atribut id untuk hasil kontainer -->
                                                                            <div class="result-container" id="result-container_{{ $data->id_matkul }}" style="margin-top: 20px"></div>
                                                                        </td>
                                                                            
                                                                        <td>
                                                                            <input type="checkbox" id="md_checkbox_{{ $no_a }}" class="filled-in chk-col-success" {{ in_array($data->id_matkul, array_column($krs_regular->toArray(), 'id_matkul')) ? 'checked' : '' }} {{ $isDisabled ? 'disabled' : '' }} />
                                                                            <label for="md_checkbox_{{ $no_a }}"></label>
                                                                        </td>
                                                                        
                                                                    </tr>
                                                                @endforeach
            
                                                                {{-- Tampilkan mata kuliah yang tidak ada di $isEnrolled --}}
                                                                @foreach ($matakuliah->whereNotIn('id_matkul', $isEnrolled) as $data)
                                                                    @php
                                                                        $isDisabled = $data->jumlah_kelas_kuliah == 0;
                                                                    @endphp
                                                                    <tr class="{{ $isDisabled ? 'disabled-row' : '' }}">
                                                                        <td class="text-center align-middle">{{ $no_a++ }}</td>
                                                                        <td class="text-start align-middle">{{ $data->kode_mata_kuliah }}</td>
                                                                        <td class="text-start align-middle">{{ $data->nama_mata_kuliah }}</td>
                                                                        <td class="text-center align-middle">{{ $data->semester }}</td>
                                                                        <td class="text-center align-middle">{{ $data->sks_mata_kuliah }}</td>
                                                                        <td class="text-center align-middle">{{ $data->jumlah_kelas_kuliah }}</td>
                                                                        
                                                                        {{-- TABEL BERHASIL DAN TERBUKA SESUAI POSISI --}}
                                                                        <td>
                                                                        
                                                                            <button class="btn btn-success-light lihat-kelas-kuliah" data-id-matkul="{{ $data->id_matkul }}" {{ $isDisabled ? 'disabled' : '' }}>
                                                                                Lihat Kelas Kuliah
                                                                            </button>
                                                                            
                                                                            <!-- Gunakan id_matkul dalam atribut id untuk hasil kontainer -->
                                                                            <div class="result-container" id="result-container_{{ $data->id_matkul }}" style="margin-top: 20px"></div>
                                                                        </td>
                                                                            
                                                                        <td>
                                                                            <input type="checkbox" id="md_checkbox_{{ $no_a }}" class="filled-in chk-col-success" {{ in_array($data->id_matkul, array_column($krs_regular->toArray(), 'id_matkul')) ? 'checked' : '' }} {{ $isDisabled ? 'disabled' : '' }} />
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
                                    <div class="row mb-20">
                                        <div class="col-xxl-12">
                                            <div class="box box-body mb-0 bg-white">
                                                <div class="row">
                                                    <div class="col-xl-12 col-lg-12">
                                                        <h3 class="fw-500 text-dark mb-20">Daftar Mata Kuliah Merdeka</h3>
                                                    </div>
                                                </div>
                                                {{-- COPY DISINI --}}
                                                <div class="row">
                                                    <div class="table-responsive">
                                                        <table id="data-matkul-merdeka" class="table table-bordered table-striped text-left">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center align-middle">No</th>
                                                                    <th class="text-center align-middle">Kode Mata Kuliah</th>
                                                                    <th class="text-center align-middle">Nama Mata Kuliah</th>
                                                                    <th class="text-center align-middle">Semester Ke</th>
                                                                    <th class="text-center align-middle">SKS Mata Kuliah</th>
                                                                    <th class="text-center align-middle">Jumlah Kelas Kuliah</th>
                                                                    <th class="text-center align-middle">Jadwal Kuliah</th>
                                                                    <th class="text-center align-middle">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $no_a=1;
                                                                    $isEnrolled = array_column($krs_merdeka->toArray(), 'id_matkul');
                                                                @endphp
                
                                                                {{-- Tampilkan mata kuliah yang ada di $isEnrolled --}}
                                                                @foreach ($mk_merdeka->whereIn('id_matkul', $isEnrolled) as $data)
                                                                    @php
                                                                        $isDisabled = $data->jumlah_kelas_kuliah == 0;
                                                                    @endphp
                                                                    <tr class="bg-success-light {{ $isDisabled ? 'disabled-row' : '' }}">
                                                                        <td class="text-center align-middle">{{ $no_a++ }}</td>
                                                                        <td class="text-start align-middle">{{ $data->kode_mata_kuliah }}</td>
                                                                        <td class="text-start align-middle">{{ $data->nama_mata_kuliah }}</td>
                                                                        <td class="text-center align-middle">{{ $data->semester }}</td>
                                                                        <td class="text-center align-middle">{{ $data->sks_mata_kuliah }}</td>
                                                                        <td class="text-center align-middle">{{ $data->jumlah_kelas_kuliah }}</td>
                                                                        
                                                                        {{-- TABEL BERHASIL DAN TERBUKA SESUAI POSISI --}}
                                                                        <td>
                                                                            
                                                                            <button class="btn btn-warning-light lihat-kelas-kuliah" data-id-matkul="{{ $data->id_matkul }}" {{ $isDisabled ? 'disabled' : '' }}>
                                                                                Ubah Kelas Kuliah
                                                                            </button>
                                                                            
                                                                            <!-- Gunakan id_matkul dalam atribut id untuk hasil kontainer -->
                                                                            <div class="result-container" id="result-container_{{ $data->id_matkul }}" style="margin-top: 20px"></div>
                                                                        </td>
                                                                            
                                                                        <td>
                                                                            <input type="checkbox" id="md_checkbox_{{ $no_a }}" class="filled-in chk-col-success" {{ in_array($data->id_matkul, array_column($krs_merdeka->toArray(), 'id_matkul')) ? 'checked' : '' }} {{ $isDisabled ? 'disabled' : '' }} />
                                                                            <label for="md_checkbox_{{ $no_a }}"></label>
                                                                        </td>
                                                                        
                                                                    </tr>
                                                                @endforeach
                
                                                                {{-- Tampilkan mata kuliah yang tidak ada di $isEnrolled --}}
                                                                @foreach ($mk_merdeka->whereNotIn('id_matkul', $isEnrolled) as $data)
                                                                    @php
                                                                        $isDisabled = $data->jumlah_kelas_kuliah == 0;
                                                                    @endphp
                                                                    <tr class="{{ $isDisabled ? 'disabled-row' : '' }}">
                                                                        <td class="text-center align-middle">{{ $no_a++ }}</td>
                                                                        <td class="text-start align-middle">{{ $data->kode_mata_kuliah }}</td>
                                                                        <td class="text-start align-middle">{{ $data->nama_mata_kuliah }}</td>
                                                                        <td class="text-center align-middle">{{ $data->semester }}</td>
                                                                        <td class="text-center align-middle">{{ $data->sks_mata_kuliah }}</td>
                                                                        <td class="text-center align-middle">{{ $data->jumlah_kelas_kuliah }}</td>
                                                                        
                                                                        {{-- TABEL BERHASIL DAN TERBUKA SESUAI POSISI --}}
                                                                        <td>
                                                                        
                                                                            <button class="btn btn-success-light lihat-kelas-kuliah" data-id-matkul="{{ $data->id_matkul }}" {{ $isDisabled ? 'disabled' : '' }}>
                                                                                Lihat Kelas Kuliah
                                                                            </button>
                                                                            
                                                                            <!-- Gunakan id_matkul dalam atribut id untuk hasil kontainer -->
                                                                            <div class="result-container" id="result-container_{{ $data->id_matkul }}" style="margin-top: 20px"></div>
                                                                        </td>
                                                                            
                                                                        <td>
                                                                            <input type="checkbox" id="md_checkbox_{{ $no_a }}" class="filled-in chk-col-success" {{ in_array($data->id_matkul, array_column($krs_regular->toArray(), 'id_matkul')) ? 'checked' : '' }} {{ $isDisabled ? 'disabled' : '' }} />
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
                            @else
                                <div class="row mb-20">
                                    <div class="col-xxl-12">
                                        <div class="box box-body mb-0 bg-white">
                                            <div class="row">
                                                <div class="col-xl-12 col-lg-12">
                                                    <h3 class="fw-500 text-dark mb-20">Kartu Rencana Studi</h3>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-lg-12 col-lg-12 col-lg-12 p-20 m-0">
                                                    <div class="box box-body bg-warning-light">
                                                        <div class="row" style="align-items: center;">
                                                            <div class="col-lg-1 text-right" style="text-align-last: end;">
                                                                <i class="fa-solid fa-2xl fa-circle-exclamation fa-danger" style="color: #d10000;"></i></i>
                                                            </div>
                                                            <div class="col-lg-10 text-left text-danger">
                                                                <label>
                                                                    Anda tidak dapat memilih Mata Kuliah!
                                                                </label>
                                                            </div>
                                                        </div>                       
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @elseif ($today->greaterThan($deadline))
                            <div class="row mb-20">
                                <div class="col-xxl-12">
                                    <div class="box box-body mb-0 bg-white">
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12">
                                                <h3 class="fw-500 text-dark mb-20">Kartu Rencana Studi</h3>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-lg-12 col-lg-12 col-lg-12 p-20 m-0">
                                                <div class="box box-body bg-warning-light">
                                                    <div class="row" style="align-items: center;">
                                                        <div class="col-lg-1 text-right" style="text-align-last: end;">
                                                            <i class="fa-solid fa-2xl fa-circle-exclamation fa-danger" style="color: #d10000;"></i></i>
                                                        </div>
                                                        <div class="col-lg-10 text-left text-danger">
                                                            <label>
                                                                Periode Pengisian KRS Telah Berakhir!
                                                            </label>
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
