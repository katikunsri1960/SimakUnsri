@extends('layouts.dppti')
@section('title')
Data Dosen AIPT
@endsection
@section('content')
<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h3 class="page-title">Data Dosen AIPT</h3>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dppti')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Monitoring</li>
                        <li class="breadcrumb-item" aria-current="page">Data AIPT</li>
                        <li class="breadcrumb-item active" aria-current="page">Dosen</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
@include('swal')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
                    <div class="d-flex justify-content-start">
                        <button type="button"
                            class="btn btn-success waves-effect waves-light"
                            data-bs-toggle="modal"
                            data-bs-target="#filter-button">
                            <i class="fa fa-filter"></i> Filter
                        </button>

                        @include('dppti.monitoring.status-aipt.dosen.filter')

                        <span class="divider-line mx-1"></span>

                        <a href="{{route('dppti.monitoring.status-aipt.dosen')}}"
                        class="btn btn-warning waves-effect waves-light">
                            <i class="fa fa-refresh"></i> Reset Filter
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">NO</th>
                                    <th class="text-center align-middle">PROGRAM STUDI<br>HOMEBASE</th>
                                    <th class="text-center align-middle">NAMA</th>
                                    <th class="text-center align-middle">GELAR DEPAN</th>
                                    <th class="text-center align-middle">GELAR BELAKANG</th>
                                    <th class="text-center align-middle">NIDK/NIDN</th>
                                    <th class="text-center align-middle">NUPTK</th>
                                    <th class="text-center align-middle">NIP</th>
                                    <th class="text-center align-middle">E-MAIL</th>

                                    <th class="text-center align-middle">JABATAN<br>FUNGSIONAL</th>
                                    <th class="text-center align-middle">STATUS<br>PEGAWAI</th>
                                    <th class="text-center align-middle">TANGGAL MULAI</th>

                                    <th class="text-center align-middle">JENJANG<br>PENDIDIKAN DOSEN</th>
                                    <th class="text-center align-middle">PERGURUAN TINGGI</th>
                                    <th class="text-center align-middle">TAHUN LULUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp

                                @foreach ($data as $d)
                                    @php
                                        $gelar = $d->gelar;

                                        $gelarDepan = $gelar
                                            ? collect([
                                                $gelar->gelar_depan_s3,
                                                $gelar->gelar_depan_s2,
                                                $gelar->gelar_depan_s1,
                                            ])->filter()->implode(' ')
                                            : null;

                                        $gelarBelakang = $gelar
                                            ? collect([
                                                $gelar->gelar_belakang_s1,
                                                $gelar->gelar_belakang_s2,
                                                $gelar->gelar_belakang_s3,
                                            ])->filter()->implode(' ')
                                            : null;
                                    @endphp

                                    @forelse ($d->jabatan_fungsional as $jabatan)

                                        @php
                                            $tahunMulaiJabatan = $jabatan->tanggal_mulai
                                                ? \Carbon\Carbon::parse($jabatan->tanggal_mulai)->year
                                                : null;

                                            $pendidikanTerakhir = $d->riwayat_pendidikan
                                                ->filter(function ($pendidikan) use ($tahunMulaiJabatan) {
                                                    if (!$tahunMulaiJabatan || !$pendidikan->tahun_lulus) {
                                                        return false;
                                                    }

                                                    return (int) $pendidikan->tahun_lulus <= $tahunMulaiJabatan;
                                                })
                                                ->sortByDesc('tahun_lulus')
                                                ->first();
                                        @endphp

                                        <tr>
                                            {{-- NO --}}
                                            <td class="text-center">
                                                {{ $no++ }}
                                            </td>

                                            {{-- PROGRAM STUDI HOMEBASE --}}
                                            <td>
                                                {{ optional($d->penugasan_terbaru)->a_sp_homebase ?? '-' }}
                                            </td>

                                            {{-- NAMA --}}
                                            <td>
                                                {{ $d->nama_dosen }}
                                            </td>

                                            {{-- GELAR DEPAN --}}
                                            <td>
                                                {{ $gelarDepan ?? '-' }}
                                            </td>

                                            {{-- GELAR BELAKANG --}}
                                            <td>
                                                {{ $gelarBelakang ?? '-' }}
                                            </td>

                                            {{-- NIDN --}}
                                            <td>
                                                {{ $d->nidn ?? '-' }}
                                            </td>

                                            {{-- NUPTK --}}
                                            <td>
                                                {{ $d->nuptk ?? '-' }}
                                            </td>

                                            {{-- NIP --}}
                                            <td>
                                                {{ $d->nip ?? '-' }}
                                            </td>

                                            {{-- E-MAIL --}}
                                            <td>
                                                {{ $d->email ?? '-' }}
                                            </td>

                                            {{-- JABATAN FUNGSIONAL --}}
                                            <td>
                                                {{ $jabatan->jabatan_fungsional ?? '-' }}
                                            </td>

                                            {{-- STATUS PEGAWAI --}}
                                            <td>
                                                {{ $jabatan->nm_stat_pegawai ?? '-' }}
                                            </td>

                                            {{-- TANGGAL MULAI --}}
                                            <td>
                                                {{ $jabatan->tanggal_mulai
                                                    ? \Carbon\Carbon::parse($jabatan->tanggal_mulai)->format('d-m-Y')
                                                    : '-' }}
                                            </td>

                                            {{-- JENJANG PENDIDIKAN --}}
                                            <td>
                                                {{ $pendidikanTerakhir->nama_jenjang_pendidikan ?? '-' }}
                                            </td>

                                            {{-- PERGURUAN TINGGI --}}
                                            <td>
                                                {{ $pendidikanTerakhir->nama_perguruan_tinggi ?? '-' }}
                                            </td>

                                            {{-- TAHUN LULUS --}}
                                            <td>
                                                {{ $pendidikanTerakhir->tahun_lulus ?? '-' }}
                                            </td>
                                        </tr>

                                    @empty

                                        {{-- Jika dosen tidak mempunyai jabatan fungsional --}}
                                        <tr>
                                            <td class="text-center">
                                                {{ $no++ }}
                                            </td>

                                            <td>
                                                {{ optional($d->penugasan_terbaru)->a_sp_homebase ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $d->nama_dosen }}
                                            </td>

                                            <td>
                                                {{ $gelarDepan ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $gelarBelakang ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $d->nidn ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $d->nuptk ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $d->nip ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $d->email ?? '-' }}
                                            </td>

                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                        </tr>

                                    @endforelse
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
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.full.min.js')}}"></script>

<script>
    $(function() {
        //"use strict";

        $('#id_prodi, #jenjang_pendidikan, #jabatan_fungsional').select2({
            dropdownParent: $('#filter-button'),
            width: '100%'
        });

        $('#data').DataTable({
            paging: false,
            ordering: true,
            searching: true,

            dom: 'Bfrtip',

            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                    title: 'Data Dosen AIPT',
                    filename: 'Data_Dosen_AIPT',
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            body: function (data, row, column, node) {
                                return $(node)
                                    .html()
                                    .replace(/<hr[^>]*>/gi, '\n')
                                    .replace(/<br\s*\/?>/gi, '\n')
                                    .replace(/<[^>]+>/g, '')
                                    .replace(/&nbsp;/g, ' ')
                                    .trim();
                            }
                        }
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o"></i> CSV',
                    title: 'Data Dosen AIPT',
                    filename: 'Data_Dosen_AIPT',
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            body: function (data, row, column, node) {
                                return $(node)
                                    .html()
                                    .replace(/<hr[^>]*>/gi, '\n')
                                    .replace(/<br\s*\/?>/gi, '\n')
                                    .replace(/<[^>]+>/g, '')
                                    .replace(/&nbsp;/g, ' ')
                                    .trim();
                            }
                        }
                    }
                }
            ]
        });
    });

    $('#editForm').submit(function(e){
        e.preventDefault();
        swal({
            title: 'Simpan Data',
            text: "Apakah anda yakin ingin menyimpan data?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#editForm').unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });

    function edit(data) {
        document.getElementById('editForm').reset();

        document.getElementById('id_dosen').value = data.id_dosen;
        document.getElementById('nama').value = data.nama_dosen ?? '';

        const gelar = data.gelar ?? {};

        document.getElementById('gelar_depan_s1').value = gelar.gelar_depan_s1 ?? '';
        document.getElementById('gelar_depan_s2').value = gelar.gelar_depan_s2 ?? '';
        document.getElementById('gelar_depan_s3').value = gelar.gelar_depan_s3 ?? '';

        document.getElementById('gelar_belakang_s1').value = gelar.gelar_belakang_s1 ?? '';
        document.getElementById('gelar_belakang_s2').value = gelar.gelar_belakang_s2 ?? '';
        document.getElementById('gelar_belakang_s3').value = gelar.gelar_belakang_s3 ?? '';
    }

</script>

@endpush
