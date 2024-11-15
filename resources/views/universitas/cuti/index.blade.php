@extends('layouts.universitas')
@section('title')
Daftar Pengajuan Cuti
@endsection
@section('content')
@include('universitas.cuti.create')
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
                            <h2>Halaman Pengajuan Cuti Mahasiswa</h2>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="d-flex justify-content-start">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <h3 class="fw-500 text-dark mt-0">Daftar Pengajuan Cuti Mahasiswa</h3>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-rounded bg-success-light" data-bs-toggle="modal"
                        data-bs-target="#createModal"><i class="fa fa-plus"></i> Tambah Pengajuan Cuti</button>
                        <span class="divider-line mx-1"></span>
                    </div>
                </div>
                
                <div class="table-responsive mt-5">
                    <table id="data" class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Semester</th>
                                <th>Prodi</th>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th>Alasan Pengajuan Cuti</th>
                                <th>Status</th>
                                <th>File Pendukung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td class="text-start align-middle" style="white-space:nowrap;">{{$d->nama_semester}}</td>
                                    <td class="text-start align-middle">{{$d->prodi->nama_jenjang_pendidikan}} {{$d->prodi->nama_program_studi}}</td>
                                    <td class="text-start align-middle">{{$d->riwayat->nim}}</td>
                                    <td class="text-start align-middle">{{$d->riwayat->nama_mahasiswa}}</td>
                                    <td class="text-start align-middle">{{$d->alasan_cuti}}</td>
                                    <td class="text-center align-middle" style="width:10%">
                                        @if($d->approved == 0)
                                            <span class="badge badge-xl badge-danger-light mb-5">Belum Disetujui</span>
                                        @elseif($d->approved == 1)
                                            <span class="badge badge-xl badge-warning-light mb-5">Disetujui Fakultas</span>
                                        @elseif($d->approved == 2)
                                            <span class="badge badge-xl badge-success-light mb-5">Disetujui BAK</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle" style="width:10%">
                                        @if($d->file_pendukung)
                                            <a href="{{ asset('storage/' . $d->file_pendukung) }}" target="_blank" class="btn btn-primary" title="Lihat File">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada file</span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center align-middle" style="width:3%">
                                        <form action="{{route('univ.cuti-kuliah.delete',$d->id_cuti)}}" method="post" class="delete-form" data-id="{{$d->id_cuti}}" id="deleteForm{{$d->id_cuti}}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger" data-id="{{ $d->id_cuti }}" title="Hapus Data">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>

$(document).ready(function() {
    $("#id_registrasi_mahasiswa").select2({
        placeholder : '-- Masukan NIM / Nama Mahasiswa --',
        dropdownParent: $('#createModal'),
        width: '100%',
        minimumInputLength: 3,
        ajax: {
            url: "{{route('univ.pengaturan.akun.get-mahasiswa')}}",
            type: "GET",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: "("+item.nim+") "+item.nama_mahasiswa,
                            id: item.id_registrasi_mahasiswa
                        }
                    })
                };
            }
        }
    });

    $('#data').DataTable({
        "paging": true,      // Menampilkan pagination
        "ordering": true,    // Mengizinkan pengurutan kolom
        "searching": true    // Menambahkan kotak pencarian
    });

    // Fetch mahasiswa data on selection
    $('#id_registrasi_mahasiswa').on('change', function() {
        var id_registrasi_mahasiswa = $(this).val();
        
        $.ajax({
            url: "{{ url('/universitas/cuti-kuliah/get-data-mahasiswa') }}/" + id_registrasi_mahasiswa,
            type: "GET",
            dataType: "json",
            success: function(response) {
                var data = response.data;
                var semester_aktif = response.semester_aktif;
                
                if (data) {
                    // Mengisi data mahasiswa pada input form
                    $('#fakultas_mahasiswa').val(data.prodi.fakultas.nama_fakultas);
                    $('#jurusan_mahasiswa').val(data.prodi.jurusan.nama_jurusan_id);
                    // $('#jenjang_mahasiswa').;
                    $('#prodi_mahasiswa').val(data.prodi.nama_jenjang_pendidikan + " - " + data.prodi.nama_program_studi);
                    
                    $('#jalan').val(data.biodata.jalan);
                    $('#dusun').val(data.biodata.dusun);
                    $('#rt').val(data.biodata.rt);
                    $('#rw').val(data.biodata.rw);
                    $('#kelurahan').val(data.biodata.kelurahan);
                    $('#kode_pos').val(data.biodata.kode_pos);
                    $('#nama_wilayah').val(data.biodata.nama_wilayah);
                    $('#handphone').val(data.biodata.handphone);

                    // Mengisi data semester aktif
                    if (semester_aktif) {
                        $('#id_semester').val(semester_aktif.semester.nama_semester);
                    }
                }
            }
        });
    });
});

</script>
@endpush
