function findGetParameter(parameterName) {
    var result = null,
        tmp = [];
    location.search
        .substr(1)
        .split("&")
        .forEach(function (item) {
            tmp = item.split("=");
            if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
        });
    return result;
}

function Get(yourUrl) {
    var Httpreq = new XMLHttpRequest(); // a new request
    Httpreq.open("GET", yourUrl, false);
    Httpreq.send(null);
    return Httpreq.responseText;
}
window.onload = function () {
    let district = findGetParameter("distrito");
    let county = findGetParameter("concelho");
    let url = "";
    if (district) {
        if (county) {
            url = "/storage/data/stats_" + encodeURI(district) + "_" + encodeURI(county) + ".json"
        } else {
            county = null;
            url = "/storage/data/stats_" + encodeURI(district) + ".json";
        }
    } else {
        district = "Portugal";
        county = null;
        url = "/storage/data/stats_global.json";
    }
    let data = JSON.parse(Get(url));
    let stations_options = {
        circumference: Math.PI,
        rotation: -Math.PI,
        responsive: true,
        legend: {
            position: 'bottom',
            fullWidth: true,
            labels: {
                fontStyle: "bold",
                fontColor: "#000",
                boxWidth: 40,
                fontSize: 14,
                generateLabels: function (chart) {
                    var data = chart.data;
                    if (data.labels.length && data.datasets.length) {
                        let obj = data.labels.map(function (label, i) {
                            var meta = chart.getDatasetMeta(0);
                            return {
                                text: label.toUpperCase(),
                                fillStyle: data.datasets[0].backgroundColor[i],
                                strokeStyle: "solid",
                                lineWidth: "0",
                                hidden: isNaN(data.datasets[0].data[i]) || meta.data[i].hidden,

                                // Extra data used for toggling the correct item
                                index: i
                            };
                        });
                        return obj;
                    }
                    return [];
                }
            }
        },
        title: {
            display: true,
            position: 'top',
            text: ['VISÃO GERAL AGREGADA', '(Universo Total: ' + data["stations_total"] + ' postos)'],
            fontSize: 20,
            fontColor: '#46ace3'
        },
        animation: {
            animateScale: false,
            animateRotate: false
        },
        plugins: {
            datalabels: {
                anchor: 'center',
                align: 'center',
                color: '#ffffff',
                formatter: function (value, context) {
                    if (value == 0) {
                        return "";
                    }
                    return value + "\n" + (value * 100 / data["stations_total"]).toFixed(2) + "%\n";
                },
                "font": {
                    "size": "13"
                }
            }
        },
        tooltips: {
            enabled: true,
            mode: 'single',
            callbacks: {
                label: function (tooltipItems, data) {
                    return data['datasets'][0]['label'][tooltipItems['index']];
                }
            }
        }
    };
    let stations_data = {
        labels: ["Sem Nenhum Combustível", "Com Algum Tipo de Combustível", "Com Todos os Combustíveis"],
        datasets: [{
            data: [data.stations_none, data.stations_partial, data.stations_all],
            backgroundColor: [
                "#c1272c",
                "#f7921e",
                "#006837"
            ],
            label: [
                "Sem Nenhum Combustível",
                "Com Algum Tipo de Combustível",
                "Com Todos os Combustíveis"
            ]
        }]
    };
    let types_options = {
        circumference: Math.PI,
        rotation: -Math.PI,
        responsive: true,
        legend: {
            position: 'bottom',
            fullWidth: true,
            labels: {
                fontStyle: "bold",
                fontColor: "#000",
                boxWidth: 40,
                fontSize: 14,
                generateLabels: function (chart) {
                    var data = chart.data;
                    if (data.labels.length && data.datasets.length) {
                        let obj = data.labels.map(function (label, i) {
                            var meta = chart.getDatasetMeta(0);
                            return {
                                text: label.toUpperCase(),
                                fillStyle: data.datasets[0].backgroundColor[i],
                                strokeStyle: "solid",
                                lineWidth: "0",
                                hidden: isNaN(data.datasets[0].data[i]) || meta.data[i].hidden,

                                // Extra data used for toggling the correct item
                                index: i
                            };
                        });
                        return obj;
                    }
                    return [];
                }
            }
        },
        title: {
            display: true,
            position: 'top',
            text: ['FALTAS POR TIPO DE COMBUSTÍVEL', '(Universo Total: ' + data["stations_total"] + ' postos)'],
            fontSize: 20,
            fontColor: '#b13d3a'
        },
        animation: {
            animateScale: false,
            animateRotate: false
        },
        plugins: {
            datalabels: {
                color: '#ffffff',
                formatter: function (value, context) {
                    if (value == 0) {
                        return "";
                    }
                    return value + "\n" + (value * 100 / data["stations_total"]).toFixed(2) + "%\n";
                },
                "font": {
                    "size": "100%"
                }
            }
        },
        tooltips: {
            enabled: true,
            mode: 'single',
            callbacks: {
                label: function (tooltipItems, data) {
                    return data['datasets'][0]['label'][tooltipItems['index']];
                }
            }
        },
    }
    let types_data = {
        labels: ["Sem Gasolina", "Sem Gasóleo", "Sem GPL"],
        datasets: [{
            data: [data.stations_no_gasoline, data.stations_no_diesel, data.stations_no_lpg],
            backgroundColor: [
                "#b38614",
                "#a16608",
                "#703200"
            ],
            label: [
                "Sem Gasolina",
                "Sem Gasóleo",
                "Sem GPL"
            ]
        }]
    };
    var stations_ctx = document.getElementById('stations-chart-area').getContext('2d');
    var stations_chart = new Chart(stations_ctx, {
        type: 'doughnut',
        data: stations_data,
        options: stations_options
    });
    var types_ctx = document.getElementById('types-chart-area').getContext('2d');
    var types_chart = new Chart(types_ctx, {
        type: 'doughnut',
        data: types_data,
        options: types_options
    });
};
