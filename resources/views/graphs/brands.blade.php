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
        <div class="graph" style="text-align: center;color: #b9524e"></div>
        <div class="graph" style="text-align: center;color: #b9524e">
            <h2>TOTAIS AGREGADOS POR MARCA - <span id="brand_name">PRIO (Dados Oficiais)</span></h2>
            <h4 style="margin-top: -1em">(Universo Total: <span id="brand_stations_total_number">0</span> Postos)</h4>
            <div id="gasoline-chart-area-brand"></div>
            <div id="diesel-chart-area-brand"></div>
            <div id="lpg-chart-area-brand"></div>
        </div>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="{{ mix('/js/graphs.js') }}" charset="utf-8"></script>
    <script src="{{ mix('/js/graph_brands.js') }}" charset="utf-8"></script>
</body>

</html>
