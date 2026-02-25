@extends('layouts.mahasiswa')
@section('title')
Transkrip Mahasiswa
@endsection
@section('content')
@include('swal')
<section class="content bg-white text-uppercase">
    <div class="row mt-10">
        <div class="col-lg-12 col-xl-12 mt-0">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border d-flex justify-content-between mx-20">
                    <div class="d-flex justify-content-start">
                        <h4 class="text-primary mb-0"><i class="fa-solid fa-scroll"></i>
                            Transkrip Mahasiswa
                        </h4>
                    </div>                  
                </div>
                <div class="box box-body mb-0">
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
                                                    <th class="text-center align-middle text-nowrap">K x B</th>
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
                                                        <tr class="{{ $d->nilai_huruf == 'E' ? 'table-danger' : '' }}">
                                                            <td class="text-center align-middle">{{$no++}}</td>
                                                            <td class="text-center align-middle">{{$d->kode_mata_kuliah}}</td>
                                                            <td class="text-start align-middle">{{$d->nama_mata_kuliah}}</td>
                                                            <td class="text-center align-middle">{{ (int) $d->sks_mata_kuliah }}</td>
                                                            <td class="text-center align-middle">{{empty($d->nilai_angka) ? '-' : $d->nilai_angka}}</td>
                                                            <td class="text-center align-middle">{{empty($d->nilai_huruf) ? '-' : $d->nilai_huruf}}</td>
                                                            <td class="text-center align-middle">{{$d->nilai_indeks===NULL ? '-' : $d->nilai_indeks}}</td>
                                                            <td class="text-center align-middle">
                                                                {{ !empty($d->nilai_indeks) ? $d->sks_mata_kuliah * $d->nilai_indeks : '-' }}
                                                            </td>
                                                            <!-- <td class="text-center align-middle">
                                                                <a type="button" href="{{route('mahasiswa.perkuliahan.nilai-perkuliahan.histori-nilai', ['id_matkul' => $d->id_matkul])}}" class="btn btn-success waves-effect waves-light" title="Lihat Histori">
                                                                <i class="fa-solid fa-eye"></i>
                                                                </a>
                                                            </td> -->
                                                        </tr>
                                                    @endforeach 
                                                @endif 
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td class="text-start align-middle" colspan="3"><strong>JUMLAH</strong></td>
                                                    <td class="text-center align-middle"><strong>{{ $total_sks_transkrip }}</strong></td>

                                                    <td colspan="3"></td>
                                                    <td class="text-center align-middle"><strong>{{ $bobot }}</strong></td>
                                                    <td class="text-center align-middle"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start align-middle" colspan="3"><strong>INDEKS PRESTASI KUMULATIF</strong></td>
                                                    <td class="text-start align-middle" colspan="5">{{ $bobot }} / {{ $total_sks_transkrip }} = <strong>{{ $ipk }}</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>	
                    @endif
                    <div class="box-footer">
                        <div class="row mb-3">
                            <div class="col-12 text-start">
                                <a href="{{ route('mahasiswa.wisuda.index') }}" 
                                class="btn btn-warning btn-sm">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var statusSync = @json($statusSync);
        var idBatch = @json($id_batch); // Assuming you have $idBatch available in your Blade template

        if (statusSync == 1) {
            checkSync(idBatch);
        }
    });

    function checkSync(id_batch) {
        $.ajax({
            url: '{{ route('mahasiswa.check-sync') }}',
            type: 'GET',
            data: {
                id_batch: id_batch
            },
            success: function(response) {
                console.log('Sync response:', response);
                // Handle the response here


                // Update the progress bar
                var progressBar = document.getElementById('sync-progress-bar');
                progressBar.style.width = response.progress + '%';
                progressBar.setAttribute('aria-valuenow', response.progress);
                // set text percentage
                progressBar.innerHTML = response.progress + '%';

                if (response.progress < 100) {
                    setTimeout(function() {
                        checkSync(id_batch);
                    }, 3000); // Request every 2 seconds
                } else {
                
                    // reload page
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error checking sync:', error);
            }
        });
    }
</script>
@endpush