@extends('layouts.prodi')
@section('title')
Kuisioner Kelas Perkuliahan
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Kuisioner Kelas Perkuliahan</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Akademik</li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('prodi.data-akademik.kelas-penjadwalan')}}">Kelas dan Penjadwalan</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('prodi.data-akademik.kelas-penjadwalan.detail', ['id_matkul' => $kelas->id_matkul, 'semester'=>$kelas->id_semester])}}">Detail Kelas dan Penjadwalan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Kuisioner Kelas Perkuliahan</li>
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
                <div class="box-body table-responsive">
                    {{-- <h1>Hasil Kuisioner Kelas</h1> --}}
                    <table>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th style="padding-left: 20px; padding-right:20px">:</th>
                            <th>{{$kelas->matkul ? $kelas->matkul->nama_mata_kuliah : '-'}}</th>
                        </tr>
                        <tr>
                            <th>Nama Kelas</th>
                            <th style="padding-left: 20px; padding-right:20px">:</th>
                            <th>{{$kelas->nama_kelas_kuliah}}</th>
                        </tr>
                        <tr>
                            <th>Semester</th>
                            <th style="padding-left: 20px; padding-right:20px">:</th>
                            <th>{{$kelas->semester ? $kelas->semester->nama_semester : '-'}}</th>
                        </tr>
                        <tr>
                            <th>Jumlah Peserta</th>
                            <th style="padding-left: 20px; padding-right:20px">:</th>
                            <th>{{$kelas->peserta_kelas_count}}</th>
                        </tr>
                        <tr>
                            <th class="align-top">Dosen Ajar</th>
                            <th class="align-top" style="padding-left: 20px; padding-right:20px">:</th>
                            <th>
                                @if ($kelas->dosen_pengajar)
                                <ul >
                                    @foreach ($kelas->dosen_pengajar as $dp)
                                    <li style="margin-left:-20px">{{$dp->dosen->nama_dosen}}</li>
                                    @endforeach

                                </ul>
                                @endif
                            </th>
                        </tr>
                    </table>
                    </table>
                    <table class="table table-bordered table-hover" id="data-kelas" style="font-size: 9pt">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">Pertanyaan (Indonesia)</th>
                                <th class="text-center align-middle">Pertanyaan (English)</th>
                                <th class="text-center align-middle">Rata-rata Nilai</th>
                                <th class="text-center align-middle">Nilai 1</th>
                                <th class="text-center align-middle">Nilai 2</th>
                                <th class="text-center align-middle">Nilai 3</th>
                                <th class="text-center align-middle">Nilai 4</th>
                                <th class="text-center align-middle">Nilai 5</th>
                                <th class="text-center align-middle">Nilai 6</th>
                                <th class="text-center align-middle">Nilai 7</th>
                                <th class="text-center align-middle">Total Responden</th>
                            </tr>
                        </thead>
                        @php
                            $total_rata = 0;
                            $total_pertanyaan = 0;
                        @endphp
                        <tbody>
                            @foreach($kuisioner as $question_id => $answers)

                                @php
                                    $total_rata += $answers->avg('nilai');
                                    $total_pertanyaan++;
                                    $question = $answers->first()->kuisoner_question;
                                    $average = $answers->avg('nilai');
                                    $counts = $nilai_counts[$question_id]->keyBy('nilai');
                                    $total_responden = $answers->count();
                                @endphp
                                <tr>
                                    <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                    <td class="text-start align-middle">{{ $question->question_indonesia }}</td>
                                    <td class="text-start align-middle">{{ $question->question_english }}</td>
                                    <td class="text-center align-middle">{{ number_format($average, 2) }}</td>
                                    <td class="text-center align-middle">{{ $counts[1]->count ?? 0 }}</td>
                                    <td class="text-center align-middle">{{ $counts[2]->count ?? 0 }}</td>
                                    <td class="text-center align-middle">{{ $counts[3]->count ?? 0 }}</td>
                                    <td class="text-center align-middle">{{ $counts[4]->count ?? 0 }}</td>
                                    <td class="text-center align-middle">{{ $counts[5]->count ?? 0 }}</td>
                                    <td class="text-center align-middle">{{ $counts[6]->count ?? 0 }}</td>
                                    <td class="text-center align-middle">{{ $counts[7]->count ?? 0 }}</td>
                                    <td class="text-center align-middle">{{ $total_responden }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Rata-rata</th>
                                <th class="text-center">
                                    @if ($total_pertanyaan > 0)
                                        {{ number_format($total_rata / $total_pertanyaan, 2) }}

                                    @endif
                                </th>
                                <th class="text-center" colspan="8"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
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
    $(function(){
        'use strict';
        $('#data-kelas').DataTable({
            "paging": false,
            "lengthChange": true,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": true
        });

    });


    $('#edit-kelas').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Ubah Kelas Kuliah',
            text: "Apakah anda yakin ingin merubah detail kelas?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#edit-kelas').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });
</script>
@endpush
