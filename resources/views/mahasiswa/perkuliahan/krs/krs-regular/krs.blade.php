<div class="tab-pane active" id="krs" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light rounded20 big-side-section mb-20 shadow-lg">
            <div class="row">
                <div class="row">
                    
                    <div class="col-xxl-12 py-10 mx-10 rounded20">
                        @if(!empty($beasiswa) ||$pembayaran_manual > 0|| $non_gelar > 0|| $penundaan_pembayaran > 0 || !empty($tagihan->pembayaran->status_pembayaran) || $semester_select != $semester_aktif->id_semester)
                            <div class="row mb-20">
                                <div class="col-xl-12">
                                    <div class="box box-body mb-0 bg-white">
                                        {{-- @if($krs_regular->isNotEmpty() || $krs_merdeka->isNotEmpty() || $krs_akt->isNotEmpty()) --}}
                                        @if($total_krs_submitted > 0 && $semester_select == $semester_aktif->id_semester)
                                        <div class="row mb-10">
                                            <span class="text-danger text-center">*Silahkan klik tombol "Ajukan KRS" agar pengajuan KRS dapat disetujui Dosen PA!</span>
                                        </div>
                                        <div class="box-header d-flex justify-content-center py-0 px-15 mt-10" style="border-bottom: 0px">
                                            <div class="row d-flex justify-content-end">
                                                <h4 class="mb-5">
                                                    <form action="{{ route('mahasiswa.krs.submit') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id_reg" value="{{ $riwayat_pendidikan->id_registrasi_mahasiswa }}">
                                                    
                                                        <div class="row">
                                                            <button type="submit" class="btn btn-success">Ajukan KRS</button>
                                                        </div>
                                                    </form>
                                                </h4>
                                            </div>
                                        </div>
                                        <hr class="my-15">
                                        @endif
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12">
                                                <h3 class="fw-500 text-dark mb-20">Rencana Studi Reguler</h3>
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
                                                        @endphp

                                                        @foreach ($krs_regular as $data)
                                                            <tr>
                                                                <td class="text-center align-middle" style="width:2%">{{ $no++ }}</td>
                                                                <td class="text-center align-middle" style="width:10%">{{$data->kode_mata_kuliah}}</td>
                                                                <td class="text-start align-middle" style="white-space: nowrap;">{{$data->nama_mata_kuliah}}</td>
                                                                <td class="text-center align-middle" style="white-space: nowrap; width:10%">{{$data->nama_kelas_kuliah}}</td>
                                                                <td class="text-center align-middle" style="width:5%">{{$data->sks_mata_kuliah}}</td>
                                                                <td class="text-center align-middle" style="white-space: nowrap; width:20%">{{$data->jadwal_hari}}, {{$data->jadwal_jam_mulai}} - {{$data->jadwal_jam_selesai}}</td>
                                                                <td class="text-center align-middle" style="width:10%">
                                                                    {{-- <div>
                                                                        {!! $data->approved == 0 ? '<span class="badge badge-xl badge-danger-light mb-5">Belum Disetujui</span>' : '<span class="badge badge-xl badge-success-light mb-5">Disetujui</span>' !!}
                                                                    </div> --}}
                                                                    <div>
                                                                        @if ($data->approved == 0 && $data->submitted == 0)
                                                                            <span class="badge badge-xl badge-danger-light">Belum Diajukan</span>
                                                                        @elseif ($data->approved == 0 && $data->submitted == 1)
                                                                            <span class="badge badge-xl badge-warning-light">Sudah Diajukan<br>(Menunggu Persetujuan Dosen PA)</span>
                                                                        @else
                                                                            <span class="badge badge-xl badge-success-light">Disetujui</span>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                                <td class="text-center align-middle" style="width:3%">
                                                                    <form action="{{route('mahasiswa.krs.hapus_kelas_kuliah',['pesertaKelas'=>$data->id])}}" method="post" class="delete-form" data-id="{{$data->id}}" id="deleteForm{{$data->id}}">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button type="submit" class="btn btn-danger rounded-10" data-id="{{ $data->id }}" title="Hapus Data" {{ ($today <= $batas_isi_krs && $data->approved == 0) ? '' : 'disabled' }}>
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td class="text-center align-middle" colspan="4"><strong>Total SKS Diambil</strong></td>
                                                            <td class="text-center align-middle"><strong>{{$total_sks_regular}}</strong></td>
                                                            <td colspan="3"></td>
                                                        </tr>
                                                    </tfoot>
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
                                                <h3 class="fw-500 text-dark mb-20">Rencana Studi Kampus Merdeka</h3>
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
                                                        @endphp

                                                        @foreach ($krs_merdeka as $data)
                                                            <tr>
                                                                <td class="text-center align-middle" style="width:2%">{{ $no++ }}</td>
                                                                <td class="text-center align-middle" style="width:10%">{{$data->kode_mata_kuliah}}</td>
                                                                <td class="text-start align-middle" style="white-space: nowrap;">{{$data->nama_mata_kuliah}}</td>
                                                                <td class="text-center align-middle" style="white-space: nowrap; width:10%">{{$data->nama_kelas_kuliah}}</td>
                                                                <td class="text-center align-middle" style="width:5%">{{$data->sks_mata_kuliah}}</td>
                                                                <td class="text-center align-middle" style="white-space: nowrap; width:20%">{{$data->jadwal_hari}}, {{$data->jadwal_jam_mulai}} - {{$data->jadwal_jam_selesai}}</td>
                                                                <td class="text-center align-middle" style="width:10%">
                                                                    {{-- <div>
                                                                        {!! $data->approved == 0 ? '<span class="badge badge-xl badge-danger-light mb-5">Belum Disetujui</span>' : '<span class="badge badge-xl badge-success-light mb-5">Disetujui</span>' !!}
                                                                    </div> --}}
                                                                    <div>
                                                                        @if ($data->approved == 0 && $data->submitted == 0)
                                                                            <span class="badge badge-xl badge-danger-light">Belum Diajukan</span>
                                                                        @elseif ($data->approved == 0 && $data->submitted == 1)
                                                                            <span class="badge badge-xl badge-warning-light">Sudah Diajukan<br>Menunggu Persetujuan Dosen PA</span>
                                                                        @else
                                                                            <span class="badge badge-xl badge-success-light">Disetujui</span>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                                <td class="text-center align-middle" style="width:3%">
                                                                    <form action="{{route('mahasiswa.krs.hapus_kelas_kuliah',['pesertaKelas'=>$data->id])}}" method="post" class="delete-form" data-id="{{$data->id}}" id="deleteForm{{$data->id}}">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button type="submit" class="btn btn-danger" data-id="{{ $data->id }}" title="Hapus Data" {{ ($today <= $batas_isi_krs && $data->approved == 0) ? '' : 'disabled' }}>
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td class="text-center align-middle" colspan="4"><strong>Total SKS Diambil</strong></td>
                                                            <td class="text-center align-middle"><strong>{{$total_sks_merdeka}}</strong></td>
                                                            <td colspan="3"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- @endif --}}
                            <div class="row mb-5">
                                <div class="col-xxl-12">
                                    <div class="box box-body mb-0 bg-white">
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12">
                                                <h3 class="fw-500 text-dark mb-20">Rencana Aktivitas Reguler Mahasiswa</h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table id="krs-akt" class="table table-bordered table-striped text-left">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center align-middle">No</th>
                                                            <th class="text-center align-middle">Nama Aktivitas</th>
                                                            <th class="text-center align-middle">Nama Mata Kuliah Konversi</th>
                                                            {{-- <th class="text-center align-middle">Semester</th> --}}
                                                            <th class="text-center align-middle">Kode Mata Kuliah</th>
                                                            <th class="text-center align-middle">SKS</th>
                                                            <th class="text-center align-middle">Dosen Pembimbing</th>
                                                            <th class="text-center align-middle">Status</th>
                                                            <th class="text-center align-middle">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no = 1;
                                                        @endphp
                                        
                                                        @foreach ($krs_akt as $data)
                                                            <tr>
                                                                <td class="text-center align-middle" style="width:2%">{{ $no++ }}</td>
                                                                <td class="text-center align-middle" style="width:10%; white-space: nowrap;">{{ mb_strtoupper($data->nama_jenis_aktivitas) }}</td>
                                                                <td class="text-start align-middle" style="white-space: nowrap;">{{ $data->konversi->nama_mata_kuliah }}</td>
                                                                <td class="text-center align-middle" style="white-space: nowrap; width:10%">{{ $data->konversi->kode_mata_kuliah }}</td>
                                                                <td class="text-center align-middle" style="width:5%">
                                                                    <div>
                                                                        {{ $data->konversi == NULL ? 'Tidak Diisi' : $data->konversi->sks_mata_kuliah }}
                                                                    </div>
                                                                </td>
                                                                <td class="text-start align-middle" style="white-space: nowrap; width:20%">
                                                                    @if($data->bimbing_mahasiswa->isEmpty())
                                                                        <span class="badge badge-xl badge-danger-light">Tidak ada dosen pembimbing</span>
                                                                    @else
                                                                        @foreach($data->bimbing_mahasiswa as $dosen_bimbing)
                                                                            <ul>
                                                                                <li>
                                                                                    {{$dosen_bimbing->nama_dosen}} 
                                                                                    <p>{{$dosen_bimbing->pembimbing_ke == 1 ? '(Pembimbing Utama)' : '(Pembimbing Pendamping)'}}</p>
                                                                                </li>
                                                                            </ul> 
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                                <td class="text-center align-middle" style="width:10%">
                                                                    @foreach ($data->bimbing_mahasiswa as $dosen_bimbing)
                                                                        <div class="mb-20">
                                                                            @if ($data->approve_krs == 0 && $data->submitted == 0)
                                                                                <span class="badge badge-xl badge-danger-light">Belum Diajukan</span>
                                                                            @elseif ($data->approve_krs == 0 && $data->submitted == 1 )
                                                                                <span class="badge badge-xl badge-warning-light">Sudah Diajukan<br>Menunggu Persetujuan Dosen PA</span>
                                                                            @elseif ($data->submitted == 1 && $dosen_bimbing->approved == 0)
                                                                                <span class="badge badge-xl badge-warning-light">Menunggu konfirmasi Koprodi</span>
                                                                            @elseif ($data->approve_krs == 1 && $dosen_bimbing->approved_dosen == 0)
                                                                                <span class="badge badge-xl badge-warning-light">Menunggu konfirmasi dosen</span>
                                                                            @elseif ($data->approve_krs == 1 && $dosen_bimbing->approved_dosen == 2)
                                                                                <span class="badge badge-xl badge-danger-light">Ditolak dosen pembimbing</span>
                                                                            @elseif ($data->approve_krs == 0 && $data->submitted == 1 && $dosen_bimbing->approved == 1)
                                                                            <span class="badge badge-xl badge-warning-light">Sudah Diajukan<br>Menunggu Persetujuan Dosen PA</span>
                                                                            @else
                                                                                <span class="badge badge-xl badge-success-light">Disetujui</span>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                </td>
                                                                
                                                                <td class="text-center align-middle" style="width:3%">
                                                                    <form action="{{route('mahasiswa.krs.hapus-aktivitas',['id'=>$data->id])}}" method="post" class="delete-form" data-id="{{$data->id}}" id="deleteForm{{$data->id}}">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button type="submit" class="btn btn-danger" data-id="{{ $data->id }}" title="Hapus Data" {{ ($today <= $batas_isi_krs && $data->approve_krs == 0) ? '' : 'disabled' }}>
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                    
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td class="text-center align-middle" colspan="4"><strong>Total SKS Diambil</strong></td>
                                                            <td class="text-center align-middle"><strong>{{ $total_sks_akt }}</strong></td>
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
                            <div class="row mb-5">
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
                                                            </label><br>
                                                            @if($cuti!=NULL)
                                                                <label>
                                                                    Anda dalam Masa <strong>Cuti Kuliah</strong> / <strong>STOP OUT</strong>
                                                                </label>
                                                            @elseif($beasiswa==NULL || $tagihan->status_pembayaran==NULL && $today<=$batas_pembayaran && $cuti==NULL)
                                                                <label>
                                                                    Segera Lakukan Pembayaran UKT Sebelum Periode Pembayaran Berakhir!
                                                                </label>
                                                            @elseif($tagihan->status_pembayaran==NULL && $today > $batas_pembayaran && $cuti!=NULL)
                                                                <label>
                                                                    Anda dalam Masa <strong>Cuti Kuliah</strong> / <strong>STOP OUT</strong>
                                                                </label>
                                                            @elseif($tagihan->status_pembayaran==NULL  && $cuti==NULL && $today > $masa_tenggang )
                                                                <label>
                                                                    Anda Dinyatakan <strong>DROP OUT</strong> karena tidak melakukan pembayaran dan tidak mengajukan <strong>STOP OUT</strong>
                                                                </label>
                                                            @elseif($tagihan->status_pembayaran==NULL && $today>$batas_pembayaran && $today<$masa_tenggang)
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
