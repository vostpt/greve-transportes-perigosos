@extends('layouts.app')
@section('title', 'Sobre · ' . config('app.name'))

@section('content')
    <div class="container my-4" style="max-width: 780px;">
        <h1>Sobre a plataforma</h1>
        <p class="lead">Já Não Dá Para Abastecer é um projeto de crowdsourcing da VOST Portugal para mapear a disponibilidade de combustíveis em Portugal continental durante períodos de crise de abastecimento.</p>
        <p>Os dados exibidos são reportados por utilizadores anónimos e validados por consenso de múltiplos IPs distintos numa janela de uma hora. Marcas parceiras (Prio, OZ Energia, Ecobrent, Bxpress, Tfuel) fornecem dados oficiais através da nossa <a href="/api/v1/">API v1</a>.</p>
        <p>Para corrigir informação sobre um posto específico ou reportar problemas, utilize o formulário <a href="{{ route('error.new') }}">Reportar Erro</a>.</p>
    </div>
@endsection
