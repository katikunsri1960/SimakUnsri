<div class="tab-pane" id="transkrip-mahasiswa" role="tabpanel">
    <div class="col-xl-12 col-lg-12 col-12">
        <div class="bg-primary-light big-side-section mb-20 shadow-lg">
            <div class="row">
                <div class="row">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 py-10 mx-10">
                        <div class="box box-body">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12">
                                    <h3 class="fw-500 text-dark mt-0 mb-20">Transkrip Mahasiswa</h3>
                                </div>                             
                            </div>
                            <div class="row mb-20">
                                <div class="col-xxl-12">
                                    <div class="box box-body mb-0 bg-white">
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table id="example1" class="table table-bordered table-striped text-left">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center align-middle">No</th>
                                                            <th class="text-center align-middle">Kode Mata Kuliah</th>
                                                            <th class="text-center align-middle">Nama Mata Kuliah</th>
                                                            <th class="text-center align-middle">SKS (K)</th>
                                                            <th class="text-center align-middle">Semester</th>
                                                            <th class="text-center align-middle">Nilai Angka</th>
                                                            <th class="text-center align-middle">Nilai Huruf</th>
                                                            <th class="text-center align-middle">Nilai Indeks (B)</th>
                                                            {{-- <th class="text-center align-middle">K x B</th> --}}
                                                            <th class="text-center align-middle">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php

                                                            $no=1;
        
                                                        @endphp

                                                        @if(!empty($transkrip))
                                                        <tr>
                                                            <td class="text-center align-middle bg-dark" colspan="9">Nilai Perkuliahan</td>
                                                        </tr>
                                                        @foreach($transkrip as $d)
                                                            <tr>
                                                                <td class="text-center align-middle">{{$no++}}</td>
                                                                <td class="text-center align-middle">{{$d->kode_mata_kuliah}}</td>
                                                                <td>{{$d->nama_mata_kuliah}}</td>
                                                                <td class="text-center align-middle">{{$d->sks_mata_kuliah}}</td>
                                                                <td class="text-center align-middle">{{$d->nama_semester}}</td>
                                                                <td class="text-center align-middle">{{empty($d->nilai_angka) ? 'Nilai Belum Diisi' : $d->nilai_angka}}</td>
                                                                <td class="text-center align-middle">{{empty($d->nilai_huruf) ? 'Nilai Belum Diisi' : $d->nilai_huruf}}</td>
                                                                <td class="text-center align-middle">{{empty($d->nilai_indeks) ? 'Nilai Belum Diisi' : $d->nilai_indeks}}</td>
                                                                {{-- <td class="text-center align-middle">
                                                                    {{ !empty($d->nilai_indeks) ? $d->sks_mata_kuliah * $d->nilai_indeks : 'Nilai Belum Diisi' }}
                                                                </td> --}}
                                                                <td class="text-center align-middle">
                                                                    <a type="button" href="{{route('mahasiswa.perkuliahan.nilai-perkuliahan.histori-nilai', ['id_matkul' => $d->id_matkul])}}" class="btn btn-success waves-effect waves-light" title="Lihat Histori">
                                                                    <i class="fa-solid fa-eye"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach 
                                                        @endif 

                                                        @if(!empty($nilai_konversi))
                                                        <tr>
                                                            <td class="text-center align-middle bg-dark" colspan="9">Nilai Konversi Aktivitas</td>
                                                        </tr>
                                                        @foreach($nilai_konversi as $n)
                                                            <tr>
                                                                <td class="text-center align-middle">{{$no++}}</td>
                                                                <td class="text-center align-middle">{{$n->kode_mata_kuliah}}</td>
                                                                <td>{{$n->nama_mata_kuliah}}</td>
                                                                <td class="text-center align-middle">{{$n->sks_mata_kuliah}}</td>
                                                                <td class="text-center align-middle">{{$n->nama_semester}}</td>
                                                                <td class="text-center align-middle">{{empty($n->nilai_angka) ? 'Nilai Belum Diisi' : $n->nilai_angka}}</td>
                                                                <td class="text-center align-middle">{{empty($n->nilai_huruf) ? 'Nilai Belum Diisi' : $n->nilai_huruf}}</td>
                                                                <td class="text-center align-middle">{{empty($n->nilai_indeks) ? 'Nilai Belum Diisi' : $n->nilai_indeks}}</td>
                                                                {{-- <td class="text-center align-middle">
                                                                    {{ !empty($n->nilai_indeks) ? $n->sks_mata_kuliah * $d->nilai_indeks : 'Nilai Belum Diisi' }}
                                                                </td> --}}
                                                                <td class="text-center align-middle">
                                                                    <a type="button" href="{{route('mahasiswa.perkuliahan.nilai-perkuliahan.histori-nilai', ['id_matkul' => $n->id_matkul])}}" class="btn btn-success waves-effect waves-light" title="Lihat Histori">
                                                                    <i class="fa-solid fa-eye"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach 
                                                        @endif  

                                                        @if(!empty($nilai_transfer))
                                                        <tr>
                                                            <td class="text-center align-middle bg-dark" colspan="9">Nilai Transfer Pendidikan</td>
                                                        </tr>
                                                        @foreach($nilai_transfer as $nt)
                                                            <tr>
                                                                <td class="text-center align-middle">{{$no++}}</td>
                                                                <td class="text-center align-middle">{{$nt->kode_matkul_diakui}}</td>
                                                                <td>{{$nt->nama_mata_kuliah_diakui}}</td>
                                                                <td class="text-center align-middle">{{$nt->sks_mata_kuliah_diakui}}</td>
                                                                <td class="text-center align-middle">{{empty($nt->nama_semester) ? '-' : $nt->nama_semester}}</td>
                                                                <td class="text-center align-middle">{{empty($nt->nilai_angka) ? '-' : $nt->nilai_angka}}</td>
                                                                <td class="text-center align-middle">{{empty($nt->nilai_huruf_diakui) ? 'Nilai Belum Diisi' : $nt->nilai_huruf_diakui}}</td>
                                                                <td class="text-center align-middle">{{empty($nt->nilai_angka_diakui) ? 'Nilai Belum Diisi' : $nt->nilai_angka_diakui}}</td>
                                                                {{-- <td class="text-center align-middle">
                                                                    {{ !empty($nt->nilai_indeks) ? $nt->sks_mata_kuliah_diakui * $nt->nilai_indeks_diakui : 'Nilai Belum Diisi' }}
                                                                </td> --}}
                                                                <td class="text-center align-middle">
                                                                    <a type="button" href="" class="btn btn-success waves-effect waves-light" title="Lihat Histori">
                                                                    <i class="fa-solid fa-eye"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach 
                                                        @endif      
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td class="text-center align-middle" colspan="3"><strong>Total SKS Ditempuh</strong></td>
                                                            <td class="text-center align-middle"><strong>{{ $total_sks }}</strong></td>
                                                            <td colspan="5"></td>
                                                        </tr>
                                                    </tfoot>
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
