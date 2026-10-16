@extends('layouts.guest')
@section('title')
Login Page
@endsection

@section('content')
<div class="container h-p100">
    <div class="row align-items-center justify-content-center h-p100">
        <div class="col-lg-4 col-md-6 col-sm-10 col-12">
            
            {{-- Logo di Luar --}}
            <div class="text-center mb-20">
                <img src="{{asset('images/logo-unsri-2.png')}}" alt="UNSRI" style="max-width: 140px; height: auto;">
            </div>

            {{-- Card Login --}}
            <div class="bg-white rounded10 shadow-lg p-30">
                <div class="text-center mb-20">
                    {{-- Teks Dipisah Menjadi 2 Baris --}}
                    <h5 class="text-primary fw-600 mb-3 fs-13 lh-base">
                        SISTEM INFORMASI MANAJEMEN<br>
                        AKADEMIK DAN KEMAHASISWAAN
                    </h5>
                    <h3 class="text-dark fw-700 fs-16 mb-5">UNIVERSITAS SRIWIJAYA</h3>
                </div>

                @if ($errors->any())
                <div class="alert alert-danger py-10 px-15 mb-20 fs-13">
                    <ul class="mb-0 ps-15">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger py-10 px-15 mb-20 fs-13">
                    {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="form-group mb-20">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="ti-user text-muted"></i></span>
                            <input type="text" class="form-control ps-10 bg-light" placeholder="Username / NIM Mahasiswa" name="username" value="{{old('username')}}" required>
                        </div>
                    </div>

                    <div class="form-group mb-25">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="ti-lock text-muted"></i></span>
                            <input type="password" class="form-control ps-10 bg-light" placeholder="Password" name="password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-danger w-p100 py-10 fw-600">LOGIN</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection