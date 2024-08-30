@extends('layouts.universitas')
@section('title')
Monitoring Pengisian KRS
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Monitoring Pengisian KRS</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Monitoring</li>
                        <li class="breadcrumb-item active" aria-current="page">Pengisian KRS</li>
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
                <div class="box-body">
                    <button id="start-process" class="btn btn-primary">Mulai Proses</button>

                    <div class="progress mt-3">
                        <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>


                    <div class="table-responsive mt-5">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                          <thead>
                             <tr>
                                <th class="text-center align-middle">Nama Fakultas</th>
                                <th class="text-center align-middle">Nama Program Studi</th>
                                <th class="text-center align-middle">Jumlah Mahasiswa Aktif</th>
                                <th class="text-center align-middle">Jumlah Mahasiswa Aktif {{date('Y') - 7}} - {{date('Y')}}</th>
                                <th class="text-center align-middle">Jumlah Mahasiswa (Yang melakukan pengisian KRS)</th>
                                <th class="text-center align-middle">Jumlah Mahasiswa Sudah di Setujui</th>
                                <th class="text-center align-middle">Jumlah Mahasiswa Belum di Setujui</th>
                                <th class="text-center align-middle">Persentase Approval</th>
                             </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $d)
                            @php
                                $persentase_approval = 0;
                                if($d->isi_krs > 0) {
                                    $persentase_approval = round(($d->krs_approved / $d->isi_krs) * 100);
                                }
                            @endphp
                                <tr class="@if ($persentase_approval < 50) table-danger @endif">

                                    <td class="text-start align-middle">{{$d->id}} - {{$d->nama_fakultas}}</td>
                                    <td class="text-start align-middle">{{$d->nama_jenjang_pendidikan}} {{$d->nama_program_studi}}</td>
                                    <td class="text-center align-middle">
                                        <a href="{{route('univ.monitoring.pengisian-krs.detail-mahasiswa-aktif', ['prodi' => $d->prodi->id])}}">
                                            {{$d->mahasiswa_aktif}}
                                        </a>

                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{route('univ.monitoring.pengisian-krs.detail-aktif-min-tujuh', ['prodi' => $d->prodi->id])}}">
                                            {{$d->mahasiswa_aktif_min_7}}
                                        </a>
                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($d->isi_krs > 0)
                                        <a href="{{route('univ.monitoring.pengisian-krs.detail-isi-krs', ['prodi' => $d->prodi->id])}}">
                                            {{$d->isi_krs}}
                                        </a>
                                        @else
                                            0
                                        @endif

                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($d->krs_approved > 0)
                                        <a href="{{route('univ.monitoring.pengisian-krs.detail-approved-krs', ['prodi' => $d->prodi->id])}}">
                                        {{$d->krs_approved}}
                                        </a>
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($d->krs_not_approved > 0)
                                        <a href="{{route('univ.monitoring.pengisian-krs.detail-not-approved-krs', ['prodi' => $d->prodi->id])}}">
                                            {{$d->krs_not_approved}}
                                            </a>
                                        @else
                                            0
                                        @endif

                                    </td>
                                    <td class="text-center align-middle">{{$persentase_approval}}%</td>
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
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#data').DataTable();
        let step = 0;
        let totalSteps = {{ $prodi->count() }}; // Jumlah total prodi

        function executeStep() {
            $.ajax({
                url: '{{ route("univ.monitoring.pengisian-krs.generate-data") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    step: step
                },
                success: function(response) {
                    let progress = response.progress;
                    $('#progress-bar').css('width', progress + '%').attr('aria-valuenow', progress).text(progress + '%');

                    if(response.completed) {
                        $('#start-process').prop('disabled', false);
                        $('body').css('pointer-events', 'auto'); // Mengembalikan interaksi

                        swal({
                            title: 'Proses Selesai',
                            type: 'success',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Lanjutkan!',
                        }, function(isConfirm){
                            if (isConfirm) {
                                window.location.reload();
                            }
                        });


                    } else {
                        step++;
                        executeStep(); // Panggil langkah berikutnya
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);

                    // Enable tombol kembali dan memungkinkan interaksi pengguna saat terjadi error
                    $('#start-process').prop('disabled', false);
                    $('body').css('pointer-events', 'auto');
                }
            });
        }

        $('#start-process').on('click', function() {
            swal({
                title: 'Apakah Anda Yakin?',
                type: 'warning',
                text: 'Proses ini mungkin memakan waktu beberapa menit.',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal',
            }, function(isConfirm){
                if (isConfirm) {
                    $('#start-process').prop('disabled', true);
                    $('body').css('pointer-events', 'none'); // Mencegah interaksi

                    // Reset progress bar dan step sebelum memulai
                    step = 0;
                    $('#progress-bar').css('width', '0%').attr('aria-valuenow', '0').text('0%');

                    // Mulai proses
                    executeStep();
                }
            });

        }); // Mulai proses
    });

</script>
@endpush
