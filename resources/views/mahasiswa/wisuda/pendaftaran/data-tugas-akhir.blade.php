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
            <h3 class="page-title">Data Tugas Akhir Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Yudisium</li>
                        <li class="breadcrumb-item active" aria-current="page">Pendaftaran</li>
                        <li class="breadcrumb-item active" aria-current="page">Data Tugas Akhir Mahasiswa</li>
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
                <form class="form" action="{{route('mahasiswa.wisuda.pendaftaran.data-tugas-akhir-store')}}" id="data-ta" method="POST" enctype="multipart/form-data">
                    @csrf
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
                    <div class="box-footer">
                        @if($wisuda && $wisuda->verified_ta == 1)
                            <div class="checkbox p-3 border border-success rounded bg-light-success">
                                <input type="checkbox" id="pernyataan_data" name="pernyataan_data" checked disabled>
                                <label for="pernyataan_data" class="text-success fw-bold">
                                    Data {{$aktivitas->nama_jenis_aktivitas}} telah diverifikasi. Perubahan data tidak diperbolehkan.
                                </label>
                            </div>
                            <div class="form-group mt-20">
                                <a type="button" href="{{route('mahasiswa.wisuda.pendaftaran.data-akademik')}}" class="btn btn-danger waves-effect waves-light">
                                    Kembali
                                </a>
                                <a type="button" href="{{route('mahasiswa.wisuda.pendaftaran.data-wisuda')}}" class="btn btn-primary waves-effect waves-light">
                                    Lanjutkan
                                </a>
                            </div>
                        @else
                            <div class="checkbox">
                                <input type="checkbox" id="pernyataan_data" name="pernyataan_data">
                                <label for="pernyataan_data">
                                    Dengan ini saya menyatakan bahwa Data {{$aktivitas->nama_jenis_aktivitas}} saya telah sesuai, dan saya bersedia menggunakan data tersebut untuk keperluan Administrasi Yudisium, Wisuda, Pencetakan Ijazah dan Transkrip.
                                </label>
                            </div>
                            <div class="form-group mt-20">
                                <a type="button" href="{{route('mahasiswa.wisuda.pendaftaran.data-akademik')}}" class="btn btn-danger waves-effect waves-light">
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
    $('#data-ta').on('submit', function (e) {
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
            text: 'Dengan ini saya menyatakan bahwa Data {{$aktivitas->nama_jenis_aktivitas}} saya telah sesuai dengan ijazah terakhir yang saya miliki, dan saya bersedia menggunakan data tersebut untuk keperluan Administrasi Yudisium, Wisuda, Pencetakan Ijazah, dan Transkrip',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Setujui',
            cancelButtonText: 'Batal'
        }, function (isConfirmed) {
            if (isConfirmed) {
                $('#data-ta').off('submit').submit();
                $('#spinner').show();
            }
        });
    });
});
</script>

@endpush
