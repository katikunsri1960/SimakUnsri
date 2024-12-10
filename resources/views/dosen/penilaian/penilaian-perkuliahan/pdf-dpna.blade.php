@extends('layouts.doc-nologo')
@section('title')
Daftar Nilai Akhir Semester
@endsection
@section('content')
@include('swal')
<div style="text-transform: uppercase; font-family: Arial, Helvetica, sans-serif">
    <div class="container-fluid">
        <table style="width: 100%" class="table-pdf">
            <tr>
                <td class="text-judul3 text-center">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</td>
            </tr>
            <tr>
                <td class="text-judul3 text-center"><strong>UNIVERSITAS SRIWIJAYA</strong></td>
            </tr>
        </table>
    </div>
    <table class="text-10" style="width: 100%; margin-top:1.0rem">

        <tr>
            <td class="text-12 text-center text-upper" height="10" colspan="3">
                <strong>DAFTAR NILAI AKHIR SEMESTER</strong>
            </td>
        </tr>
    </table>
    <div style="text-align: center; margin-top:1.0rem; margin-bottom:1.0rem">
        <table style="width: 50%; margin: 0 auto; font-size:10pt; font-weight:bold;">
            <tr>
                <td>MATA KULIAH</td>
                <td>:</td>
                <td style="padding-left:0.5rem">{{$data->matkul ? $data->matkul->nama_mata_kuliah : '-'}}</td>
            </tr>
            <tr>
                <td>KODE MK</td>
                <td>:</td>
                <td style="padding-left:0.5rem">{{$data->matkul ? $data->matkul->kode_mata_kuliah : '-'}}</td>
            </tr>
            <tr>
                <td>SKS</td>
                <td>:</td>
                <td style="padding-left:0.5rem">{{$data->matkul ? $data->matkul->sks_mata_kuliah : '-'}}</td>
            </tr>
            <tr>
                <td>KELAS/RUANG</td>
                <td>:</td>
                <td style="padding-left:0.5rem">{{$data->nama_kelas_kuliah}} (Ruang: {{$data->ruang_perkuliahan ? $data->ruang_perkuliahan->nama_ruang : '-'}})</td>
            </tr>
            <tr>
                <td>DOSEN PENGAJAR</td>
                <td>:</td>
                <td>
                    @if ($dosen->count() > 0)
                    <ul style="margin-left:-1.2rem">
                        @foreach ($dosen as $d)
                            <li>{{$d->dosen->nama_dosen}}</li>
                        @endforeach
                    </ul>
                    @endif
                </td>
            </tr>
            <tr>
                <td>PROGRAM STUDI</td>
                <td>:</td>
                <td style="padding-left:0.5rem">{{$data->prodi ? $data->prodi->nama_jenjang_pendidikan.'-'.$data->prodi->nama_program_studi : '-'}}</td>
            </tr>
            <tr>
                <td>TAHUN AKADEMIK</td>
                <td>:</td>
                <td style="padding-left:0.5rem">{{$data->semester ? $data->semester->nama_semester : '-'}}</td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="table-responsive">
        <table id="header" class="text-10" border="1" rules="all" style="width: 100%">
            <thead>
                <tr>
                    <th rowspan="2">NO</th>
                    <th rowspan="2">NIM</th>
                    <th rowspan="2">NAMA</th>
                    <th colspan="6">Nilai Komponen Evaluasi</th>
                    <th colspan="2">Nilai Perkuliahan</th>
                </tr>
                <tr>
                    <th>Aktivitas<br>Partisipatif</th>
                    <th>Hasil<br>Proyek</th>
                    <th>Tugas</th>
                    <th>Quiz</th>
                    <th>UTS</th>
                    <th>UAS</th>
                    <th>Angka</th>
                    <th>Huruf</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($peserta as $d)
                <tr>
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td class="text-center">{{$d->nim}}</td>
                    <td class="text-start" style="padding:0.2rem">{{$d->nama_mahasiswa}}</td>
                    @if ($nilai_komponen->count() > 0)
                        @foreach ($nilai_komponen->where('id_registrasi_mahasiswa', $d->id_registrasi_mahasiswa) as $nk)
                            <td class="text-center">{{$nk->nilai_komp_eval ?? '0'}}</td>
                        @endforeach
                        <td class="text-center">{{$d->nilai_angka}}</td>
                        <td class="text-center">{{$d->nilai_huruf}}</td>
                    @else
                        <td class="text-center" colspan="8">DATA NILAI BELUM DI UPLOAD</td>
                    @endif

                </tr>
            @endforeach
            </tbody>

        </table>
    </div>
</div>
<div style="margin-top: 1.0rem; font-size:10pt;page-break-inside: avoid;" class="text-right" id="ttd">
    <table style="width: 100%">
        <tr class="text-right">
            <td></td>
            <td> Inderalaya, {{ $today}}</td>
        </tr>
        <tr class="text-right">
            <td></td>
            <td>Dosen Pengajar,</td>
        </tr>
        @if ($dosen->count() > 0)

            @foreach ($dosen as $d)
                <tr class="text-right">
                    <td>

                    </td>
                    <td style="padding-top: 2.5rem;padding-bottom:0.5rem">
                        {{$d->dosen->nama_dosen}} &nbsp;(...............................................)
                    </td>
                </tr>
            @endforeach

        @endif
    </table>
</div>

{{-- </div> --}}
@endsection
