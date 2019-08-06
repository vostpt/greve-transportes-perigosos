<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Já Não Dá Para Abastecer - Estastísticas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css">
    <style>
        canvas {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }
    </style>

</head>

<body>
    <h1 style="text-align:center;" id="district">Distrito</h1>
    <h2 style="text-align:center;" id="county">Concelho</h2>
    <div style="width:50%;float:left">
        <canvas id="stations-chart-area"></canvas>
    </div>
    <div style="width:50%;float:right">
        <canvas id="types-chart-area"></canvas>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js" charset="utf-8"></script>
    <script>
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
        function Get(yourUrl){
            var Httpreq = new XMLHttpRequest(); // a new request
            Httpreq.open("GET",yourUrl,false);
            Httpreq.send(null);
            return Httpreq.responseText;          
        }
        window.onload = function () {
            let district = findGetParameter("distrito");
            let county = findGetParameter("concelho");
            let url = "";
            if(district) {
                if(county) {
                    url = "/storage/data/stats_"+ encodeURI(district) +"_"+ encodeURI(county) +".json"
                }
                else {
                    county = null;
                    url = "/storage/data/stats_"+ encodeURI(district) + ".json";
                }
            }
            else {
                district = "Portugal";
                county = null;
                url = "/storage/data/stats_global.json";
            }
            document.getElementById("district").innerHTML = district;
            if(county == null) {
                let county_element = document.getElementById("county");
                if(county_element) {
                    county_element.parentNode.removeChild(county_element);
                }
            }
            else {
                document.getElementById("county").innerHTML = county;
            }
            let data = JSON.parse(Get(url));
            let stations_options = {
                circumference: Math.PI,
                rotation: -Math.PI,
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Postos de Combústivel'
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
            let stations_data = {
                labels: ["Sem Nenhum Combustível", "Sem Um Combustível", "Com Todos os Combustível"],
                datasets: [{
                    data: [data.stations_none, data.stations_partial, data.stations_all],
                    backgroundColor: [
                        "#c1272c",
                        "#f7921e",
                        "#006837"
                    ],
                    label: [
                        "Sem Nenhum Combustível",
                        "Sem Um Combustível",
                        "Com Todos os Combustível"
                    ]
                }]
            };
            let types_options = {
                circumference: Math.PI,
                rotation: -Math.PI,
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Tipos de Combústivel'
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
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
    </script>
</body>

</html>