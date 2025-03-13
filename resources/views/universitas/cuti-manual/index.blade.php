@extends('layouts.universitas')
@section('title')
PENGAJUAN CUTI
@endsection
@section('content')
<section class="content">
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Daftar Pengajuan Cuti Mahasiswa</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('univ')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Pengajuan Cuti</li>
                        <li class="breadcrumb-item active" aria-current="page">Daftar</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
@include('swal')
@include('universitas.cuti-manual.create')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border d-flex justify-content-between">
                    <div class="d-flex justify-content-start">
                        <form action="{{ route('univ.cuti-manual') }}" method="get" id="semesterForm">
                            <div class="mb-3">
                                <label for="semester_view" class="form-label">Semester</label>
                                <select
                                    class="form-select"
                                    name="semester_view"
                                    id="semester_view"
                                    onchange="document.getElementById('semesterForm').submit();"
                                >
                                    <option value="" selected disabled>-- Pilih Semester --</option>
                                    @foreach ($pilihan_semester as $p)
                                        <option value="{{$p->id_semester}}"
                                            @if ($semester_view != null)
                                            {{$semester_view == $p->id_semester ? 'selected' : ''}}
                                            @else
                                            {{$semester_aktif->id_semester == $p->id_semester ? 'selected' : ''}}
                                            @endif
                                            >{{$p->nama_semester}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="d-flex justify-content-end">
                        <div class="d-flex justify-content-end align-items-center">
                            <button class="btn btn-success waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#createModal"><i class="fa fa-plus"></i> Tambah Pengajuan Cuti</button>
                            <span class="divider-line mx-1"></span>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                
                    <div class="table-responsive mt-5">
                        <table id="data" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-start align-middle">Nama Mahasiswa</th>
                                    <th class="text-start align-middle">Program Studi</th>
                                    <th class="text-center align-middle">Semester</th>
                                    <th class="text-center align-middle">Alasan Cuti</th>
                                    <th class="text-center align-middle">No. HP</th>
                                    <th class="text-center align-middle">Nomor SK</th>
                                    <th class="text-center align-middle">Tanggal SK</th>
                                    <th class="text-center align-middle">Status</th>
                                    <th class="text-center align-middle">Alasan Ditolak</th>
                                    <th class="text-center align-middle">File Pendukung</th>
                                    <th class="text-center align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                    <tr>
                                        <td class="text-center align-middle">{{$loop->iteration}}</td>
                                        <td class="text-center align-middle">{{$d->nim}}</td>
                                        <td class="text-start align-middle">{{$d->nama_mahasiswa}}</td>
                                        <td class="text-start align-middle">{{$d->riwayat->nama_program_studi}}</td>
                                        <td class="text-center align-middle">{{$d->nama_semester}}</td>
                                        <td class="text-center align-middle">{{$d->alasan_cuti}}</td>
                                        <td class="text-center align-middle">{{$d->handphone ? $d->handphone : '-'}}</td>
                                        <td class="text-center align-middle">{{$d->no_sk ? $d->no_sk : '-'}}</td>
                                        <td class="text-center align-middle">{{$d->tanggal_sk ? date('d-m-Y', strtotime($d->tanggal_sk)) : '-'}}</td>
                                        <td class="text-center align-middle" style="width:10%">
                                            @if($d->approved == 0)
                                                <span class="badge badge-xl badge-danger mb-5">Belum Disetujui</span>
                                            @elseif($d->approved == 1)
                                                <span class="badge badge-xl badge-warning mb-5">Disetujui Fakultas</span>
                                            @elseif($d->approved == 2)
                                                <span class="badge badge-xl badge-success mb-5">Disetujui BAK</span>
                                            @elseif($d->approved == 3)
                                                <span class="badge badge-xl badge-danger mb-5">Ditolak Fakultas</span>
                                            @elseif($d->approved == 4)
                                                <span class="badge badge-xl badge-danger mb-5">Ditolak BAK</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">{{$d->alasan_pembatalan}}</td>
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
                                            <button class="btn btn-rounded bg-warning" title="Edit Data" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editBatas({{$d}}, {{$d->id}})">
                                                <i class="fa fa-pencil-square-o"><span class="path1"></span><span class="path2"></span></i>
                                            </button>
                                            <form action="{{route('univ.cuti-manual.delete',$d->id_cuti)}}" method="post" class="delete-form" data-id="{{$d->id_cuti}}" id="deleteForm{{$d->id_cuti}}">
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

    function deleteKRSManual(id) {
        swal({
            title: 'Delete Data',
            text: "Apakah anda yakin ingin menghapus data?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                document.getElementById('delete-form-' + id).submit();
                $('#spinner').show();
            }
        });
    }

    // Fetch mahasiswa data on selection
    $('#id_registrasi_mahasiswa').on('change', function() {
        var id_registrasi_mahasiswa = $(this).val();
        
        $.ajax({
            url: "{{ url('/universitas/stop-out/get-data-mahasiswa') }}/" + id_registrasi_mahasiswa,
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
