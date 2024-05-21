<div class="tab-pane active" id="krs" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light rounded20 big-side-section mb-20 shadow-lg">
            <div class="row">
                <div class="row">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                        @if($data_status_mahasiswa == "A" )
                            <div class="row mb-20">
                                <div class="col-xxl-12">
                                    <div class="box box-body mb-0 bg-white">
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12">
                                                <h3 class="fw-500 text-dark mb-20">Kartu Rencana Studi Reguler</h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table id="krs-regular" class="table table-bordered table-striped text-left">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center align-middle">No</th>
                                                            <th class="text-center align-middle">Kode Mata Kuliah</th>
                                                            <th class="text-center align-middle">Nama Mata Kuliah</th>
                                                            <th class="text-center align-middle">Nama Kelas</th>
                                                            <th class="text-center align-middle">SKS</th>
                                                            <th class="text-center align-middle">Waktu Kuliah</th>
                                                            <th class="text-center align-middle">Status</th>
                                                            <th class="text-center align-middle">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no=1;
                                                            $totalSks = 0;
                                                            
                                                            $today = \Carbon\Carbon::now();
                                                            $deadline = \Carbon\Carbon::parse($semester_aktif->krs_selesai);
                                                        @endphp
                            
                                                        @foreach ($krs_regular as $data)
                                                            <tr>
                                                                <td class="text-center align-middle">{{ $no++ }}</td>
                                                                <td class="text-start align-middle">{{$data->kode_mata_kuliah}}</td>
                                                                <td class="text-start align-middle">{{$data->nama_mata_kuliah}}</td>
                                                                <td class="text-center align-middle">{{$data->nama_kelas_kuliah}}</td>
                                                                <td class="text-center align-middle">{{$data->sks_mata_kuliah}}</td>
                                                                <td class="text-start align-middle">{{$data->jadwal_hari}}, {{$data->jadwal_jam_mulai}} - {{$data->jadwal_jam_selesai}}</td>
                                                                <td><div class="px-25 py-10"><span class="badge badge-danger-light mb-5">Belum Disetujui</span></div></td>
                                                                <td>
                                                                    @if(!$today->greaterThan($deadline))
                                                                        <form action="{{ route('mahasiswa.krs.hapus_kelas_kuliah') }}" method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <input type="hidden" name="id_kelas_kuliah" value="{{ $data->id_kelas_kuliah }}">
                                                                            <button type="submit" class="btn btn-danger btn-hapus-kelas" onclick="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini dari KRS?')">
                                                                                Hapus Mata Kuliah
                                                                            </button>
                                                                        </form>
                                                                    @elseif ($today->greaterThan($deadline))
                                                                        <form action="{{ route('mahasiswa.krs.hapus_kelas_kuliah') }}" method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <input type="hidden" name="id_kelas_kuliah" value="{{ $data->id_kelas_kuliah }}">
                                                                            <button type="submit" class="btn btn-danger btn-hapus-kelas disabled" onclick="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini dari KRS?')">
                                                                                Hapus Mata Kuliah
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $totalSks += $data->sks_mata_kuliah;
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td class="text-center align-middle" colspan="4"><strong>Total SKS Diambil</strong></td>
                                                            <td class="text-center align-middle"><strong>{{$totalSks}}</strong></td>
                                                            <td colspan="3"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif ($data_status_mahasiswa == "M" )
                            <div class="row mb-20">
                                <div class="col-xxl-12">
                                    <div class="box box-body mb-0 bg-white">
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12">
                                                <h3 class="fw-500 text-dark mb-20">Kartu Rencana Studi Reguler</h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table id="krs-regular" class="table table-bordered table-striped text-left">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center align-middle">No</th>
                                                            <th class="text-center align-middle">Kode Mata Kuliah</th>
                                                            <th class="text-center align-middle">Nama Mata Kuliah</th>
                                                            <th class="text-center align-middle">Nama Kelas</th>
                                                            <th class="text-center align-middle">SKS</th>
                                                            <th class="text-center align-middle">Waktu Kuliah</th>
                                                            <th class="text-center align-middle">Status</th>
                                                            <th class="text-center align-middle">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no=1;
                                                            $totalSks = 0;
                                                            
                                                            $today = \Carbon\Carbon::now();
                                                            $deadline = \Carbon\Carbon::parse($semester_aktif->krs_selesai);
                                                        @endphp
                            
                                                        @foreach ($krs_regular as $data)
                                                            <tr>
                                                                <td class="text-center align-middle">{{ $no++ }}</td>
                                                                <td class="text-start align-middle">{{$data->kode_mata_kuliah}}</td>
                                                                <td class="text-start align-middle">{{$data->nama_mata_kuliah}}</td>
                                                                <td class="text-center align-middle">{{$data->nama_kelas_kuliah}}</td>
                                                                <td class="text-center align-middle">{{$data->sks_mata_kuliah}}</td>
                                                                <td class="text-start align-middle">{{$data->jadwal_hari}}, {{$data->jadwal_jam_mulai}} - {{$data->jadwal_jam_selesai}}</td>
                                                                <td><div class="px-25 py-10"><span class="badge badge-danger-light mb-5">Belum Disetujui</span></div></td>
                                                                <td>
                                                                    @if(!$today->greaterThan($deadline))
                                                                        <form action="{{ route('mahasiswa.krs.hapus_kelas_kuliah') }}" method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <input type="hidden" name="id_kelas_kuliah" value="{{ $data->id_kelas_kuliah }}">
                                                                            <button type="submit" class="btn btn-danger btn-hapus-kelas" onclick="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini dari KRS?')">
                                                                                Hapus Mata Kuliah
                                                                            </button>
                                                                        </form>
                                                                    @elseif ($today->greaterThan($deadline))
                                                                        <form action="{{ route('mahasiswa.krs.hapus_kelas_kuliah') }}" method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <input type="hidden" name="id_kelas_kuliah" value="{{ $data->id_kelas_kuliah }}">
                                                                            <button type="submit" class="btn btn-danger btn-hapus-kelas disabled" onclick="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini dari KRS?')">
                                                                                Hapus Mata Kuliah
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $totalSks += $data->sks_mata_kuliah;
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td class="text-center align-middle" colspan="4"><strong>Total SKS Diambil</strong></td>
                                                            <td class="text-center align-middle"><strong>{{$totalSks}}</strong></td>
                                                            <td colspan="3"></td>
                                                        </tr>
                                                    </tfoot>
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
                                                <h3 class="fw-500 text-dark mb-20">Kartu Rencana Studi Kampus Merdeka</h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table id="krs-merdeka" class="table table-bordered table-striped text-left">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center align-middle">No</th>
                                                            <th class="text-center align-middle">Kode Mata Kuliah</th>
                                                            <th class="text-center align-middle">Nama Mata Kuliah</th>
                                                            <th class="text-center align-middle">Nama Kelas</th>
                                                            <th class="text-center align-middle">SKS</th>
                                                            <th class="text-center align-middle">Waktu Kuliah</th>
                                                            <th class="text-center align-middle">Status</th>
                                                            <th class="text-center align-middle">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no=1;
                                                            $totalSks = 0;
                                                            
                                                            $today = \Carbon\Carbon::now();
                                                            $deadline = \Carbon\Carbon::parse($semester_aktif->krs_selesai);
                                                        @endphp
                            
                                                        @foreach ($krs_merdeka as $data)
                                                            <tr>
                                                                <td class="text-center align-middle">{{ $no++ }}</td>
                                                                <td class="text-start align-middle">{{$data->kode_mata_kuliah}}</td>
                                                                <td class="text-start align-middle">{{$data->nama_mata_kuliah}}</td>
                                                                <td class="text-center align-middle">{{$data->nama_kelas_kuliah}}</td>
                                                                <td class="text-center align-middle">{{$data->sks_mata_kuliah}}</td>
                                                                <td class="text-start align-middle">{{$data->jadwal_hari}}, {{$data->jadwal_jam_mulai}} - {{$data->jadwal_jam_selesai}}</td>
                                                                <td><div class="px-25 py-10"><span class="badge badge-danger-light mb-5">Belum Disetujui</span></div></td>
                                                                <td>
                                                                    @if(!$today->greaterThan($deadline))
                                                                        <form action="{{ route('mahasiswa.krs.hapus_kelas_kuliah') }}" method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <input type="hidden" name="id_kelas_kuliah" value="{{ $data->id_kelas_kuliah }}">
                                                                            <button type="submit" class="btn btn-danger btn-hapus-kelas" onclick="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini dari KRS?')">
                                                                                Hapus Mata Kuliah
                                                                            </button>
                                                                        </form>
                                                                    @elseif ($today->greaterThan($deadline))
                                                                        <form action="{{ route('mahasiswa.krs.hapus_kelas_kuliah') }}" method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <input type="hidden" name="id_kelas_kuliah" value="{{ $data->id_kelas_kuliah }}">
                                                                            <button type="submit" class="btn btn-danger btn-hapus-kelas disabled" onclick="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini dari KRS?')">
                                                                                Hapus Mata Kuliah
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $totalSks += $data->sks_mata_kuliah;
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td class="text-center align-middle" colspan="4"><strong>Total SKS Diambil</strong></td>
                                                            <td class="text-center align-middle"><strong>{{$totalSks}}</strong></td>
                                                            <td colspan="3"></td>
                                                        </tr>
                                                    </tfoot>
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
                                                                Anda tidak dapat melakukan pengisian KRS!
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
