@extends('layouts.universitas')

@section('title', 'Import Tagihan')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Import Data Tagihan (Pangkalan Data UNSRI)</h4>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="mb-3">
            <a href="{{ route('univ.import.keu.preview.tagihan') }}" class="btn btn-warning">
                Preview Tagihan
            </a>

            <form action="{{ route('univ.import.keu.tagihan') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin import data tagihan?')">
                    Import Tagihan
                </button>
            </form>
        </div>

        @if(!empty($data) && is_array($data))
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        @foreach(array_keys((array) reset($data)) as $key)
                            <th>{{ strtoupper(str_replace('_', ' ', $key)) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                        <tr>
                            @foreach((array) $row as $val)
                                <td>{{ is_array($val) || is_object($val) ? json_encode($val) : ($val ?? '-') }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="alert alert-info">Data kosong / tidak ditemukan.</div>
        @endif
    </div>
</div>
@endsection