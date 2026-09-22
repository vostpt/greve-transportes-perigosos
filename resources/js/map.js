import '../sass/map.scss';
import mapboxgl from 'mapbox-gl';
import MapboxGeocoder from '@mapbox/mapbox-gl-geocoder';

const config = window.__MAP_CONFIG__ ?? {};

if (!config.token) {
    console.warn('Mapbox token missing (VITE_MAPBOX_TOKEN / config[map.token])');
}

mapboxgl.accessToken = config.token ?? '';

const map = new mapboxgl.Map({
    container: 'map',
    style: config.style ?? 'mapbox://styles/mapbox/streets-v12',
    center: config.center ?? [-8.5, 39.5],
    zoom: config.zoom ?? 6.5,
    minZoom: 5,
    maxZoom: 18,
});

map.addControl(new mapboxgl.NavigationControl(), 'top-left');
map.addControl(new mapboxgl.GeolocateControl({
    positionOptions: { enableHighAccuracy: true },
    trackUserLocation: true,
    showUserHeading: true,
}), 'top-left');
map.addControl(new MapboxGeocoder({
    accessToken: mapboxgl.accessToken,
    mapboxgl,
    countries: 'pt',
    language: 'pt',
    placeholder: 'Procurar...',
}), 'top-left');

const filters = {
    type: 'all',
    brands: [],
};

document.querySelectorAll('input[name="fuel_stations_type"]').forEach((input) => {
    input.addEventListener('change', (event) => {
        filters.type = event.target.value;
        applyFilters();
    });
});

document.querySelectorAll('input[name="fuel_stations_brand[]"]').forEach((input) => {
    input.addEventListener('change', () => {
        filters.brands = Array.from(document.querySelectorAll('input[name="fuel_stations_brand[]"]:checked')).map((el) => el.value);
        applyFilters();
    });
});

document.getElementById('reset-brand-filter')?.addEventListener('click', (e) => {
    e.preventDefault();
    document.querySelectorAll('input[name="fuel_stations_brand[]"]').forEach((el) => { el.checked = false; });
    filters.brands = [];
    applyFilters();
});

document.getElementById('toggle-filters')?.addEventListener('click', (e) => {
    e.preventDefault();
    document.getElementById('map-filters')?.classList.toggle('d-none');
});

document.getElementById('toggle-legend')?.addEventListener('click', (e) => {
    e.preventDefault();
    document.getElementById('map-legend')?.classList.toggle('d-none');
});

let allStations = [];

function classify(station) {
    const sells = ['sell_gasoline', 'sell_diesel', 'sell_lpg'];
    const has = ['has_gasoline', 'has_diesel', 'has_lpg'];
    const soldItems = sells.filter((key) => station[key]).length;
    const availableItems = sells.filter((_, i) => station[sells[i]] && station[has[i]]).length;

    if (soldItems === 0) return 'unknown';
    if (availableItems === soldItems) return 'all';
    if (availableItems === 0) return 'none';
    return 'partial';
}

function matches(station) {
    if (filters.brands.length && !filters.brands.includes(station.brand)) {
        return false;
    }
    switch (filters.type) {
        case 'all': return true;
        case 'gasoline': return station.sell_gasoline && station.has_gasoline;
        case 'diesel': return station.sell_diesel && station.has_diesel;
        case 'lpg': return station.sell_lpg && station.has_lpg;
        case 'without_gasoline': return station.sell_gasoline && !station.has_gasoline;
        case 'without_diesel': return station.sell_diesel && !station.has_diesel;
        case 'without_lpg': return station.sell_lpg && !station.has_lpg;
        case 'none': return classify(station) === 'none';
        default: return true;
    }
}

function stationFeature(station) {
    return {
        type: 'Feature',
        geometry: { type: 'Point', coordinates: [station.long, station.lat] },
        properties: {
            id: station.id,
            name: station.name,
            brand: station.brand,
            status: classify(station),
            repa: station.repa,
            sell_gasoline: station.sell_gasoline,
            sell_diesel: station.sell_diesel,
            sell_lpg: station.sell_lpg,
            has_gasoline: station.has_gasoline,
            has_diesel: station.has_diesel,
            has_lpg: station.has_lpg,
        },
    };
}

function applyFilters() {
    const source = map.getSource('stations');
    if (!source) return;
    source.setData({
        type: 'FeatureCollection',
        features: allStations.filter(matches).map(stationFeature),
    });
}

async function loadStations() {
    const response = await fetch('/storage/data/cache.json', { cache: 'no-cache' });
    allStations = await response.json();
    applyFilters();
}

const statusColors = {
    all: '#2e7d32',
    partial: '#f9a825',
    none: '#c62828',
    unknown: '#9e9e9e',
};

map.on('load', async () => {
    map.addSource('stations', { type: 'geojson', data: { type: 'FeatureCollection', features: [] } });

    map.addLayer({
        id: 'stations-layer',
        type: 'circle',
        source: 'stations',
        paint: {
            'circle-radius': [
                'interpolate', ['linear'], ['zoom'],
                5, 4,
                10, 7,
                14, 10,
            ],
            'circle-color': [
                'match', ['get', 'status'],
                'all', statusColors.all,
                'partial', statusColors.partial,
                'none', statusColors.none,
                statusColors.unknown,
            ],
            'circle-stroke-width': 1,
            'circle-stroke-color': '#fff',
        },
    });

    map.on('click', 'stations-layer', (event) => {
        const feature = event.features?.[0];
        if (!feature) return;
        const props = feature.properties;

        const line = (label, sells, has) => sells === '1' || sells === true
            ? `<dt>${label}</dt><dd>${has === '1' || has === true ? 'Disponível' : '<strong>Indisponível</strong>'}</dd>`
            : '';

        new mapboxgl.Popup({ maxWidth: '280px' })
            .setLngLat(event.lngLat)
            .setHTML(`
                <div class="map-popup">
                    <strong>${props.name}</strong><br>
                    <small class="text-muted">${props.brand}</small>
                    <dl class="mt-2">
                        ${line('Gasolina', props.sell_gasoline, props.has_gasoline)}
                        ${line('Gasóleo', props.sell_diesel, props.has_diesel)}
                        ${line('GPL', props.sell_lpg, props.has_lpg)}
                    </dl>
                </div>
            `)
            .addTo(map);
    });

    map.on('mouseenter', 'stations-layer', () => { map.getCanvas().style.cursor = 'pointer'; });
    map.on('mouseleave', 'stations-layer', () => { map.getCanvas().style.cursor = ''; });

    await loadStations();
    setInterval(loadStations, 60_000);
});
