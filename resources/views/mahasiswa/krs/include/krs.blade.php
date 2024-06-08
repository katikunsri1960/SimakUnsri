<div class="tab-pane active" id="krs" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light rounded20 big-side-section mb-20 shadow-lg">
            <div class="row">
                <div class="row">
                    @php
                        $today = \Carbon\Carbon::now();
                        $deadline = \Carbon\Carbon::parse($semester_aktif->krs_selesai);
                    @endphp

                    <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                        @if($data_status_mahasiswa == "A" || $data_status_mahasiswa == "M" )
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
                                                        @endphp

                                                        @foreach ($krs_regular as $data)
                                                            <tr>
                                                                <td class="text-center align-middle" style="width:2%">{{ $no++ }}</td>
                                                                <td class="text-start align-middle" style="width:5%">{{$data->kode_mata_kuliah}}</td>
                                                                <td class="text-start align-middle" style="white-space: nowrap;">{{$data->nama_mata_kuliah}}</td>
                                                                <td class="text-center align-middle">{{$data->nama_kelas_kuliah}}</td>
                                                                <td class="text-center align-middle" style="width:5%">{{$data->sks_mata_kuliah}}</td>
                                                                <td class="text-center align-middle" style="white-space: nowrap; width:20%">{{$data->jadwal_hari}}, {{$data->jadwal_jam_mulai}} - {{$data->jadwal_jam_selesai}}</td>
                                                                <td class="text-center align-middle" style="width:10%">
                                                                    <div>
                                                                        {!! $data->approved == 0 ? '<span class="badge badge-xl badge-danger-light mb-5">Belum Disetujui</span>' : '<span class="badge badge-xl badge-success-light mb-5">Disetujui</span>' !!}
                                                                    </div>
                                                                </td>
                                                                <td class="text-center align-middle" style="width:3%">
                                                                    <form action="{{route('mahasiswa.krs.hapus_kelas_kuliah',['pesertaKelas'=>$data->id])}}" method="post" class="delete-form" data-id="{{$data->id}}" id="deleteForm{{$data->id}}">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button type="submit" class="btn btn-danger" data-id="{{ $data->id }}" title="Hapus Data" {{ (!$today->greaterThan($deadline) && $data->approved == 0) ? '' : 'disabled' }}>
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
                            
                            @if ($data_status_mahasiswa == "M" )
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
                                                            @endphp

                                                            @foreach ($krs_merdeka as $data)
                                                                <tr>
                                                                    <td class="text-center align-middle" style="width:2%">{{ $no++ }}</td>
                                                                    <td class="text-start align-middle" style="width:5%">{{$data->kode_mata_kuliah}}</td>
                                                                    <td class="text-start align-middle" style="white-space: nowrap;">{{$data->nama_mata_kuliah}}</td>
                                                                    <td class="text-center align-middle">{{$data->nama_kelas_kuliah}}</td>
                                                                    <td class="text-center align-middle" style="width:5%">{{$data->sks_mata_kuliah}}</td>
                                                                    <td class="text-start align-middle" style="white-space: nowrap; width:20%">{{$data->jadwal_hari}}, {{$data->jadwal_jam_mulai}} - {{$data->jadwal_jam_selesai}}</td>
                                                                    <td class="text-center align-middle" style="width:10%">
                                                                        <div>
                                                                            {!! $data->approved == 0 ? '<span class="badge badge-xl badge-danger-light mb-5">Belum Disetujui</span>' : '<span class="badge badge-xl badge-success-light mb-5">Disetujui</span>' !!}
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-center align-middle" style="width:3%">
                                                                        <form action="{{route('mahasiswa.krs.hapus_kelas_kuliah',['pesertaKelas'=>$data->id])}}" method="post" class="delete-form" data-id="{{$data->id}}" id="deleteForm{{$data->id}}">
                                                                            @csrf
                                                                            @method('delete')
                                                                            <button type="submit" class="btn btn-danger" data-id="{{ $data->id }}" title="Hapus Data" {{ (!$today->greaterThan($deadline) && $data->approved == 0) ? '' : 'disabled' }}>
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
                            @endif

                            <div class="row mb-20">
                                <div class="col-xxl-12">
                                    <div class="box box-body mb-0 bg-white">
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12">
                                                <h3 class="fw-500 text-dark mb-20">Aktivitas Mahasiswa</h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table id="krs-akt" class="table table-bordered table-striped text-left">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center align-middle">No</th>
                                                            <th class="text-center align-middle">Jenis Aktivitas</th>
                                                            <th class="text-center align-middle">Judul</th>
                                                            {{-- <th class="text-center align-middle">Semester</th> --}}
                                                            <th class="text-center align-middle">Lokasi</th>
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
                                                                <td class="text-center align-middle" style="width:5%" style="white-space: nowrap;">{{ $data->aktivitas_mahasiswa->nama_jenis_aktivitas }}</td>
                                                                <td class="text-start align-middle" style="white-space: nowrap;">{{ $data->judul }}</td>
                                                                {{-- <td class="text-center align-middle" style="white-space: nowrap;">{{ $data->nama_semester }}</td> --}}
                                                                <td class="text-center align-middle" style="white-space: nowrap;">{{ $data->aktivitas_mahasiswa->lokasi }}</td>
                                                                <td class="text-center align-middle" style="width:5%">{{ $data->aktivitas_mahasiswa->konversi->sks_mata_kuliah }}</td>
                                                                <td class="text-start align-middle"  style="white-space: nowrap; width:20%">
                                                                    @foreach($data->aktivitas_mahasiswa->bimbing_mahasiswa as $dosen_bimbing)
                                                                        <ul>
                                                                            <li>
                                                                                {{$dosen_bimbing->nama_dosen}} <p>(Pembimbing {{$dosen_bimbing->pembimbing_ke}})</p>
                                                                            </li>
                                                                        </ul> 
                                                                    @endforeach
                                                                </td>
                                                                <td class="text-center align-middle" style="width:10%">
                                                                    <div>
                                                                        {!! $data->approved == 0 ? '<span class="badge badge-xl badge-danger-light mb-5">Belum Disetujui</span>' : '<span class="badge badge-xl badge-success-light mb-5">Disetujui</span>' !!}
                                                                    </div>
                                                                </td>
                                                                {{-- <td class="text-center align-middle">
                                                                    <form action="{{ route('mahasiswa.krs.hapus-aktivitas', ['id' => $data->id_aktivitas]) }}" method="POST" class="delete-aktivitas" id="deleteForm{{ $data->id_aktivitas }}">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button type="button" class="btn btn-danger delete-button" data-id="{{ $data->id_aktivitas }}" title="Hapus Data" {{ (!$today->greaterThan($deadline) && $data->approved == 0) ? '' : 'disabled' }}>
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </td> --}}
                                                                
                                                                <td class="text-center align-middle" style="width:3%">
                                                                    <button class="btn btn-danger btn-rounded hapus-aktivitas" data-id="{{ $data->aktivitas_mahasiswa->id }}"><i class="fa fa-trash"></i></button>
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
