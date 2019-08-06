@extends('layouts.app') 
@section('content')
<div class="container">
    <h1 class="text-center">{{ __('Change Password') }}</h1>
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('status'))
            <div class="alert alert-primary" role="alert">
                {{ session('status') }}
            </div>
            @endif         
            <form method="post" action=" {{ route('users.updatePassword') }}">
                @csrf
                <input type="hidden" name="id" value="{{ Auth::user()->id }}" />
                <div class="form-group">
                    <label for="currentpasswordInput">{{ __('Current Password') }}</label>
                    <input name="current-password" id="currentpasswordInput" type="password" class="form-control{{ $errors->has('current-password') ? ' is-invalid' : '' }}" 
                    placeholder="{{ __('Current Password') }}" value="{{ old('current-password') }}">
                    @if ($errors->has('current-password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('current-password') }}</strong>
                    </span> 
                    @endif
                </div>
                <div class="form-group">
                    <label for="passwordInput">{{ __('Password') }}</label>
                    <input name="password" id="passwordInput" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" 
                    placeholder="{{ __('Password') }}" value="{{ old('password') }}">
                    @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span> 
                    @endif
                </div>
                <div class="form-group">
                    <label for="password_confirmationInput">{{ __('Password Confirmation') }}</label>
                    <input name="password_confirmation" id="password_confirmationInput" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" 
                    placeholder="{{ __('Password Confirmation') }}" value="{{ old('password_confirmation') }}">
                    @if ($errors->has('password_confirmation'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span> 
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Change Password') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection