@extends('layouts.app')
@section('title', 'Adicionar utilizador · ' . config('app.name'))

@section('content')
    <div class="container my-4" style="max-width: 520px;">
        <h1 class="mb-4">Adicionar utilizador</h1>
        <form method="POST" action="{{ route('panel.users.create') }}" class="card card-body shadow-sm">
            @csrf
            <div class="mb-3"><label class="form-label" for="name">Nome</label><input type="text" name="name" id="name" required class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label" for="email">Email</label><input type="email" name="email" id="email" required class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label" for="password">Password</label><input type="password" name="password" id="password" required class="form-control @error('password') is-invalid @enderror">@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <button class="btn btn-danger">Criar</button>
        </form>
    </div>
@endsection
