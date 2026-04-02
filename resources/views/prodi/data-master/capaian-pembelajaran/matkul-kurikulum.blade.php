<div class="row">
    <div class="col-12">
        <div class="box box-outline-success bs-3 border-success">
            {{-- <div class="box-header with-border">
                <div class="d-flex justify-content-start">
                    <h4>Kurikulum Kuliah</h4>
                </div>
            </div> --}}
            <div class="box-body">
                <div class="table-responsive">
                    <table id="data" class="table table-hover margin-top-10 w-p100" style="font-size:12px">
                        <thead>
                            <tr>
                                <th class="text-center align-middle" rowspan="2">Status</th>
                                <th class="text-center align-middle" rowspan="2">No</th>
                                <th class="text-center align-middle" rowspan="2">Kode Matakuliah</th>
                                <th class="text-center align-middle" rowspan="2">Nama Matakuliah</th>
                                <th class="text-center align-middle" colspan="5">Aturan Jumlah sks</th>
                                <th class="text-center align-middle" rowspan="2">Semester</th>
                                <th class="text-center align-middle" rowspan="2">Wajib?</th>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">Mata Kuliah</th>
                                <th class="text-center align-middle">Tatap Muka</th>
                                <th class="text-center align-middle">Praktikum</th>
                                <th class="text-center align-middle">Prakt Lapangan</th>
                                <th class="text-center align-middle">Simulasi</th>
                            </tr>
                        </thead>
                        @php
                            $sks_mk = 0;
                            $sks_tm = 0;
                            $sks_praktikum = 0;
                            $sks_pl = 0;
                            $sks_simulasi = 0;
                        @endphp
                        <tbody>
                            @foreach ($data->matkul_kurikulum as $d)
                            <tr>
                                <td class="text-center align-middle">
                                    <span class="badge badge-success">{{$d->status_sync}}</span>
                                </td>
                                <td class="text-center align-middle"></td>
                                <td class="text-center align-middle">
                                  {{$d->kode_mata_kuliah}}
                                </td>
                                <td class="text-start align-middle">{{$d->nama_mata_kuliah}}</td>
                                <td class="text-center align-middle">{{$d->sks_mata_kuliah}}</td>
                                <td class="text-center align-middle">{{$d->sks_tatap_muka}}</td>
                                <td class="text-center align-middle">{{$d->sks_praktek}}</td>
                                <td class="text-center align-middle">{{$d->sks_praktek_lapangan}}</td>
                                <td class="text-center align-middle">{{$d->sks_simulasi}}</td>
                                <td class="text-center align-middle">{{$d->semester}}</td>
                                <td class="text-center align-middle">
                                    @if ($d->apakah_wajib == 1)
                                    <i class="fa fa-check"></i>
                                    @endif
                                </td>
                            </tr>
                            @php
                                $sks_mk += $d->sks_mata_kuliah;
                                $sks_tm += $d->sks_tatap_muka;
                                $sks_praktikum += $d->sks_praktek;
                                $sks_pl += $d->sks_praktek_lapangan;
                                $sks_simulasi += $d->sks_simulasi;
                            @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-center align-middle" colspan="4">Total</th>
                                <th class="text-center align-middle">{{$sks_mk}}</th>
                                <th class="text-center align-middle">{{$sks_tm}}</th>
                                <th class="text-center align-middle">{{$sks_praktikum}}</th>
                                <th class="text-center align-middle">{{$sks_pl}}</th>
                                <th class="text-center align-middle">{{$sks_simulasi}}</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
