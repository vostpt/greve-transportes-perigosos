@extends('layouts.app') 
@section('content')
<div class="container">
    <h1 class="text-center">Dashboard</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif 
            You are logged in!
        </div>
    </div>
</div>
@endsection