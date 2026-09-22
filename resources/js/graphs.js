import { Chart, DoughnutController, ArcElement, BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend, Title } from 'chart.js';

Chart.register(DoughnutController, ArcElement, BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend, Title);

const statusColors = { all: '#2e7d32', partial: '#f9a825', none: '#c62828', unknown: '#9e9e9e' };

async function fetchJson(url) {
    const response = await fetch(url, { cache: 'no-cache' });
    return response.json();
}

function renderStationsDoughnut(canvas, stats) {
    const ctx = canvas.getContext('2d');
    if (canvas._chart) canvas._chart.destroy();
    canvas._chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Com tudo', 'Parcial', 'Sem nada'],
            datasets: [{
                data: [stats.stations_all, stats.stations_partial, stats.stations_none],
                backgroundColor: [statusColors.all, statusColors.partial, statusColors.none],
            }],
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
        },
    });
}

function renderShortagesBar(canvas, stats) {
    const ctx = canvas.getContext('2d');
    if (canvas._chart) canvas._chart.destroy();
    canvas._chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Gasolina', 'Gasóleo', 'GPL'],
            datasets: [{
                label: 'Sem stock',
                data: [stats.stations_no_gasoline, stats.stations_no_diesel, stats.stations_no_lpg],
                backgroundColor: statusColors.none,
            }, {
                label: 'Vende',
                data: [stats.stations_sell_gasoline, stats.stations_sell_diesel, stats.stations_sell_lpg],
                backgroundColor: statusColors.all,
            }],
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } },
        },
    });
}

async function refresh() {
    const globalStats = await fetchJson('/storage/data/stats_global.json');
    const entriesStats = await fetchJson('/storage/data/stats_entries.json');

    document.getElementById('entries-total')?.replaceChildren(document.createTextNode(entriesStats.entries_total ?? 0));
    document.getElementById('entries-last-day')?.replaceChildren(document.createTextNode(entriesStats.entries_last_day ?? 0));
    document.getElementById('entries-last-hour')?.replaceChildren(document.createTextNode(entriesStats.entries_last_hour ?? 0));
    document.getElementById('stations-total')?.replaceChildren(document.createTextNode(globalStats.stations_total ?? 0));

    const stationsCanvas = document.getElementById('stations-chart');
    if (stationsCanvas) renderStationsDoughnut(stationsCanvas, globalStats);

    const shortagesCanvas = document.getElementById('shortages-chart');
    if (shortagesCanvas) renderShortagesBar(shortagesCanvas, globalStats);
}

document.addEventListener('DOMContentLoaded', () => {
    refresh().catch((err) => console.error('stats refresh failed', err));
    setInterval(refresh, 30_000);
});
