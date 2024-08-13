<div class="tab-pane" id="data-kelas-kuliah" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light rounded20 big-side-section mb-20 shadow-lg">
            <div class="row">
                <div class="row">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                        <!-- Modal -->
                        <div class="modal fade" id="rpsModal" tabindex="-1" aria-labelledby="rpsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="row mb-20">
                                    <div class="col-xxl-12">
                                        <div class="box box-body mb-0 bg-white">
                                            <div class="row">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id="rpsModalLabel">Rencana Pembelajaran Semester (RPS)</h3>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="table-responsive">
                                                                <table id="data-rencana-pembelajaran" class="table table-bordered table-striped text-left">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="text-center align-middle" style="width: 4%">Pertemuan</th>
                                                                            <th class="text-center align-middle" style="width: 45%">Materi Indonesia</th>
                                                                            <th class="text-center align-middle" style="width: 45%">Materi Inggris</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <!-- Data RPS akan dimasukkan di sini oleh jQuery -->
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
                                    
                        @php
                            $no=1;
                            
                            $today = \Carbon\Carbon::now();
                            $deadline = \Carbon\Carbon::parse($semester_aktif->krs_selesai);
                        @endphp
                        @if ($today->greaterThan($deadline) || $semester_aktif->id_semester > $semester_select)
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
                                                                Periode Pengisian KRS pada semester yang Anda pilih Telah Berakhir!
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif(!$today->greaterThan($deadline))
                            @if(!empty($beasiswa) || !empty($tagihan->pembayaran->status_pembayaran))
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
                                                                <th class="text-center align-middle" style="width: 100%">Nama Mata Kuliah</th>
                                                                <th class="text-center align-middle">RPS</th>
                                                                <th class="text-center align-middle">Semester Ke</th>
                                                                <th class="text-center align-middle">SKS Mata Kuliah</th>
                                                                <th class="text-center align-middle">Jumlah Kelas Kuliah</th>
                                                                <th class="text-center align-middle">Lihat Kelas</th>
                                                                {{-- <th class="text-center align-middle">Action</th> --}}
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $no_a=1;
                                                                $isEnrolled = array_column($krs_regular->toArray(), 'id_matkul');
                                                                $kelas_Enrolled = array_column($krs_regular->toArray(), 'id_kelas_kuliah');
                                                            @endphp

                                                            {{-- Tampilkan mata kuliah yang ada di $isEnrolled --}}
                                                            @foreach ($mk_regular as $data)
                                                                @php
                                                                    $isDisabled = $data->jumlah_kelas == 0;
                                                                    $isEnrolledMatkul = in_array($data->id_matkul, $isEnrolled);
                                                                    $noRPS = $data->jumlah_rps == 0;
                                                                @endphp
                                                                <tr class="{{ $isEnrolledMatkul ? 'bg-success-light' : '' }} {{ $isDisabled ? 'disabled-row' : '' }}">
                                                                    <td class="text-center align-middle" style="width: 5%;">{{ $no_a++ }}. </td>
                                                                    <td class="text-center align-middle" style="width: 10%;">{{ $data->kode_mata_kuliah }}</td>
                                                                    <td class="text-start align-middle" style="white-space: nowrap;">{{ $data->nama_mata_kuliah }}</td>
                                                                    <td class="text-center align-middle" style="white-space: nowrap;">
                                                                        <button type="button" class="btn btn-warning-light lihat-rps" data-bs-toggle="modal" data-id-matkul="{{ $data['id_matkul'] }}">
                                                                            <i class="fa fa-newspaper-o"></i> Lihat RPS
                                                                        </button>
                                                                    </td>
                                                                    <td class="text-center align-middle">{{ $data->matkul_kurikulum->semester }}</td>
                                                                    <td class="text-center align-middle">{{ $data->sks_mata_kuliah }}</td>
                                                                    <td class="text-center align-middle">{{ $data->jumlah_kelas }}</td>
                                                                    <td class="text-center align-middle">
                                                                        <button class="btn btn-success-light lihat-kelas-kuliah" title="Lihat kelas kuliah" data-id-matkul="{{ $data->id_matkul }}" 
                                                                            {{ $isDisabled || $isEnrolledMatkul || $noRPS ? 'disabled' : '' }}>
                                                                            <i class="fa fa-eye"></i>
                                                                        </button>
                                                                        <div class="result-container" id="result-container_{{ $data->id_matkul }}" style="margin-top: 5px"></div>
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
                                {{-- @if ($data_status_mahasiswa == "M" ) --}}
                                    <div class="row mb-20">
                                        <div class="col-xxl-12">
                                            <div class="box box-body mb-0 bg-white">
                                                <div class="row">
                                                    <div class="col-xl-12 col-lg-12">
                                                        <h3 class="fw-500 text-dark mb-20">Daftar Mata Kuliah Merdeka</h3>
                                                    </div>
                                                </div>
                                                {{-- Dropdown untuk memilih fakultas dan prodi --}}
                                                <div class="row mb-20">
                                                    <div class="col-md-6">
                                                        <label for="select-fakultas">Pilih Fakultas</label>
                                                        <select id="select-fakultas" class="form-control">
                                                            <option value="">Pilih Fakultas</option>
                                                            @foreach($fakultas as $fak)
                                                                <option value="{{ $fak->id }}">{{ $fak->nama_fakultas }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="select-prodi-label">Pilih Program Studi</label>
                                                        <select id="select-prodi" class="form-control" >
                                                            <option value="">Pilih Program Studi</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="table-responsive">
                                                        <table id="data-matkul-aktivitas" class="table table-bordered table-striped text-left">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center align-middle">No</th>
                                                                    <th class="text-center align-middle">Kode Mata Kuliah</th>
                                                                    <th class="text-center align-middle" style="width: 100%">Nama Mata Kuliah</th>
                                                                    <th class="text-center align-middle">RPS</th>
                                                                    <th class="text-center align-middle">Semester Ke</th>
                                                                    <th class="text-center align-middle">SKS Mata Kuliah</th>
                                                                    <th class="text-center align-middle">Jumlah Kelas Kuliah</th>
                                                                    <th class="text-center align-middle">Lihat Kelas</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="mk-merdeka-tbody">
                                                                <!-- Data Mata Kuliah Merdeka Akan Ditampilkan Disni -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {{-- @endif --}}

                                {{-- DATA AKTIVITAS KULIAH --}}
                                <div class="row mb-20">
                                    <div class="col-xxl-12">
                                        <div class="box box-body mb-0 bg-white">
                                            <div class="row">
                                                <div class="col-xl-12 col-lg-12">
                                                    <h3 class="fw-500 text-dark mb-20">Daftar Aktivitas Mahasiswa</h3>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <table id="data-matkul-merdeka" class="table table-bordered table-striped text-left">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center align-middle">No</th>
                                                                <th class="text-center align-middle">Kode Mata Kuliah</th>
                                                                <th class="text-center align-middle" style="width: 100%">Nama Mata Kuliah</th>
                                                                <th class="text-center align-middle">Semester Ke</th>
                                                                <th class="text-center align-middle">SKS Mata Kuliah</th>
                                                                <th class="text-center align-middle">Jadwal Kuliah</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $no_a=1;
                                                                $isEnrolled = array_column($krs_akt->toArray(), 'mk_konversi');
                                                                // ($isEnrolled);
                                                            @endphp
                                                            @if (!empty($mk_akt)) 
                                                                {{-- Tampilkan mata kuliah yang ada di $isEnrolled --}}
                                                                @foreach ($mk_akt as $data)
                                                                    @php
                                                                        $isEnrolledMatkul = in_array($data['id_matkul'], $isEnrolled);
                                                                        $isLower = $semester_ke < $data['semester'] ;
                                                                    @endphp
                                                                    <tr class="{{ $isEnrolledMatkul ? 'bg-success-light' : '' }}">
                                                                        <td class="text-center align-middle" style="width: 5%;">{{ $no_a++ }}. </td>
                                                                        <td class="text-center align-middle" style="width: 10%;">{{ $data['kode_mata_kuliah'] }}</td>
                                                                        <td class="text-start align-middle" style="white-space: nowrap;">{{ $data['nama_mata_kuliah'] }}</td>
                                                                        <td class="text-center align-middle">{{ $data['semester'] }}</td>
                                                                        <td class="text-center align-middle">{{ $data['sks_mata_kuliah'] }}</td>
                                                                        <td>
                                                                            <a href="{{ route('mahasiswa.krs.ambil-aktivitas', $data['id_matkul']) }}" class="btn btn-primary-light ambil-aktivitas {{ $isEnrolledMatkul  ? 'disabled' : '' }}" >
                                                                                Ambil
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
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
                                                                </label><br>
                                                                @if($beasiswa==NULL || $tagihan->status_pembayaran==NULL && $today<=$deadline && $cuti==NULL)
                                                                    <label>
                                                                        Segera Lakukan Pembayaran UKT Sebelum Periode Pembayaran Berakhir!
                                                                    </label>
                                                                @elseif($tagihan->status_pembayaran==NULL && $today<=$deadline && $cuti!=NULL)
                                                                    <label>
                                                                        Anda dalam Masa <strong>Cuti Kuliah</strong> / <strong>STOP OUT</strong>
                                                                    </label>
                                                                @elseif($tagihan->status_pembayaran==NULL && $today>$deadline && $cuti==NULL)
                                                                    <label>
                                                                        Anda Dinyatakan <strong>DROP OUT</strong> karena tidak melakukan pembayaran dan tidak mengajukan <strong>STOP OUT</strong>
                                                                    </label>
                                                                @elseif($tagihan->status_pembayaran==NULL && $today>$deadline)
                                                                    <label>
                                                                        Segera Ajukan <strong>Cuti Kuliah</strong> / Stop Out Sebelum Periode Pengajuan Berakhir!
                                                                    </label>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
