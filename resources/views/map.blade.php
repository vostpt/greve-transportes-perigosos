@extends('layouts.app')
@section('styles')
<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v1.1.1/mapbox-gl.css' rel='stylesheet' />
<link rel="stylesheet" href="{{ mix('/css/map.css') }}">
@endsection

@section('content')
<div id="selector_view">
    <div class="container">
        <div class="row col d-flex align-items-center justify-content-center">
            <img src="/img/logo.png" style="width:20vw" />
        </div>
        <div class="row text-center">
            <div class="col-3 offset-3">
                <button type="button" class="btn btn-primary btn-lg" onclick="consult()">Consultar</button>
            </div>
            <div class="col-3">
                <button type="button" class="btn btn-primary btn-lg" onclick="help()">Ajudar</button>
            </div>
        </div>
    </div>
    <div class="navbar navbar-default navbar-fixed-bottom">
        <div style="position: fixed; bottom:10px; width:100%;" class="d-flex align-items-center justify-content-center">
            <img src="/img/logo-vost.png" style="width:5vw" />
        </div>
    </div>
</div>
<div id="map_view" style="visibility: hidden;">
    <div id="map"></div>
    <div class='map-overlay' id='features'>
        <h2>Opções</h2>
        <div id='pd'>
            <h4>Disponibilidade de Combustível</h4>
            <div class="form-check">
                <input class="form-check-input type" type="radio" id="fuel_of_stations_all" checked
                    name="fuel_stations_type" value="all" autocomplete="off">
                <label class="form-check-label" for="fuel_of_stations_all">
                    Mostrar Todos os Postos de Combustível
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input type" type="radio" id="fuel_of_stations_gasoline"
                    name="fuel_stations_type" value="gasoline" autocomplete="off">
                <label class="form-check-label" for="fuel_of_stations_gasoline">
                    Gasolina
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input type" type="radio" id="fuel_of_stations_diesel" name="fuel_stations_type"
                    value="diesel" autocomplete="off">
                <label class="form-check-label" for="fuel_of_stations_diesel">
                    Gasóleo
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input type" type="radio" id="fuel_of_stations_lpg" name="fuel_stations_type"
                    value="lpg" autocomplete="off">
                <label class="form-check-label" for="fuel_of_stations_lpg">
                    GPL
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input type" type="radio" id="fuel_of_stations_none" name="fuel_stations_type"
                    value="none" autocomplete="off">
                <label class="form-check-label" for="fuel_of_stations_none">
                    Sem Nenhum
                </label>
            </div>
            <hr />
            <h4>Postos REPA</h4>
            <div class="form-check">
                <input class="form-check-input repa" type="radio" id="fuel_stations_repa_all" checked
                    name="fuel_stations_repa" value="all" autocomplete="off">
                <label class="form-check-label" for="fuel_stations_repa_all">
                    Mostrar Todos os Postos de Combustível
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input repa" type="radio" id="fuel_stations_repa_priority"
                    name="fuel_stations_repa" value="sos" autocomplete="off">
                <label class="form-check-label" for="fuel_stations_repa_priority">
                    Mostrar só Postos de Combustível REPA - Veículos Prioritários
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input repa" type="radio" id="fuel_stations_repa_normal"
                    name="fuel_stations_repa" value="normal" autocomplete="off">
                <label class="form-check-label" for="fuel_stations_repa_normal">
                    Mostrar só Postos de Combustível REPA - Todos os Veículos
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input repa" type="radio" id="fuel_stations_repa_none" name="fuel_stations_repa"
                    value="none" autocomplete="off">
                <label class="form-check-label" for="fuel_stations_repa_none">
                    Não Mostrar Postos REPA
                </label>
            </div>
            <hr />
            <label><a href="#" onclick="selector()">Voltar ao Menu</a></label>
        </div>
    </div>
    <div class='map-overlay' id='legend'>
        <h2>Legenda</h2>
        <div>
            <img src="/img/map/VOSTPT_JNDPA_ALL_ICON_25x25.png" /><label>- Todos os Combustíveis
                Disponiveis</label><br />
        </div>
        <div>
            <img src="/img/map/VOSTPT_JNDPA_PARTIAL_ICON_25x25.png" /><label>- Alguns Combustíveis Não Estão
                Disponiveis</label><br />
        </div>
        <div>
            <img src="/img/map/VOSTPT_JNDPA_NONE_ICON_25x25.png" /><label>- Nenhum Combustível Está
                Disponiveis</label><br />
        </div>
        <div>
            <img src="/img/map/VOSTPT_JNDPA_REPA_ICON_25x25.png" /><label>- Posto de Combustível REPA </label><br />
        </div>
    </div>
    <div id='warning' style="visibility: hidden">
        <h2>Em Modo de Ajuda</h2>
    </div>
</div>
@endsection

@section('javascript')
<script src="https://www.google.com/recaptcha/api.js?render=6LdeNbAUAAAAAHooW_a98lAfARf1alSBCKVVmexn"></script>
<script src="{{ mix('/js/recaptcha.js') }}" charset="utf-8"></script>
<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v1.1.1/mapbox-gl.js'></script>
<script src="{{ mix('/js/map_direct.js') }}" charset="utf-8"></script>
<script src="{{ mix('/js/map.js') }}" charset="utf-8"></script>
@endsection