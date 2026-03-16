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
            <h3 class="page-title">Resume Data Pendaftaran Yudisium</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Yudisium</li>
                        <li class="breadcrumb-item" aria-current="page">Pendaftaran</li>
                        <li class="breadcrumb-item active" aria-current="page">Resume Data Pendaftaran Yudisium</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="content">
    {{-- DATA INDUK MAHASISWA --}}
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-body">
                    {{-- MAHASISWA --}}
                    <h6 class="text-primary mb-0"><i class="fa fa-user"></i> Data Diri Mahasiswa</h6>
                    <hr class="my-15">
                    <div class="data-wisuda-field row">
                        <div class=" col-lg-6 mb-3">
                            <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
                            <input type="text" class="form-control" name="nama_mahasiswa" id="nama_mahasiswa" aria-describedby="helpId"
                                value="{{$riwayat_pendidikan->nama_mahasiswa}}" disabled required />
                        </div>
                        <div class=" col-lg-6 mb-3">
                            <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nik" id="nik" aria-describedby="helpId"
                                value="{{$riwayat_pendidikan->biodata->nik}}" disabled required />
                        </div>
                    </div>

                    <h6 class="text-primary mb-10 mt-10">Alamat Mahasiswa</h6>
                    <div class="data-wisuda-field row" style="margin-left: 10px;">
                        <div class=" col-lg-12 mb-3">
                            <label for="jalan" class="form-label">Jalan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="jalan" id="jalan" aria-describedby="helpId"
                                value="{{$riwayat_pendidikan->biodata->jalan}}" disabled required />
                        </div>
                        <div class=" col-lg-4 mb-3">
                            <label for="dusun" class="form-label">Dusun</label>
                            <input type="text" class="form-control" name="dusun" id="dusun" aria-describedby="helpId"
                                value="{{$riwayat_pendidikan->biodata->dusun}}" disabled />
                        </div>
                        <div class=" col-lg-2 mb-3">
                            <label for="rt" class="form-label">RT <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="rt" id="rt" aria-describedby="helpId"
                                value="{{$riwayat_pendidikan->biodata->rt}}" disabled required />
                        </div>
                        <div class=" col-lg-2 mb-3">
                            <label for="rw" class="form-label">RW <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="rw" id="rw" aria-describedby="helpId"
                                value="{{$riwayat_pendidikan->biodata->rw}}" disabled required />
                        </div>
                        <div class=" col-lg-4 mb-3">
                            <label for="kelurahan" class="form-label">Kelurahan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="kelurahan" id="kelurahan" aria-describedby="helpId"
                                value="{{$riwayat_pendidikan->biodata->kelurahan}}" disabled required />
                        </div>
                        <div class=" col-lg-4 mb-3">
                            <label for="kode_pos" class="form-label">Kode Pos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="kode_pos" id="kode_pos" aria-describedby="helpId"
                                value="{{$riwayat_pendidikan->biodata->kode_pos}}" disabled />
                        </div>
                        <div class="col-lg-8 mb-3">
                            <label for="id_wilayah" class="form-label">
                                Wilayah <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="id_wilayah" id="id_wilayah" aria-describedby="helpId"
                                value="{{$riwayat_pendidikan->biodata->wilayah->nama_wilayah}}" disabled />
                        </div>

                        <div class=" col-lg-6 mb-3">
                            <label for="handphone" class="form-label">No. Telp/HP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="handphone" id="handphone" aria-describedby="helpId"
                                value="{{$riwayat_pendidikan->biodata->handphone}}" disabled required />
                        </div>
                        <div class=" col-lg-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="email" id="email" aria-describedby="helpId"
                                value="{{$riwayat_pendidikan->biodata->email}}" disabled required />
                        </div>
                    </div>

                    {{-- ORANG TUA --}}
                    <h6 class="text-primary mb-0" style="padding-top: 40px;">
                        <i class="fa-solid fa-users"></i> Data Diri Orang Tua
                    </h6>
                    <hr class="my-15">
                    <div class="data-wisuda-field row">
                        <div class=" col-lg-6 mb-3">
                            <label for="nama_ayah" class="form-label">
                                Nama Ayah <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="nama_ayah" id="nama_ayah" aria-describedby="helpId"
                                value="{{ strtoupper($riwayat_pendidikan->biodata->nama_ayah) }}" disabled required />
                        </div>

                        <div class=" col-lg-6 mb-3">
                            <label for="no_hp_ayah" class="form-label">
                                Nomor Handphone Ayah <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="no_hp_ayah" id="no_hp_ayah" aria-describedby="helpId"
                                value="{{ $riwayat_pendidikan->biodata->no_hp_ayah }}" disabled required />
                        </div>

                        <div class=" col-lg-6 mb-3">
                            <label for="nama_ibu" class="form-label">
                                Nama Ibu Kandung <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="nama_ibu" id="nama_ibu" aria-describedby="helpId"
                                value="{{ $riwayat_pendidikan->biodata->nama_ibu_kandung }}" disabled required />
                        </div>

                        <div class=" col-lg-6 mb-3">
                            <label for="no_hp_ibu" class="form-label">
                                Nomor Handphone Ibu <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="no_hp_ibu" id="no_hp_ibu" aria-describedby="helpId"
                                value="{{ $riwayat_pendidikan->biodata->no_hp_ibu }}" disabled required />
                        </div>

                        <div class=" col-lg-12 mb-3">
                            <label for="alamat_orang_tua" class="form-label">
                                ALAMAT ORANG TUA <span class="text-danger">*</span>
                            </label>
                            <textarea placeholder="MASUKKAN ALAMAT ORANG TUA" class="form-control" name="alamat_orang_tua" id="alamat_orang_tua" disabled aria-describedby="helpId" required>{{ strtoupper($riwayat_pendidikan->biodata->alamat_orang_tua ?? '') }}</textarea>
                        </div>
                    </div>


                    <h6 class="text-primary mb-0" style="padding-top: 40px;"><i class="fa-solid fa-school"></i> Data Asal Sekolah Mahasiswa</h6>
                    <hr class="my-15">

                    <div class="form-group">
                        @if ($asal_sekolah)
                        <div id="data-sekolah-fields">
                            <h6 class="text-primary mb-0">Sekolah Dasar</h6>
                            <div class="data-sekolah-field row" style="margin-left: 10px;">
                                <div class=" col-lg-4 mb-3">
                                    <label for="nama_sd" class="form-label">Nama Sekolah</label>
                                    <input type="text" class="form-control" name="nama_sd" id="nama_sd" aria-describedby="helpId"
                                        value="{{$asal_sekolah->rm_pddk_sd_nama ?? '-'}}" disabled required />
                                </div>
                                <div class=" col-lg-6 mb-3">
                                    <label for="lokasi_sd" class="form-label">Alamat Sekolah</label>
                                    <input type="text" class="form-control" name="lokasi_sd" id="lokasi_sd" aria-describedby="helpId"
                                        value="{{$asal_sekolah->rm_pddk_sd_lokasi ?? '-'}}" disabled required />
                                </div>
                                <div class="col-lg-2 mb-3">
                                    <label for="lulus_sd" class="form-label">Tahun Lulus</label>
                                    <input type="text" class="form-control" name="lulus_sd" id="lulus_sd" aria-describedby="helpId"
                                        value="{{$asal_sekolah->rm_pddk_sd_thn_lulus ?? '-'}}" disabled required />
                                </div>
                            </div>
                        </div>
                        <div id="data-sekolah-fields">
                            <h6 class="text-primary mb-0">Sekolah Menengah Pertama</h6>
                            <div class="data-sekolah-field row" style="margin-left: 10px;">
                                <div class=" col-lg-4 mb-3">
                                    <label for="nama_smp" class="form-label">Nama Sekolah</label>
                                    <input type="text" class="form-control" name="nama_smp" id="nama_smp" aria-describedby="helpId"
                                        value="{{$asal_sekolah->rm_pddk_sltp_nama ?? '-'}}" disabled required />
                                </div>
                                <div class=" col-lg-6 mb-3">
                                    <label for="lokasi_smp" class="form-label">Alamat Sekolah</label>
                                    <input type="text" class="form-control" name="lokasi_smp" id="lokasi_smp" aria-describedby="helpId"
                                        value="{{$asal_sekolah->rm_pddk_sltp_lokasi ?? '-'}}" disabled required />
                                </div>
                                <div class="col-lg-2 mb-3">
                                    <label for="lulus_smp" class="form-label">Tahun Lulus</label>
                                    <input type="text" class="form-control" name="lulus_smp" id="lulus_smp" aria-describedby="helpId"
                                        value="{{$asal_sekolah->rm_pddk_sltp_thn_lulus ?? '-'}}" disabled required />
                                </div>
                            </div>
                        </div>
                        <div id="data-sekolah-fields">
                            <h6 class="text-primary mb-0">Sekolah Menengah Atas</h6>
                            <div class="data-sekolah-field row" style="margin-left: 10px;">
                                <div class=" col-lg-4 mb-3">
                                    <label for="nama_slta" class="form-label">Nama Sekolah</label>
                                    <input type="text" class="form-control" name="nama_slta" id="nama_slta" aria-describedby="helpId"
                                        value="{{$asal_sekolah->nama_sekolah ?? '-' }}" disabled required />
                                </div>
                                <div class=" col-lg-6 mb-3">
                                    <label for="lokasi_slta" class="form-label">Alamat Sekolah</label>
                                    <input type="text" class="form-control" name="lokasi_slta" id="lokasi_slta" aria-describedby="helpId"
                                        value="{{strtoupper($asal_sekolah->nama_kabupaten ?? '-') }}, {{strtoupper($asal_sekolah->nama_provinsi ?? '-')}}" disabled required />
                                </div>
                                <div class="col-lg-2 mb-3">
                                    <label for="lulus_slta" class="form-label">Tahun Lulus</label>
                                    <input type="text" class="form-control" name="lulus_slta" id="lulus_slta" aria-describedby="helpId"
                                        value="{{$asal_sekolah->rm_pddk_slta_thn_lulus ?? '-' }}" disabled required />
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-warning" role="alert">
                            Akses data asal sekolah tidak tersedia!
                        </div>
                        @endif

                        @if($wisuda && $wisuda->verified_induk == 1)
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">File Ijazah Terakhir</label>

                            <div class="border rounded p-2">
                                <iframe
                                    src="{{ asset($wisuda->ijazah_terakhir_file) }}"
                                    width="100%"
                                    height="500px"
                                    style="border:none;">
                                </iframe>
                            </div>

                            <div class="mt-2">
                                <a href="{{ asset($wisuda->ijazah_terakhir_file) }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="fa fa-download"></i> Download File
                                </a>
                            </div>
                        </div>
                        @else
                        <div id="data-sekolah-fields">
                            <h6 class="text-primary mb-0">File Ijazah Terakhir</h6>
                            <div class="data-sekolah-field row" style="margin-left: 10px;">
                                <div class="col-lg-6 mb-3">
                                    <label for="ijazah_terakhir_file" class="form-label">Upload Ijazah Terakhir (.pdf)</label><span class="text-danger"> *</span>
                                    <input type="file"
                                        class="form-control"
                                        name="ijazah_terakhir_file"
                                        id="ijazah_terakhir_file"
                                        aria-describedby="fileHelpId"
                                        accept=".pdf"
                                        required />
                                    <small id="fileHelpId" class="form-text text-danger">
                                        Maksimal ukuran file <strong>500 KB</strong>.
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DATA AKADEMIK --}}
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-body">
                    {{-- DATA AKADEMIK --}}
                    <h4 class="text-primary mb-0"><i class="fa fa-university"></i> Data Akademik Mahasiswa</h4>
                    <hr class="my-15">
                    <div class="form-group">
                        <div id="data-wisuda-fields">
                            <div class="data-wisuda-field row">
                                <div class=" col-lg-12 mb-3">
                                    <label for="nim" class="form-label">NIM Mahasiswa</label>
                                    <input type="text" class="form-control" name="nim" id="nim" aria-describedby="helpId"
                                        value="{{$riwayat_pendidikan->nim}}" disabled required />
                                </div>
                                <div class=" col-lg-2 mb-3">
                                    <label for="jenjang_pendidikan" class="form-label">Program Pendidikan</label>
                                    <input type="text" class="form-control" name="jenjang_pendidikan" id="jenjang_pendidikan" aria-describedby="helpId"
                                        value="{{$riwayat_pendidikan->prodi->nama_jenjang_pendidikan}}" disabled required />
                                </div>
                                <div class=" col-lg-6 mb-3">
                                    <label for="prodi" class="form-label">Program Studi</label>
                                    <input type="text" class="form-control" name="prodi" id="prodi" aria-describedby="helpId"
                                        value="{{$riwayat_pendidikan->prodi->nama_program_studi}}" disabled required />
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
                                    <input type="text" class="form-control" name="jurusan" id="jurusan" aria-describedby="helpId"
                                        value="{{$riwayat_pendidikan->prodi->jurusan->nama_jurusan_id}}" disabled required />
                                </div>
                                <div class=" col-lg-6 mb-3">
                                    <label for="fakultas" class="form-label">Fakultas</label>
                                    <input type="text" class="form-control" name="fakultas" id="fakultas" aria-describedby="helpId"
                                        value="{{$riwayat_pendidikan->prodi->fakultas->nama_fakultas}}" disabled required />
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

                    <h4 class="text-primary mb-0" style="padding-top: 40px;"><i class="fa fa-graduation-cap"></i> Data Aktivitas Kuliah Mahasiswa</h4>
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
            </div>
        </div>
    </div>

    {{-- DATA TUGAS AKHIR --}}
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-body">
                    {{-- DATA AKTIVITAS TUGAS AKHIR --}}
                    <h4 class="text-primary mb-0" style="padding-top: 40px;"><i class="fa fa-book"></i> Data {{ $aktivitas->nama_jenis_aktivitas }} Mahasiswa</h4>
                    <hr class="my-15">
                    <div class="form-group">
                        <div id="data-wisuda-fields">
                            <div class="data-wisuda-field row">
                                <div class=" col-lg-12 mb-3">
                                    <label for="judul_ta" class="form-label">Judul {{$aktivitas->nama_jenis_aktivitas}} dalam Bahasa Indonesia</label><span class="text-danger"> *</span>
                                    <textarea type="text" class="form-control" name="judul_ta" id="judul_ta" aria-describedby="helpId" style="height: 80px;"
                                        disabled required>{{$aktivitas->judul}}
                                    </textarea>
                                </div>

                                @if($wisuda && $wisuda->verified_ta == 1)
                                <div class="col-lg-12 mb-3">
                                    <label for="judul_eng" class="form-label">Judul {{$aktivitas->nama_jenis_aktivitas}} dalam Bahasa Inggris</label><span class="text-danger"> *</span>
                                    <textarea type="text" class="form-control" name="judul_eng" id="judul_eng" aria-describedby="helpId"
                                        placeholder="Masukkan Judul {{$aktivitas->nama_jenis_aktivitas}} dalam Bahasa Inggris" disabled>{{$wisuda->judul_eng}}</textarea>
                                </div>
                                @else
                                <div class="col-lg-12 mb-3">
                                    <label for="judul_eng" class="form-label">Judul {{$aktivitas->nama_jenis_aktivitas}} dalam Bahasa Inggris</label><span class="text-danger"> *</span>
                                    <textarea type="text" class="form-control" name="judul_eng" id="judul_eng" aria-describedby="helpId"
                                        placeholder="Masukkan Judul {{$aktivitas->nama_jenis_aktivitas}} dalam Bahasa Inggris" required></textarea>
                                </div>
                                @endif

                                @if($aktivitas->prodi->bku_pada_ijazah == 1)
                                <div class="col-lg-12 mb-3">
                                    <label for="bku_prodi" class="form-label">BKU Program Studi</label><span class="text-danger"> *</span>
                                    <select id="bku_prodi" name="bku_prodi" class="form-select" required>
                                        <option value="">-- BKU Program Studi --</option>
                                        @foreach ($bku_prodi as $bku)
                                        <option value="{{ $bku->id }}">
                                            {{ $bku->bku_prodi_id }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <div class="col-lg-6 mb-3">
                                    <label for="tgl_sk_pembimbing" class="form-label">Tanggal SK Pembimbing</label>
                                    <input type="date" class="form-control" name="tgl_sk_pembimbing" id="tgl_sk_pembimbing" aria-describedby="helpId"
                                        value="{{ $aktivitas->tanggal_sk_tugas }}" disabled required />
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="no_sk_pembimbing" class="form-label">Nomor SK Pembimbing</label>
                                    <input type="text" class="form-control" name="no_sk_pembimbing" id="no_sk_pembimbing" aria-describedby="helpId"
                                        value="{{ $aktivitas->sk_tugas }}" disabled required />
                                </div>
                            </div>
                            <h4 class="text-primary mb-10 mt-10">Abstrak {{$aktivitas->nama_jenis_aktivitas}}</h4>
                            <div class="data-wisuda-field row">
                                @if($wisuda && $wisuda->verified_ta == 1)
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Teks Abstrak</label>
                                    <textarea class="form-control" rows="6" disabled>{{ trim($wisuda->abstrak_ta) }}</textarea>
                                </div>

                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Preview File Abstrak {{ $aktivitas->nama_jenis_aktivitas }}</label>

                                    <div class="border rounded p-2">
                                        <iframe
                                            src="{{ asset($wisuda->abstrak_file) }}"
                                            width="100%"
                                            height="500px"
                                            style="border:none;">
                                        </iframe>
                                    </div>

                                    <div class="mt-2">
                                        <a href="{{ asset($wisuda->abstrak_file) }}" target="_blank" class="btn btn-success btn-sm">
                                            <i class="fa fa-download"></i> Download File
                                        </a>
                                    </div>
                                </div>
                                @else
                                <div class=" col-lg-12 mb-3">
                                    <label for="abstrak_ta" class="form-label">Abstrak {{$aktivitas->nama_jenis_aktivitas}}</label><span class="text-danger"> *</span>
                                    <textarea type="text" class="form-control" name="abstrak_ta" id="abstrak_ta" aria-describedby="helpId"
                                        placeholder="Masukkan Abstrak {{$aktivitas->nama_jenis_aktivitas}}" required></textarea>
                                    <small id="helpId" class="form-text text-danger">
                                        Maksimal 500 kata.
                                    </small>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label for="abstrak_file" class="form-label">Bahasa Indonesia(.pdf)</label><span class="text-danger"> *</span>
                                    <input type="file" class="form-control" name="abstrak_file" id="abstrak_file"
                                        aria-describedby="fileHelpId" accept=".pdf" required />
                                    <small id="fileHelpId" class="form-text text-danger">
                                        Maksimal ukuran file <strong>1 MB</strong>.
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- DATA PEMBIMBING TUGAS AKHIR --}}
                    <h4 class="text-primary mb-0" style="padding-top: 40px;"><i class="fa fa-group"></i> Data Pembimbing {{ $aktivitas->nama_jenis_aktivitas }} Mahasiswa</h4>
                    <hr class="my-15">
                    <div class="form-group">
                        <div id="data-wisuda-fields">
                            <div class="data-wisuda-field row">
                                <div class="col-lg-12 mb-3">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover text-center align-middle">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th style="width:60px">No</th>
                                                    <th>Nama Dosen</th>
                                                    <th style="width:200px">Pembimbing Ke-</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp

                                                @foreach($aktivitas -> bimbing_mahasiswa as $pembimbing)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td class="text-start">{{ $pembimbing->nama_dosen }}</td>
                                                    <td>{{ $pembimbing->pembimbing_ke }}</td>
                                                </tr>
                                                @endforeach
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

    {{-- DATA WISUDA --}}
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-body">
                    <h4 class="text-primary mb-0" style="padding-top: 40px;"><i class="fa fa-graduation-cap"></i> Data Wisuda Mahasiswa</h4>
                    <hr class="my-15">
                    <div class="form-group">
                        <div id="data-wisuda-fields">
                            <div class="form-group">
                                @if ($wisuda && $wisuda->verified_wisuda == 1)
                                <div class="data-wisuda-field row">

                                    {{-- PERIODE WISUDA --}}
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Periode Wisuda</label>
                                        <input type="text"
                                            class="form-control"
                                            value="{{ $wisuda->wisuda_ke }}"
                                            disabled>
                                    </div>

                                    {{-- FOTO WISUDA --}}
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Foto Wisuda</label>

                                        <div class="text-center">
                                            <img src="{{ asset('/storage/'.$wisuda->pas_foto) }}"
                                                alt="Foto Wisuda"
                                                style="width:150px;height:200px;object-fit:cover;border:1px solid #ddd;padding:5px;">
                                        </div>

                                        <div class="text-center mt-2">
                                            <a href="{{ asset('/storage/'.$wisuda->pas_foto) }}"
                                                target="_blank"
                                                class="btn btn-success btn-sm">
                                                <i class="fa fa-download"></i> Download Foto
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="data-wisuda-field row">
                                    <div class=" col-lg-6 mb-3">
                                        <label for="wisuda_ke" class="form-label">Wisuda Ke-</label><span class="text-danger"> *</span>
                                        <select id="wisuda_ke" name="wisuda_ke" class="form-select" required>
                                            <option value="">-- Pilih Angkatan Wisuda --</option>
                                            @if ($wisuda_ke)
                                            <option value="{{$wisuda_ke->periode}}">{{$wisuda_ke->periode}}</option>
                                            @else
                                            <option value="0">Tidak ada periode Wisuda</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="pas_foto" class="form-label">
                                            Foto Wisuda (.jpg / .png)
                                        </label>
                                        <span class="text-danger"> *</span>

                                        <input type="file"
                                            class="form-control"
                                            name="pas_foto"
                                            id="pas_foto"
                                            accept=".jpg,.jpeg,.png"
                                            required />

                                        <div class="mt-3">
                                            <label class="form-label fw-bold">
                                                Contoh Foto Wisuda (Rasio 3:4)
                                            </label>

                                            <div class="row justify-content-center mt-2">
                                                <!-- Contoh Laki-laki -->
                                                <div class="col-6 text-center">
                                                    <img src="{{ asset('images/contoh_wisuda_laki.jpg') }}"
                                                        alt="Contoh Foto Wisuda Laki-laki"
                                                        style="width:120px;height:160px;object-fit:cover;border:1px solid #ddd;padding:4px;">
                                                    <small class="d-block mt-1 text-muted">
                                                        Laki-laki
                                                    </small>
                                                </div>

                                                <!-- Contoh Perempuan -->
                                                <div class="col-6 text-center">
                                                    <img src="{{ asset('images/contoh_wisuda_perempuan.jpg') }}"
                                                        alt="Contoh Foto Wisuda Perempuan"
                                                        style="width:120px;height:160px;object-fit:cover;border:1px solid #ddd;padding:4px;">
                                                    <small class="d-block mt-1 text-muted">
                                                        Perempuan
                                                    </small>
                                                </div>
                                            </div>

                                            <small class="form-text text-danger d-block mt-3">
                                                Gunakan rasio <strong>3:4</strong> |
                                                Disarankan minimal <strong>300 x 400 piksel</strong> |
                                                Format <strong>.jpg / .png</strong>
                                            </small>

                                            <small id="fileHelpId" class="form-text text-danger">
                                                Maksimal ukuran file <strong>500 KB</strong><br><br>
                                            </small>

                                            <small id="fileHelpId" class="form-text text-start">
                                                <strong>Catatan:</strong>
                                                <ul>
                                                    <li>
                                                        Foto Yang telah diupload tidak dapat diganti oleh mahasiswa dan akan tercetak di Ijazah Anda.
                                                    </li>
                                                    <li>
                                                        Pastikan menyesuaiakan Pas Foto dengan ketentuan di atas. posisikan pundak kepala di tengah frame foto dengan latar belakang sesuai.
                                                    </li>
                                                    <li>
                                                        Laki Laki : Latar Belakang Biru, Kemeja Putih, Dasi Hitam.<br>
                                                        Perempuan : Latar Belakang Merah, Kebaya, Selendang.
                                                    </li>
                                                </ul>
                                            </small>
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

    {{-- DATA SKPI --}}
    @if($skpi_data->isNotEmpty())
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border d-flex justify-content-between align-items-center mx-20">
                    <div>
                        <h4 class="text-primary my-2">
                            <i class="fa fa-file"></i>
                            Data SKPI Mahasiswa
                        </h4>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th class="text-center" width="20">No</th>
                                    <th class="text-center" width="200">Nama Kegiatan</th>
                                    <th class="text-center" width="200">Jenis</th>
                                    <th class="text-center" width="200">Kategori</th>
                                    <th class="text-center" width="100">File Pendukung</th>
                                    <th class="text-center" width="100">Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($skpi_data->values() as $index => $row)
                                <tr>
                                    <td class="text-center">{{ $index+1 }}</td>
                                    <td class="text-start">
                                        {{ $row->nama_kegiatan }}
                                    </td>
                                    <td class="text-start">
                                        {{ $row->nama_jenis_skpi }}
                                    </td>
                                    <td class="text-start">
                                        {{ $row->kriteria }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ asset('storage/'.$row->file_pendukung) }}"
                                            class="btn btn-sm btn-success"
                                            target="_blank">

                                            <i class="fa fa-file"></i>
                                            Lihat File
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        {{ $row->skor }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-light fw-bold">
                                    <td colspan="5" class="text-center">
                                        TOTAL SKOR
                                    </td>
                                    <td class="text-center">
                                        {{ $skpi_data->sum('skor') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <!-- <div class="box-header with-border d-flex justify-content-between align-items-center mx-20"> -->
                <form class="form" action="{{route('mahasiswa.wisuda.pendaftaran.finalisasi')}}" id="finalisasi-data" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="box-footer">
                        @if($wisuda && $wisuda->finalisasi_data == 1)
                            <div class="checkbox p-3 border border-success rounded bg-light-success">
                                <input type="checkbox" id="pernyataan_data" name="pernyataan_data" checked disabled>
                                <label for="pernyataan_data" class="text-success fw-bold">
                                    Data Pendaftaran Yudisium telah difinalisasi. Perubahan data tidak diperbolehkan.
                                </label>
                            </div>
                            <div class="form-group mt-20">
                                <a type="button" href="{{route('mahasiswa.wisuda.pendaftaran.data-skpi')}}" class="btn btn-danger waves-effect waves-light">
                                    Kembali
                                </a>
                                <a type="button" href="{{route('mahasiswa.wisuda.index')}}" class="btn btn-primary waves-effect waves-light">
                                    Selesai
                                </a>
                            </div>
                        @else
                            <div class="checkbox">
                                <input type="checkbox" id="pernyataan_data" name="pernyataan_data">
                                <label for="pernyataan_data">
                                    Dengan ini saya menyatakan bahwa <b>Data Pendaftaran Yudisium</b> saya telah sesuai, dan tidak akan melakukan perubahan, serta saya mengizinkan data tersebut digunakan untuk keperluan Administrasi Yudisium, Wisuda, Pencetakan Ijazah, Transkrip dan SKPI.
                                </label>
                            </div>
                            <div class="form-group mt-20">
                                <a type="button" href="{{route('mahasiswa.wisuda.pendaftaran.data-skpi')}}" class="btn btn-danger waves-effect waves-light">
                                    Kembali
                                </a>
                                <button type="submit" id="submit-button" class="btn btn-primary waves-effect waves-light">Finalisasi</button>
                            </div>
                        @endif
                    </div>
                </form>
                <!-- </div> -->
            </div>
        </div>
    </div>
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>
    $(document).ready(function() {

        // VALIDASI SUBMIT
        $('#finalisasi-data').on('submit', function(e) {
            e.preventDefault();

            let pernyataan = $('#pernyataan_data').is(':checked');

            // VALIDASI CHECKBOX PERNYATAAN
            if (!pernyataan) {
                swal({
                    title: 'Pernyataan Belum Dicentang',
                    text: 'Silakan centang pernyataan bahwa data induk sudah benar sebelum menyimpan.',
                    type: 'warning',
                    confirmButtonText: 'OK'
                });

                $('#pernyataan_data').focus();

                return false;
            }

            // KONFIRMASI SIMPAN
            swal({
                title: 'Persertujuan',
                text: 'Dengan ini saya menyatakan bahwa Data Pendaftaran Yudisium saya telah sesuai, dan tidak akan melakukan perubahan, serta saya mengizinkan data tersebut digunakan untuk keperluan Administrasi Yudisium, Wisuda, Pencetakan Ijazah, Transkrip dan SKPI',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Setujui',
                cancelButtonText: 'Batal'
            }, function(isConfirmed) {
                if (isConfirmed) {
                    $('#finalisasi-data').off('submit').submit();
                    $('#spinner').show();
                }
            });
        });

    });
</script>

@endpush