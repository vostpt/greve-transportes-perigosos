@extends('layouts.app')

@section('title', 'Login · ' . config('app.name'))

@section('content')
    <div class="container my-5" style="max-width: 420px;">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Acesso reservado</h1>
                <form method="POST" action="{{ route('panel.login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" required autofocus autocomplete="username" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" required autocomplete="current-password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Manter sessão iniciada</label>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
