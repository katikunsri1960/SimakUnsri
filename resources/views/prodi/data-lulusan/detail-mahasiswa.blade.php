@extends('layouts.prodi')
@section('title')
Dashboard
@endsection
@section('content')
@include('swal')
<section class="content">
    <div class="row mt-5">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-body">
                    <form class="form" action="{{route('prodi.data-lulusan.detail.update', $data->id)}}" id="update-detail-wisuda" method="POST">
                        @csrf
                        <div class="d-flex justify-content-between">
                            <a href="{{route('prodi.data-lulusan.index')}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-arrow-left"></i> Kembali</a>
                            <button type="submit" class="btn btn-success btn-rounded waves-effect waves-light"><i class="fa fa-edit"></i> Update Detail Ajuan</button>
                        </div>
                        <h3 class="text-info mb-0 mt-40"><i class="fa fa-user"></i> Detail Ajuan Wisuda Mahasiswa</h3>
                        <hr class="my-15">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="mb-2">
                                        <label for="nim" class="form-label">NIM</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="nim"
                                            id="nim"
                                            aria-describedby="helpId"
                                            value="{{$data->nim}}"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="mb-2">
                                        <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="nama_mahasiswa"
                                            id="nama_mahasiswa"
                                            aria-describedby="helpId"
                                            value="{{$data->nama_mahasiswa}}"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="mb-2">
                                        <label for="semester" class="form-label">Angkatan</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="semester"
                                            id="semester"
                                            aria-describedby="helpId"
                                            value="{{$data->angkatan}}"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="mb-2">
                                        <label for="semester" class="form-label">Tanggal Daftar</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="semester"
                                            id="semester"
                                            aria-describedby="helpId"
                                            value="{{$data->riwayat_pendidikan->tanggal_daftar}}"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="mb-2">
                                    <label for="nilai_usept" class="form-label">Nilai Usept</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="nilai_usept"
                                        id="nilai_usept"
                                        aria-describedby="helpId"
                                        value="{{ 
                                            $nilai_usept > 0 
                                            ? $nilai_usept . ' - ' . ($status_usept ? 'Lulus USEPT' : 'Belum Lulus USEPT') 
                                            : 'Belum Ada Nilai'
                                        }}"
                                        disabled
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-2">
                                    <label for="judul" class="form-label">Judul (Indonesia)</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="judul"
                                        id="judul"
                                        aria-describedby="helpId"
                                        value="{{$data->aktivitas_mahasiswa->judul}}"
                                        disabled
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-2">
                                    <label for="judul_en" class="form-label">Judul (Inggris) <strong class="text-danger">*</strong></label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="judul_en"
                                        id="judul_en"
                                        aria-describedby="helpId"
                                        value="{{$data->judul_eng}}"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-2">
                                    <label for="jenis_aktivitas" class="form-label">Jadwal Sidang</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="jenis_aktivitas"
                                        id="jenis_aktivitas"
                                        aria-describedby="helpId"
                                        value="{{$data->aktivitas_mahasiswa->jadwal_ujian}}"
                                        disabled
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                             <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="mb-2">
                                        <label for="abstrak" class="form-label">Abstrak</label>
                                        <textarea
                                            class="form-control"
                                            name="abstrak"
                                            id="abstrak"
                                            disabled
                                        >{{$data->abstrak_ta}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                             <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="mb-2">
                                        <label for="predikat_mhs" class="form-label">BKU / Peminatan</label>
                                        <select class="form-select" name="predikat_mhs" id="predikat_mhs">
                                            <option value="0" {{ $data->id_bku_prodi == 0 ? 'selected' : '' }}>Tidak BKU / Peminatan</option>
                                            @foreach($bku as $b)
                                                <option value="{{ $b->id }}" {{ $data->id_bku_prodi == $b->id ? 'selected' : '' }}>
                                                    {{ $b->bku_prodi_id }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        @if($data->prodi->bku_pada_ijazah == 1 )
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="mb-2">
                                            <label for="bku_mhs" class="form-label">Bidang Kajian Umum / Peminatan <strong class="text-danger">*</strong></label>
                                            <select class="form-select" name="bku_mhs" id="bku_mhs">
                                                @foreach($bku as $b)
                                                    <option value="{{ $b->id }}" {{ $data->id_bku_prodi == $b->id ? 'selected' : '' }}>
                                                        {{ strtoupper($b->bku_prodi_id) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($data->prodi->peminatan_pada_transkrip == 1 )
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="mb-2">
                                            <label for="bku_mhs" class="form-label">Bidang Kajian Umum / Peminatan <strong class="text-danger">*</strong> </label>
                                            <select class="form-select" name="bku_mhs" id="bku_mhs">
                                                @foreach($bku as $b)
                                                    <option value="{{ $b->id }}" {{ $data->id_bku_prodi == $b->id ? 'selected' : '' }}>
                                                        {{ strtoupper($b->bku_prodi_id) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                    <hr class="my-15">
                    <h4>
                        Status Eligible : 
                        @if($data->jumlah_sks != 1)
                            <span class="badge badge-lg badge-danger">SKS tidak memenuhi syarat minimal SKS kelulusan</span>
                        @elseif($data->status_ipk != 1)
                            <span class="badge badge-lg badge-danger">IPK pada transkrip nilai tidak memenuhi syarat minimum kelulusan</span>
                        @elseif($data->status_masa_studi != 1)
                            <span class="badge badge-lg badge-danger">Masa studi melebihi syarat kelulusan</span>
                        @elseif ($data->status_semester_pendek != 1)
                            <span class="badge badge-lg badge-danger">Jumlah SKS pada semester pendek melebihi batas maksimum yang di izinkan.</span>       
                        @elseif ($data->sks_transkrip_akm != 1)
                            <span class="badge badge-lg badge-danger">SKS pada transkrip nilai tidak sama dengan sks pada aktivitas kuliah mahasiswa</span>          
                        @elseif ($data->ipk_transkrip_akm != 1)
                            <span class="badge badge-lg badge-danger">IPK pada transkrip nilai tidak sama dengan IPK pada aktivitas kuliah mahasiswa</span>
                        @else
                            <span class="badge badge-lg badge-success">Eligible</span>
                        @endif
                    <h4>
                    <hr class="my-15">
                    <h4>
                        Transkrip Mahasiswa
                    <h4>
                    <div class="row mt-10">
                        <div class="table-responsive">
                            <table id="dt" class="table table-bordered table-striped text-center text-uppercase" style="font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th>Kode Mata Kuliah</th>
                                        <th>Nama Mata Kuliah</th>
                                        <th>SKS Mata Kuliah</th>
                                        <th>Nilai Indeks</th>
                                        <th>Nilai Huruf</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($data->transkrip_mahasiswa)
                                        @foreach ($data->transkrip_mahasiswa as $t)
                                            <tr>
                                                <td>{{$t->kode_mata_kuliah}}</td>
                                                <td class="text-start">{{$t->nama_mata_kuliah}}</td>
                                                <td>{{$t->sks_mata_kuliah}}</td>
                                                <td>{{$t->nilai_indeks}}</td>
                                                <td>{{$t->nilai_huruf}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">TIDAK ADA DATA</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-start align-middle" colspan="2"><strong>JUMLAH</strong></td>
                                        <td class="text-center align-middle"><strong>{{ $data->transkrip_mahasiswa_sum_sks_mata_kuliah }}</strong></td>
                                        <td class="text-center align-middle" colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start align-middle" colspan="2"><strong>INDEKS PRESTASI KUMULATIF</strong></td>
                                        <td class="text-center align-middle"><strong>{{ number_format($data->ipk,2) }}</strong></td>
                                        <td class="text-center align-middle" colspan="3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <h4>
                        Aktivitas Kuliah Mahasiswa
                    <h4>
                    <div class="row mt-10">
                        <div class="table-responsive">
                            <table id="dt" class="table table-bordered table-striped text-center" style="font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th>Semester</th>
                                        <th>SKS Semester</th>
                                        <th>SKS Total</th>
                                        <th>IP Semester</th>
                                        <th>IP Kumulatif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($data->aktivitas_kuliah)
                                        @foreach ($data->aktivitas_kuliah as $akm)
                                            <tr>
                                                <td>{{$akm->nama_semester}}</td>
                                                <td>{{$akm->sks_semester}}</td>
                                                <td>{{$akm->sks_total}}</td>
                                                <td>{{$akm->ips}}</td>
                                                <td>{{number_format($akm->ipk,2)}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">TIDAK ADA DATA</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="box-footer text-end">
                    <form action="{{route('prodi.data-lulusan.approved', $data->id)}}"
                        method="post" id="approve-ajuan-wisuda">
                        @csrf
                        <!-- Checkbox Agreement -->
                        <div class="row">
                            <div class="form-group">
                                <input type="checkbox" id="agreement" name="agreement" value="1">
                                <label for="agreement"><p style="font-size: 14px;">Dengan ini saya menyatakan bahwa data yang terlampir adalah valid dan mahasiswa sudah menyelesaikan seluruh persyaratan dari program studi.</p></label>
                            </div>
                        </div>
                        <div class="row mt-15">
                            <p id="error-message" style="color: red; display: none; font-size: 14px;">Checkbox wajib dicentang.</p>
                        </div>
                        <hr class="my-15">
                        <a type="button" href="{{route('prodi.data-lulusan.index')}}" class="btn btn-danger waves-effect waves-light">
                            Batal
                        </a>
                        @if ($data->ipk_transkrip_akm != 1 || $data->status_ipk != 1 || $data->jumlah_sks != 1 || $data->sks_transkrip_akm != 1 || $data->approved == 1 || $data->approved == 2 || $data->approved == 3)
                            <button type="submit" class="btn btn-primary waves-effect waves-light" disabled>Approve Ajuan</button>
                        @else
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Approve Ajuan</button>
                        @endif
                    </form>
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

    $(document).ready(function () {
        $('#approve-ajuan-wisuda').submit(function (e) {
            e.preventDefault(); // Prevent form submission

            let checkbox = document.getElementById("agreement");
            let errorMessage = document.getElementById("error-message");

            if (!checkbox.checked) {
                errorMessage.style.display = "block"; // Show error message
                return; // Stop form submission
            } else {
                errorMessage.style.display = "none"; // Hide error message
            }

            // SweetAlert v1 Confirmation
            swal({
                title: 'Persetujuan Ajuan Wisuda Mahasiswa',
                text: "Apakah anda yakin ingin melanjutkan?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal',
                closeOnConfirm: false
            }, function (isConfirmed) {
                if (isConfirmed) {
                    $('#approve-ajuan-wisuda').off('submit').submit();
                    $('#spinner').show(); // Show spinner (if applicable)
                }
            });
        });

        $('#update-detail-wisuda').submit(function (e) {
            e.preventDefault(); // Prevent form submission

            swal({
                title: 'Perubahan Data Detail Ajuan Wisuda Mahasiswa',
                text: "Apakah anda yakin ingin melanjutkan?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal',
                closeOnConfirm: false
            }, function (isConfirmed) {
                if (isConfirmed) {
                    $('#update-detail-wisuda').off('submit').submit();
                    $('#spinner').show(); // Show spinner (if applicable)
                }
            });
        });
    });


</script>
@endpush
