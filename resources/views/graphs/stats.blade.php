<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Já Não Dá Para Abastecer - Estastísticas</title>
    <style>

        .flex-container {
            display: flex;
            flex-wrap: wrap;
            font-family: sans-serif;
        }

        @media (min-width: 768px) {
            .graph {
                width: 50%;
            }
        }

        @media  (max-width: 768px) {
            .graph {
                width: 100%;
            }
        }

        .graph {
            min-width: 400px;
        }
    </style>

</head>

<body>
    <div class="flex-container">
        <div class="graph" style="text-align: center;color: #46ace3">
            <h2>VISÃO GERAL AGREGADA</h2>
            <h4 style="margin-top: -1em">(Universo Total: <span id="stations_total_number">0</span> Postos)</h4>
            <div id="stations-chart-area"></div>
        </div>
        <div class="graph" style="text-align: center;color: #b9524e">
            <h2>TOTAIS AGREGADOS POR COMBUSTÍVEL</h2>
            <div id="gasoline-chart-area"></div>
            <div id="diesel-chart-area"></div>
            <div id="lpg-chart-area"></div>
        </div>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="{{ mix('/js/graphs.js') }}" charset="utf-8"></script>
    <script src="{{ mix('/js/graph_stats.js') }}" charset="utf-8"></script>
</body>

</html>
