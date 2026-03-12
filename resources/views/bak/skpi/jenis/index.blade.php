@extends('layouts.bak')

@section('content')

<div class="container">

<h4>Master Jenis Kegiatan SKPI</h4>

<a href="{{ route('bak.skpi-jenis.create') }}" class="btn btn-primary mb-3">
    Tambah Jenis
</a>

<table class="table table-bordered">

<thead>
<tr>
    <th>No</th>
    <th>Bidang</th>
    <th>Jenis Kegiatan</th>
    <th>Kriteria</th>
    <th>Skor</th>
    <th width="150">Aksi</th>
</tr>
</thead>

<tbody>

@foreach ($data as $row)

<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $row->bidang->nama_bidang ?? '-' }}</td>
    <td>{{ $row->nama_jenis }}</td>
    <td>{{ $row->kriteria }}</td>
    <td>{{ $row->skor }}</td>

    <td>

        <a href="{{ route('bak.skpi-jenis.edit', $row->id) }}" class="btn btn-warning btn-sm">
            Edit
        </a>

        <form action="{{ route('bak.skpi-jenis.destroy', $row->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger btn-sm">
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