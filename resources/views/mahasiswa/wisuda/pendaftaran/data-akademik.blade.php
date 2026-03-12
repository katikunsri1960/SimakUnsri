@extends('layouts.mahasiswa')
@section('title')
Pendaftaran Yudisium Mahasiswa
@endsection
@section('content')
{{-- @push('header')
<div class="mx-4">
    <a href="{{route('mahasiswa.wisuda.index')}}"
        class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
</div>
@endpush --}}
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Data Akademik Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Yudisium</li>
                        <li class="breadcrumb-item active" aria-current="page">Pendaftaran</li>
                        <li class="breadcrumb-item active" aria-current="page">Data Akademik Mahasiswa</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <form class="form" action="{{route('mahasiswa.wisuda.pendaftaran.data-akademik-store')}}" id="data-akademik" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- <div class="box-header with-border">
                        <h4 class="box-title text-primary"><i class="fa fa-university"></i> Data Akademik Mahasiswa</h4>
                    </div> -->
                    <div class="box-body">
                        {{-- DATA AKADEMIK --}}
                        <h4 class="text-primary mb-0"><i class="fa fa-university"></i> Data Akademik Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="data-wisuda-fields">
                                <div class="data-wisuda-field row">
                                    <div class=" col-lg-12 mb-3">
                                        <label for="nim" class="form-label">NIM Mahasiswa</label>
                                        <input type="text"class="form-control"name="nim"id="nim"aria-describedby="helpId"
                                            value="{{$riwayat_pendidikan->nim}}" disabled required/>
                                    </div>
                                    <div class=" col-lg-2 mb-3">
                                        <label for="jenjang_pendidikan" class="form-label">Program Pendidikan</label>
                                        <input type="text" class="form-control" name="jenjang_pendidikan" id="jenjang_pendidikan" aria-describedby="helpId"
                                            value="{{$riwayat_pendidikan->prodi->nama_jenjang_pendidikan}}" disabled required/>
                                    </div>
                                    <div class=" col-lg-6 mb-3">
                                        <label for="prodi" class="form-label">Program Studi</label>
                                        <input type="text"class="form-control"name="prodi"id="prodi"aria-describedby="helpId"
                                            value="{{$riwayat_pendidikan->prodi->nama_program_studi}}" disabled required/>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label for="lokasi_kuliah" class="form-label">
                                            Lokasi Kuliah <span class="text-danger">*</span>
                                        </label>

                                        <select id="lokasi_kuliah"
                                                name="lokasi_kuliah"
                                                class="form-select"
                                                @disabled($wisuda && $wisuda->verified_akademik == 1)
                                                required>

                                            <option value="">-- Pilih Lokasi Kuliah --</option>
                                            <option value="INDERALAYA"
                                                @selected(old('lokasi_kuliah', $wisuda->lokasi_kuliah ?? '') == 'INDERALAYA')>
                                                INDERALAYA
                                            </option>

                                            <option value="PALEMBANG"
                                                @selected(old('lokasi_kuliah', $wisuda->lokasi_kuliah ?? '') == 'PALEMBANG')>
                                                PALEMBANG
                                            </option>

                                        </select>
                                    </div>
                                </div>
                                <div class="data-wisuda-field row">
                                    <div class=" col-lg-6 mb-3">
                                        <label for="jurusan" class="form-label">Jurusan</label>
                                        <input type="text"class="form-control"name="jurusan"id="jurusan"aria-describedby="helpId"
                                            value="{{$riwayat_pendidikan->prodi->jurusan->nama_jurusan_id}}" disabled required/>
                                    </div>
                                    <div class=" col-lg-6 mb-3">
                                        <label for="fakultas" class="form-label">Fakultas</label>
                                        <input type="text" class="form-control" name="fakultas" id="fakultas" aria-describedby="helpId"
                                            value="{{$riwayat_pendidikan->prodi->fakultas->nama_fakultas}}" disabled required/>
                                    </div>
                                </div>

                                <h4 class="text-primary mt-40">Data Status, IPK dan SKS</h4>
                                <hr class="my-15">
                                <div class="data-wisuda-field row">
                                    <div class="table-responsive
                                    {{-- d-flex justify-content-between --}}
                                    ">
                                        <table class="table table-striped">
                                            <tr>
                                                <td class="text-left text-nowrap" style="width:80px">
                                                    <strong>Status Mahasiswa</strong>
                                                    <!-- <br><small class="text-primary">(SYARAT 5)</small> -->
                                                </td>
                                                <td class="text-center">:</td>
                                                @php
                                                    $statusKeluar = $riwayat_pendidikan->lulus_do?->nama_jenis_keluar 
                                                        ?? $riwayat_pendidikan->keterangan_keluar;

                                                    $idJenisKeluar = $riwayat_pendidikan->lulus_do?->id_jenis_keluar 
                                                        ?? $riwayat_pendidikan->id_jenis_keluar;
                                                @endphp

                                                <td class="text-left" style="text-align: justify">
                                                    <strong>
                                                        {{ $statusKeluar ?? 'Aktif' }}
                                                    </strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-left text-nowrap">
                                                    <strong>IPK</strong>
                                                </td>
                                                <td class="text-center">:</td>
                                                <td class="text-left" style="text-align: justify">
                                                    <strong>{{$ipk}}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-left text-nowrap">
                                                    <strong>Total SKS</strong>
                                                    <!-- <br><small class="text-primary">(SYARAT 2)</small> -->
                                                </td>
                                                <td class="text-center">:</td>
                                                <td class="text-left" style="text-align: justify">
                                                    <strong>{{$total_sks_transkrip}} SKS</strong><br>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                                
                        <h4 class="text-primary mb-0" style="padding-top: 40px;"><i class="fa-solid fa-file"></i> Data Aktivitas Kuliah Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="data-wisuda-fields">
                                <div class="data-wisuda-field row">
                                    <div class="col-lg-12 mb-3">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover text-center align-middle">
                                                <thead class="table-success">
                                                    <tr>
                                                        <th style="width:60px">No</th>
                                                        <th>Nama Semester</th>
                                                        <th style="width:120px">Status</th>
                                                        <th style="width:120px">SKS Semester</th>
                                                        <th style="width:120px">SKS Total</th>
                                                        <th style="width:100px">IPS</th>
                                                        <th style="width:100px">IPK</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $no = 1; @endphp

                                                    @forelse($aktivitas_kuliah as $akm)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td class="text-start">{{ $akm->nama_semester }}</td>
                                                        <td class="text-center">{{ $akm->nama_status_mahasiswa}}</td>
                                                        <td>{{ $akm->sks_semester }}</td>
                                                        <td>{{ $akm->sks_total }}</td>
                                                        <td>{{ number_format($akm->ips,2) }}</td>
                                                        <td>{{ number_format($akm->ipk,2) }}</td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted">
                                                            Data aktivitas kuliah tidak tersedia
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4 class="text-primary mb-0" style="padding-top: 40px;"><i class="fa fa-graduation-cap"></i> Data Transkrip Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="data-wisuda-fields">
                                <div class="data-wisuda-field row">
                                    <div class="col-lg-12 mb-3">
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
                                                                        <!-- <th class="text-center align-middle">Nilai Angka</th> -->
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
                                                                                <!-- <td class="text-center align-middle">{{empty($d->nilai_angka) ? '-' : $d->nilai_angka}}</td> -->
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

                                                                        <td colspan="2"></td>
                                                                        <td class="text-center align-middle"><strong>{{ $bobot }}</strong></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start align-middle" colspan="2"><strong>INDEKS PRESTASI KUMULATIF</strong></td>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        @if($wisuda && $wisuda->verified_akademik == 1)
                            <div class="checkbox p-3 border border-success rounded bg-light-success">
                                <input type="checkbox" id="pernyataan_data" name="pernyataan_data" checked disabled>
                                <label for="pernyataan_data" class="text-success fw-bold">
                                    Data Akademik telah diverifikasi. Perubahan data tidak diperbolehkan.
                                </label>
                            </div>
                            <div class="form-group mt-20">
                                <a type="button" href="{{route('mahasiswa.wisuda.pendaftaran.data-induk')}}" class="btn btn-danger waves-effect waves-light">
                                    Kembali
                                </a>
                                <a type="button" href="{{route('mahasiswa.wisuda.pendaftaran.data-tugas-akhir')}}" class="btn btn-primary waves-effect waves-light">
                                    Lanjutkan
                                </a>
                            </div>
                        @else
                            <div class="checkbox">
                                <input type="checkbox" id="pernyataan_data" name="pernyataan_data">
                                <label for="pernyataan_data">
                                    Dengan ini saya menyatakan bahwa Data Akademik saya telah sesuai, dan saya bersedia menggunakan data tersebut untuk keperluan Administrasi Yudisium, Wisuda, Pencetakan Ijazah, dan Transkrip.
                                </label>
                            </div>
                            <div class="form-group mt-20">
                                <a type="button" href="{{route('mahasiswa.wisuda.pendaftaran.data-induk')}}" class="btn btn-danger waves-effect waves-light">
                                    Kembali
                                </a>
                                <button type="submit" id="submit-button" class="btn btn-primary waves-effect waves-light">Simpan</button>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script>
$(document).ready(function () {

    // VALIDASI SUBMIT
    $('#data-akademik').on('submit', function (e) {
        e.preventDefault();

        let pernyataan = $('#pernyataan_data').is(':checked');

        // VALIDASI CHECKBOX PERNYATAAN
        if (!pernyataan) {
            swal({
                title: 'Pernyataan Belum Dicentang',
                text: 'Silakan centang pernyataan bahwa data akademik sudah benar sebelum menyimpan.',
                type: 'warning',
                confirmButtonText: 'OK'
            });

            $('#pernyataan_data').focus();

            return false;
        }

        // KONFIRMASI SIMPAN
        swal({
            title: 'Persertujuan',
            text: 'Dengan ini saya menyatakan bahwa Data Induk saya telah sesuai dengan ijazah terakhir yang saya miliki, dan saya bersedia menggunakan data tersebut untuk keperluan Administrasi Yudisium, Wisuda, Pencetakan Ijazah, dan Transkrip',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Setujui',
            cancelButtonText: 'Batal'
        }, function (isConfirmed) {
            if (isConfirmed) {
                $('#data-akademik').off('submit').submit();
                $('#spinner').show();
            }
        });
    });

    // Hilangkan border merah saat dipilih
    $('#id_wilayah').on('change', function () {
        $('#id_wilayah').next('.select2-container')
            .find('.select2-selection')
            .css('border', '');
    });

});
</script>

@endpush
