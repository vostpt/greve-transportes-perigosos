// portuguese boundaries
const SW = new L.LatLng(42.20, -9.54);
const NE = new L.LatLng(36.95, -6.15);
const bounds = new L.LatLngBounds(SW, NE);
const ICON_SIZE = 16;

// fit view to bounds
const map = L.map('map').fitBounds(bounds);

// set tiles
L.tileLayer('http://services.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}.png', {
	maxZoom: 18
}).addTo(map);

// add icon layer
var iconLayer = L.canvasIconLayer({}).addTo(map);

// set icons
function icon(emoji) {
    return L.icon({
        iconUrl: twemoji.base + '72x72/' + twemoji.convert.toCodePoint(emoji) + '.png',
        iconSize: [20, 18],
        iconAnchor: [10, 9]
    });
}
const icons = {
    pump: icon('⛽'),
    only_diesel: icon('⚫'),
    only_gas: icon('🔵'),
    empty: icon('🔴'),
    unknown: icon('🟡'),
    known: icon('🔵')
};

// get JSON
$.getJSON('postos-reports.json', function(data) {
    const postos = data['stations']
    let markers = [];

    const last_update = moment.unix(data['last_update']).format('YYYY-MM-DD HH:mm:ss');

    for (let p of Object.values(postos)) {
        let icon;

        // figure out what's missing
        let has_diesel = true;
        let has_gas = true;
        for (let match of p.matches) {
            if (match['Tipo Gasóleo Não Disponível'] == 'Todos') {
                has_diesel = false;
            }

            if (match['Tipo de Gasolina Não Disponível'] == 'Todos') {
                has_gas = false;
            }
        }

        if (p.matches.length > 0) {
            if (!has_diesel && !has_gas)
                icon = icons.empty
            else if (has_gas)
                icon = icons.only_gas
            else if (has_diesel)
                icon = icons.only_diesel
        }
        else {
            icon = icons.unknown;
        }
        
    
        const coords = [p['latitude'], p['longitude']];
        const marker = L.marker(coords, {
            icon: icon
        });

        marker.bindPopup(`<div class="popup">
            <b>${p.name}</b><hr/>
            <b>Distrito: </b> ${p.distrito}<br/>
            <b>Concelho: </b> ${p.municipio}<br/>
            <table>
                <tr>
                    <th>Certeza</th>
                    <th>Nome Posto</th>
                    <th>Localidade</th>
                    <th>Tipo em Falta</th>
                    <th>Tipo Gasoleo</th>
                    <th>Tipo Gasolina</th>
                </tr>
                ${
                    p.matches.map(t => `<tr>
                        <td>${t['Nome Posto Combustível']}</td>
                        <td>${t['Localidade']}</td>
                        <td>${t['Tipo de Combustível Não Disponível']}</td>
                        <td>${t['Tipo Gasóleo Não Disponível']}</td>
                        <td>${t['Tipo de Gasolina Não Disponível']}</td>
                    </tr>`).join('')
                }
            </table>
        </div>`);
        markers.push(marker);
    }

    // add legend
    var legend = L.control({position: 'bottomleft'});
    legend.onAdd = function (map){
        var div = L.DomUtil.create('div', 'info');
        div.appendChild(icons.unknown.createIcon());
        div.innerHTML += 'Tudo disponível/Sem informação<br/>';

        div.appendChild(icons.only_diesel.createIcon());
        div.innerHTML += 'Só há gasóleo<br/>';

        div.appendChild(icons.only_gas.createIcon());
        div.innerHTML += 'Só há gasolina<br/>';

        div.appendChild(icons.empty.createIcon());
        div.innerHTML += 'Esgotada <br/>';

        div.innerHTML += `<strong>Última actualização:</strong> ${last_update}`
        
        return div;
    };
    legend.addTo(map);

    iconLayer.addMarkers(markers);
});
