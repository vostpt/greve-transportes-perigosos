@extends('layouts.app')
@section('styles')
@endsection

@section('content')
<div class="container">
    <div class="row col d-flex align-items-center justify-content-center">
        <img src="/img/logo.png" style="width:20vw" />
    </div>
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
<script type="text/javascript">
function updateStats() {
    $.getJSON( "/storage/data/stats.json", (data) => {
        $("#entries_last_hour").html(data["entries_last_hour"]);
        $("#entries_last_day").html(data["entries_last_day"]);
        $("#entries_total").html(data["entries_total"]);
        $("#stations_none").html(data["stations_none"]);
        $("#stations_no_gasoline").html(data["stations_no_gasoline"]);
        $("#stations_no_diesel").html(data["stations_no_diesel"]);
        $("#stations_no_lpg").html(data["stations_no_lpg"]);
        var date = new Date;
        var seconds = date.getSeconds();
        var minutes = date.getMinutes();
        var hour = date.getHours();
        $("#last_update").html(("0" + hour).slice(-2)+'h'+("0" + minutes).slice(-2)+'m'+("0" + seconds).slice(-2)+'s');
    });
}
updateStats();
setInterval(updateStats,30000);
</script>
@endsection