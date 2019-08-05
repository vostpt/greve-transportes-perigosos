@extends('layouts.app')
@section('styles')
@endsection

@section('content')
<div class="container text-center">
    <img src="/img/VOSTPT_FuelCrisisPT_JNDPA_Logo_With_VOSTPT_Logo_800pxX800px.png" style="width:20em" />
    <div class="row">
        <h2 style="margin: 0 auto;">Estatisticas</h2>
    </div>
    <div class="row">
        <p><b>Número de Entradas (Última Hora):</b> <span id="entries_last_hour">A carregar</span></p><br/>
    </div>
    <div class="row">
        <p><b>Número de Entradas (Último Dia):</b> <span id="entries_last_day">A carregar</span></p><br/>
    </div>
    <div class="row">
        <p><b>Número de Entradas (Totais):</b> <span id="entries_total">A carregar</span></p><br/>
    </div>
    <div class="row">
        <p><b>Número de Postos de Combústivel Sem Nenhum Combustível Disponivel:</b> <span id="stations_none">A carregar</span></p><br/>
    </div>
    <div class="row">
        <p><b>Número de Postos de Combústivel Sem Gasolina Combustível Disponivel:</b> <span id="stations_no_gasoline">A carregar</span></p><br/>
    </div>
    <div class="row">
        <p><b>Número de Postos de Combústivel Sem Gasóleo Combustível Disponivel:</b> <span id="stations_no_diesel">A carregar</span></p><br/>
    </div>
    <div class="row">
        <p><b>Número de Postos de Combústivel Sem GPL Combustível Disponivel:</b> <span id="stations_no_lpg">A carregar</span></p><br/>
    </div>
    <div class="row">
        <p><b>Atenção, Postos de Combústivel sem Gasolina, sem Gasóleo e sem GPL, são contabilizados para cada uma das ultimas 3 categorias exatamente acima.</b></p><br/>
    </div>
    <div class="row">
        <p><b>Última atualização às: </b> <span id="last_update">A carregar</span></p>
    </div>
</div>
<div class="navbar navbar-default navbar-fixed-bottom">
    <div style="position: fixed; bottom:10px; width:100%;" class="d-flex align-items-center justify-content-center">
        <img src="/img/logo-vost.png" style="width:5vw" />
    </div>
</div>
@endsection

@section('javascript')
<script src="{{ mix('/js/stats.js') }}" charset="utf-8"></script>
@endsection