@extends('layouts.app')
@section('title', 'Nova credencial · ' . config('app.name'))

@section('content')
    <div class="container my-4" style="max-width: 520px;">
        <h1 class="mb-4">Nova credencial externa</h1>
        <form method="POST" action="{{ route('panel.externalauth.create') }}" class="card card-body shadow-sm">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="key">Key</label>
                <input type="text" name="key" id="key" required class="form-control @error('key') is-invalid @enderror" value="{{ old('key') }}">
                @error('key')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="brand">Brand</label>
                <input type="text" name="brand" id="brand" required class="form-control @error('brand') is-invalid @enderror" value="{{ old('brand') }}" placeholder="READONLY, WRITEREAD, ou nome da marca">
                @error('brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text">Use <code>READONLY</code> para acesso só de leitura a todos os postos. <code>WRITEREAD</code> concede escrita total.</div>
            </div>
            <button class="btn btn-danger">Criar</button>
        </form>
    </div>
@endsection
