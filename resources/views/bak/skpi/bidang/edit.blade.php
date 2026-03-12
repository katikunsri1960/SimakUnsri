@extends('layouts.bak')

@section('content')

<div class="container">

<h4>Edit Bidang Kegiatan</h4>

<form action="{{route('bak.skpi-bidang.update',$data->id)}}" method="POST">

@csrf
@method('PUT')

<div class="mb-3">
<label>Nama Bidang</label>
<input type="text" name="nama_bidang"
value="{{$data->nama_bidang}}"
class="form-control">
</div>

<div class="mb-3">
<label>Nama Kegiatan</label>
<input type="text" name="nama_kegiatan"
value="{{$data->nama_kegiatan}}"
class="form-control">
</div>

<button class="btn btn-success">Update</button>

<a href="{{route('bak.skpi-bidang.index')}}" class="btn btn-secondary">
Kembali
</a>

</form>

</div>

@endsection