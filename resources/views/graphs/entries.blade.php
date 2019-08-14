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
            <h2>Registo de submissões no JNDPA</h2>
            <h3>( Ultimas 12 horas )</h3>
            <div id="entries-last12-hours" ></div>
        </div>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="{{ mix('/js/helpers.js') }}" charset="utf-8"></script>
    <script src="{{ mix('/js/graphs.js') }}" charset="utf-8"></script>
    <script src="{{ mix('/js/graph_entries.js') }}" charset="utf-8"></script>
</body>

</html>
