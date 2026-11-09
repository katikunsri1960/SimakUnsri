@extends('layouts.mahasiswa')

@section('title')
Pemilihan Presiden Mahasiswa
@endsection

@push('css')
<style>
/* 1. Atur lebar dan padding box modal SweetAlert */
.sweet-alert {
    padding: 25px 20px !important;
    border-radius: 12px !important;
    max-width: 480px !important; /* Membatasi lebar agar proporsional */
    width: 90% !important;
}

/* 2. Rapikan ukuran judul dan teks deskripsi */
.sweet-alert h2 {
    font-size: 20px !important;
    margin: 15px 0 10px 0 !important;
    color: #333 !important;
    font-weight: 700 !important;
}

.sweet-alert p {
    font-size: 13px !important;
    line-height: 1.5 !important;
    color: #666 !important;
    margin-bottom: 20px !important;
}

/* 3. Menata container tombol SweetAlert agar sejajar & proporsional */
.sweet-alert .sa-button-container {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    gap: 10px !important;
    margin-top: 15px !important;
}

/* 4. Penyesuaian tombol Confirm & Cancel */
.sweet-alert button {
    margin: 0 !important;
    padding: 8px 16px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    border-radius: 6px !important;
    box-shadow: none !important;
    height: auto !important;
    line-height: 1.4 !important;
    flex: 1 !important; /* Membuat lebar kedua tombol seimbang */
    max-width: 200px !important;
}

/* Tombol Ya / Confirm di sebelah KIRI */
.sweet-alert button.confirm {
    background-color: #28a745 !important;
    order: 1 !important;
}

/* Tombol Batal / Cancel di sebelah KANAN */
.sweet-alert button.cancel {
    background-color: #dc3545 !important;
    order: 2 !important;
}
</style>
@endpush

@section('content')
@include('swal')

<section class="content">
    {{-- Banner Header --}}
    <div class="row align-items-end mb-3">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3 text-center">
                            <img src="{{ asset('images/images/svg-icon/color-svg/custom-14.svg') }}" alt="E-Voting" style="max-height: 120px;">
                        </div>
                        <div class="col-12 col-lg-9 p-4">
                            <h2>Pemilihan Presiden & Wakil Presiden Mahasiswa</h2>
                            <p class="text-muted mb-0">Gunakan hak suara Anda secara bijak, jujur, dan adil untuk masa depan Ormawa yang lebih baik (Semester: {{ $idSemesterAktif }}).</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Status Suara Mahasiswa --}}
    @if($alreadyVoted)
        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
            <i class="fa fa-check-circle fa-2x me-3"></i>
            <div>
                <strong>Hak Suara Terpakai!</strong> Anda telah menggunakan hak suara untuk memilih <strong>Paslon No. Urut {{ $alreadyVoted->no_urut }}</strong> pada {{ \Carbon\Carbon::parse($alreadyVoted->created_at)->translatedFormat('d F Y - H:i') }} WIB.
            </div>
        </div>
    @else
        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
            <i class="fa fa-info-circle fa-2x me-3"></i>
            <div>
                <strong>Perhatian!</strong> Anda belum menggunakan hak suara. Silakan pelajari visi, misi, dan berkas paslon sebelum menekan tombol vote. Pilihan Anda tidak dapat diubah setelah dikonfirmasi.
            </div>
        </div>
    @endif

    {{-- Grid Card Paslon --}}
    <div class="row">
        @forelse($kandidat as $d)
            @php
                $isMyChoice = $alreadyVoted && $alreadyVoted->no_urut == $d->no_urut;
            @endphp
            <div class="col-xl-6 col-lg-6 col-12 mb-4">
                <div class="box {{ $isMyChoice ? 'box-outline-success' : 'box-outline-primary' }} pull-up h-p100 d-flex flex-column justify-content-between">
                    <div>
                        {{-- Header Card --}}
                        <div class="box-header with-border text-center {{ $isMyChoice ? 'bg-success text-white' : 'bg-primary-light' }}">
                            <h3 class="box-title {{ $isMyChoice ? 'text-white' : 'text-primary' }} fw-bold mb-0">
                                PASLON NO. URUT {{ $d->no_urut }}
                                @if($isMyChoice)
                                    <span class="badge bg-white text-success ms-2">
                                        <i class="fa fa-check me-1"></i> Pilihan Anda
                                    </span>
                                @endif
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

                            {{-- Nama Capresma & Cawapresma --}}
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

                            {{-- Visi & Misi --}}
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

                            {{-- Video Kampanye --}}
                            @if($d->video)
                                <div class="text-start mb-2">
                                    <a href="{{ $d->video }}" target="_blank" class="btn btn-xs btn-outline btn-danger w-p100 mb-1">
                                        <i class="fa fa-youtube-play me-1"></i> Tonton Video Kampanye
                                    </a>
                                </div>
                            @endif

                            {{-- Berkas Lampiran --}}
                            @if($d->berkas)
                                <div class="text-start mb-2">
                                    <a href="{{ asset('storage/' . $d->berkas) }}" target="_blank" class="btn btn-xs btn-outline btn-info w-p100">
                                        <i class="fa fa-download me-1"></i> Unduh Berkas / Lampiran
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Tombol Vote / Pilihan Suara --}}
                    <div class="box-footer text-center bg-light">
                        @if($alreadyVoted)
                            @if($isMyChoice)
                                <button type="button" class="btn btn-success w-p100">
                                    <i class="fa fa-check-circle me-1"></i> Suara Anda Berada di Paslon Ini
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary w-p100">
                                    <i class="fa fa-lock me-1"></i> Anda Sudah Memilih
                                </button>
                            @endif
                        @else
                            <form action="{{ route('mahasiswa.lain-lain.presiden-mahasiswa.vote') }}" method="POST" class="form-vote">
                                @csrf
                                <input type="hidden" name="no_urut" value="{{ $d->no_urut }}">
                                <button type="button" class="btn btn-success waves-effect waves-light w-p100 btn-submit-vote" data-no="{{ $d->no_urut }}" data-paslon="{{ optional($d->capresma)->nama_mahasiswa }} & {{ optional($d->cawapresma)->nama_mahasiswa }}">
                                    <i class="fa fa-paper-plane me-1"></i> Pilih Paslon No. {{ $d->no_urut }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="box box-body text-center p-5">
                    <i class="fa fa-info-circle fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Belum ada Pasangan Calon yang ditetapkan untuk pemilihan.</h4>
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
    // Toggle Show More / Show Less Teks Visi & Misi
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

    // Konfirmasi SweetAlert saat tombol Vote diklik
    $(document).on('click', '.btn-submit-vote', function(e){
        e.preventDefault();
        var form = $(this).closest('form');
        var noUrut = $(this).data('no');
        var namaPaslon = $(this).data('paslon');

        swal({
            title: "Konfirmasi Pilihan Anda",
            text: "Apakah Anda yakin ingin memberikan suara kepada Paslon No. " + noUrut + " (" + namaPaslon + ")? Pilihan tidak dapat diubah kembali!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#dc3545",
            confirmButtonText: "Ya, Berikan Suara Saya!",
            cancelButtonText: "Batal / Pikirkan Lagi",
            closeOnConfirm: false
        }, function(isConfirm){
            if (isConfirm) {
                form.submit();
            }
        });
    });
});
</script>
@endpush