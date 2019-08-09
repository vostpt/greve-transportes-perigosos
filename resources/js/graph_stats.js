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
    charts(url);
};


function charts(dataSourceUri) {
    let data = JSON.parse(Get(dataSourceUri));

    google.charts.load("current", {
        packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(() => {
        let dataTable1 = new google.visualization.DataTable();

        dataTable1.addColumn('string', 'Combustivel');
        dataTable1.addColumn('number', 'Postos');
        dataTable1.addRows([
            ['Todos', data.stations_all],
            ['Parte', data.stations_partial],
            ['Nenhum', data.stations_none]
        ]);

        let options = {
            pieHole: 0.2,
            chartArea: {
                top: 50,
                height: "300px"
            },
            height: 300,
            legend: {
                position: "top",
                alignment: "center",
            },
            pieSliceText: 'value-and-percentage',
            tooltip: {
                ignoreBounds: true
            },
            sliceVisibilityThreshold: 0
        };

        let optionsChart1 = Object.assign(options, {
            colors: ['#8BC34A', '#f6bd00', '#f62317'],
            backgroundColor: { fill:'transparent' }
        });
        let chart1 = new google.visualization.PieChart(document.getElementById('stations-chart-area'));
        chart1.draw(dataTable1, optionsChart1);
        document.getElementById('stations_total_number').innerHTML = data["stations_total"];
        

        var dataTable2 = google.visualization.arrayToDataTable([
            ['Combustivel', 'Esgotado', {
                role: 'annotation'
            }, 'Vende', {
                role: 'annotation'
            }],
            ['Gasolina', data.stations_no_gasoline, data.stations_no_gasoline, data.stations_sell_gasoline, data.stations_sell_gasoline],
            ['Gasoleo', data.stations_no_diesel, data.stations_no_diesel, data.stations_sell_diesel, data.stations_sell_diesel],
            ['GPL', data.stations_no_lpg, data.stations_no_lpg, data.stations_sell_lpg, data.stations_sell_lpg]
        ]);

        let optionsChart2 = Object.assign(options, {
            legend: {
                position: 'top',
                maxLines: 3
            },
            bar: {
                groupWidth: '75%'
            },
            isStacked: true,
            colors: ['#f62317', '#8BC34A'],
            backgroundColor: { fill:'transparent' }
        });
        let chart2 = new google.visualization.BarChart(document.getElementById('types-chart-area'));
        chart2.draw(dataTable2, optionsChart2);
    });
}
