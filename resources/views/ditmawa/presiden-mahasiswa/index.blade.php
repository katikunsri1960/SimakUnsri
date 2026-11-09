@extends('layouts.ditmawa')

@section('title')
Kandidat Capresma
@endsection

@section('content')
@include('swal')

<section class="content">
    <div class="row align-items-end mb-3">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3">
                            <img src="{{ asset('images/images/svg-icon/color-svg/custom-14.svg') }}" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h2>Daftar Kandidat Capresma & Cawapresma</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Bar --}}
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="box-title mb-0">Pasangan Calon Presiden Mahasiswa</h4>
            <a href="{{ route('ditmawa.presiden-mahasiswa.kandidat.create') }}" class="btn btn-primary waves-effect waves-light">
                <i class="fa fa-plus me-1"></i> Tambah Kandidat
            </a>
        </div>
    </div>

    {{-- Grid Card Paslon --}}
    <div class="row">
        @forelse($kandidat as $d)
            <div class="col-xl-6 col-lg-6 col-12">
                <div class="box box-outline-success bs-3 border-success pull-up h-p100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="box-header with-border text-center bg-primary-light">
                            <h3 class="box-title text-primary fw-bold mb-0">
                                PASLON NO. URUT {{ $d->no_urut }}
                            </h3>
                        </div>

                        <div class="box-body text-center">
                            {{-- Foto Paslon --}}
                            <div class="mb-3 text-center">
                                @if($d->foto)
                                    <img src="{{ asset('storage/' . $d->foto) }}" alt="Foto Paslon No. {{ $d->no_urut }}" class="img-fluid rounded border border-primary shadow-sm" style="max-height: 250px; object-fit: cover; width: 100%;">
                                @else
                                    <div class="bg-light p-4 rounded border text-muted">
                                        <i class="fa fa-user-circle-o fa-5x mb-2"></i>
                                        <p class="mb-0">Foto Tidak Tersedia</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Profil Capresma & Cawapresma --}}
                            <div class="row text-start my-3">
                                <div class="col-6 border-end">
                                    <span class="badge badge-primary-light mb-1">CAPRESMA</span>
                                    <h5 class="fw-bold mb-0 text-truncate" title="{{ optional($d->capresma)->nama_mahasiswa ?? '-' }}">
                                        {{ optional($d->capresma)->nama_mahasiswa ?? '-' }}
                                    </h5>
                                    <small class="text-muted">NIM: {{ optional($d->capresma)->nim ?? '-' }}</small>
                                </div>
                                <div class="col-6">
                                    <span class="badge badge-primary-light mb-1">CAWAPRESMA</span>
                                    <h5 class="fw-bold mb-0 text-truncate" title="{{ optional($d->cawapresma)->nama_mahasiswa ?? '-' }}">
                                        {{ optional($d->cawapresma)->nama_mahasiswa ?? '-' }}
                                    </h5>
                                    <small class="text-muted">NIM: {{ optional($d->cawapresma)->nim ?? '-' }}</small>
                                </div>
                            </div>

                            <hr>

                            {{-- Visi & Misi (Tombol ada di Kanan Bawah) --}}
                            <div class="text-start mb-3">
                                {{-- Visi --}}
                                <div class="mb-3">
                                    <strong class="text-dark"><i class="fa fa-bullseye text-danger me-1"></i> Visi:</strong>
                                    <div class="text-muted small mt-1">
                                        @if(strlen($d->visi) > 120)
                                            <span class="text-short">{{ Str::limit($d->visi, 120) }}</span>
                                            <span class="text-full d-none">{{ $d->visi }}</span>
                                            <div class="text-end mt-1">
                                                <button type="button" class="btn btn-xs btn-outline btn-primary toggle-text" style="font-size: 10px;">
                                                    <i class="fa fa-chevron-down me-1"></i> Lihat Selengkapnya
                                                </button>
                                            </div>
                                        @else
                                            <span>{{ $d->visi }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Misi --}}
                                <div>
                                    <strong class="text-dark"><i class="fa fa-tasks text-success me-1"></i> Misi:</strong>
                                    <div class="text-muted small mt-1" style="white-space: pre-line;">
                                        @if(strlen($d->misi) > 120)
                                            <span class="text-short">{{ Str::limit($d->misi, 120) }}</span>
                                            <span class="text-full d-none">{{ $d->misi }}</span>
                                            <div class="text-end mt-1">
                                                <button type="button" class="btn btn-xs btn-outline btn-primary toggle-text" style="font-size: 10px;">
                                                    <i class="fa fa-chevron-down me-1"></i> Lihat Selengkapnya
                                                </button>
                                            </div>
                                        @else
                                            <span>{{ $d->misi }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Tautan Video Kampanye --}}
                            @if($d->video)
                                <div class="text-start mb-2">
                                    <a href="{{ $d->video }}" target="_blank" class="btn btn-xs btn-outline btn-danger w-p100 mb-1">
                                        <i class="fa fa-youtube-play me-1"></i> Tonton Video Kampanye
                                    </a>
                                </div>
                            @endif

                            {{-- Unduh Berkas Lampiran --}}
                            @if($d->berkas)
                                <div class="text-start mb-2">
                                    <a href="{{ asset('storage/' . $d->berkas) }}" target="_blank" class="btn btn-xs btn-outline btn-info w-p100">
                                        <i class="fa fa-download me-1"></i> Unduh Berkas / Lampiran
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="box-footer text-center bg-light">
                        <a href="{{ route('ditmawa.presiden-mahasiswa.kandidat.edit', $d->no_urut) }}" class="btn btn-warning btn-sm me-1">
                            <i class="fa fa-edit me-1"></i> Edit
                        </a>

                        <form action="{{ route('ditmawa.presiden-mahasiswa.kandidat.destroy', $d->no_urut) }}" method="POST" class="d-inline form-delete">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm btn-delete">
                                <i class="fa fa-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="box box-body text-center p-5">
                    <i class="fa fa-users fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Belum ada data kandidat Paslon yang ditambahkan.</h4>
                    <a href="{{ route('ditmawa.presiden-mahasiswa.kandidat.create') }}" class="btn btn-primary mt-2">
                        <i class="fa fa-plus me-1"></i> Tambah Kandidat Sekarang
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</section>
@endsection

@push('js')
<script src="{{ asset('assets/vendor_components/sweetalert/sweetalert.min.js') }}"></script>

<script>
$(document).ready(function(){
    // Toggle Show More / Show Less Visi & Misi pada Tombol Kanan Bawah
    $(document).on('click', '.toggle-text', function(e){
        e.preventDefault();
        var parent = $(this).closest('.text-muted');
        var shortText = parent.find('.text-short');
        var fullText = parent.find('.text-full');

        if (fullText.hasClass('d-none')) {
            fullText.removeClass('d-none');
            shortText.addClass('d-none');
            $(this).html('<i class="fa fa-chevron-up me-1"></i> Sembunyikan');
        } else {
            fullText.addClass('d-none');
            shortText.removeClass('d-none');
            $(this).html('<i class="fa fa-chevron-down me-1"></i> Lihat Selengkapnya');
        }
    });

    // Konfirmasi Hapus SweetAlert
    $(document).on('click', '.btn-delete', function(e){
        e.preventDefault();
        var form = $(this).closest('form');

        swal({
            title: "Yakin Hapus?",
            text: "Seluruh data kandidat dan data dukung nomor urut ini akan dihapus!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }, function(isConfirm){
            if (isConfirm) form.submit();
        });
    });
});
</script>
@endpush