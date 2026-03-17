@extends('layouts.bak')

@section('content')

<div class="container">

<h4>Tambah Bidang Kegiatan</h4>

<form action="{{route('bak.skpi.bidang.store')}}" method="POST">
@csrf

<div class="mb-3">
<label>Nama Bidang</label>
<input type="text" name="nama_bidang" class="form-control" required>
</div>

<div class="mb-3">
<label>Nama Kegiatan</label>
<input type="text" name="nama_kegiatan" class="form-control" required>
</div>

<button class="btn btn-success">Simpan</button>

<a href="{{route('bak.skpi.bidang.index')}}" class="btn btn-secondary">
Kembali
</a>

</form>

</div>

@endsection