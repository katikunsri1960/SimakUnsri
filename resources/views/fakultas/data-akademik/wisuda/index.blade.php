@extends('layouts.fakultas')
@section('title')
Pendaftaran Wisuda Fakultas
@endsection
@section('content')
@include('swal')
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-12">
			<div class="box pull-up">
				<div class="box-body bg-img bg-primary-light">
					<div class="d-lg-flex align-items-center justify-content-between">
						<div class="d-lg-flex align-items-center mb-30 mb-xl-0 w-p100">
			    			<img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" class="img-fluid max-w-250" alt="" />
							<div class="ms-30">
								<h2 class="mb-10">Pendaftaran Wisuda Fakultas</h2>
								<p class="mb-0 text-fade fs-18">Universitas Sriwijaya</p>
							</div>
						</div>
					<div>
				</div>
			</div>							
		</div>
    </div>
    <div class="row">
        <div class="col-xxl-12">
            <div class="box box-body mb-0">
                <div class="row">
                    <div class="col-xl-6 col-lg-12">
                        <h3 class="fw-500 text-dark mt-0">Daftar Peserta Wisuda Fakultas</h3>
                    </div>                             
                </div>
                {{-- <div class="row mb-5">
                    <div class="col-xl-12 col-lg-12 text-end">
                        <div class="btn-group">
                            <a class="btn btn-rounded bg-success-light " href="{{route('fakultas.pengajuan-cuti.tambah')}}"><i class="fa fa-plus"><span class="path1"></span><span class="path2"></span></i> Tambah Pendaftaran Wisuda</a>
                        </div>   
                    </div>                           
                </div><br> --}}
                <div class="row">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">NO</th>
                                    <th class="text-center align-middle">PERIODE</th>
                                    <th class="text-center align-middle">STATUS</th>
                                    <th class="text-center align-middle">IJAZAH TERAKHIR</th>
                                    <th class="text-center align-middle">FOTO</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">NAMA</th>
                                    <th class="text-center align-middle">PROGRAM STUDI</th>
                                    <th class="text-center align-middle">BEBAS PUSTAKA</th>
                                    <th class="text-center align-middle">REPOSITORY</th>
                                    <th class="text-center align-middle">SKOR USEPT</th>
                                    <th class="text-center align-middle">ABSTRAK</th>
                                    <th class="text-center align-middle text-nowrap">SK YUDISIUM<br>(TGL YUDISIUM)</th>
                                    <th class="text-center align-middle">LAMA STUDI</th>
                                    <th class="text-center align-middle">SK YUDISIUM</th>
                                    <th class="text-center align-middle">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                @include('fakultas.data-akademik.wisuda.upload-sk')
                                @include('fakultas.data-akademik.wisuda.edit-sk')
                                @include('fakultas.data-akademik.wisuda.decline-wisuda')
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td class="text-center align-middle">{{$d->wisuda_ke}}</td>
                                        <td class="text-center align-middle" style="width:10%">
                                            @if($d->approved == 0)
                                                <span class="badge rounded badge-warning" style="padding: 8px">Belum Disetujui Koor. Prodi</span>
                                            @elseif($d->approved == 1)
                                                <span class="badge rounded badge-primary" style="padding: 8px">Disetujui Koor. Prodi</span>
                                            @elseif($d->approved == 2)
                                                <span class="badge rounded badge-primary" style="padding: 8px">Disetujui Fakultas</span>
                                            @elseif($d->approved == 3)
                                                <span class="badge rounded badge-success" style="padding: 8px">Disetujui BAK</span>
                                            @elseif($d->approved == 97)
                                                <span class="badge rounded badge-danger" style="padding: 8px">Ditolak Koor. Prodi</span>
                                            @elseif($d->approved == 98)
                                                <span class="badge rounded badge-danger" style="padding: 8px">Ditolak Fakultas</span>
                                            @elseif($d->approved == 99)
                                                <span class="badge rounded badge-danger" style="padding: 8px">Ditolak BAK</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="{{ $d->ijazah_terakhir_file? asset($d->ijazah_terakhir_file) : '#' }}" 
                                               target="{{ $d->ijazah_terakhir_file? '_blank' : '_self' }}" 
                                               class="btn btn-sm {{ $d->ijazah_terakhir_file? 'btn-success' : 'btn-warning' }} my-2">
                                                <i class="fa {{ $d->ijazah_terakhir_file? 'fa-file me-2' : 'fa-exclamation-circle' }}"></i> 
                                                {{ $d->ijazah_terakhir_file? 'Lihat Ijazah' : 'Belum Upload Ijazah' }}
                                            </a>
                                        </td>
                                        <td class="text-center align-middle text-nowrap">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#fotoModal{{$d->id}}">
                                                <img src="{{ asset($d->pas_foto) }}" alt="Pas Foto" style="width: 150px;" title="Lihat Foto">
                                            </a>
                                            <!-- Modal -->
                                            <div class="modal fade" id="fotoModal{{$d->id}}" tabindex="-1" aria-labelledby="fotoModalLabel{{$d->id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content rounded-3">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="fotoModalLabel{{$d->id}}">FOTO {{$d->nama_mahasiswa}}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center m-20">
                                                            <img src="{{ asset($d->pas_foto) }}" alt="Foto" style="width: 100%; max-width: 500px;" class="rounded-3">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">{{$d->nim}}</td>
                                        <td class="text-start align-middle">{{$d->nama_mahasiswa}}</td>
                                        <td class="text-start align-middle">{{$d->prodi->nama_jenjang_pendidikan}} - {{$d->prodi->nama_program_studi}}</td>
                                        <td class="text-center align-middle text-nowrap">
                                            @if(!$d->bebas_pustaka)
                                                <span class="badge rounded bg-danger" style="padding: 8px">Belum Bebas Pustaka</span>
                                            @else
                                                <a class="btn btn-sm btn-success" href="{{ asset('storage') }}/{{$d->bebas_pustaka->file_bebas_pustaka}}" type="button" target="_blank">Lihat Bebas Pustaka</a>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle text-nowrap">
                                            @if(!$d->bebas_pustaka)
                                                <span class="badge rounded bg-danger" style="padding: 8px">Belum Upload Repositroy</span>
                                            @else
                                                <a class="btn btn-sm btn-success" href="{{$d->bebas_pustaka->link_repo}}" type="button" target="_blank">Lihat Repository</a>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle text-nowrap">
                                            @if(!$d->riwayat_pendidikan->id_kurikulum)
                                                <span class="badge rounded bg-warning" style="padding: 8px">Kurikulum Belum Diatur</span>
                                            @elseif(isset($d->useptData['class']) && $d->useptData['class'] == "danger")
                                                {{ isset($d->useptData['score']) ? $d->useptData['score'] : 0 }}<br>
                                                <span class="badge rounded bg-danger" style="padding: 8px">  
                                                    ({{$d->useptData['status'] ?? 'N/A'}})
                                                </span>
                                            @endif
                                        </td> 
                                        <td class="text-center align-middle text-nowrap">
                                            @if(!$d->abstrak_file)
                                                <span class="badge rounded bg-warning" style="padding: 8px"><i class="fa fa-exclamation-circle me-1"></i>Belum Upload Abstak</span>
                                            @else
                                                <a href="{{ asset($d->abstrak_file) }}" target="_blank" class="btn btn-sm btn-success my-2">
                                                    <i class="fa fa-file me-2"></i> Lihat Abstrak
                                                </a>
                                            @endif
                                        </td> 
                                        <td class="text-center align-middle text-nowrap">
                                            @if ($d->no_sk_yudisium && $d->tgl_sk_yudisium)
                                                {{$d->no_sk_yudisium}}<br>( {{ \Carbon\Carbon::parse($d->tgl_sk_yudisium)->translatedFormat('d F Y') }} )
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-start align-middle text-nowrap">
                                            @if ($d->tgl_sk_yudisium)
                                                @php
                                                    $years = floor($d->lama_studi / 12);
                                                    $months = $d->lama_studi % 12;
                                                @endphp
                                                {{$years}} tahun {{$months}} bulan
                                            @else
                                                <span class="badge rounded bg-danger" style="padding: 8px">Tanggal SK Yudisium Belum Diisi</span>
                                            @endif
                                        </td>
                                        {{-- <td class="text-center align-middle">
                                            <a href="{{ $d->sk_yudisium_file ? asset($d->sk_yudisium_file) : '#' }}" 
                                               target="{{ $d->sk_yudisium_file ? '_blank' : '_self' }}" 
                                               class="btn btn-sm {{ $d->sk_yudisium_file ? 'btn-success' : 'btn-warning' }} my-2">
                                                <i class="fa {{ $d->sk_yudisium_file ? 'fa-file me-2' : 'fa-exclamation-circle' }}"></i> 
                                                {{ $d->sk_yudisium_file ? 'Lihat SK Yudisium' : 'Belum Upload SK Yudisium' }}
                                            </a>
                                        </td> --}}
                                        <td class="text-center align-middle">
                                            @if (is_null($d->sk_yudisium_file))
                                                <a href="#" class="btn btn-warning btn-sm my-2 
                                                " title="Upload SK Yudisium" data-bs-toggle="modal" data-bs-target="#uploadModal{{$d->id}}">
                                                    <i class="fa fa-upload"></i> Pilih SK Yudisium
                                                </a>
                                            @else
                                                <a href="{{ asset($d->sk_yudisium_file) }}" target="_blank" class="btn btn-success btn-sm my-2">
                                                    <i class="fa fa-file"></i> Lihat
                                                </a>

                                                <a href="#" class="btn btn-warning btn-sm my-2" data-bs-toggle="modal" data-bs-target="#editModal{{$d->id}}">
                                                    <i class="fa fa-edit"></i> Ubah
                                                </a>

                                                <button type="button" class="btn btn-danger btn-sm my-2 btn-hapus-sk" data-id="{{ $d->id }}">
                                                    <i class="fa fa-trash"></i> Hapus
                                                </button>
                                                <form id="form-hapus-sk-{{ $d->id }}" action="{{ route('fakultas.wisuda.hapus-sk-yudisium', $d->id) }}" method="POST" style="display:none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle text-nowrap">
                                            <div class="row">
                                                @if($d->approved == 1)
                                                    {{-- <form action="{{ route('fakultas.wisuda.approve', $d->id) }}" method="POST" style="display:inline;" class="form-approve-wisuda"> --}}
                                                    <form action="{{route('fakultas.wisuda.approve', $d)}}" method="post" id="approveForm{{$d->id}}" class="approve-class" data-id='{{$d->id}}'>
                                                         @csrf
                                                        <div class="row  mb-5">
                                                            <button 
                                                            type="submit" 
                                                            class="btn btn-sm btn-success" title="Setujui Pendaftaran Wisuda"><i class="fa fa-check"></i> Approve</button>
                                                        </div>
                                                    </form>
                                                @endif
                                                @if($d->approved == 1 || $d->approved == 2)
                                                    <a href="#" class="btn btn-danger btn-sm my-2" title="Tolak Pangajuan" data-bs-toggle="modal" data-bs-target="#declineModal{{$d->id}}"><i class="fa fa-ban"> </i>  Decline</a>
                                                @endif                                                
                                            </div>
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

<script>
    $(function () {
        $('#data').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            // "responsive": true,
        });

        $('.approve-class').submit(function(e){
            e.preventDefault();
            var form = this;
            swal({
                title: 'Setujui Pendaftaran Wisuda?',
                text: "Anda akan menyetujui Pendaftaran Wisuda?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    form.submit();
                }
            });
        });

        $('.btn-hapus-sk').click(function(e){
            e.preventDefault();
            var id = $(this).data('id');
            swal({
                title: 'Hapus SK Yudisium?',
                text: "Anda yakin ingin menghapus SK Yudisium ini?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }, function(isConfirm){
                if (isConfirm) {
                    $('#form-hapus-sk-' + id).submit();
                }
            });
        });
    });
</script>

@endpush

