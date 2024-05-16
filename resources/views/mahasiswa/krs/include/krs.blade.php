<div class="tab-pane active" id="krs" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light rounded20 big-side-section mb-20 shadow-lg">
            <div class="row">
                <div class="row">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                        <div class="box box-body">
                            {{-- <div class="row">
                                <div class="col-xl-12 col-lg-12">
                                    <h3 class="fw-500 text-dark mt-0 mb-20">Kartu Rencana Studi Reguler</h3>
                                </div>                             
                            </div> --}}
                            @if($akm[0]->id_status_mahasiswa == 'A' )
                                <div class="row mb-20">
                                    <div class="col-xxl-12">
                                        <div class="box box-body mb-0 bg-white">
                                            <div class="row">
                                                <div class="col-xl-4 col-lg-12">
                                                    <h3 class="fw-500 text-dark mt-0">Kartu Rencana Studi Reguler</h3>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <table id="example1" class="table table-bordered table-striped text-left">
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
                                                                <!-- <td>Action</td> -->
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
                                                                    {{-- <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td> --}}
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
                            @elseif ($akm[0]->id_status_mahasiswa == 'M' )
                                <div class="row mb-20">
                                    <div class="col-xxl-12">
                                        <div class="box box-body mb-0 bg-white">
                                            <div class="row">
                                                <div class="col-xl-4 col-lg-12">
                                                    <h3 class="fw-500 text-dark mt-0">Kartu Rencana Studi Kampus Merdeka</h3>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <table id="example1" class="table table-bordered table-striped text-left">
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
                                                                <!-- <td>Action</td> -->
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
                                                                    {{-- <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td> --}}
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
                                                <div class="col-xl-4 col-lg-12">
                                                    <h3 class="fw-500 text-dark mt-0">Kartu Rencana Studi</h3>
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
                                                                    Anda tidak bisa melakukan pengisian KRS!
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
                            


                            {{-- <div class="row">
                                <div class="col-xxl-12">
                                    <div class="box box-body mb-0 bg-white">
                                        <div class="row">
                                            <div class="col-xl-4 col-lg-12">
                                                <h3 class="fw-500 text-dark mt-0">Aktivitas Mahasiswa</h3>
                                            </div>                             
                                        </div>
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table id="example1" class="table table-bordered table-striped text-center">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Kode Aktivitas Mahasiswa</th>
                                                            <th>Nama Aktivitas Mahasiswa</th>                                    
                                                            <th>Kode Kelas</th>
                                                            <th>Nama Kelas</th>
                                                            <th>SKS</th>
                                                            <th>Nama Dosen</th>
                                                            <th>Waktu Aktivitas Mahasiswa</th>
                                                            <th>Status</th>
                                                            <th><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></th>
                                                            <!-- <td>Action</td> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
                                                        <tr>
                                                            <td>5</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
                                                        <tr>
                                                            <td>6</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
                                                    </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>	 --}}

                        </div>
                    </div>
                </div>  
            </div>
        </div>

        {{-- <div class="bg-primary-light rounded20 big-side-section">
            <div class="row">
                <div class="row">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                        <div class="box box-body">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12">
                                    <h3 class="fw-500 text-dark mt-0 mb-20">Kartu Rencana Studi Kampus Merdeka</h3>
                                </div>                             
                            </div>
                            <div class="row mb-20">
                                <div class="col-xxl-12">
                                    <div class="box box-body mb-0 bg-white">
                                        <div class="row">
                                            <div class="col-xl-4 col-lg-12">
                                                <h3 class="fw-500 text-dark mt-0">Kartu Rencana Studi</h3>
                                            </div>                             
                                        </div>
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table id="example1" class="table table-bordered table-striped text-center">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Kode Mata Kuliah</th>
                                                            <th>Nama Mata Kuliah</th>                                    
                                                            <th>Kode Kelas</th>
                                                            <th>Nama Kelas</th>
                                                            <th>SKS</th>
                                                            <th>Nama Dosen</th>
                                                            <th>Waktu Kuliah</th>
                                                            <th>Status</th>
                                                            <th><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></th>
                                                            <!-- <td>Action</td> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
                                                        <tr>
                                                            <td>5</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
                                                        <tr>
                                                            <td>6</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
                                                    </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>		


                            <div class="row">
                                <div class="col-xxl-12">
                                    <div class="box box-body mb-0 bg-white">
                                        <div class="row">
                                            <div class="col-xl-4 col-lg-12">
                                                <h3 class="fw-500 text-dark mt-0">Aktivitas Mahasiswa</h3>
                                            </div>                             
                                        </div>
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table id="example1" class="table table-bordered table-striped text-center">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Kode Aktivitas Mahasiswa</th>
                                                            <th>Nama Aktivitas Mahasiswa</th>                                    
                                                            <th>Kode Kelas</th>
                                                            <th>Nama Kelas</th>
                                                            <th>SKS</th>
                                                            <th>Nama Dosen</th>
                                                            <th>Waktu Aktivitas Mahasiswa</th>
                                                            <th>Status</th>
                                                            <th><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></th>
                                                            <!-- <td>Action</td> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
                                                        <tr>
                                                            <td>5</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
                                                        <tr>
                                                            <td>6</td>
                                                            <td>FSK11711</td>
                                                            <td>KALKULUS II</td>
                                                            <td>IDL01</td>
                                                            <td>Inderalaya A</td>
                                                            <td>3</td>
                                                            <td>PROF. DR. IR. BAMBANG TUTUKO, M.T.</td>
                                                            <td>Senin, Pukul 08.00 - 10.30 WIB</td>
                                                            <td><button type="button" class="waves-effect waves-light btn btn-danger-light mb-5">Belum Disetujui</button></td>
                                                            <td><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></td>
                                                            <!-- <td>
                                                                <a class="btn btn-rounded bg-success-light" href="{{route('mahasiswa.create-krs')}}"><i class="fa fa-line-chart"><span class="path1"></span><span class="path2"></span></i> Ambil</a>
                                                            </td> -->
                                                        </tr>
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
         --}}
    </div>
</div>
