@extends('layouts.bak')

@section('content')

<div class="container">

<h4>Master Bidang Kegiatan SKPI</h4>

<a href="{{route('bak.skpi.bidang.create')}}" class="btn btn-primary mb-3">
Tambah Data
</a>

<table class="table table-bordered">
<thead>
<tr>
<th>No</th>
<th>Nama Bidang</th>
<th>Nama Kegiatan</th>
<th width="150">Aksi</th>
</tr>
</thead>

<tbody>

@foreach($data as $row)

<tr>
<td>{{$loop->iteration}}</td>
<td>{{$row->nama_bidang}}</td>
<td>{{$row->nama_kegiatan}}</td>

<td>

<a href="{{route('bak.skpi.bidang.edit',$row->id)}}" class="btn btn-warning btn-sm">
Edit
</a>

<form action="{{route('bak.skpi.bidang.destroy',$row->id)}}" method="POST" style="display:inline">
@csrf
@method('DELETE')

<button class="btn btn-danger btn-sm">
Hapus
</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection