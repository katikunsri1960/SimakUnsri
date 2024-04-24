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
                                                            <th class="text-center align-middle">SKS</th>
                                                            <th class="text-center align-middle">Semester Di Ambil</th>
                                                            <th class="text-center align-middle">Nilai Angka</th>
                                                            <th class="text-center align-middle">Nilai Huruf</th>
                                                            <th class="text-center align-middle">Nilai Indeks</th>
                                                            <!-- <th class="text-center align-middle"><input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-success" /><label for="md_checkbox_23"></label></th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php

                                                            $no=1;

                                                        @endphp

                                                        @foreach($transkrip as $d)
                                                            <tr>
                                                                <td class="text-center align-middle">{{$no++}}</td>
                                                                <td class="text-center align-middle">{{$d->kode_mata_kuliah}}</td>
                                                                <td>{{$d->nama_mata_kuliah}}</td>
                                                                <td class="text-center align-middle">{{$d->sks_mata_kuliah}}</td>
                                                                <td class="text-center align-middle">{{$d->smt_diambil}}</td>
                                                                <td class="text-center align-middle">{{$d->nilai_angka}}</td>
                                                                <td class="text-center align-middle">{{$d->nilai_huruf}}</td>
                                                                <td class="text-center align-middle">{{$d->nilai_indeks}}</td>
                                                            </tr>
                                                        @endforeach       
                                                    </tbody>
                                                    <!-- <tfoot>
                                                        <tr>
                                                            <td class="text-center align-middle" colspan="3"><strong>Total SKS Diambil</strong></td>
                                                            <td class="text-center align-middle">
                                                                <strong>
                                                                    @php
                                                                        $total_sks=0;
                                                                        foreach($transkrip as $t){
                                                                            $total_sks += $t->sks_mata_kuliah;
                                                                        }
                                                                    @endphp
                                                                    
                                                                    {{$total_sks}}
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                    </tfoot> -->
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
