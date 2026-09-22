@extends('layouts.app')
@section('title', 'Ajuda · ' . config('app.name'))

@section('content')
    <div class="container my-4" style="max-width: 780px;">
        <h1>Ajuda</h1>
        <p>Utilize o mapa em <a href="{{ route('map') }}">Início</a> para consultar disponibilidade em tempo real. Consulte <a href="{{ route('stats') }}">Estatísticas</a> para uma visão agregada.</p>
    </div>
@endsection
