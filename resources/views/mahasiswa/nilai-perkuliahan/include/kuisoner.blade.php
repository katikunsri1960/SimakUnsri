@extends('layouts.mahasiswa')
@section('title')
Dashboard
@endsection
@section('content')
<section class="content">
    <div class="row align-items-end">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3"><img
                                src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h2>Nilai Perkuliahan, {{auth()->user()->name}}</h2>
                            <p class="text-dark mb-0 fs-16">
                                SIMAK Universitas Sriwijaya
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-10">
        <div class="col-lg-12 col-xl-12 mt-0">
            <!-- Nav tabs -->
            <ul class="nav nav-pills justify-content-left" role="tablist">
                <li class="nav-item bg-secondary-light"> <a class="nav-link active" data-bs-toggle="tab" href="#khs"
                        role="tab"><span><i class="fa-solid fa-file-invoice"></i></span> <span
                            class="hidden-xs-down ms-15">Kartu Hasil Studi</span></a> </li>

            </ul>
            <div class="box">
                <!-- Tab panes -->
                <div class="tab-content tabcontent">
                    <div class="tab-pane active" id="khs" role="tabpanel">
                        <div class="col-xl-12 col-lg-12 col-12">
                            <div class="bg-primary-light big-side-section mb-20 shadow-lg">
                                <div class="box box-body mb-0 bg-white">
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="box no-shadow mb-0 bg-transparent">
                                                <div class="box-header no-border px-0">
                                                    <a type="button"
                                                        href="{{route('mahasiswa.perkuliahan.nilai-perkuliahan.lihat-khs', $kelas->id_semester)}}"
                                                        class="btn btn-warning btn-rounded waves-effect waves-light">
                                                        <i class="fa-solid fa-arrow-left"></i> Kembali
                                                    </a>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="box bs-5 border-danger rounded mb-10 pull-up">
                                                <div class="box-body">
                                                    <div class="flex-grow-1">
                                                        <p class="mt-5 mb-5 text-fade fs-12">MATAKULIAH</p>
                                                        <h4>{{$kelas->matkul->nama_mata_kuliah}}</h4>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="box bs-5 border-primary rounded mb-10 pull-up">
                                                <div class="box-body">
                                                    <div class="flex-grow-1">
                                                        <p class="mt-5 mb-5 text-fade fs-12">Nama Kelas Kuliah
                                                        </p>
                                                        <h4>{{$kelas->nama_kelas_kuliah}}</h4>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="box bs-5 border-warning rounded mb-10 pull-up">
                                                <div class="box-body">
                                                    <div class="flex-grow-1">
                                                        <p class="mt-5 mb-5 text-fade fs-12">Semester</p>
                                                        <h4>{{$kelas->semester->nama_semester}}</h4>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="table-responsive">
                                            <form id="kuisionerForm" action="{{ route('mahasiswa.perkuliahan.nilai-perkuliahan.kuisioner.store', ['kelas' => $kelas->id_kelas_kuliah]) }}" method="post">
                                                @csrf
                                                <table class="table table-bordered table-striped text-left">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center align-middle" style="width: 5%">No
                                                            </th>
                                                            <th class="text-center align-middle">Pertanyaan (ID)
                                                            </th>
                                                            <th class="text-center align-middle">Pertanyaan (EN)</th>
                                                            <th class="text-center align-middle">Nilai</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($data as $k)
                                                        <tr>
                                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                            <td class="text-start align-middle">
                                                                <p style="text-align: justify;">
                                                                    {{ $k->question_indonesia }}
                                                                </p>
                                                            </td>
                                                            <td class="text-start align-middle">
                                                                <p style="text-align: justify;">
                                                                    {{ $k->question_english }}
                                                                </p>
                                                            </td>
                                                            @if($k->id < 9)
                                                            <td class="text-start align-middle text-nowrap">
                                                                @for ($i = 1; $i <= 7; $i++)
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" id="nilai_{{ $loop->iteration }}_{{ $i }}" name="nilai[{{ $loop->iteration }}]" value="{{ $i }}" @if ($k->nilai == $i) checked @endif>
                                                                    <label class="form-check-label" for="nilai_{{ $loop->iteration }}_{{ $i }}">{{ $i }}</label>
                                                                </div>
                                                                @endfor
                                                            </td>
                                                            @else
                                                            <td class="text-start align-middle text-nowrap">
                                                                <input 
                                                                    class="form-control" 
                                                                    type="number" 
                                                                    id="nilai_workload_{{ $loop->iteration }}" 
                                                                    name="nilai_workload[{{ $loop->iteration }}]" 
                                                                    value="{{ $k->nilai }}"
                                                                    required
                                                                >
                                                            </td>
                                                            @endif
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                        </table>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
    </div>
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script>
    document.getElementById('kuisionerForm').addEventListener('submit', function(event) {
        let valid = true;
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach((row, index) => {
            const rowIndex = index + 1;  // Adjusting for 1-based index in Blade
            const radioButtons = row.querySelectorAll(`input[name="nilai[${rowIndex}]"]`);
            let oneChecked = false;

            if (radioButtons.length === 0) {
                oneChecked = true;
            } else {
                radioButtons.forEach(radio => {
                    if (radio.checked) {
                        oneChecked = true;
                    }
                });
            }

            if (!oneChecked) {
                valid = false;
                row.style.backgroundColor = '#f8d7da'; // Highlight the row with a red background
            } else {
                row.style.backgroundColor = ''; // Reset the row background
            }
        });

        if (!valid) {
            event.preventDefault();
            swal({
                title: 'Harap isi semua pertanyaan terlebih dahulu!',
                type: 'warning',
                showCancelButton: true,
                showConfirmButton: false,
                cancelButtonColor: '#d33',
                cancelButtonText: 'Tutup'
            });
        }

        if(valid) {
            event.preventDefault();
            swal({
                title: 'Apakah anda yakin?',
                text: "Data yang sudah disubmit tidak dapat diubah!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, submit!',
                cancelButtonText: 'Tidak, batalkan!',
            }, function(isConfirm) {
                if (isConfirm) {
                    document.getElementById('kuisionerForm').submit();
                }
            });
        }
    });
</script>

@endpush
