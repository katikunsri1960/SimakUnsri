@extends('layouts.mahasiswa')
@section('title')
Pendaftaran Wisuda Mahasiswa
@endsection
@section('content')
@include('swal')

<section class="content bg-white text-uppercase">
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
                                                <span class="badge bg-warning rounded" style="padding: 7px">Kurikulum Belum Diatur</span>
                                            @elseif($usept)
                                                <strong>{{ isset($usept['score']) ? $usept['score'] : 0 }}</strong><br>
                                                <span class="badge bg-{{$usept['class']}} rounded" style="padding: 7px">  
                                                    ({{$usept['status'] ?? 'N/A'}})
                                                </span>
                                            @endif
                                        </td>                                                                               
                                    </tr>
                                    <tr>
                                        <td class="text-left">Bebas Pustaka</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">
                                            @if(!$bebas_pustaka || !$bebas_pustaka->file_bebas_pustaka)
                                                <span class="badge bg-danger">Belum Bebas Pustaka</span>
                                            @else
                                                <a class="btn btn-sm btn-success" href="{{ asset('storage') }}/{{$bebas_pustaka->file_bebas_pustaka}}" type="button" target="_blank">Lihat Bebas Pustaka</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">Link Repository</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">
                                            @if(!$bebas_pustaka || !$bebas_pustaka->link_repo)
                                                <span class="badge bg-danger">Belum Upload Repositroy</span>
                                            @else
                                                <a class="btn btn-sm btn-success" href="{{$bebas_pustaka->link_repo}}" type="button" target="_blank">Lihat Repository</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left text-nowrap">Total SKS</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">
                                            <strong>{{$aktivitas_kuliah->sks_total}} SKS</strong><br>
                                            @if ($aktivitas_kuliah->sks_total >= $kurikulum->jumlah_sks_lulus)
                                                <span class="badge rounded bg-success" style="padding: 7px"> (Memenuhi Syarat)</span>
                                            @else
                                                <span class="badge rounded bg-danger" style="padding: 7px"> (Tidak Memenuhi Syarat SKS Lulus)</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left text-nowrap">IPK</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">
                                            <strong>{{$aktivitas_kuliah->ipk}}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left text-nowrap">Status Mahasiswa</td>
                                        <td class="text-center">:</td>
                                        <td class="text-left" style="text-align: justify">
                                            <strong>{{$riwayat_pendidikan->lulus_do->nama_jenis_keluar}}</strong><br>
                                            @if ($riwayat_pendidikan->lulus_do->id_jenis_keluar == 1)
                                                <span class="badge rounded bg-success" style="padding: 7px"> (Memenuhi Syarat)</span>
                                            @else
                                                <span class="badge rounded bg-danger" style="padding: 7px"> (Tidak Memenuhi Syarat SKS Lulus)</span>
                                            @endif
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
                                    href="{{ route('mahasiswa.wisuda.tambah') }}"
                                    id="daftar-wisuda-btn">
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
                                                $imagePath = public_path('storage/' . $wisuda->pas_foto);
                                            @endphp

                                            <img class="rounded bg-success"
                                                src="{{ (!empty($wisuda->pas_foto) && file_exists($imagePath))
                                                        ? asset('storage/' . $wisuda->pas_foto)
                                                        : asset('images/images/avatar/avatar-15.png') }}"
                                                alt="User Avatar"
                                                style="width: 250px;">
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <tr>
                                                <td class="text-left" style="width: 30%;">Judul {{$wisuda->aktivitas_mahasiswa->nama_jenis_aktivitas}}</td>
                                                <td class="text-center" style="width: 5%;">:</td>
                                                <td class="text-left" style="width: 65%; text-align: justify;">{{$wisuda->aktivitas_mahasiswa->judul}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left" style="width: 30%;">Wisuda Ke-</td>
                                                <td class="text-center" style="width: 5%;">:</td>
                                                <td class="text-left" style="width: 65%; text-align: justify;">{{$wisuda->wisuda_ke}}</td>
                                            </tr>
                                            @if($wisuda->prodi->bku_pada_ijazah == 1)                        
                                                <tr>
                                                    <td class="text-left" style="width: 30%;">Bidang Kajian Utama (BKU) / Kosentrasi</td>
                                                    <td class="text-center" style="width: 5%;">:</td>
                                                    <td class="text-left" style="width: 65%; text-align: justify;">{{$wisuda->bku_prodi ? $wisuda->bku_prodi->bku_prodi_id : '-'}}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td class="text-left" style="width: 30%;">Abstrak {{$wisuda->aktivitas_mahasiswa->nama_jenis_aktivitas}}</td>
                                                <td class="text-center" style="width: 5%;">:</td>
                                                <td class="text-left" style="width: 65%; text-align: justify;">{{$wisuda->abstrak_ta}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left" style="width: 30%;">File Abstrak {{$wisuda->aktivitas_mahasiswa->nama_jenis_aktivitas}}</td>
                                                <td class="text-center" style="width: 5%;">:</td>
                                                {{-- <td class="text-left" style="text-align: justify">{{$wisuda->abstrak_file}}</td> --}}
                                                <td class="text-left" style="width: 65%; text-align: justify;">
                                                    @if($wisuda->abstrak_file)
                                                        <a class="btn btn-sm btn-success my-5" href="{{ asset($wisuda->abstrak_file) }}" type="button" target="_blank">Lihat Abstrak Indonesia</a>
                                                    @else
                                                        <span class="badge badge-lg bg-danger mb-5">Abstrak Indonesia Tidak Diupload</span>
                                                    @endif
                                                    <!-- @if($wisuda->abstrak_file_eng)
                                                        <a class="btn btn-sm btn-success  my-5" href="{{ asset($wisuda->abstrak_file_eng) }}" type="button" target="_blank">Lihat Abstrak Inggris</a>
                                                    @else
                                                        <span class="badge badge-lg bg-danger mb-5">Abstrak Inggris Tidak Diupload</span>
                                                    @endif -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-left" style="width: 30%;">File Ijazah Terakhir</td>
                                                <td class="text-center" style="width: 5%;">:</td>
                                                {{-- <td class="text-left" style="text-align: justify">{{$wisuda->abstrak_file}}</td> --}}
                                                <td class="text-left" style="width: 65%; text-align: justify;">
                                                    @if($wisuda->abstrak_file)
                                                        <a class="btn btn-sm btn-success" href="{{ asset($wisuda->ijazah_terakhir_file) }}" type="button" target="_blank">Lihat Ijazah Terakhir</a>
                                                    @else
                                                        <span class="badge badge-lg bg-danger">Ijazah Terakhir Tidak Diupload</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-left" style="width: 30%;">Status Pendaftaran Wisuda</td>
                                                <td class="text-center" style="width: 5%;">:</td>
                                                <td class="text-left align-middle" style="width:10%">
                                                    @if($bebas_pustaka && $bebas_pustaka->file_bebas_pustaka && $bebas_pustaka->link_repo)
                                                        @if($wisuda->approved == 0)
                                                            <span class="badge badge-lg badge-warning mb-5 rounded">Belum Disetujui Koor. Prodi</span>
                                                        @elseif($wisuda->approved == 1)
                                                            <span class="badge badge-lg badge-primary mb-5 rounded">Disetujui Koor. Prodi</span>
                                                        @elseif($wisuda->approved == 2)
                                                            <span class="badge badge-lg badge-primary mb-5 rounded">Disetujui Fakultas</span>
                                                        @elseif($wisuda->approved == 3)
                                                            <span class="badge badge-lg badge-success mb-5 rounded">Disetujui Dir. Akademik</span>
                                                        @elseif($wisuda->approved == 97)
                                                            <span class="badge badge-lg badge-danger mb-5 rounded">Ditolak Koor. Prodi</span>
                                                        @elseif($wisuda->approved == 98)
                                                            <span class="badge badge-lg badge-danger mb-5 rounded">Ditolak Fakultas</span>
                                                        @elseif($wisuda->approved == 99)
                                                            <span class="badge badge-lg badge-danger mb-5 rounded">Ditolak Dir. Akademik</span>
                                                        @endif
                                                    @elseif($bebas_pustaka && !$bebas_pustaka->file_bebas_pustaka)
                                                        <span class="badge badge-lg bg-danger mb-5 rounded">
                                                            Ditangguhkan
                                                        </span>
                                                        <p class="text-danger">
                                                            <strong>
                                                                Anda belum Mengumpulkan Bundle Skripsi/Tesis/Disertasi ke UPT Perpustakaan!
                                                            </strong>
                                                        </p>
                                                    @elseif($bebas_pustaka && !$bebas_pustaka->link_repo)
                                                        <span class="badge badge-lg bg-danger mb-5 rounded">
                                                            Ditangguhkan
                                                        </span>
                                                        <p class="text-danger">
                                                            <strong>
                                                                Anda belum Upload Repository!
                                                            </strong>
                                                        </p>
                                                    @else
                                                        <span class="badge badge-lg bg-danger mb-5 rounded">
                                                            Persyaratan Wisuda Belum Lengkap
                                                        </span>
                                                    @endif

                                                    
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td class="text-left" style="width: 30%;">Berkas Registrasi Wisuda</td>
                                                <td class="text-center" style="width: 5%;">:</td>
                                                <td class="text-left align-middle">
                                                    @if($bebas_pustaka && !$bebas_pustaka->file_bebas_pustaka)
                                                        <span class="badge badge-lg bg-danger mb-5">
                                                            Berkas Registrasi Ditangguhkan
                                                        </span>
                                                    @elseif($bebas_pustaka && !$bebas_pustaka->file_bebas_pustaka)
                                                        <span class="badge badge-lg bg-danger mb-5">
                                                            Berkas Registrasi Ditangguhkan
                                                        </span>
                                                    @elseif($wisuda->approved >= 2 && $wisuda->approved < 90)
                                                        <a class="btn btn-sm btn-primary my-5"
                                                        href="{{ route('mahasiswa.wisuda.formulir', ['id' => $wisuda->id]) }}"
                                                        target="_blank">
                                                            <i class="fa fa-file me-2"></i> Unduh Berkas Registrasi
                                                        </a>
                                                    @else
                                                        <span class="badge badge-lg bg-danger">
                                                            Berkas Registrasi Belum Tersedia
                                                        </span>
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
    
    $('#daftar-wisuda-btn').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        swal({
            title: 'Daftar Wisuda',
            text: "Apakah anda yakin ingin mendaftar wisuda?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                window.location.href = url;
            }
        });
    });

</script>
@endpush