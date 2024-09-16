@extends('layouts.dosen')
@section('title')
Detail Penilaian Perkuliahan Mahasiswa
@endsection
@section('content')
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-12">
            <div class="box pull-up">
                <div class="box-body bg-img bg-primary-light">
                    <div class="d-lg-flex align-items-center justify-content-between">
                        <div class="d-lg-flex align-items-center mb-30 mb-xl-0 w-p100">
                            <img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}"
                                class="img-fluid max-w-250" alt="" />
                            <div class="ms-30">
                                <h2 class="mb-10">{{$data->peserta_kelas[0]->kode_mata_kuliah}} - {{$data->peserta_kelas[0]->nama_mata_kuliah}}</h2>
                                <p class="mb-0 text-fade fs-18">{{$data->peserta_kelas[0]->nama_program_studi}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header mb-3">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('dosen')}}"><i class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dosen.penilaian.penilaian-perkuliahan')}}">Penilaian Perkuliahan</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-12">
                    <div class="box box-body mb-0 ">
                        <div class="row mb-5">
                            <div class="col-xl-4 col-lg-12">
                                <a class="btn btn-rounded bg-warning-light" href="{{route('dosen.penilaian.penilaian-perkuliahan')}}"><i class="fa fa-chevron-left"><span class="path1"></span><span class="path2"></span></i> Kembali</a>
                            </div>                             
                        </div><br>
                        <div class="row">
                            <div class="table-responsive">
                                <table id="data" class="table table-bordered table-striped text-center" style="font-size: 12px">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2">NIM</th>
                                            <th rowspan="2">Nama Mahasiswa</th>
                                            <th rowspan="2">Nama Kelas Kuliah</th>
                                            <th rowspan="2">Angkatan</th>
                                            <th colspan="6">Nilai Komponen Evaluasi</th>
                                            <th colspan="2">Nilai Perkuliahan</th>
                                        </tr>
                                        <tr>
                                            <th>Aktivitas Partisipatif</th>
                                            <th>Hasil Proyek</th>
                                            <th>Tugas</th>
                                            <th>Quiz</th>
                                            <th>UTS</th>
                                            <th>UAS</th>
                                            <th>Angka</th>
                                            <th>Huruf</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->peserta_kelas as $p)
                                            <tr>
                                                <td class="text-center align-middle">{{$loop->iteration}}</td>
                                                <td class="text-center align-middle">{{$p->nim}}</td>
                                                <td class="text-start align-middle">{{$p->nama_mahasiswa}}</td>
                                                <td class="text-start align-middle">{{$p->nama_kelas_kuliah}}</td>
                                                <td class="text-center align-middle">{{$p->angkatan}}</td>

                                                @php
                                                    // Find the corresponding nilai_perkuliahan for this peserta_kelas
                                                    $nilaiPerkuliahan = $data->nilai_perkuliahan->where('id_peserta_kelas', $p->id_peserta_kelas)->first();
                                                @endphp

                                                @if(is_null($nilaiPerkuliahan))
                                                    <td class="text-center align-middle" colspan="8">DATA NILAI BELUM DI UPLOAD.</td>
                                                @else
                                                    @for ($i = 1; $i <= 6; $i++)
                                                        @php
                                                            $nilaiKomponen = $nilaiPerkuliahan->nilai_komponen->where('urutan', $i)->first();
                                                        @endphp
                                                        <td class="text-center align-middle">
                                                            {{ optional($nilaiKomponen)->nilai_komp_eval ?? '0' }}
                                                        </td>
                                                    @endfor

                                                    <td class="text-center align-middle">{{$nilaiPerkuliahan->nilai_angka}}</td>
                                                    <td class="text-center align-middle">{{$nilaiPerkuliahan->nilai_huruf}}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
        $('#data').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
            // "scrollCollapse": true,
            // "scrollY": "550px",
            "fixedColumns": {
                "rightColumns": 6
            },
            columnDefs:[]
        });

    });
</script>

@endpush
