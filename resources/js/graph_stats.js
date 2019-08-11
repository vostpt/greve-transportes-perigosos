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


        let hasGasoline = data.stations_sell_gasoline - data.stations_no_gasoline;
        let hasDiesel = data.stations_sell_diesel - data.stations_no_diesel;
        let hasLpg = data.stations_sell_lpg - data.stations_no_lpg;

        //
        // GASOLINE
        //
        let barOptions = {
            legend : {position: "top", alignment: "left"},
            tooltip: { ignoreBounds:true},
            sliceVisibilityThreshold: 0,
            bar: { groupWidth: '50%' },
            isStacked: true,
            top:0,
            height: 130,
            hAxis: {textPosition: 'none'}
        };
        let dataTable2 = google.visualization.arrayToDataTable([
            ['Combustivel','Esgotado',{ role: 'annotation'},'Vende',{ role: 'annotation'},{role: 'style'}],
            ['Gasolina', data.stations_no_gasoline,data.stations_no_gasoline,hasGasoline,hasGasoline,'#AAAE43']
        ]);
        let optionsChart2 = Object.assign(barOptions,{colors: ['#f62317','#AAAE43']});
        let chart2 = new google.visualization.BarChart(document.getElementById('gasoline-chart-area'));
        chart2.draw(dataTable2, optionsChart2);
        //
        // DIESEL
        //
        let dataTable3Diesel = google.visualization.arrayToDataTable([
            ['Combustivel','Esgotado',{ role: 'annotation'},'Vende',{ role: 'annotation'},{role: 'style'}],
            ['Gasoleo', data.stations_no_diesel,data.stations_no_diesel,hasDiesel,hasDiesel,'#DB6E3E'],
        ]);
        let optionsChart3 = Object.assign(barOptions,{colors: ['#f62317','#DB6E3E']});
        let chart3lpg = new google.visualization.BarChart(document.getElementById('diesel-chart-area'));
        chart3lpg.draw(dataTable3Diesel, optionsChart3);
        //
        // LPG
        //
        let dataTable4 = google.visualization.arrayToDataTable([
            ['Combustivel','Esgotado',{ role: 'annotation'},'Vende',{ role: 'annotation'},{role: 'style'}],
            ['GPL', data.stations_no_lpg,data.stations_no_lpg,hasLpg,hasLpg,'3D8CB1']
        ]);
        let optionsChart4 = Object.assign(barOptions,{colors: ['#f62317','#3D8CB1']});
        let chart4lpg = new google.visualization.BarChart(document.getElementById('lpg-chart-area'));
        chart4lpg.draw(dataTable4, optionsChart4);
    });
}
