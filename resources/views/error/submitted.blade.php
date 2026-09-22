@extends('layouts.app')
@section('title', 'Obrigado · ' . config('app.name'))

@section('content')
    <div class="container my-5 text-center">
        <h1>Obrigado</h1>
        <p>Informação enviada com sucesso. A equipa VOST Portugal vai analisar o teu reporte.</p>
        <a href="{{ route('map') }}" class="btn btn-danger mt-3">Voltar ao mapa</a>
    </div>
@endsection
