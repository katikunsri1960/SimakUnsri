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
                            @if ($statusSync == 1)
                            <div class="alert alert-warning mt-4">
                                <h3 class="alert-heading">Perhatian!</h3>
                                <hr>
                                <p class="mb-0">Data Transkrip sedang proses sinkronisasi. Harap menunggu terlebih dahulu!</p>
                                {{-- progress bar --}}
                                <div class="progress mt-3">
                                    <div id="sync-progress-bar"
                                        class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                        role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                        style="width: 0%"></div>
                                </div>
                            </div>
                            @else
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
                                                            {{-- <th class="text-center align-middle">Semester</th> --}}
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

                                                        @if($transkrip->isNotEmpty())
                                                            {{-- <tr>
                                                                <td class="text-center align-middle bg-dark" colspan="9">Nilai Perkuliahan</td>
                                                            </tr> --}}
                                                            @foreach($transkrip as $d)
                                                                <tr>
                                                                    <td class="text-center align-middle">{{$no++}}</td>
                                                                    <td class="text-center align-middle">{{$d->kode_mata_kuliah}}</td>
                                                                    <td class="text-start align-middle">{{$d->nama_mata_kuliah}}</td>
                                                                    <td class="text-center align-middle">{{$d->sks_mata_kuliah}}</td>
                                                                    {{-- <td class="text-center align-middle">{{$d->nama_semester}}</td> --}}
                                                                    <td class="text-center align-middle">{{empty($d->nilai_angka) ? 'Nilai Belum Diisi' : $d->nilai_angka}}</td>
                                                                    <td class="text-center align-middle">{{empty($d->nilai_huruf) ? 'Nilai Belum Diisi' : $d->nilai_huruf}}</td>
                                                                    <td class="text-center align-middle">{{$d->nilai_indeks===NULL ? 'Nilai Belum Diisi' : $d->nilai_indeks}}</td>
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
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td class="text-start align-middle" colspan="3"><strong>JUMLAH</strong></td>
                                                            <td class="text-center align-middle"><strong>{{ $total_sks_transkrip }}.00</strong></td>

                                                            <td colspan="2"></td>
                                                            <td class="text-center align-middle"><strong>{{ $bobot }}</strong></td>
                                                            <td class="text-center align-middle"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-start align-middle" colspan="3"><strong>INDEKS PRESTASI KUMULATIF</strong></td>
                                                            <td class="text-start align-middle" colspan="5"><strong>{{ $ipk }}</strong></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
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
</div>
