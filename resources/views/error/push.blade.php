@extends('layouts.app')
@section('styles')
@endsection

@section('content')
<div class="container">
    <div class="text-center">
        <img src="/img/VOSTPT_FuelCrisisPT_JNDPA_Logo_With_VOSTPT_Logo_800pxX800px.png" style="width:20em" />
    </div>
    @if (session('status'))
    <div class="alert alert-primary" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <p>Muito obrigado pelo teu contributo!</p>
@endsection

@section('javascript')
@endsection
