@extends('layouts.fakultas')
@section('title')
Edit Kelas Perkuliahan
@endsection
@section('content')
@include('swal')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Edit Kelas Perkuliahan</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('prodi')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Akademik</li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('fakultas.data-akademik.kelas-penjadwalan')}}">Kelas dan Penjadwalan</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('fakultas.data-akademik.kelas-penjadwalan.detail', ['id_matkul' => $kelas->id_matkul])}}">Detail Kelas dan Penjadwalan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Kelas Perkuliahan</li>
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
                <form class="form" action="{{route('fakultas.data-akademik.kelas-penjadwalan.update', ['id_matkul' => $kelas->id_matkul, 'id_kelas' => $kelas->id_kelas_kuliah])}}" id="edit-kelas" method="POST">
                    @csrf
                    <div class="box-body">
                        <h4 class="text-info mb-0"><i class="fa fa-university"></i> Detail Kelas Kuliah</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nama_mata_kuliah" class="form-label">Nama Mata Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mata_kuliah"
                                    id="nama_mata_kuliah"
                                    aria-describedby="helpId"
                                    value="{{$matkul->kode_mata_kuliah.' - '.$matkul->nama_mata_kuliah}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-lg-12 mb-3">
                                <label for="nama_mata_kuliah" class="form-label">Nama Kelas Kuliah</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_mata_kuliah"
                                    id="nama_mata_kuliah"
                                    aria-describedby="helpId"
                                    value="{{$kelas->nama_kelas_kuliah}}"
                                    disabled
                                    required
                                />
                            </div>
                        </div>
                        
                        <h4 class="text-info mb-0 mt-40"><i class="fa fa-calendar-o"></i> Lokasi Dan Jadwal Ujian</h4>
                        <hr class="my-15">
                        <div class="form-group">
                            <div class="mb-4">
                                <label for="lokasi_ujian_id" class="form-label">Ruang Kelas Ujian</label>
                                <select class="form-select" name="lokasi_ujian_id" id="lokasi_ujian_id" required>
                                    <option value="">-- Pilih Ruang Kelas --</option>
                                    @foreach($ruang as $r)
                                        <option value="{{$r->id}}" {{ $kelas->lokasi_ujian_id == $r->id ? 'selected' : '' }}>
                                            {{$r->nama_ruang}} ( {{strtoupper($r->lokasi)}} )
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        
                        <!-- Input untuk Tanggal dan Waktu -->
                        <div class="form-group">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="jadwal_mulai_ujian" class="form-label">Jadwal Mulai Ujian</label>
                                    <input class="form-control" type="datetime-local" name="jadwal_mulai_ujian" 
                                           value="{{ isset($kelas->jadwal_mulai_ujian) ? $kelas->jadwal_mulai_ujian : '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="jadwal_selesai_ujian" class="form-label">Jadwal Selesai Ujian</label>
                                    <input class="form-control" type="datetime-local" name="jadwal_selesai_ujian" 
                                           value="{{ isset($kelas->jadwal_selesai_ujian) ? $kelas->jadwal_selesai_ujian : '' }}" required>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class="box-footer">
                        <a type="button" href="{{route('fakultas.data-akademik.kelas-penjadwalan.detail', ['id_matkul' => $kelas->id_matkul])}}" class="btn btn-danger waves-effect waves-light">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
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
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/js/flatpickr/flatpickr.js')}}"></script>

<script>
    $(document).ready(function () {
        $('#lokasi_ujian_id').select2({
            placeholder: "-- Pilih Ruang Kelas --", // Placeholder saat tidak ada yang dipilih
            allowClear: true, // Menambahkan tombol hapus
            width: '100%', // Mengatur lebar agar menyesuaikan
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

    $(document).ready(function () {
        // Tambahkan Datepicker
        $('#datetimepicker').datetimepicker({
            dateFormat: 'DD, dd MM yy', // Format: Hari, Tanggal Bulan Tahun
            timeFormat: 'HH:mm',       // Format: Jam:Menit (24 Jam)
            showSecond: false,         // Tidak tampil detik
            controlType: 'select',     // Gunakan dropdown untuk jam dan menit
            stepMinute: 1,             // Interval menit
            hourGrid: 4,               // Grid pada selector jam (opsional)
            minuteGrid: 10,            // Grid pada selector menit (opsional)
            showButtonPanel: true,     // Menampilkan panel navigasi untuk mempermudah
        });
    });


</script>
@endpush
