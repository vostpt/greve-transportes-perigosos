@extends('layouts.app')
@section('styles')
<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v1.1.1/mapbox-gl.css' rel='stylesheet' />
<style>
    #map {
        position: absolute;
        top: 55px;
        bottom: 0;
        width: 100%;
        height: calc(100%-55px)
    }

    .mapboxgl-popup {
        width: 20% !important;
        max-width: 20% !important;
    }

    .mapboxgl-popup-content {
        width: 100%;
        height: 100%;
        padding: 0;
        border: none;
        border-radius: 0;
        position: relative;
        margin: 0 auto;
        line-height: 1.4em;
    }

    .mapboxgl-popup-content strong {
        margin-bottom: 2rem;
        text-align: center;
    }

    .mapboxgl-popup-content div,
    .mapboxgl-popup-content label {
        margin-bottom: 0 !important;
    }

    .mapboxgl-popup-close-button {
        font-size: 2em;
        padding-top: 5px;
        color: #fff;
    }

    .v-popup-header {
        background-color: grey;
        color: #fff;
        padding: 1.5vh;
    }

    .v-popup-header h5 {
        font-weight: 800;
        font-size: 1.5vh;
        padding: 0;
        margin: 0;
    }

    .v-popup-header small {
        font-weight: 800;
    }

    .v-popup-header a {
        text-decoration: none;
        color: white;
        text-align: right;
    }

    .v-popup-body {
        padding: 1.5vh;
        font-size: 1.5vh
    }

    .v-popup-body .v-fuel-info {
        display: inline-grid;
        justify-content: center;
        text-align: center;
    }

    .v-popup-body .v-fuel-info h6 {
        margin-top: 10px;
        font-weight: 600;
        font-size: 100%;
    }

    .v-popup-body .v-fuel-info img {
        width: 4vw;
        margin: 0 auto;
    }

    .v-popup-body.directions {
        padding: 0;
    }

    .v-popup-body.directions img {
        width: 100%;
        height: 100%
    }

    .v-popup-body .v-fuel-info img.no-gas {
        opacity: 0.3;
        filter: alpha(opacity=30);
        /* msie */
    }

    .map-overlay {
        position: absolute;
        bottom: 0;
        right: 0;
        background: rgba(255, 255, 255, 0.8);
        margin-right: 20px;
        margin-left: 20px;
        font-family: Arial, sans-serif;
        overflow: auto;
        border-radius: 3px;
        padding: 5px 5px 5px 5px;
    }

    #features {
        top: calc(1vh + 55px);
        width: 20vw;
        height: 35.5vh;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    #features h2 {
        font-size: 1.5vw;
        font-weight: 600;
    }

    #features h4 {
        font-size: 0.8vw;
    }

    #features #pd {
        font-size: 0.6vw;
    }

    #warning {
        position: absolute;
        top: calc(1vh + 55px);
        color: #2f86ca;
        font-family: Arial, sans-serif;
        overflow: auto;
        text-align: center;
        width: 100%;
    }

    #legend {
        top: calc(1vh + 55px);
        left: 0;
        padding: 10px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        width: 20vw;
        height: 20vh;
    }

    #legend h2 {
        font-size: 1.5vw;
        font-weight: 600;
    }

    #legend img {
        height: 3vh;
    }

    #legend label {
        font-size: 1.3vh;
        line-height: 3vh;
        vertical-align: middle;
        margin-bottom: 0 !important;
    }

    #legend div {
        line-height: 3vh;
    }
</style>
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
                <input class="form-check-input" type="radio" id="fuel_of_stations_all" checked
                    name="fuel_stations_type">
                <label class="form-check-label" for="fuel_of_stations_all">
                    Todos os Combustíveis
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="fuel_of_stations_gasoline" name="fuel_stations_type">
                <label class="form-check-label" for="fuel_of_stations_gasoline">
                    Gasolina
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="fuel_of_stations_diesel" name="fuel_stations_type">
                <label class="form-check-label" for="fuel_of_stations_diesel">
                    Gasóleo
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="fuel_of_stations_lpg" name="fuel_stations_type">
                <label class="form-check-label" for="fuel_of_stations_lpg">
                    GPL
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="fuel_of_stations_none" name="fuel_stations_type">
                <label class="form-check-label" for="fuel_of_stations_none">
                    Sem Nenhum
                </label>
            </div>
            <hr />
            <h4>Outros</h4>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="spanish_border">
                <label class="form-check-label" for="spanish_border">
                    Postos Espanhóis Perto da Fronteira
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="repa_only">
                <label class="form-check-label" for="repa_only">
                    Mostrar só Postos REPA (Veiculos Prioritários)
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="repa_only">
                <label class="form-check-label" for="repa_only">
                    Mostrar só Postos REPA (Todos os Veiculos)
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
            <img src="/img/map/VOSTPT_JNDPA_NONE_ICON_25x25.png" /><label>- Nenhum Combustíveis Está
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
<script>
    grecaptcha.ready(function() {
        console.log("Recaptcha loaded");
    });
    function validateCaptcha(callback) {
        grecaptcha.execute('6LdeNbAUAAAAAHooW_a98lAfARf1alSBCKVVmexn', {action:'validate_captcha'}).then(function(token) {
            callback(token);
        });
    }
</script>
<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v1.1.1/mapbox-gl.js'></script>
<script>
    let helping = false;
    let points = [];
    let promises = [];
    let popup = null;
    mapboxgl.accessToken = 'pk.eyJ1IjoiY290ZW1lcm8iLCJhIjoiY2p5NzQyeTdvMDc1MzNlbGNnbzh3NjVuOCJ9.cPrQc61yiHA0kOptuuZsSA';
    var map = new mapboxgl.Map({
        container: 'map', // container id,
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [-7.8536599, 39.557191],
        zoom: 6
    });
    function consult() {
        $("#map").css({"border": "0"});
        helping = false;
        $("#selector_view").css("visibility", "hidden");
        $("#map_view").css("visibility", "visible");
    }

    function help() {
        $("#warning").css("visibility", "visible");
        $("#map").css({"border-color": "#2f86ca", 
             "border-width":"3px", 
             "border-style":"solid"});
        helping = true;
        $("#selector_view").css("visibility", "hidden");
        $("#map_view").css("visibility", "visible");
    }

    function selector() {
        $("#warning").css("visibility", "hidden");
        $("#map_view").css("visibility", "hidden");
        $("#selector_view").css("visibility", "visible");
    }

    function swapIcon(obj) {
        let img = $(obj).find('img');
        if(img.hasClass( "no-gas" )) {
            img.removeClass( "no-gas" );
        }
        else {
            img.addClass( "no-gas" );
        }
    }

    function submitEntry(obj, id) {
        let gasoline = Number(!($(obj).parent().parent().find('.gasoline img').hasClass('no-gas')));
        let diesel = Number(!($(obj).parent().parent().find('.diesel img').hasClass('no-gas')));
        let lpg = Number(!($(obj).parent().parent().find('.lpg img').hasClass('no-gas')));
        validateCaptcha((token) => {
            let data = {
                "fuel_station": id,
                "gasoline": gasoline,
                "diesel": diesel,
                "lpg": lpg,
                "captcha": token
            }
            popup.remove();
            $.post( "/entries/add",data, function( reply ) {
                console.log("Entrada adicionada: " + reply.success + " (0 -> falha, 1 -> sucesso)");
            }, "json");
        });
    }

    String.prototype.capitalize = function() {
        return this.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
    };
    function loadPoints() {
        return new Promise(function(resolve,reject) {
            $.getJSON( "/storage/data/cache.json", (data) => {
                data.forEach(fuelStation => {
                    let with_gasoline = (fuelStation.sell_gasoline && fuelStation.has_gasoline);
                    let with_diesel = (fuelStation.sell_diesel && fuelStation.has_diesel);
                    let with_lpg = (fuelStation.sell_lpg && fuelStation.has_lpg);
                    let icon = '';
                    let popup_color = '';
                    if(fuelStation.repa == 1) {
                        icon = 'REPA';
                        popup_color = '#2f86ca';
                    }
                    else {
                        let count = 0;
                        if(with_gasoline || (!fuelStation.sell_gasoline)) {
                            count++;
                        }
                        if(with_diesel || (!fuelStation.sell_diesel)) {
                            count++;
                        }
                        if(with_lpg || (!fuelStation.sell_lpg)) {
                            count++;
                        }
                        if(count == 3) {
                            icon = 'ALL';
                            popup_color = '#65ac3d';
                        }
                        else if(count == 0) {
                            icon = 'NONE';
                            popup_color = '#b32e25';
                        }
                        else {
                            icon = 'PARTIAL';
                            popup_color = '#f3a433';
                        }
                    }

                    points.push({
                        "type": "Feature",
                        "geometry": {
                            "type": "Point",
                            "coordinates": [fuelStation.long, fuelStation.lat]
                        },
                        "properties": {
                            "id": fuelStation.id,
                            "name": fuelStation.name,
                            "brand": fuelStation.brand,
                            "repa": fuelStation.repa,
                            "with_gasoline": with_gasoline,
                            "with_diesel": with_diesel,
                            "with_lpg": with_lpg,
                            "sell_gasoline": fuelStation.sell_gasoline,
                            "sell_diesel": fuelStation.sell_diesel,
                            "sell_lpg": fuelStation.sell_lpg,
                            "has_gasoline": fuelStation.has_gasoline,
                            "has_diesel": fuelStation.has_diesel,
                            "has_lpg": fuelStation.has_lpg,
                            "icon": icon,
                            "popup_color": popup_color
                        }
                    });
                });
                resolve();
            });
        });
        
    }
    function loadBrandImage(brand,url) {
        return new Promise(function(resolve,reject) {
            map.loadImage(url, function(error, image) {
                if (error) {
                    console.log("ERROR:" + error);
                    reject(error);
                }
                else {
                    console.log("IMAGE LOADED");
                    map.addImage(brand, image);
                    resolve();
                }
            });
        });
    }

    map.on('load', function() {
        promises.push(loadBrandImage('REPA','/img/map/VOSTPT_JNDPA_REPA_ICON_25x25.png'));
        promises.push(loadBrandImage('NONE','/img/map/VOSTPT_JNDPA_NONE_ICON_25x25.png'));
        promises.push(loadBrandImage('PARTIAL','/img/map/VOSTPT_JNDPA_PARTIAL_ICON_25x25.png'));
        promises.push(loadBrandImage('ALL','/img/map/VOSTPT_JNDPA_ALL_ICON_25x25.png'));
        promises.push(loadPoints());
        Promise.all(promises).then(function() {
            console.log("promises done");
            map.addSource("points", {
                "type": "geojson",
                "data": {
                    "type": "FeatureCollection",
                    "features": points
                }
            });
            let layers = ['with_gasoline', 'with_diesel', 'with_lpg'];
            layers.forEach(element => {
                let layerID = 'poi-'+ element;
                map.addLayer({
                    "id": layerID,
                    "type": "symbol",
                    "source": "points",
                    "filter": [">", element, 0],
                    "layout": {
                        "icon-image": "{icon}",
                        "symbol-sort-key": ["get", "repa"],
                    }
                });

                map.on('click', layerID, function (e) {
                    var coordinates = e.features[0].geometry.coordinates.slice();
                    let gasolineIcon = e.features[0].properties.sell_gasoline && e.features[0].properties.has_gasoline ?
                        '<img src="img/map/VOSTPT_GASPUMP_GASOLINA_500pxX500px.png"/>' :
                        '<img class="no-gas"src="img/map/VOSTPT_GASPUMP_GASOLINA_500pxX500px.png"/>';
                    let dieselIcon = e.features[0].properties.sell_diesel && e.features[0].properties.has_diesel ?
                        '<img src="img/map/VOSTPT_GASPUMP_GASOLEO_500pxX500px.png"/>' :
                        '<img class="no-gas" src="img/map/VOSTPT_GASPUMP_GASOLEO_500pxX500px.png"/>';
                    let lpgIcon = e.features[0].properties.sell_lpg && e.features[0].properties.has_lpg ?
                        '<img width="75px" src="img/map/VOSTPT_GASPUMP_GPL_500pxX500px.png"/>' :
                        '<img class="no-gas" src="img/map/VOSTPT_GASPUMP_GPL_500pxX500px.png"/>';
                    let fuelStationName = e.features[0].properties.name ? e.features[0].properties.name.toUpperCase() : '';
                    let description = "";
                    if(helping) {
                        description = '<div class="v-popup-content">' +
                            '<div class="v-popup-header" style="background-color:#6bd7fc"><h5>' + e.features[0].properties.brand.toUpperCase() + '<br><small>' + fuelStationName + '</small></h5></div>' +
                            '<div class="v-popup-body" style="background-color:#afe2fb">' +
                            '<div class="row">' +
                            '<div class="col-md-4 v-fuel-info gasoline"><a href="#" onclick="swapIcon(this)">' + gasolineIcon + '</a><h6>GASOLINA</h6></div>' +
                            '<div class="col-md-4 v-fuel-info diesel"><a href="#" onclick="swapIcon(this)">' + dieselIcon + '</a><h6>GASOLEO</h6></div>' +
                            '<div class="col-md-4 v-fuel-info lpg"><a href="#" onclick="swapIcon(this)">' + lpgIcon + '</a><h6>GPL</h6></div>' +
                            '</div>'+
                            '<div class="row"><div class="col-md-12">Por favor indica que combústiveis não estão disponiveis na '+ e.features[0].properties.brand.toUpperCase() + ' - ' + fuelStationName + '.</div></div>'+
                            '<div class="row"><div class="col-md-12">Carrega nas imagens deixando as disponiveis mais nitidas.</div></div>' +
                            '</div>' +
                            '<div class="v-popup-header" style="background-color:#6bd7fc"><a href="#" onclick="submitEntry(this,'+e.features[0].properties.id+')"><h5>VALIDAR</h5></a></div>' +
                            '</div>';
                    }
                    else {
                        description = '<div class="v-popup-content">' +
                            '<div class="v-popup-header" style="background-color:'+e.features[0].properties.popup_color+'"><h5>' + e.features[0].properties.brand.toUpperCase() + '<br><small>' + fuelStationName + '</small></h5></div>' +
                            '<div class="v-popup-body">' +
                            '<div class="row">' +
                            '<div class="col-md-4 v-fuel-info">' + gasolineIcon + '<h6>GASOLINA</h6></div>' +
                            '<div class="col-md-4 v-fuel-info">' + dieselIcon + '<h6>GASOLEO</h6></div>' +
                            '<div class="col-md-4 v-fuel-info">' + lpgIcon + '<h6>GPL</h6></div>' +
                            '</div></div>' + 
                            '<div class="v-popup-header" style="background-color:'+e.features[0].properties.popup_color+'"><h5>OBTER DIREÇÕES</h5></div>' +
                            '<div class="v-popup-body directions"><a href="https://www.waze.com/ul?ll='+coordinates[1]+'%2C'+coordinates[0]+'&navigate=yes&zoom=16&download_prompt=false"  target="_blank" rel="noopener noreferrer"><img src="/img/map/map_blur.png"></div>' +
                            '</div>';
                    }
                    while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                        coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                    }

                    popup = new mapboxgl.Popup()
                    .setLngLat(coordinates)
                    .setHTML(description)
                    .addTo(map);
                });

                map.on('mouseenter', layerID, function () {
                    map.getCanvas().style.cursor = 'pointer';
                });

                // Change it back to a pointer when it leaves.
                map.on('mouseleave', layerID, function () {
                    map.getCanvas().style.cursor = '';
                });

            });
            if ("geolocation" in navigator) { 
                navigator.geolocation.getCurrentPosition(position => {
                    map.flyTo({center: [position.coords.longitude,position.coords.latitude], zoom: 14});
                });
            }
        });
    });
    map.on('error', function(error) {
        console.log('MAP LOAD ERROR')
        console.log(error);
    });

</script>
@endsection