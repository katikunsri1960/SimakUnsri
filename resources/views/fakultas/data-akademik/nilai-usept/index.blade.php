@extends('layouts.fakultas')
@section('title')
Nilai USEPT Mahasiswa
@endsection
@include('swal')
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Nilai USEPT Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Akademik</li>
                        <li class="breadcrumb-item active" aria-current="page">Nilai USEPT Mahasiswa</li>
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
                <div class="box-header with-border">
                    <!-- Form untuk NIM -->
                    <form action="{{ route('fakultas.data-akademik.nilai-usept.get') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="nim" class="form-label">Nomor Induk Mahasiswa</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="nim" id="nim" required />
                                    <button class="input-group-button btn btn-primary btn-sm" type="submit">
                                        <i class="fa fa-search"></i> Proses
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        @if(isset($riwayat))
                        <div id="krsDiv">
                            {{-- <h3 class="text-center">Nilai USEPT Mahasiswa</h3> --}}
                            {{-- <table style="width:100%" class="mb-3">
                                <tr>
                                    <td class="text-start align-middle" style="width: 12%">NIM</td>
                                    <td>:</td>
                                    <td class="text-start" id="nimKrs" style="width: 45%; padding-left: 10px">
                                        {{$riwayat->nim}}
                                    </td>
                                    <td class="text-start align-middle" style="width: 18%">FAKULTAS</td>
                                    <td>:</td>
                                    <td class="text-start align-middle" id="fakultasKrs" style="width: 30%; padding-left: 10px">
                                        {{$riwayat->prodi->fakultas->nama_fakultas}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-start align-middle" style="width: 12%">NAMA</td>
                                    <td>:</td>
                                    <td class="text-start" id="namaKrs" style="width: 45%; padding-left: 10px">
                                        {{$riwayat->nama_mahasiswa}}
                                    </td>
                                    <td class="text-start align-middle" style="width: 18%">JURUSAN</td>
                                    <td>:</td>
                                    <td class="text-start align-middle" id="jurusanKrs" style="width: 30%; padding-left: 10px">
                                        {{$riwayat->prodi->jurusan->nama_jurusan_id}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-start align-middle" style="width: 12%">NIP PA</td>
                                    <td>:</td>
                                    <td class="text-start" id="nippaKrs" style="width: 45%; padding-left: 10px">
                                        {{$riwayat->dosen_pa ? $riwayat->pembimbing_akademik->nip : '-'}}
                                    </td>
                                    <td class="text-start align-middle" style="width: 18%">PROGRAM STUDI</td>
                                    <td>:</td>
                                    <td class="text-start align-middle" id="prodiKrs"
                                        style="width: 30%; padding-left: 10px">
                                        {{$riwayat->prodi->nama_program_studi}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-start align-middle" style="width: 12%">DOSEN PA</td>
                                    <td>:</td>
                                    <td class="text-start" id="dosenpaKrs" style="width: 45%; padding-left: 10px">
                                        {{$riwayat->dosen_pa ? $riwayat->pembimbing_akademik->nama_dosen : '-'}}
                                    </td>
                                    <td class="text-start align-middle" style="width: 18%">SKOR KELULUSAN</td>
                                    <td>:</td>
                                    <td class="text-start align-middle" id="semesterKrs"
                                        style="width: 30%; padding-left: 10px">
                                        {{$nilai_usept_prodi->nilai_usept}}
                                    </td>
                                </tr>
                            </table> --}}
                            <div class="box-header">
                                <div class="row align-items-end">
                                    <div class="col-md-12">
                                        <div class="box box-widget widget-user-2">
                                            <div class="widget-user-header bg-gradient-secondary">
                                                <div class="widget-user-image">
                                                    @php
                                                    $imagePath =
                                                    public_path('storage/'.$riwayat->angkatan.'/'.$riwayat->nim.'.jpg');
                                                    @endphp
                                                    <img class="rounded bg-success-light"
                                                        src="{{file_exists($imagePath) ? asset('storage/'.$riwayat->angkatan.'/'.$riwayat->nim.'.jpg') : asset('images/images/avatar/avatar-15.png')}}"
                                                        alt="User Avatar">
                                                </div>
                                                <h3 class="widget-user-username">{{$riwayat->nama_mahasiswa}} </h3>
                                                <h4 class="widget-user-desc">NIM: {{$riwayat->nim}}<br
                                                        class="mb-1">ANGKATAN: {{$riwayat->angkatan}}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12">
                                    <h4 class="mb-5">
                                        Nilai Kelulusan USEPT Prodi {{$riwayat->nama_program_studi}} :
                                        @if(empty($nilai_usept_prodi->nilai_usept))
                                            <span class="badge badge-xl badge-danger mb-5">Nilai Kelulusan Belum diatur</span>
                                        @else
                                            <span class="badge badge-xl badge-success mb-5 px-20">{{ $nilai_usept_prodi->nilai_usept }}</span>
                                        @endif
                                    </h4>
                                </div>                             
                            </div>
                            <div class="box-body">
                                <h3 class="text-info mb-25"><i class="fa fa-book"></i> Daftar Nilai Tes USEPT</h3>
                                {{-- <hr class="my-15"> --}}
                                <div class="table-responsive">
                                    <table id="test" class="table table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama Mahasiswa</th>                                    
                                                <th>Tanggal Ujian</th>
                                                <th>Skor USEPT</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @if($test_usept->isEmpty())
                                                <tr>
                                                    <td colspan="6" class="text-center">Data tidak ditemukan</td>
                                                </tr>
                                            @else --}}
                                                @foreach($test_usept as $t)
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>
                                                        <td class="text-center align-middle" style="white-space:nowrap;">{{$riwayat->nim}}</td>
                                                        <td class="text-start align-middle">{{$riwayat->nama_mahasiswa}}</td>
                                                        <td>{{ date('d M Y', strtotime($t->tgl_test)) }}</td>
                                                        <td>{{$t->score}}</td>
                                                        <td class="text-center align-middle"> 
                                                            @if ($t->score < $nilai_usept_prodi->nilai_usept)
                                                                <span class="badge bg-danger">Belum Lulus</span>
                                                            @elseif ($nilai_usept_prodi->nilai_usept == NULL)
                                                                <span class="badge bg-danger">Nilai Kelulusan Belum diatur</span>
                                                            @elseif ($t->score >= $nilai_usept_prodi->nilai_usept)
                                                                <span class="badge bg-success">Lulus</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            {{-- @endif --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if(isset($course_usept))
                            <div class="box-footer">
                                <h3 class="text-info mb-25"><i class="fa fa-book"></i> Daftar Nilai Course USEPT</h3>
                                {{-- <hr class="my-15"> --}}
                                <div class="table-responsive">
                                    <table id="course" class="table table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Tanggal Upload</th>                                    
                                                <th>Nilai Angka</th>
                                                <th>Nilai Huruf</th>
                                                <th>Nilai Konversi USEPT</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($course_usept as $c)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td class="text-center align-middle" style="white-space:nowrap;">{{$riwayat->nim}}</td>
                                                <td class="text-start align-middle">{{$riwayat->nama_mahasiswa}}</td>
                                                <td>{{ date('d M Y', strtotime($c->tgl_upload)) }}</td>
                                                <td>{{$c->total_score}}</td>
                                                <td>{{$c->grade}}</td>
                                                <td>{{$c->konversi}}</td>
                                                <td class="text-center align-middle"> 
                                                    @if ($c->konversi < $nilai_usept_prodi->nilai_usept)
                                                        <span class="badge bg-danger">Belum Lulus</span>
                                                    @elseif ($nilai_usept_prodi->nilai_usept == NULL)
                                                        <span class="badge bg-danger">Nilai Kelulusan Belum diatur</span>
                                                    @elseif ($c->konversi >= $nilai_usept_prodi->nilai_usept)
                                                        <span class="badge bg-success">Lulus</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('js')
<script>
  $(document).ready(function() {
    // Inisialisasi DataTable pada tabel dengan ID yang sesuai
    $('#test').DataTable({
      "paging": true,
      "ordering": true,
      "searching": true
    });

    $('#course').DataTable({
      "paging": true,
      "ordering": true,
      "searching": true
    });

    // Cek untuk pesan error dari session
    @if(session('error'))
      $('#krsDiv').attr('hidden', true); // Sembunyikan div jika ada error
      swal({
        title: "Gagal!",
        text: "{{ session('error') }}",
        type: "warning",
        buttons: {
          confirm: {
            className: 'btn btn-warning'
          }
        }
      });
    @endif
  });
</script>
@endpush

