@extends('layouts.app')
@section('title', 'Password · ' . config('app.name'))

@section('content')
    <div class="container my-4" style="max-width: 520px;">
        <h1 class="mb-4">Mudar password</h1>
        <form method="POST" action="{{ route('panel.users.updatePassword') }}" class="card card-body shadow-sm">
            @csrf
            <div class="mb-3"><label class="form-label" for="current_password">Password atual</label><input type="password" name="current_password" id="current_password" required class="form-control @error('current_password') is-invalid @enderror">@error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label" for="password">Nova password</label><input type="password" name="password" id="password" required class="form-control @error('password') is-invalid @enderror">@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label" for="password_confirmation">Confirmar</label><input type="password" name="password_confirmation" id="password_confirmation" required class="form-control"></div>
            <button class="btn btn-danger">Atualizar</button>
        </form>
    </div>
@endsection
