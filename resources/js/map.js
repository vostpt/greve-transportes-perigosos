let helping = false;
let points = [];
let promises = [];
let popup = null;
const customAttributions = [
    'Thanks to <a href="https://waze.com/pt" >Waze</a> for providing important data and permission to use their services',
    'This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy">Privacy Policy</a> and <a href="https://policies.google.com/terms">Terms of Service</a> apply',
];
let attributionControl = {"obj": null};
const fuel_layers = ['gasoline', 'diesel', 'lpg', 'none'];
const repa_layers = ['normal', 'sos', 'none'];

mapboxgl.accessToken = 'pk.eyJ1IjoiY290ZW1lcm8iLCJhIjoiY2p5NzQyeTdvMDc1MzNlbGNnbzh3NjVuOCJ9.cPrQc61yiHA0kOptuuZsSA';
var map = new mapboxgl.Map({
    container: 'map', // container id,
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [-7.8536599, 39.557191],
    zoom: 6,
    attributionControl: false
});

function consult() {
    $("#map").css({
        "border": "0"
    });
    helping = false;
    $("#selector_view").css("visibility", "hidden");
    $("#map_view").css("visibility", "visible");
}

function help() {
    $("#warning").css("visibility", "visible");
    $("#map").css({
        "border-color": "#2f86ca",
        "border-width": "3px",
        "border-style": "solid"
    });
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
    if (img.hasClass("no-gas")) {
        img.removeClass("no-gas");
    } else {
        img.addClass("no-gas");
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
        $.post("/entries/add", data, function (reply) {
            console.log("Entrada adicionada: " + reply.success + " (0 -> falha, 1 -> sucesso)");
        }, "json");
    });
}

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
                let priority = 0;
                let brand = fuelStation.brand;
                if (fuelStation.repa == "SOS") {
                    fuelStation.repa = "sos";
                    icon = 'REPA';
                    priority = 2;
                    popup_color = '#2f86ca';
                    brand = brand + " (REPA - Veículos Prioritários)";
                } else if (fuelStation.repa == "Normal") {
                    fuelStation.repa = "normal";
                    icon = 'REPA';
                    priority = 1;
                    popup_color = '#2f86ca';
                    brand = brand + " (REPA - Todos os Veículos)";
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
                        popup_color = '#65ac3d';
                    } else if (count == 0) {
                        icon = 'NONE';
                        popup_color = '#b32e25';
                    } else {
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
                        "priority": 0
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
        map.removeControl(attributionControl.obj);
        delete attributionControl.obj;
        attributionControl.obj = new mapboxgl.AttributionControl({
            compact: false,
            customAttribution: getAttributions()
        });
        map.addControl(attributionControl.obj);
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
        if (helping) {
            description = '<div class="v-popup-content">' +
                '<div class="v-popup-header" style="background-color:#6bd7fc"><h5>' + e.features[0].properties.brand.toUpperCase() + '<br><small>' + fuelStationName + '</small></h5></div>' +
                '<div class="v-popup-body" style="background-color:#ffffff">' +
                '<div class="row">' +
                '<div class="col-md-4 v-fuel-info gasoline"><a href="#" onclick="swapIcon(this)">' + gasolineIcon + '</a><h6>GASOLINA</h6></div>' +
                '<div class="col-md-4 v-fuel-info diesel"><a href="#" onclick="swapIcon(this)">' + dieselIcon + '</a><h6>GASOLEO</h6></div>' +
                '<div class="col-md-4 v-fuel-info lpg"><a href="#" onclick="swapIcon(this)">' + lpgIcon + '</a><h6>GPL</h6></div>' +
                '</div>' +
                '<div class="row"><div class="col-md-12">Por favor indica que combústiveis não estão disponiveis na ' + fuelStationName + '.</div></div>' +
                '<div class="row"><div class="col-md-12">Carrega nas imagens deixando as disponiveis mais nitidas.</div></div>' +
                '</div>' +
                '<div class="v-popup-header" style="background-color:#6bd7fc"><a href="#" onclick="submitEntry(this,' + e.features[0].properties.id + ')"><h5>VALIDAR</h5></a></div>' +
                '</div>';
        } else {
            description = '<div class="v-popup-content">' +
                '<div class="v-popup-header" style="background-color:' + e.features[0].properties.popup_color + '"><h5>' + e.features[0].properties.brand.toUpperCase() + '<br><small>' + fuelStationName + '</small></h5></div>' +
                '<div class="v-popup-body">' +
                '<div class="row">' +
                '<div class="col-md-4 v-fuel-info">' + gasolineIcon + '<h6>GASOLINA</h6></div>' +
                '<div class="col-md-4 v-fuel-info">' + dieselIcon + '<h6>GASOLEO</h6></div>' +
                '<div class="col-md-4 v-fuel-info">' + lpgIcon + '<h6>GPL</h6></div>' +
                '</div></div>' +
                '<div class="v-popup-header" style="background-color:' + e.features[0].properties.popup_color + '"><h5>OBTER DIREÇÕES</h5></div>' +
                '<div class="v-popup-body directions"><a href="https://www.waze.com/ul?ll=' + coordinates[1] + '%2C' + coordinates[0] + '&navigate=yes&zoom=16&download_prompt=false"  target="_blank" rel="noopener noreferrer"><img src="/img/map/map_blur.png"></a></div>' +
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
}

map.on('load', function () {
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
            compact: false,
            customAttribution: getAttributions()
        });
        map.addControl(attributionControl.obj);
        updateLayersOptions();
        setInterval(updatePoints, 30000);
    });
});
map.on('error', function (error) {
    console.log('MAP LOAD ERROR')
    console.log(error);
});

function updateLayersOptions() {
    let type = $("input.type[type=radio]:checked").val();
    let repa = $("input.repa[type=radio]:checked").val();
    repa_layers.forEach(repa_element => {
        fuel_layers.forEach(fuel_element => {
            let layerID = 'poi-' + repa_element + '-' + fuel_element;
            let repa_condition = ((repa_element == repa) || (repa == "all"));
            let fuel_condition = ((fuel_element == type) || (type == "all"));
            let condition = repa_condition && fuel_condition;
            map.setLayoutProperty(layerID, 'visibility', (condition) ? 'visible' : 'none');
        });
    });
}

$('input.type[type=radio]').change(function () {
    updateLayersOptions();
});

$('input.repa[type=radio]').change(function () {
    updateLayersOptions();
});
