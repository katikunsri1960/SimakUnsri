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
                            <img class="rounded bg-success"
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
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Data Akademik Mahasiswa
                                {{-- Aktivitas {{$aktivitas->nama_jenis_aktivitas}} --}}
                        </h4>
                    </div>                  
                </div>
                <div class="box box-body mb-0">
                    <div class="row mx-20">
                        <div class="table-responsive
                        {{-- d-flex justify-content-between --}}
                        ">
                            {{-- <div class="d-flex justify-content-start"> --}}
                                <table class="table table-striped">
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
                                                {{ isset($usept['score']) ? $usept['score'] : 0 }}<br>
                                                <span class="badge bg-danger">  
                                                    ({{$usept['status'] ?? 'N/A'}})
                                                </span>
                                            @endif
                                        </td>                                                                               
                                    </tr>
                                    <tr>
                                        <td class="text-left">Bebas Pustaka</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">
                                            @if(!$bebas_pustaka)
                                                <span class="badge bg-danger">Belum Bebas Pustaka</span>
                                            @else
                                                <a class="btn btn-sm btn-success" href="{{ asset('storage') }}/{{$bebas_pustaka->file_bebas_pustaka}}" type="button" title="Lihat Bebas Pustaka" target="_blank">{{$bebas_pustaka->file_bebas_pustaka}}</a>
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
                                                <a class="btn btn-sm btn-success" href="{{$bebas_pustaka->link_repo}}" type="button" title="Lihat Repository" target="_blank">{{$bebas_pustaka->link_repo}}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left text-nowrap">Total SKS</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">{{$aktivitas_kuliah->sks_total}} SKS<br>
                                            @if ($aktivitas_kuliah->sks_total >= $kurikulum->jumlah_sks_lulus)
                                                <span class="badge bg-success"> (Memenuhi Syarat)</span>
                                            @else
                                                <span class="badge bg-danger"> (Tidak Memenuhi Syarat SKS Lulus)</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left text-nowrap">IPK</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">{{$aktivitas_kuliah->ipk}}
                                        </td>
                                    </tr>
                                </table>
                            {{-- </div> --}}
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
                <div class="box-header with-border d-flex justify-content-between mx-20">
                    <div class="d-flex justify-content-start">
                        <h4 class="text-info mb-0"><i class="fa fa-graduation-cap"></i> Detail Pendaftaran Wisuda</h4>
                                        {{-- <hr class="my-15"> --}}
                    </div>                  
                </div>
                <div class="box box-body mb-0">
                    <div class="row mx-20">
                        <div class="col-12">
                            <div class="box">
                                <div class="box-body">
                                    <div class="row text-center mb-20">
                                        <div class="widget-user-image">
                                            @php
                                            $imagePath =
                                            public_path($wisuda->pas_foto);
                                            @endphp
                                            <img class="rounded bg-success"
                                                src="{{file_exists($imagePath) ? asset($wisuda->pas_foto) : asset('images/images/avatar/avatar-15.png')}}"
                                                alt="User Avatar" style="width: 250px;">
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <tr>
                                                <td class="text-left">Wisuda Ke-</td>
                                                <td class="text-center">:</td>
                                                <td class="text-left" style="text-align: justify">{{$wisuda->wisuda_ke}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left">Bidang Kajian Utama (BKU) / Kosentrasi</td>
                                                <td class="text-center">:</td>
                                                <td class="text-left" style="text-align: justify">{{$wisuda->kosentrasi}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left">Abstrak Tugas Akhir</td>
                                                <td class="text-center">:</td>
                                                <td class="text-left" style="text-align: justify">{{$wisuda->abstrak_ta}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left">File Abstak Tugas Akhir</td>
                                                <td class="text-center">:</td>
                                                {{-- <td class="text-left" style="text-align: justify">{{$wisuda->abstrak_file}}</td> --}}
                                                <td class="text-left" style="text-align: justify">
                                                    @if(!$wisuda)
                                                        <span class="badge bg-danger">Belum Bebas Pustaka</span>
                                                    @else
                                                        <a class="btn btn-sm btn-success" href="{{ asset('storage') }}/{{$wisuda->abstrak_file}}" type="button" title="Lihat File Abstrak" target="_blank">{{$wisuda->abstrak_file}}</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-left">Status Pendaftaran Wisuda</td>
                                                <td class="text-center">:</td>
                                                <td class="text-left align-middle" style="width:10%">
                                                    @if($wisuda->approved == 0)
                                                        <span class="badge badge-lg badge-warning mb-5">Belum Disetujui Koor. Prodi</span>
                                                    @elseif($wisuda->approved == 1)
                                                        <span class="badge badge-lg badge-primary mb-5">Disetujui Koor. Prodi</span>
                                                    @elseif($wisuda->approved == 2)
                                                        <span class="badge badge-lg badge-primary mb-5">Disetujui Fakultas</span>
                                                    @elseif($wisuda->approved == 3)
                                                        <span class="badge badge-lg badge-success mb-5">Disetujui BAK</span>
                                                    @elseif($wisuda->approved == 97)
                                                        <span class="badge badge-lg badge-danger mb-5">Ditolak Koor. Prodi</span>
                                                    @elseif($wisuda->approved == 98)
                                                        <span class="badge badge-lg badge-danger mb-5">Ditolak Fakultas</span>
                                                    @elseif($wisuda->approved == 99)
                                                        <span class="badge badge-lg badge-danger mb-5">Ditolak BAK</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
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