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
                                                        @endphp

                                                        @foreach ($matakuliah as $data)
                                                        @php
                                                            $isDisabled = $data->jumlah_kelas_kuliah == 0;
                                                            $isEnrolled = in_array($data->id_matkul, array_column($krs->toArray(), 'id_matkul'));
                                                        @endphp

                                                        {{-- <tr class="{{ $isEnrolled ? 'bg-success-light' : '' }} {{ $isDisabled ? 'disabled-row' : '' }}"> --}}
                                                        {{-- <tr class="{{ in_array($data->id_matkul, array_column($krs->toArray(), 'id_matkul')) ? $isDisabled : '' }}"> --}}
                                                        <tr class=" {{ $isEnrolled ? 'bg-success-light disabled-row' : '' }} {{ $isDisabled ? 'disabled-row' : '' }}">
                                                            <td class="text-center align-middle">{{ $no_a++ }}</td>
                                                            <td class="text-start align-middle">{{ $data->kode_mata_kuliah }}</td>
                                                            <td class="text-start align-middle">{{ $data->nama_mata_kuliah }}</td>
                                                            <td class="text-center align-middle">{{ $data->semester }}</td>
                                                            <td class="text-center align-middle">{{ $data->sks_mata_kuliah }}</td>
                                                            <td class="text-center align-middle">{{ $data->jumlah_kelas_kuliah }}</td>
                                                            
                                                            {{-- TABEL BERHASIL DAN TERBUKA SESUAI POSISI --}}
                                                            <td>
                                                                @if ($isEnrolled)
                                                                    <button class="btn btn-warning-light lihat-kelas-kuliah" data-id-matkul="{{ $data->id_matkul }}" {{ $isDisabled ? 'disabled' : '' }}>
                                                                        Ubah Kelas Kuliah
                                                                    </button>
                                                                @else
                                                                    <button class="btn btn-success-light lihat-kelas-kuliah" data-id-matkul="{{ $data->id_matkul }}" {{ $isDisabled ? 'disabled' : '' }}>
                                                                        Lihat Kelas Kuliah
                                                                    </button>
                                                                @endif
                                                                
                                                                <!-- Gunakan id_matkul dalam atribut id untuk hasil kontainer -->
                                                                <div class="result-container" id="result-container_{{ $data->id_matkul }}" style="margin-top: 20px"></div>
                                                            </td>
                                                                
                                                            <td>
                                                                <input type="checkbox" id="md_checkbox_{{ $no_a }}" class="filled-in chk-col-success" {{ in_array($data->id_matkul, array_column($krs->toArray(), 'id_matkul')) ? 'checked' : '' }} {{ $isDisabled ? 'disabled' : '' }} />
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