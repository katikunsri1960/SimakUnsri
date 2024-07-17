@extends('layouts.guest')
@section('title')
Akun Mahasiswa
@endsection
@section('content')
<div class="container h-p100">
    <div class="row align-items-center justify-content-md-center h-p100">
        <div class="col-12">
            <div class="row justify-content-center g-0">
                <div class="col-lg-5 col-md-12 col-sm-12">
                    <div class="text-center">
                        <img src="{{asset('images/logo-unsri.png')}}" alt="UNSRI" class="img-responsive w-50">
                    </div>
                    <div class="bg-white rounded10 shadow-lg">
                        <div class="content-top-agile p-20 pb-0">
                            <h2 class="text-primary">SISTEM INFORMASI AKADEMIK</h2>
                            <h2 class="text-primary">UNIVERSITAS SRIWIJAYA</h2>
                        </div>
                        <div class="p-30">
                            <form action="{{route('store-akun')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="form-label">Masukan NIM Anda</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-transparent"><i class="ti-user"></i></span>
                                        <input type="text" class="form-control ps-15 bg-transparent" placeholder="NIM"
                                            name="nim" value="{{old('nim')}}" required>
                                        <button class="btn btn-primary" onclick="checkNim()">Periksa</button>
                                    </div>
                                    @error('nim')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="" id="isiDiv" hidden>
                                    <div class="form-group mt-3">
                                        <label for="form-label">Nama</label>
                                        <input type="text" class="form-control" name="nama" value="{{old('nama')}}"
                                            id="nama" disabled>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="password" class="form-label">Buat Kata Sandi Baru</label>
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="new-password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="password-confirm" class="form-label">Konfirmasi Kata Sandi</label>

                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                    <div class="row mx-2">
                                        <p class="text-danger">Pastikan data NIM dan Nama sudah sesuai dengan data anda sebelum melakukan submit!!</p>
                                    </div>
                                    <div class="row mx-2 mt-3">
                                        <button class="btn btn-primary" type="submit">
                                            Submit
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@push('js')
<script>
    function checkNim() {
            var nim = document.querySelector('input[name=nim]').value;
            // ajax request
            fetch(`/checkNim/${nim}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status == 'success') {
                        document.querySelector('#isiDiv').removeAttribute('hidden');
                        document.querySelector('#nama').value = data.data.nama_mahasiswa;
                    } else {
                        alert('NIM tidak ditemukan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
</script>
@endpush
