let points = [];
let promises = [];
const customAttributions = [
    'Thanks to <a href="https://www.facebook.com/WazePortugal/">Waze Portugal</a> for providing important data and permission to use their services',
    'This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy">Privacy Policy</a> and <a href="https://policies.google.com/terms">Terms of Service</a> apply',
];
let attributionControl = {
    "obj": null
};
let nagivationControl = {
    "obj": null
};
const fuel_layers = ['gasoline', 'diesel', 'lpg', 'none'];
const repa_layers = ['normal', 'sos', 'none'];

mapboxgl.accessToken = 'pk.eyJ1Ijoidm9zdHB0IiwiYSI6ImNqeXR3aHQxdTAyYjgzY21wbDMwaHJoaDQifQ.ql-IskzjOdAtEFvbltquaw';
var map = new mapboxgl.Map({
    container: 'map', // container id,
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [-7.8536599, 39.557191],
    zoom: 6,
    attributionControl: false
});

String.prototype.capitalize = function () {
    return this.replace(/(?:^|\s)\S/g, function (a) {
        return a.toUpperCase();
    });
};

function loadPoints() {
    return new Promise(function (resolve, reject) {
        points = [];
        $.getJSON("/storage/data/cache.json", (data) => {
            data.forEach(fuelStation => {
                let with_gasoline = (fuelStation.sell_gasoline && fuelStation.has_gasoline);
                let with_diesel = (fuelStation.sell_diesel && fuelStation.has_diesel);
                let with_lpg = (fuelStation.sell_lpg && fuelStation.has_lpg);
                let with_none = (!with_gasoline) && (!with_diesel) && (!with_lpg);
                let icon = '';
                let popup_color = '';
                let background_color = '';
                let priority = 0;
                let brand = fuelStation.brand;
                if (fuelStation.repa == "SOS") {
                    fuelStation.repa = "sos";
                    icon = 'REPA';
                    priority = 2;
                    popup_color = '0070bb';
                    background_color = "a9aeff";
                    brand = brand + " (REPA - Veículos Prioritários)";
                    priority = 2;
                } else if (fuelStation.repa == "Normal") {
                    fuelStation.repa = "normal";
                    icon = 'REPA';
                    priority = 1;
                    popup_color = '0070bb';
                    background_color = "a9aeff";
                    brand = brand + " (REPA - Todos os Veículos)";
                    priority = 1;
                } else {
                    let count = 0;
                    if (with_gasoline || (!fuelStation.sell_gasoline)) {
                        count++;
                    }
                    if (with_diesel || (!fuelStation.sell_diesel)) {
                        count++;
                    }
                    if (with_lpg || (!fuelStation.sell_lpg)) {
                        count++;
                    }
                    if (count == 3) {
                        icon = 'ALL';
                        popup_color = '006837';
                        background_color = "53ea9f";
                    } else if (count == 0) {
                        icon = 'NONE';
                        popup_color = 'c1272c';
                        background_color = "ff838b";
                    } else {
                        icon = 'PARTIAL';
                        popup_color = 'f7921e';
                        background_color = "f1b87d";
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
                        "brand": brand,
                        "repa": fuelStation.repa,
                        "with_gasoline": with_gasoline,
                        "with_diesel": with_diesel,
                        "with_lpg": with_lpg,
                        "with_none": with_none,
                        "sell_gasoline": fuelStation.sell_gasoline,
                        "sell_diesel": fuelStation.sell_diesel,
                        "sell_lpg": fuelStation.sell_lpg,
                        "has_gasoline": fuelStation.has_gasoline,
                        "has_diesel": fuelStation.has_diesel,
                        "has_lpg": fuelStation.has_lpg,
                        "icon": icon,
                        "popup_color": popup_color,
                        "background_color": background_color,
                        "priority": priority
                    }
                });
            });
            resolve();
        });
    });

}

function loadBrandImage(brand, url) {
    return new Promise(function (resolve, reject) {
        map.loadImage(url, function (error, image) {
            if (error) {
                console.log("ERROR:" + error);
                reject(error);
            } else {
                console.log("IMAGE LOADED");
                map.addImage(brand, image);
                resolve();
            }
        });
    });
}

function getAttributions() {
    var date = new Date;
    var seconds = date.getSeconds();
    var minutes = date.getMinutes();
    var hour = date.getHours();
    let attributions = [...customAttributions];
    attributions.push('Última Atualização às: ' + ("0" + hour).slice(-2) + 'h' + ("0" + minutes).slice(-2) + 'm' + ("0" + seconds).slice(-2) + 's');
    return attributions;
}

function updatePoints() {
    promises.push(loadPoints());
    Promise.all(promises).then(function () {
        promises = [];
        repa_layers.forEach(repa_element => {
            let repa_value = repa_element;
            if (repa_value == "none") {
                repa_value = '';
            }
            fuel_layers.forEach(fuel_element => {
                let layerID = 'poi-' + repa_element + '-' + fuel_element;
                map.removeLayer(layerID);
            });
        });
        map.removeSource("points");
        map.addSource("points", {
            "type": "geojson",
            "data": {
                "type": "FeatureCollection",
                "features": points
            }
        });
        repa_layers.forEach(repa_element => {
            let repa_value = repa_element;
            if (repa_value == "none") {
                repa_value = '';
            }
            fuel_layers.forEach(fuel_element => {
                let layerID = 'poi-' + repa_element + '-' + fuel_element;
                map.addLayer({
                    "id": layerID,
                    "type": "symbol",
                    "source": "points",
                    "layout": {
                        "icon-image": "{icon}",
                        "symbol-sort-key": ["get", "priority"],
                    }
                });
                map.setFilter(layerID, [
                    "all",
                    [">", "with_" + fuel_element, 0],
                    ['==', 'repa', repa_value]
                ]);
            });
        });
        map.removeControl(nagivationControl.obj);
        map.removeControl(attributionControl.obj);
        delete attributionControl.obj;
        attributionControl.obj = new mapboxgl.AttributionControl({
            compact: true,
            customAttribution: getAttributions()
        });
        map.addControl(attributionControl.obj);
        map.addControl(nagivationControl.obj, "bottom-right");
        updateLayersOptions();
    });
}

function addLayersFunctionality(layerID) {
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
        let fuelIcons = "";
        if (isHelping()) {
            fuelIcons = "";
            if (e.features[0].properties.sell_gasoline) {
                fuelIcons += '<div class="col-md v-fuel-info gasoline"><a href="#" onclick="swapIcon(this)">' + gasolineIcon + '</a><h6>GASOLINA</h6></div>';
            }
            if (e.features[0].properties.sell_diesel) {
                fuelIcons += '<div class="col-md v-fuel-info diesel"><a href="#" onclick="swapIcon(this)">' + dieselIcon + '</a><h6>GASÓLEO</h6></div>';
            }
            if (e.features[0].properties.sell_lpg) {
                fuelIcons += '<div class="col-md v-fuel-info lpg"><a href="#" onclick="swapIcon(this)">' + lpgIcon + '</a><h6>GPL</h6></div>';
            }
        } else {
            fuelIcons = "";
            if (e.features[0].properties.sell_gasoline) {
                fuelIcons += '<div class="col-md v-fuel-info">' + gasolineIcon + '<h6>GASOLINA</h6></div>';
            }
            if (e.features[0].properties.sell_diesel) {
                fuelIcons += '<div class="col-md v-fuel-info">' + dieselIcon + '<h6>GASÓLEO</h6></div>';
            }
            if (e.features[0].properties.sell_lpg) {
                fuelIcons += '<div class="col-md v-fuel-info">' + lpgIcon + '<h6>GPL</h6></div>';
            }
        }
        if (isHelping()) {
            description = '<div class="v-popup-content">' +
                '<div class="v-popup-header" style="background-color:#85d5f8; text-align: center;"><h5>ADICIONAR INFORMAÇÃO</h5></div>' +
                '<div class="v-popup-body" style="background-color:#b8e1f8">' +
                '<div class="row">' +
                fuelIcons +
                '</div>' +
                '<img src="/img/map/separation.png" style="width: calc(100% + 1.6em); margin-left:-0.8em;" />' +
                '<div class="row"><div class="col-md"><b>POR FAVOR INDICA QUE COMBUSTÍVEIS NÃO ESTÃO</b></div></div>'+
                '<div class="row"><div class="col-md"><b>DISPONÍVEIS NA ' + fuelStationName + '.</b></div></div>' +
                '<div class="row"><div class="col-md"><b>CARREGA NAS IMAGENS.</b></div></div>' +
                '</div>' +
                '<div class="v-popup-header" style="background-color:#85d5f8"><a href="#" onclick="submitEntry(this,' + e.features[0].properties.id + ')"><h5 class="popup_submit_text">VALIDAR</h5></a></div>' +
                '</div>';
        } else {
            description = '<div class="v-popup-content">' +
                '<div class="v-popup-header" style="background-color: #' + e.features[0].properties.popup_color + '"><h5>' + e.features[0].properties.brand.toUpperCase() + '<br><small>' + fuelStationName + '</small></h5></div>' +
                '<div class="v-popup-body" style="background-color: #' + e.features[0].properties.background_color + '">' +
                '<div class="row">' +
                fuelIcons +
                '</div>' +
                '</div>' +
                '<div class="v-popup-body directions"><a href="https://www.waze.com/ul?ll=' + coordinates[1] + '%2C' + coordinates[0] + '&navigate=yes&zoom=16&download_prompt=false"  target="_blank" rel="noopener noreferrer">'+
                '<img src="/img/map/map_separation_'+e.features[0].properties.background_color+'.png" style="width: 100%;" />'+
                '</a></div>' +
                '<div class="v-popup-header" style="background-color: #' + e.features[0].properties.popup_color + '"><a href="https://www.waze.com/ul?ll=' + coordinates[1] + '%2C' + coordinates[0] + '&navigate=yes&zoom=16&download_prompt=false"><h5>OBTER DIREÇÕES</h5></a></div>' +
                '</div>';
        }
        while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
            coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
        }

        map.flyTo({
            center: coordinates,
            zoom: 14
        });

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
}

map.on('load', function () {
    $(".mapboxgl-ctrl-logo").css("float", "left");
    $(".mapboxgl-ctrl-bottom-left .mapboxgl-ctrl").append("<a style=\"cursor: pointer;\" target=\"_blank\" rel=\"noopener nofollow\"  href=\"https://twitter.com/vostpt\"><img src=\"/img/VOSTPT_LETTERING_COLOR.png\" style=\"height: 42px; margin-top: -15px; margin-left: 10px;\"/></a>");
    promises.push(loadBrandImage('REPA', '/img/map/VOSTPT_JNDPA_REPA_ICON_25x25.png'));
    promises.push(loadBrandImage('NONE', '/img/map/VOSTPT_JNDPA_NONE_ICON_25x25.png'));
    promises.push(loadBrandImage('PARTIAL', '/img/map/VOSTPT_JNDPA_PARTIAL_ICON_25x25.png'));
    promises.push(loadBrandImage('ALL', '/img/map/VOSTPT_JNDPA_ALL_ICON_25x25.png'));
    promises.push(loadPoints());
    Promise.all(promises).then(function () {
        promises = [];
        map.addSource("points", {
            "type": "geojson",
            "data": {
                "type": "FeatureCollection",
                "features": points
            }
        });
        repa_layers.forEach(repa_element => {
            let repa_value = repa_element;
            if (repa_value == "none") {
                repa_value = '';
            }
            fuel_layers.forEach(fuel_element => {
                let layerID = 'poi-' + repa_element + '-' + fuel_element;
                map.addLayer({
                    "id": layerID,
                    "type": "symbol",
                    "source": "points",
                    "layout": {
                        "icon-image": "{icon}",
                        "symbol-sort-key": ["get", "priority"],
                    }
                });
                map.setFilter(layerID, [
                    "all",
                    [">", "with_" + fuel_element, 0],
                    ['==', 'repa', repa_value]
                ]);
                addLayersFunctionality(layerID);
            });
        });
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(position => {
                map.flyTo({
                    center: [position.coords.longitude, position.coords.latitude],
                    zoom: 14
                });
            });
        }
        attributionControl.obj = new mapboxgl.AttributionControl({
            compact: true,
            customAttribution: getAttributions()
        });
        map.addControl(attributionControl.obj);
        nagivationControl.obj = new mapboxgl.NavigationControl({
            visualizePitch: true,
            showZoom: true,
            showCompass: true
        });
        map.addControl(nagivationControl.obj, 'bottom-right');
        updateLayersOptions();
        setInterval(updatePoints, 30000);
    });
});
map.on('error', function (error) {
    console.log('MAP LOAD ERROR');
    console.log(error);
});

function updateLayersOptions() {
    let type = $("input.type[type=radio]:checked").val();
    let repa = [];
    let repa_objects = $('input[name="fuel_stations_repa[]"]:checked');
    Object.values(repa_objects).forEach(repa_object => {
        let value = repa_object.value;
        if (value) {
            repa.push(value);
        }
    });
    repa_layers.forEach(repa_element => {
        fuel_layers.forEach(fuel_element => {
            let layerID = 'poi-' + repa_element + '-' + fuel_element;
            let repa_condition = repa.includes(repa_element);
            let fuel_condition = ((fuel_element == type) || (type == "all"));
            let condition = repa_condition && fuel_condition;
            map.setLayoutProperty(layerID, 'visibility', (condition) ? 'visible' : 'none');
        });
    });
}

$('input.type[type=radio]').change(function () {
    updateLayersOptions();
});

$('input.repa[type=checkbox]').change(function () {
    updateLayersOptions();
});
