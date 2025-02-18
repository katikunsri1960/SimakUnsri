@extends('layouts.mahasiswa')
@section('title')
Pendaftaran Wisuda Mahasiswa
@endsection
@section('content')
@include('swal')
{{-- @include('mahasiswa.bimbingan.tugas-akhir.asistensi-tambah') --}}
<section class="content bg-white">
    
    {{-- @if ($wisuda)
        <div class="row align-items-end">
            <div class="col-md-12">
                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-gradient-secondary">
                        <div class="widget-user-image">
                            @php
                            $imagePath =
                            public_path($wisuda->pas_foto.'.jpg');
                            @endphp
                            <img class="rounded bg-success-light"
                                src="{{$wisuda->pas_foto.'.jpg'}}"
                                alt="User Avatar">
                        </div>
                        <h3 class="widget-user-username">{{$aktivitas->anggota_aktivitas_personal->nama_mahasiswa}} </h3>
                        <h4 class="widget-user-desc">NIM: {{$aktivitas->anggota_aktivitas_personal->nim}}<br
                            class="mb-1">ANGKATAN: {{$aktivitas->anggota_aktivitas_personal->mahasiswa->angkatan}}</h4>                   
                    </div>
                </div>
            </div>
        </div>
    @endif --}}

    <div class="row">
        <div class="col-xxl-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border d-flex justify-content-between mx-20">
                    <div class="d-flex justify-content-start">
                        <h3 class="text-info mb-0"><i class="fa fa-book"></i> Data Mahasiswa
                                {{-- Aktivitas {{$aktivitas->nama_jenis_aktivitas}} --}}
                        </h3>
                    </div>                  
                </div>
                <div class="box box-body mb-0">
                    <div class="row mx-20">
                        <div class="col-xl-12 col-lg-12 
                        {{-- d-flex justify-content-between --}}
                        ">
                            <div class="d-flex justify-content-start">
                                <table class="table">
                                    <tr>
                                        <td class="text-left">Nama</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">{{$riwayat_pendidikan->nama_mahasiswa}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">NIM</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">{{$riwayat_pendidikan->nim}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">Jenjang Pendidikan</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">{{$riwayat_pendidikan->prodi->nama_jenjang_pendidikan}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">Program Studi</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">{{$riwayat_pendidikan->prodi->nama_program_studi}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">Jurusan</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">{{$riwayat_pendidikan->prodi->jurusan->nama_jurusan_id}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">Fakultas</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">{{$riwayat_pendidikan->prodi->fakultas->nama_fakultas}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left text-nowrap">Nilai USEPT</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">
                                            @if($riwayat_pendidikan->id_kurikulum === NULL)
                                                <span class="badge bg-warning">Kurikulum Belum Diatur</span>
                                            @elseif(isset($usept['class']) && $usept['class'] == "danger")
                                                <span class="badge bg-danger">  
                                                    {{ isset($usept['score']) ? $usept['score'] : 0 }} ({{$usept['status'] ?? 'N/A'}})
                                                </span>
                                            @endif
                                        </td>                                                                               
                                    </tr>
                                    <tr>
                                        <td class="text-left">Link Repository</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">
                                            @if(!$bebas_pustaka)
                                                <span class="badge bg-danger">Belum Bebas Pustaka</span>
                                            @else
                                                <a class="btn btn-sm btn-info" href="{{ asset('storage') }}/". $bebas_pustaka->file_bebas_pustaka" type="button" title="Lihat Repository" target="_blank">{{$repository->link_repo}}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">Link Repository</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">
                                            @if(!$bebas_pustaka)
                                                <span class="badge bg-danger">Belum Upload Repositroy</span>
                                            @else
                                                <a class="btn btn-sm btn-info" href="{{$bebas_pustaka->link_repo}}" type="button" title="Lihat Repository" target="_blank">{{$repository->link_repo}}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left text-nowrap">Total SKS</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">{{$aktivitas_kuliah->sks_total}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left text-nowrap">IPK</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">{{$aktivitas_kuliah->ipk}}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if(!$wisuda)
                    <div class="row mb-5 mt-30">
                        <div class="col-xl-12 col-lg-12 text-center">
                            <div class="d-flex justify-content-center">
                                <a class="btn btn-lg btn-rounded bg-primary" 
                                    href="{{ route('mahasiswa.wisuda.tambah') }}">
                                    <i class="fa fa-graduation-cap"><span class="path1"></span><span class="path2"></span></i> 
                                    Daftar Wisuda
                                </a>
                            </div>  
                        </div>
                    </div>
                    @endif
                </div>
                @if($wisuda)
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="box">
                                <div class="box-body">
                                    <form class="form" action="#" id="update-detail-sidang" method="POST">
                                        <h3 class="text-info mb-0"><i class="fa fa-user"></i> Detail Pendaftaran Wisuda</h3>
                                        <hr class="my-15">
                                        <div class="row text-center mb-20">
                                            <div class="widget-user-image">
                                                @php
                                                $imagePath =
                                                public_path($wisuda->pas_foto.'.jpg');
                                                @endphp
                                                <img class="rounded bg-success-light"
                                                    src="{{file_exists($imagePath) ? asset($wisuda->pas_foto.'.jpg') : asset('images/images/avatar/avatar-15.png')}}"
                                                    alt="User Avatar">
                                            </div>
                                        </div>
                                        <table class="table">
                                            <tr>
                                                <td class="text-left">Wisuda Ke-</td>
                                                <td class="text-center">:</td>
                                                <td class="text-left" style="text-align: justify">{{$wisuda->wisuda_ke}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left">Kosentrasi</td>
                                                <td class="text-center">:</td>
                                                <td class="text-left" style="text-align: justify">{{$wisuda->kosentrasi}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left">Abstrak Tugas Akhir</td>
                                                <td class="text-center">:</td>
                                                <td class="text-left" style="text-align: justify">{{$wisuda->abstrak_ta}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left">Status Pendaftaran Wisuda</td>
                                                <td class="text-center">:</td>
                                                <td class="text-left" style="text-align: justify">
                                                    @if($wisuda->approved == 0)
                                                        <span class="badge bg-warning">Menunggu Konfirmasi Program Studi</span>
                                                    @elseif($wisuda->approved == 1)
                                                        <span class="badge bg-primary">Menunggu Konfirmasi Fakultas</span>
                                                    @elseif($wisuda->approved == 2)
                                                        <span class="badge bg-primary">Menunggu Konfirmasi BAAK</span>
                                                    @elseif($wisuda->approved == 3)
                                                        <span class="badge bg-success">Disetujui</span>
                                                    @elseif($wisuda->approved == 99)
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    
</section>
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('assets/vendor_components/select2/dist/css/select2.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>
    $(document).ready(function() {        
        $('.select2').select2();

        $('#data').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
        });
    });
</script>

</script>
@endpush