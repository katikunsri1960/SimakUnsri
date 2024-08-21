@extends('layouts.mahasiswa')
@section('title')
Pengajuan Aktivitas MBKM
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Tambah Aktivitas MBKM Pertukaran Pelajar</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('mahasiswa.perkuliahan.mbkm.view')}}">Aktivitas MBKM</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Aktivitas MBKM</li>
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
                <form class="form" action="{{route('mahasiswa.perkuliahan.mbkm.store-pertukaran')}}" id="tambah-aktivitas-magang" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Data Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mahasiswa"
                                    id="nama_mahasiswa"
                                    aria-describedby="helpId"
                                    value="{{$data->nama_mahasiswa}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nim" class="form-label">NIM Mahasiswa</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nim"
                                    id="nim"
                                    aria-describedby="helpId"
                                    value="{{$data->nim}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="prodi" class="form-label">Program Studi</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="prodi"
                                    id="prodi"
                                    aria-describedby="helpId"
                                    value="{{$data->nama_program_studi}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <h4 class="text-info mb-0 mt-40"><i class="fa fa-user"></i> Aktivitas MBKM Pertukaran Pelajar</h4>
                        <hr class="my-15">
                        <div class="form-group mb-20">
                            <div id="aktivitas-fields">
                                <div class="aktivitas-field row">
                                    <div class="col-md-12 mb-10">
                                        <label>Jenis Aktivitas MBKM</label>
                                        <select id="aktivitas_mbkm" name="aktivitas_mbkm" class="form-select" >
                                            <option value="" disabled selected>-- Pilih Aktivitas MBKM --</option>
                                            @foreach($aktivitas_mbkm as $aktivitas)
                                                <option value="{{ $aktivitas['id_jenis_aktivitas'] }}">{{ $aktivitas['nama_jenis_aktivitas'] }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="sks-fields">
                                <div class="sks-field row">
                                    <div class="col-md-12 mb-20">
                                        <label>Total SKS Aktivitas MBKM </label>
                                        <select id="sks_mbkm" name="sks_mbkm" class="form-select" >
                                            <option value="" disabled selected>-- Pilih jumlah SKS yang akan dikonversi --</option>
                                            @foreach($sks_aktivitas_mbkm as $sks_aktivitas)
                                                <option value="{{ $sks_aktivitas}}">{{ $sks_aktivitas  }} SKS </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="judul-fields">
                                <div class="judul-field row">
                                    <div class="col-md-12 mb-10">
                                        <label>Judul</label>
                                        <textarea id="judul" class="form-control" name="judul" placeholder="Masukkan Judul Aktivitas" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div id="lokasi-fields">
                                <div class="lokasi-field row">
                                    <div class="col-md-12 mb-10">
                                        <label for="lokasi" class="form-label">Lokasi</label>
                                        <input type="text" id="lokasi" class="form-control" name="lokasi" placeholder="Masukkan Lokasi Aktivitas" required>
                                    </div>
                                </div>
                            </div>
                            <div id="keterangan-fields">
                                <div class="keterangan-field row">
                                    <div class="col-md-12 mb-10">
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <input type="text" id="keterangan" class="form-control" name="keterangan" placeholder="Masukkan Keterangan Aktivitas">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <h4 class="text-info mt-40"><i class="fa fa-user"></i>  Dosen Pembimbing</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div id="dosen-fields">
                                <div class="dosen-field row">
                                    <label for="dosen_bimbing_aktivitas" class="form-label">Nama Dosen</label>
                                    <div class="col-md-12 mb-2">
                                        <select class="form-select" name="dosen_bimbing_aktivitas" id="dosen_bimbing_aktivitas" required></select>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('mahasiswa.perkuliahan.mbkm.pertukaran')}}" class="btn btn-danger waves-effect waves-light">
                            Batal
                        </a>
                        <button type="submit" id="submit-button" class="btn btn-primary waves-effect waves-light">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#tambah-aktivitas-magang').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Tambah Aktivitas magang',
                text: "Apakah anda yakin ingin?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            },function(isConfirmed){
                if (isConfirmed) {
                    $('#tambah-aktivitas-magang').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });

    $(document).ready(function(){
        // Initialize Select2 for the first select element
        var initialSelect = initializeSelect2($('#dosen_bimbing_aktivitas'));

        function initializeSelect2(selectElement) {
            return selectElement.select2({
                placeholder : '-- Pilih Dosen --',
                minimumInputLength: 3,
                width: 'resolve', // Auto width
                ajax: {
                    url: "{{route('mahasiswa.perkuliahan.mbkm.get-dosen')}}",
                    type: "GET",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term // search term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama_dosen + " ( " + item.nama_program_studi + " )",
                                    id: item.id_registrasi_dosen
                                }
                            })
                        };
                    },
                }
            });
        }
    });
</script>
@endpush
