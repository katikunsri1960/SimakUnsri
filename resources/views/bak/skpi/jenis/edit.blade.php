@extends('layouts.bak')

@section('content')

<div class="container">

<h4>Edit Jenis Kegiatan</h4>

<form action="{{route('bak.skpi.jenis.update',$data->id)}}" method="POST">

@csrf
@method('PUT')

<div class="mb-3">

<label>Bidang</label>

<select name="bidang_id" class="form-control">

@foreach($bidang as $b)

<option value="{{$b->id}}"
{{$data->bidang_id == $b->id ? 'selected' : ''}}>
{{$b->nama_bidang}}
</option>

@endforeach

</select>

</div>

<div class="mb-3">
<label>Nama Jenis</label>
<input type="text" name="nama_jenis"
value="{{$data->nama_jenis}}"
class="form-control">
</div>

<div class="mb-3">
<label>Kriteria</label>
<textarea name="kriteria" class="form-control">{{$data->kriteria}}</textarea>
</div>

<div class="mb-3">
<label>Skor</label>
<input type="number" name="skor"
value="{{$data->skor}}"
class="form-control">
</div>

<button class="btn btn-success">Update</button>

<a href="{{route('bak.skpi.jenis.index')}}" class="btn btn-secondary">
Kembali
</a>

</form>

</div>

@endsection