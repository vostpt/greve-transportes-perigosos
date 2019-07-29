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

    .filter-group {
        font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
        font-weight: 600;
        position: absolute;
        top: 65px;
        right: 10px;
        z-index: 1;
        border-radius: 3px;
        width: 120px;
        color: #fff;
    }

    .filter-group input[type=checkbox]:first-child+label {
        border-radius: 3px 3px 0 0;
    }

    .filter-group label:last-child {
        border-radius: 0 0 3px 3px;
        border: none;
    }

    .filter-group input[type=checkbox] {
        display: none;
    }

    .filter-group input[type=checkbox]+label {
        background-color: #3386c0;
        display: block;
        cursor: pointer;
        padding: 10px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.25);
    }

    .filter-group input[type=checkbox]+label {
        background-color: #3386c0;
        text-transform: capitalize;
    }

    .filter-group input[type=checkbox]+label:hover,
    .filter-group input[type=checkbox]:checked+label {
        background-color: #4ea0da;
    }

    .filter-group input[type=checkbox]:checked+label:before {
        content: '✔';
        margin-right: 5px;
    }

    .filter-group label {
        margin-bottom: 0 !important;
    }

    .mapboxgl-popup-content {
        width: 450px;
        height: 100%;
        padding: 0;
        border: 1px solid rgba(0,0,0,.2);
        border-radius: 6px;
        position: relative;
        margin:0 auto;
        line-height: 1.4em;
    }

    @media only screen and (max-width: 479px){
        .mapboxgl-popup-content { width: 95%; }
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
        background-color: #f3a433;
        color: #fff;
        padding: 15px;
    }
    .v-popup-header h5{
        font-weight: 800;
        padding: 0;
        margin: 0;
    }
    .v-popup-header small{
        font-weight: 800;
    }
    .v-popup-body {
        padding: 15px;
    }
    .v-popup-body .v-fuel-info {
        display: inline-grid;
        justify-content: center;
        text-align: center;
    }

    .v-popup-body .v-fuel-info h6 {
        margin-top: 10px;
        font-weight: 600;
    }
    .img-no-gas{
        opacity: 0.3;
        filter: alpha(opacity=30); /* msie */
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
        top: 65px;
        height: 100px;
        width: 250px;
    }

    #legend {
        top: 65px;
        left: 0;
        padding: 10px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        height: 175px;
        margin-bottom: 40px;
        width: 250px;
    }

    #legend label {
        line-height: 25px;
        vertical-align: middle;
        margin-bottom: 0 !important;
    }
</style>
@endsection

@section('content')
<div id="selector_view">
    <div class="container">
        <div class="row col d-flex align-items-center justify-content-center">
            <img src="/img/logo.png" />
        </div>
        <div class="row text-center">
            <div class="col-lg-3 offset-lg-3">
                <button type="button" class="btn btn-primary btn-lg" onclick="consult()">Consultar</button>
            </div>
            <div class="col-lg-3">
                <button type="button" class="btn btn-primary btn-lg" onclick="help()">Ajudar</button>
            </div>
        </div>
    </div>
    <div class="navbar navbar-default navbar-fixed-bottom">
        <div style="position: fixed; bottom:10px; width:100%;" class="d-flex align-items-center justify-content-center">
            <img src="/img/logo-vost.png" width="100px" />
        </div>
    </div>
</div>
<div id="map_view" style="visibility: hidden;">
    <div id="map"></div>
    <nav id='filter-group' class='filter-group'></nav>
    <div class='map-overlay' id='features'>
        <h2>Opções</h2>
        <div id='pd'>Some text here</div>
    </div>
    <div class='map-overlay' id='legend'>
        <h2>Legenda</h2>
        <img src="/img/map/VOSTPT_JNDPA_ALL_ICON_25x25.png" /><label>- ALL</label><br />
        <img src="/img/map/VOSTPT_JNDPA_PARTIAL_ICON_25x25.png" /><label>- PARTIAL</label><br />
        <img src="/img/map/VOSTPT_JNDPA_NONE_ICON_25x25.png" /><label>- NONE</label><br />
        <img src="/img/map/VOSTPT_JNDPA_REPA_ICON_25x25.png" /><label>- REPA</label><br />
    </div>
</div>
@endsection

@section('javascript')
<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v1.1.1/mapbox-gl.js'></script>
<script>
    let helping = false;
    function consult() {
        $("#selector_view").hide();
        $("#map_view").css("visibility", "visible");
        helping = false;
    }

    function help() {
        $("#selector_view").hide();
        $("#map_view").css("visibility", "visible");
        helping = true;
    }

    function selector() {
        $("#map_view").hide();
        $("#selector_view").show();
    }
    mapboxgl.accessToken = 'pk.eyJ1IjoiY290ZW1lcm8iLCJhIjoiY2p5NzQyeTdvMDc1MzNlbGNnbzh3NjVuOCJ9.cPrQc61yiHA0kOptuuZsSA';
    var map = new mapboxgl.Map({
        container: 'map', // container id,
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [-7.8536599, 39.557191],
        zoom: 6
    });
    let points = [];
    var promises = [];
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
                    if(fuelStation.repa == 1) {
                        icon = 'REPA';
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
                        }
                        else if(count == 0) {
                            icon = 'NONE';
                        }
                        else {
                            icon = 'PARTIAL';
                        }
                    }
                    /*let description_gasoline = "";
                    let description_diesel = "";
                    let description_lpg = "";
                    if(fuelStation.sell_gasoline) {
                        if(fuelStation.has_gasoline) {
                            description_gasoline = "<label style='color:#65ac3d'>Disponivel</label>";
                        }
                        else {
                            description_gasoline = "<label style='color:#b32e25'>Indisponível</label>";
                        }
                    }
                    else {
                        description_gasoline = "<label>Não Vende</label>";
                    }
                    if(fuelStation.sell_diesel) {
                        if(fuelStation.has_diesel) {
                            description_diesel = "<label style='color:#65ac3d'>Disponivel</label>";
                        }
                        else {
                            description_diesel = "<label style='color:#b32e25'>Indisponível</label>";
                        }
                    }
                    else {
                        description_diesel = "<label>Não Vende</label>";
                    }
                    if(fuelStation.sell_lpg) {
                        if(fuelStation.has_lpg) {
                            description_lpg = "<label style='color:#65ac3d'>Disponivel</label>";
                        }
                        else {
                            description_lpg = "<label style='color:#b32e25'>Indisponível</label>";
                        }
                    }
                    else {
                        description_lpg = "<label>Não Vende</label>";
                    }*/

                    //THIS IS THE POPUP HTML
                    let description = "";
                    if(helping) {
                        //Edition POPUP
                        //<p><strong>"+fuelStation.name.toLowerCase().capitalize()+"</strong></p><p><b>Marca:</b> "+fuelStation.brand+"</p><p><b>Gasolina:</b> "+description_gasoline+"</p><p><b>Gasoleo:</b> "+description_diesel+"</p><p><b>GPL:</b> "+description_lpg+"</p><p><a href=\"#\">Modificar Dados</a></p>
                        description = '<div>' +
                            '<div class="row"><div class="col-sm-12 h-10">LALALLALA</div></div>' +
                            '<div class="row"><div class="col-sm-12 h-30">LALALLALA</div></div>' +
                            '<div class="row"><div class="col-sm-12 h-10">LALALLALA</div></div>' +
                            '<div class="row"><div class="col-sm-12">LALALLALA</div></div>' +
                            '</div>';
                    }
                    else {
                        //Consulting POPUP
                        console.log(fuelStation);
                        //<p><strong>"+fuelStation.name.toLowerCase().capitalize()+"</strong></p><p><b>Marca:</b> "+fuelStation.brand+"</p><p><b>Gasolina:</b> "+description_gasoline+"</p><p><b>Gasoleo:</b> "+description_diesel+"</p><p><b>GPL:</b> "+description_lpg+"</p><p><a href=\"#\">Modificar Dados</a></p>
                        let gasolineIcon = fuelStation.sell_gasoline && fuelStation.has_gasoline ?
                            '<img width="75px" src="img/map/VOSTPT_GASPUMP_GASOLINA_500pxX500px.png"/>' :
                            '<img class="img-no-gas" width="75px" src="img/map/VOSTPT_GASPUMP_GASOLINA_500pxX500px.png"/>';
                        let dieselIcon = fuelStation.sell_diesel && fuelStation.has_diesel ?
                            '<img width="75px" src="img/map/VOSTPT_GASPUMP_GASOLEO_500pxX500px.png"/>' :
                            '<img class="img-no-gas" width="75px" src="img/map/VOSTPT_GASPUMP_GASOLEO_500pxX500px.png"/>';
                        let lpgIcon = fuelStation.sell_lpg && fuelStation.has_lpg ?
                            '<img width="75px" src="img/map/VOSTPT_GASPUMP_GPL_500pxX500px.png"/>' :
                            '<img class="img-no-gas" width="75px" src="img/map/VOSTPT_GASPUMP_GPL_500pxX500px.png"/>';

                        let fuelStationName = fuelStation.name ? fuelStation.name.toUpperCase() : '';
                        description = '<div class="v-popup-content">' +
                            '<div class="v-popup-header"><h5>' + fuelStation.brand.toUpperCase() + '<br><small>' + fuelStationName + '</small></h5></div>' +
                            '<div class="v-popup-body">' +
                            '<div class="row">' +
                            '<div class="col-md-4 v-fuel-info">' + gasolineIcon + '<h6>GASOLINA</h6></div>' +
                            '<div class="col-md-4 v-fuel-info">' + dieselIcon + '<h6>GASOLEO</h6></div>' +
                            '<div class="col-md-4 v-fuel-info">' + lpgIcon + '<h6>GPL</h6></div>' +
                            '</div></div>' +
                            '<div class="v-popup-header"><h5>INFORMAÇÕES ADICIONAIS</h5></div>' +
                            '<div class="v-popup-body">Certis lapidosos congeriem. Aestu caesa tellure distinxit sidera. Conversa temperiemque verba fecit divino nubes umentia titan. Orbe membra circumfuso austro aer homo semina sponte grandia. </div>' +
                            '</div>';
                    }
                    points.push({
                        "type": "Feature",
                        "geometry": {
                            "type": "Point",
                            "coordinates": [fuelStation.long, fuelStation.lat]
                        },
                        "properties": {
                            "repa": fuelStation.repa,
                            "with_gasoline": with_gasoline,
                            "with_diesel": with_diesel,
                            "with_lpg": with_lpg,
                            "icon": icon,
                            "description": description,
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

    var filterGroup = document.getElementById('filter-group');

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
                var input = document.createElement('input');
                input.type = 'checkbox';
                input.id = layerID;
                input.checked = true;
                filterGroup.appendChild(input);

                var label = document.createElement('label');
                label.setAttribute('for', layerID);
                label.textContent = element;
                filterGroup.appendChild(label);

                input.addEventListener('change', function(e) {
                    map.setLayoutProperty(layerID, 'visibility', e.target.checked ? 'visible' : 'none');
                });
                map.on('click', layerID, function (e) {
                    var coordinates = e.features[0].geometry.coordinates.slice();
                    var description = e.features[0].properties.description;

                    while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                        coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                    }

                    new mapboxgl.Popup()
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
        });
    });
    map.on('error', function(error) {
        console.log('MAP LOAD ERROR')
        console.log(error);
    });


</script>
@endsection
