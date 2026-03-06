@extends('layouts.universitas')

@section('title', 'Import Pembayaran')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Import Data Pembayaran (Server Keuangan)</h4>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('univ.import.keu.preview.pembayaran') }}" class="btn btn-info">
                Preview Pembayaran
            </a>

            <form action="{{ route('univ.import.keu.pembayaran') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success"
                    onclick="return confirm('Yakin import data pembayaran?')">
                    Import Pembayaran
                </button>
            </form>
        </div>

        @isset($data)
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        @foreach($data->first() ?? [] as $key => $val)
                            <th>{{ $key }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                        <tr>
                            @foreach($row as $val)
                                <td>{{ $val }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endisset

    </div>
</div>

@endsection
