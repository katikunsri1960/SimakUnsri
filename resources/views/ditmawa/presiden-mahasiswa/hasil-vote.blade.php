@extends('layouts.ditmawa')

@section('title')
Hasil Perolehan Suara Pemilihan Presma
@endsection

@push('css')
<style>
.progress-bar-animated {
    transition: width 0.6s ease;
}
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.03);
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
                            <img src="{{ asset('images/images/svg-icon/color-svg/custom-14.svg') }}" alt="Realtime Vote" style="max-height: 110px;">
                        </div>
                        <div class="col-12 col-lg-9 p-4">
                            <h2>Hasil Quick Count Pemilihan Presiden & Wakil Presiden Mahasiswa</h2>
                            <p class="text-muted mb-0">Rekapitulasi perolehan suara real-time untuk Semester Aktif: <strong>{{ $idSemesterAktif }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Widget Ringkasan Statistik --}}
    <div class="row">
        <div class="col-xl-4 col-md-4 col-12">
            <div class="box pull-up bg-primary">
                <div class="box-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-0 text-white-50">Total Suara Masuk</p>
                            <h2 class="mb-0 text-white fw-bold">{{ number_format($totalVotes) }}</h2>
                        </div>
                        <div class="p-3 bg-white-10 rounded">
                            <i class="fa fa-paper-plane fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4 col-12">
            <div class="box pull-up bg-info">
                <div class="box-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-0 text-white-50">Tingkat Partisipasi</p>
                            <h2 class="mb-0 text-white fw-bold">{{ $persentasePartisipasi }}%</h2>
                        </div>
                        <div class="p-3 bg-white-10 rounded">
                            <i class="fa fa-pie-chart fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4 col-12">
            <div class="box pull-up bg-warning">
                <div class="box-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-0 text-white-50">Belum Menggunakan Hak Suara</p>
                            <h2 class="mb-0 text-white fw-bold">{{ number_format($belumMemilih) }}</h2>
                        </div>
                        <div class="p-3 bg-white-10 rounded">
                            <i class="fa fa-users fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Perolehan Suara Per Paslon --}}
    <div class="row">
        @forelse($kandidat as $h)
            <div class="col-xl-6 col-lg-6 col-12 mb-4">
                <div class="box box-outline-primary pull-up h-p100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="box-header with-border text-center bg-primary-light">
                            <h3 class="box-title text-primary fw-bold mb-0">PASLON NO. URUT {{ $h->no_urut }}</h3>
                        </div>
                        <div class="box-body text-center">
                            {{-- Foto Paslon --}}
                            <div class="mb-3 text-center">
                                @if($h->foto)
                                    <img src="{{ asset('storage/' . $h->foto) }}" alt="Foto Paslon" class="img-fluid rounded border border-primary shadow-sm" style="max-height: 220px; object-fit: cover; width: 100%;">
                                @else
                                    <div class="bg-light p-4 rounded border text-muted">
                                        <i class="fa fa-user-circle-o fa-5x mb-2"></i>
                                        <p class="mb-0">Foto Tidak Tersedia</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Nama Paslon --}}
                            <div class="row text-start my-3">
                                <div class="col-6 border-end">
                                    <span class="badge badge-primary-light mb-1">CAPRESMA</span>
                                    <h5 class="fw-bold mb-0 text-truncate" title="{{ optional($h->capresma)->nama_mahasiswa ?? '-' }}">
                                        {{ optional($h->capresma)->nama_mahasiswa ?? '-' }}
                                    </h5>
                                    <small class="text-muted">NIM: {{ optional($h->capresma)->nim ?? '-' }}</small>
                                </div>
                                <div class="col-6">
                                    <span class="badge badge-primary-light mb-1">CAWAPRESMA</span>
                                    <h5 class="fw-bold mb-0 text-truncate" title="{{ optional($h->cawapresma)->nama_mahasiswa ?? '-' }}">
                                        {{ optional($h->cawapresma)->nama_mahasiswa ?? '-' }}
                                    </h5>
                                    <small class="text-muted">NIM: {{ optional($h->cawapresma)->nim ?? '-' }}</small>
                                </div>
                            </div>

                            <hr>
                            
                            
                        </div>
                    </div>

                    {{-- Progress Bar Perolehan Suara --}}
                    <div class="box-footer bg-light p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-dark">Perolehan Suara:</span>
                            <span class="badge bg-success fs-5 px-3 py-2">{{ number_format($h->jumlah_suara) }} Suara ({{ $h->persentase }}%)</span>
                        </div>
                        <div class="progress progress-lg mb-0" style="height: 22px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success fw-bold" role="progressbar" style="width: {{ $h->persentase }}%;" aria-valuenow="{{ $h->persentase }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $h->persentase }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="box box-body text-center p-5">
                    <i class="fa fa-info-circle fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Belum ada Pasangan Calon yang terdaftar.</h4>
                </div>
            </div>
        @endforelse
    </div>

    {{-- TABEL REKAPITULASI VOTING PER FAKULTAS --}}
    <div class="row mt-3">
        <div class="col-12">
            <div class="box box-outline-info">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title fw-bold text-info"><i class="fa fa-university me-2"></i> Rekapitulasi Perolehan Suara Per Fakultas</h4>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Nama Fakultas</th>
                                    @foreach($kandidat as $paslon)
                                        <th class="text-center">Paslon No. {{ $paslon->no_urut }}</th>
                                    @endforeach
                                    <th class="text-center bg-primary-light" style="width: 200px;">Total Suara Fakultas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekapFakultas as $namaFakultas => $votes)
                                    @php
                                        $totalFakultas = $totalSuaraPerFakultas[$namaFakultas] ?? 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-bold text-dark">{{ $namaFakultas }}</td>
                                        
                                        @foreach($kandidat as $paslon)
                                            @php
                                                $votePaslon = $votes->firstWhere('no_urut', $paslon->no_urut);
                                                $jumlah = $votePaslon ? $votePaslon->total_suara_paslon : 0;
                                                $persenFakultas = $totalFakultas > 0 ? round(($jumlah / $totalFakultas) * 100, 1) : 0;
                                            @endphp
                                            <td class="text-center">
                                                <span class="fw-bold fs-6">{{ number_format($jumlah) }}</span>
                                                <small class="text-muted d-block">({{ $persenFakultas }}%)</small>
                                            </td>
                                        @endforeach

                                        <td class="text-center bg-primary-light fw-bold">
                                            <span class="badge bg-primary fs-6">{{ number_format($totalFakultas) }} Suara</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($kandidat) + 3 }}" class="text-center py-4 text-muted">
                                            Belum ada data suara masuk dari fakultas manapun.
                                        </td>
                                    </tr>
                                @endforelse
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
});
</script>
@endpush