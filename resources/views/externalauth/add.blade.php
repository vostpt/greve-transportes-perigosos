@extends('layouts.app') 
@section('content')
<div class="container">
    <h1 class="text-center">{{ __('Add External Auth') }}</h1>
    <div class="row justify-content-center">
        <div class="col-md-12">            
            <form method="post" action=" {{ route('externalauth.create') }}">
                @csrf
                <div class="form-group">
                    <label for="keyInput">{{ __('Key') }}</label>
                    <input name="key" id="keyInput" type="text" class="form-control{{ $errors->has('key') ? ' is-invalid' : '' }}" 
                    placeholder="{{ __('Key') }}" value="{{ old('key') }}">
                    @if ($errors->has('key'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('key') }}</strong>
                    </span> 
                    @endif
                </div>
                <div class="form-group">
                    <label for="brandInput">{{ __('Brand') }}</label>
                    <input name="brand" id="brandInput" type="text" class="form-control{{ $errors->has('brand') ? ' is-invalid' : '' }}" 
                    placeholder="{{ __('Brand') }}" value="{{ old('brand') }}">
                    @if ($errors->has('brand'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('brand') }}</strong>
                    </span> 
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection