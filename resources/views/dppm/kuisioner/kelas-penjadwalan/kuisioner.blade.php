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
                    <hr>
                    <div class="form-group">
                        <label for="filterQuestions">Filter Pertanyaan:</label>
                        <select id="filterQuestions" class="form-control" multiple>
                            @foreach($kuisioner as $question_id => $answers)
                                @php
                                    $question = $answers->first()->kuisoner_question;
                                @endphp
                                <option value="{{ $question_id }}">{{ $question->question_indonesia }}</option>
                            @endforeach
                        </select>
                    </div>
                    <canvas id="kuisionerChart" width="400" height="200"></canvas>
                    <hr>
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
                            $labels = [];
                            $fullLabels = [];
                            $dataRataRata = [];
                            $dataNilai1 = [];
                            $dataNilai2 = [];
                            $dataNilai3 = [];
                            $dataNilai4 = [];
                            $dataNilai5 = [];
                            $dataNilai6 = [];
                            $dataNilai7 = [];
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

                                    $fullLabels[] = $question->question_indonesia;
                                    $labels[] = Str::words($question->question_indonesia, 2, '...');
                                    $dataRataRata[] = $average;
                                    $dataNilai1[] = $counts[1]->count ?? 0;
                                    $dataNilai2[] = $counts[2]->count ?? 0;
                                    $dataNilai3[] = $counts[3]->count ?? 0;
                                    $dataNilai4[] = $counts[4]->count ?? 0;
                                    $dataNilai5[] = $counts[5]->count ?? 0;
                                    $dataNilai6[] = $counts[6]->count ?? 0;
                                    $dataNilai7[] = $counts[7]->count ?? 0;
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/flatpickr/flatpickr.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('kuisionerChart').getContext('2d');
        var kuisionerChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [
                    // {
                    //     label: 'Rata-rata Nilai',
                    //     data: @json($dataRataRata),
                    //     backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    //     borderColor: 'rgba(75, 192, 192, 1)',
                    //     borderWidth: 1
                    // },
                    {
                        label: 'Nilai 1',
                        data: @json($dataNilai1),
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Nilai 2',
                        data: @json($dataNilai2),
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Nilai 3',
                        data: @json($dataNilai3),
                        backgroundColor: 'rgba(255, 206, 86, 0.5)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Nilai 4',
                        data: @json($dataNilai4),
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Nilai 5',
                        data: @json($dataNilai5),
                        backgroundColor: 'rgba(153, 102, 255, 0.5)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Nilai 6',
                        data: @json($dataNilai6),
                        backgroundColor: 'rgba(255, 159, 64, 0.5)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Nilai 7',
                        data: @json($dataNilai7),
                        backgroundColor: 'rgba(199, 199, 199, 0.5)',
                        borderColor: 'rgba(199, 199, 199, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            title: function(tooltipItems) {
                                return @json($fullLabels)[tooltipItems[0].dataIndex];
                            }
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        formatter: function(value, context) {
                            return value;
                        },
                        color: 'black',
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            }
        });

        // Filter functionality
        document.getElementById('filterQuestions').addEventListener('change', function() {
            var selectedQuestions = Array.from(this.selectedOptions).map(option => option.value);
            var filteredLabels = [];
            var filteredFullLabels = [];
            var filteredDataRataRata = [];
            var filteredDataNilai1 = [];
            var filteredDataNilai2 = [];
            var filteredDataNilai3 = [];
            var filteredDataNilai4 = [];
            var filteredDataNilai5 = [];
            var filteredDataNilai6 = [];
            var filteredDataNilai7 = [];

            @foreach($kuisioner as $question_id => $answers)
                if (selectedQuestions.includes('{{ $question_id }}')) {
                    filteredFullLabels.push(@json($fullLabels)[{{ $loop->index }}]);
                    filteredLabels.push(@json($labels)[{{ $loop->index }}]);
                    // filteredDataRataRata.push(@json($dataRataRata)[{{ $loop->index }}]);
                    filteredDataNilai1.push(@json($dataNilai1)[{{ $loop->index }}]);
                    filteredDataNilai2.push(@json($dataNilai2)[{{ $loop->index }}]);
                    filteredDataNilai3.push(@json($dataNilai3)[{{ $loop->index }}]);
                    filteredDataNilai4.push(@json($dataNilai4)[{{ $loop->index }}]);
                    filteredDataNilai5.push(@json($dataNilai5)[{{ $loop->index }}]);
                    filteredDataNilai6.push(@json($dataNilai6)[{{ $loop->index }}]);
                    filteredDataNilai7.push(@json($dataNilai7)[{{ $loop->index }}]);
                }
            @endforeach

            kuisionerChart.data.labels = filteredLabels;
            // kuisionerChart.data.datasets[0].data = filteredDataRataRata;
            kuisionerChart.data.datasets[0].data = filteredDataNilai1;
            kuisionerChart.data.datasets[1].data = filteredDataNilai2;
            kuisionerChart.data.datasets[2].data = filteredDataNilai3;
            kuisionerChart.data.datasets[3].data = filteredDataNilai4;
            kuisionerChart.data.datasets[4].data = filteredDataNilai5;
            kuisionerChart.data.datasets[5].data = filteredDataNilai6;
            kuisionerChart.data.datasets[6].data = filteredDataNilai7;
            kuisionerChart.update();
        });
    });

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
