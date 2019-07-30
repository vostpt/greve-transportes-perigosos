@extends('layouts.app') 
@section('content')
<div class="container">
    <h1 class="text-center">{{ __('Add User') }}</h1>
    <div class="row justify-content-center">
        <div class="col-md-12">            
            <form method="post" action=" {{ route('users.create') }}">
                @csrf
                <div class="form-group">
                    <label for="nameInput">{{ __('Name') }}</label>
                    <input name="name" id="nameInput" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" 
                    placeholder="{{ __('Name') }}" value="{{ old('name') }}">
                    @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span> 
                    @endif
                </div>
                <div class="form-group">
                    <label for="emailAddressInput">{{ __('Email address') }}</label>
                    <input name="email" id="emailAddressInput" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" 
                    placeholder="{{ __('Email address') }}" value="{{ old('email') }}">
                    @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span> 
                    @endif
                </div>
                <div class="form-group">
                    <label for="passwordInput">{{ __('Password') }}</label>
                    <input name="password" id="passwordInput" type="password" 
                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Password') }}">
                    @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span> 
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection