@extends('layouts.prodi')
@section('title')
Tambah Kelas Perkuliahan
@endsection
@section('content')
@include('swal')
@php
$id_matkul = $mata_kuliah[0]['id_matkul'];
@endphp
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Tambah Kelas Perkuliahan</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Akademik</li>
                        <li class="breadcrumb-item" aria-current="page"><a
                                href="{{route('prodi.data-akademik.kelas-penjadwalan')}}">Kelas dan Penjadwalan</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a
                                href="{{route('prodi.data-akademik.kelas-penjadwalan.detail', ['id_matkul' => $id_matkul])}}">Detail
                                Kelas dan Penjadwalan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Kelas Perkuliahan</li>
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
                <form class="form"
                    action="{{route('prodi.data-akademik.kelas-penjadwalan.store', ['id_matkul' => $id_matkul])}}"
                    id="tambah-kelas" method="POST">
                    @csrf
                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Detail Kelas Kuliah</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nama_mata_kuliah" class="form-label">Nama Mata Kuliah</label>
                                <input type="text" class="form-control" name="nama_mata_kuliah" id="nama_mata_kuliah"
                                    aria-describedby="helpId"
                                    value="{{$mata_kuliah[0]['kode_mata_kuliah'].' - '.$mata_kuliah[0]['nama_mata_kuliah']}}"
                                    disabled required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Mulai Efektif</label>
                                    <input type="text" class="form-control" name="tanggal_mulai" id="tanggal_mulai"
                                        aria-describedby="helpId" placeholder="" required
                                        value="{{old('tanggal_mulai')}}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Akhir Efektif</label>
                                    <input type="text" class="form-control" name="tanggal_akhir" id="tanggal_akhir"
                                        aria-describedby="helpId" placeholder="" required
                                        value="{{old('tanggal_akhir')}}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kapasitas_kelas" class="form-label">Kapasitas Kelas Kuliah</label>
                                    <input type="number" class="form-control" name="kapasitas_kelas"
                                        id="kapasitas_kelas" aria-describedby="helpId"
                                        placeholder="Kapasitas Kelas" required value="{{old('kapasitas_kelas')}}"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="mb-3">
                                <label for="ruang_kelas" class="form-label">Ruang Kelas Kuliah</label>
                                <select class="form-select" name="ruang_kelas" id="ruang_kelas" required>
                                    <option value="">-- Pilih Ruang Kelas --</option>
                                    @foreach($ruang as $r)
                                    <option value="{{$r->id}}" @if (old('ruang_kelas') == $r->id) selected @endif>{{$r->nama_ruang}} - {{$r->lokasi}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="mode_kelas" class="form-label">Mode Kelas Kuliah</label>
                                <select class="form-select" name="mode_kelas" id="mode_kelas" required>
                                    <option value="">-- Pilih Mode Kelas --</option>
                                    <option value="O" @if(old('mode_kelas') == 'O') selected @endif>Online</option>
                                    <option value="F" @if(old('mode_kelas') == 'F') selected @endif>Offline</option>
                                    <option value="M" @if(old('mode_kelas') == 'M') selected @endif>Campuran</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="lingkup_kelas" class="form-label">Lingkup Kelas Kuliah</label>
                                <select class="form-select" name="lingkup_kelas" id="lingkup_kelas" required>
                                    <option value="">-- Pilih Lingkup Kelas --</option>
                                    <option value="1" @if(old('lingkup_kelas') == '1') selected @endif>Internal</option>
                                    <option value="2" @if(old('lingkup_kelas') == '2') selected @endif>External</option>
                                    <option value="3" @if(old('lingkup_kelas') == '3') selected @endif>Campuran</option>
                                </select>
                            </div>
                        </div>
                        <h4 class="text-info mb-0"><i class="fa fa-calendar-o"></i> Jadwal Kelas Kuliah</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="jadwal_hari" class="form-label">Jadwal Hari</label>
                                <select class="form-select" name="jadwal_hari" id="jadwal_hari" required>
                                    <option value="">-- Pilih Hari --</option>
                                    <option value="SENIN">SENIN</option>
                                    <option value="SELASA">SELASA</option>
                                    <option value="RABU">RABU</option>
                                    <option value="KAMIS">KAMIS</option>
                                    <option value="JUMAT">JUMAT</option>
                                    <option value="SABTU">SABTU</option>
                                    <option value="MINGGU">MINGGU</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Jam Mulai Kelas</label>
                                    <div class="row">
                                        <div class="col-sm-4 mb-2">
                                            <select class="form-select" name="jam_mulai" id="jam_mulai" required>
                                                <option value="">Jam</option>
                                                @for($i=0;$i < 24;$i++) @php $based_num=0; $num=$based_num.$i; @endphp
                                                    <option value="{{$i < 10 ? $num : $i}}">{{$i < 10 ? $num :
                                                        $i}}</option>
                                                        @endfor
                                            </select>
                                        </div>
                                        <div class="col-sm-4 mb-2">
                                            <select class="form-select" name="menit_mulai" id="menit_mulai" required>
                                                <option value="">Menit</option>
                                                @for($i=0;$i < 60;$i++) @php $based_num=0; $num=$based_num.$i; @endphp
                                                    <option value="{{$i < 10 ? $num : $i}}">{{$i < 10 ? $num :
                                                        $i}}</option>
                                                        @endfor
                                            </select>
                                        </div>
                                        <div class="col-sm-4 mb-2">
                                            <input type="text" class="form-control" name="detik_mulai" id="detik_mulai"
                                                aria-describedby="helpId" placeholder="Detik" value="{{'00'}}"
                                                disabled />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Jam Selesai Kelas</label>
                                    <div class="row">
                                        <div class="col-sm-4 mb-2">
                                            <select class="form-select" name="jam_selesai" id="jam_selesai" required>
                                                <option value="">Jam</option>
                                                @for($i=0;$i < 24;$i++) @php $based_num=0; $num=$based_num.$i; @endphp
                                                    <option value="{{$i < 10 ? $num : $i}}">{{$i < 10 ? $num :
                                                        $i}}</option>
                                                        @endfor
                                            </select>
                                        </div>
                                        <div class="col-sm-4 mb-2">
                                            <select class="form-select" name="menit_selesai" id="menit_selesai"
                                                required>
                                                <option value="">Menit</option>
                                                @for($i=0;$i < 60;$i++) @php $based_num=0; $num=$based_num.$i; @endphp
                                                    <option value="{{$i < 10 ? $num : $i}}">{{$i < 10 ? $num :
                                                        $i}}</option>
                                                        @endfor
                                            </select>
                                        </div>
                                        <div class="col-sm-4 mb-2">
                                            <input type="text" class="form-control" name="detik_selesai"
                                                id="detik_selesai" aria-describedby="helpId" placeholder="Detik"
                                                value="{{'00'}}" disabled required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('prodi.data-akademik.kelas-penjadwalan')}}"
                            class="btn btn-danger waves-effect waves-light">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('assets/js/flatpickr/flatpickr.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/flatpickr/flatpickr.js')}}"></script>
<script>
    flatpickr("#tanggal_mulai", {
            dateFormat: "d-m-Y",
        });

    flatpickr("#tanggal_akhir", {
        dateFormat: "d-m-Y",
    });
    $('#tambah-kelas').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Pembuatan Kelas Kuliah',
            text: "Apakah anda yakin ingin menambahkan kelas?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#tambah-kelas').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });

</script>
@endpush
