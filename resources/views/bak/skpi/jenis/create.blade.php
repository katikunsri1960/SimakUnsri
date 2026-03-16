@extends('layouts.bak')

@section('content')

<div class="container">

<h4>Tambah Jenis Kegiatan</h4>

<form action="{{route('bak.skpi.jenis.store')}}" method="POST">

@csrf

<div class="mb-3">

<label>Bidang Kegiatan</label>

<select name="bidang_id" class="form-control">

@foreach($bidang as $b)

<option value="{{$b->id}}">{{$b->nama_bidang}}</option>

@endforeach

</select>

</div>

<div class="mb-3">
<label>Nama Jenis</label>
<input type="text" name="nama_jenis" class="form-control">
</div>

<div class="mb-3">
<label>Kriteria</label>
<textarea name="kriteria" class="form-control"></textarea>
</div>

<div class="mb-3">
<label>Skor</label>
<input type="number" name="skor" class="form-control">
</div>

<button class="btn btn-success">Simpan</button>

<a href="{{route('bak.skpi.jenis.index')}}" class="btn btn-secondary">
Kembali
</a>

</form>

</div>

@endsection