@extends('layouts.dosen')
@section('title')
Catatan Perbaikan Mahasiswa
@endsection
@section('content')
@include('swal')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <form class="form" action="{{route('dosen.penilaian.sidang-mahasiswa.detail-sidang.revisi.store', ['aktivitas' => $data->id])}}" id="revisi-sidang" method="POST">
                    @csrf
                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Detail Sidang Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mahasiswa"
                                    id="nama_mahasiswa"
                                    aria-describedby="helpId"
                                    value="{{$data->anggota_aktivitas_personal->nama_mahasiswa}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nim" class="form-label">NIM</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nim"
                                    id="nim"
                                    aria-describedby="helpId"
                                    value="{{$data->anggota_aktivitas_personal->nim}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="angkatan" class="form-label">Angkatan</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="angkatan"
                                    id="angkatan"
                                    aria-describedby="helpId"
                                    value="{{substr($data->anggota_aktivitas_personal->mahasiswa->id_periode_masuk,0,4)}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="judul" class="form-label">Judul</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="judul"
                                    id="judul"
                                    aria-describedby="helpId"
                                    value="{{$data->judul}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <h4 class="text-info mb-0"><i class="fa fa-user"></i> Catatan Perbaikan Mahasiswa</h4>
                        <hr class="my-15">
                        <div class="row">
                            <div class="form-group">
                                <label class="form-label">Tanggal Batas Perbaikan</label>
                                <div class="row">
                                    <div class="col-sm-4 mb-2">
                                        <select class="form-select" name="tanggal_batas_perbaikan" id="tanggal_batas_perbaikan" required>
                                            <option value="">Tanggal</option>
                                            @for($i=1;$i <= 31;$i++)
                                                @php
                                                    $based_num = 0;
                                                    $num = $based_num.$i;
                                                @endphp
                                                <option value="{{$i < 10 ? $num : $i}}">{{$i < 10 ? $num : $i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-sm-4 mb-2">
                                        <select class="form-select" name="bulan_batas_perbaikan" id="bulan_batas_perbaikan" required>
                                            <option value="">Bulan</option>
                                            @for($i=1;$i <= 12;$i++)
                                                @php
                                                    $based_num = 0;
                                                    $num = $based_num.$i;
                                                @endphp
                                                <option value="{{$i < 10 ? $num : $i}}">{{$i < 10 ? $num : $i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-sm-4 mb-2">
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="tahun_batas_perbaikan"
                                            id="tahun_batas_perbaikan"
                                            aria-describedby="helpId"
                                            placeholder="Tahun"
                                            value="{{date('Y')}}"
                                            disabled
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div id="revisi-fields">
                                <div class="revisi-field row">
                                    <div class="col-md-11 mb-2">
                                        <textarea class="form-control" rows="5" name="revisi[]" placeholder="Isi Perbaikan Mahasiswa" required></textarea>
                                    </div>
                                    <div class="col-md-1 mb-2">
                                        <button type="button" class="btn btn-danger btn-rounded btn-sm remove-revisi" title="Hapus Perbaikan" style="display : none;"><i class="fa fa-user-times" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                            <button id="add-notulen" type="button" class="btn btn-primary" title="Tambah Notulensi"><i class="fa fa-plus" aria-hidden="true"></i> Tambah Perbaikan</button>
                        </div>
                    </div>
                    <div class="box-footer text-end">
                        <a type="button" href="{{route('dosen.penilaian.sidang-mahasiswa.detail-sidang', ['aktivitas' => $data->id])}}" class="btn btn-danger btn-rounded waves-effect waves-light">
                        <i class="fa fa-sign-out"></i> Keluar
                        </a>
                        <button type="submit" id="submit-button" class="btn btn-primary btn-rounded waves-effect waves-light"><i class="fa fa-hdd-o"></i> Simpan</button>
                        @if($count_revisi > 0)
                            <a href="{{route('dosen.penilaian.sidang-mahasiswa.detail-sidang.penilaian-sidang', $data->id)}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-play"></i> Selanjutnya</a>
                        @else
                            <button type="submit" id="submit-button" class="btn btn-warning btn-rounded waves-effect waves-light" disabled><i class="fa fa-play"></i> Selanjutnya</button>
                        @endif
                        {{--@if($data->jadwal_ujian == date('Y-m-d') && $count_revisi > 0)
                            <a href="{{route('dosen.penilaian.sidang-mahasiswa.detail-sidang.penilaian-sidang', $data->id)}}" class="btn btn-warning btn-rounded waves-effect waves-light"><i class="fa fa-play"></i> Selanjutnya</a>
                        @else
                            <button type="submit" id="submit-button" class="btn btn-warning btn-rounded waves-effect waves-light" disabled><i class="fa fa-play"></i> Selanjutnya</button>
                        @endif--}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
    $(document).ready(function(){

        // Add notulen row
        $('#add-notulen').click(function() {
            var newRow = $('<div class="revisi-field row">' +
                '<div class="col-md-11 mb-2">' +
                '<textarea class="form-control" rows="5" name="revisi[]" placeholder="Isi Perbaikan Mahasiswa" required></textarea>' +
                '</div>' +
                '<div class="col-md-1 mb-2">' +
                '<button type="button" class="btn btn-danger btn-rounded btn-sm remove-revisi" title="Hapus Perbaikan"><i class="fa fa-user-times" aria-hidden="true"></i></button>' +
                '</div>' +
                '</div>');
            newRow.appendTo('#revisi-fields');
        });

        // Remove notulen row
        $(document).on('click', '.remove-revisi', function() {
            $(this).closest('.revisi-field').remove();
        });

        // Form submission with SweetAlert confirmation
        $('#revisi-sidang').submit(function(e){
            e.preventDefault();
            swal({
                title: 'Revisi Sidang Mahasiswa',
                text: "Apakah anda yakin ingin melanjutkan?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal'
            },function(isConfirmed){
                if (isConfirmed) {
                    $('#revisi-sidang').unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });
    });
</script>

@endpush
