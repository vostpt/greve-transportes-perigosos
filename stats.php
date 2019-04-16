<?php
/**
 * Created by PhpStorm.
 * User: tomahock
 * Date: 16/04/2019
 * Time: 16:09
 */

require_once 'vendor/autoload.php';

use voku\helper\AntiXSS;

$antiXss = new AntiXSS();
?>

<!doctype html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>VOST.pt - Informação de Posto de Abastecimento sem combustível</title>
    <meta name="Description" content="Lista de posto de abastecimento sem combustível">
    <meta name="og:description" content="Lista de posto de abastecimento sem combustível">
    <meta name="og:title" content="VOST.pt - Informação de Posto de Abastecimento sem combustível">
    <meta name="Keywords" content="vost, greve, transportes, perigosos, gasolina, gasoleo">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.css"
          integrity="sha256-BbA16MRVnPLkcJWY/l5MsqhyOIQr7OpgUAkYkKVvYco=" crossorigin="anonymous"/>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            padding-top: 50px;
            padding-bottom: 20px;
        }
    </style>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/css/dataTables.bootstrap.min.css"
          integrity="sha256-PbaYLBab86/uCEz3diunGMEYvjah3uDFIiID+jAtIfw=" crossorigin="anonymous"/>
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/main.css?cachebuster=lel2">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js" integrity="sha256-0rguYS0qgS6L4qVzANq4kjxPLtvnp5nn2nB5G1lWRv4="
            crossorigin="anonymous"></script>
</head>
<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">VOST.pt - Greve Transportes perigosos</a>
        </div>
        <nav class="collapse navbar-collapse" id="bs-navbar">
            <ul class="nav navbar-nav">
                <li><a href="index.php">Início</a></li>
                <li><a href="stats.php">Estatísticas</a></li>
            </ul>
        </nav>
    </div>

</nav>

<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
    <div class="container">
        <p>ℹ️⛽️🚘 Sabes de algum posto de combustível onde não seja possível abastecer neste momento?</p>
        <p>Preenche <a href="https://docs.google.com/forms/d/e/1FAIpQLSemmYZ-KF6mSa_aqFN0bXwEnZiBnSUC3BXghcVRK0bvwuA6gA/viewform">este formulário</a>, por
            favor.🚘⛽️ℹ️</p>

        <?php

        $csvData
               = file_get_contents('file.csv');
        $lines = explode(PHP_EOL, $csvData);
        $array = array();
        foreach ($lines as $line) {
            $x = str_getcsv($line);
            if ($x[8] === '1') {
                $array[] = $x;
            }
        }
        //        print_r($array);
        unset($array[0]);
        $array = array_reverse($array);


        $stats = array_count_values(array_column($array, 5));

        $counter = [
            'galp'     => 0,
            'prio'     => 0,
            'bp'       => 0,
            'repsol'   => 0,
            'cepsa'    => 0,
            'outros'   => 0,
            'jumbo'    => 0,
            'inter'    => 0,
            'pingo'    => 0,
            'bandeira' => 0,
        ];
        foreach ($array as $item) {
            if (strpos(strtolower($item[1]), 'galp') !== false) {
                $counter['galp']++;
            } elseif (strpos(strtolower($item[1]), 'repsol') !== false) {
                $counter['repsol']++;
            } elseif (strpos(strtolower($item[1]), 'bp') !== false) {
                $counter['bp']++;
            } elseif (strpos(strtolower($item[1]), 'prio') !== false) {
                $counter['prio']++;
            } elseif (strpos(strtolower($item[1]), 'cepsa') !== false) {
                $counter['cepsa']++;
            } elseif (strpos(strtolower($item[1]), 'bandeira') !== false) {
                $counter['bandeira']++;
            } elseif (strpos(strtolower($item[1]), 'jumbo') !== false) {
                $counter['jumbo']++;
            } elseif (strpos(strtolower($item[1]), 'intermarch') !== false) {
                $counter['inter']++;
            } elseif (strpos(strtolower($item[1]), 'pingo') !== false) {
                $counter['pingo']++;
            } else {
                $counter['outros']++;
            }

        }
        ?>

        <br>
        <div class="row">
            <div class="col-md-offset-3 col-md-6 col-sd-12">
                <h3>Tipos de Combustivel</h3>
                <br>
                <canvas id="gasTypes" width="400" height="400"></canvas>
            </div>
            <div class="col-md-offset-3 col-md-6 col-sd-12">
                <h3>Marcas</h3>
                <br>
                <canvas id="gasBrands" width="400" height="400"></canvas>
            </div>
        </div>
    </div>
</div>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js" integrity="sha256-Uv9BNBucvCPipKQ2NS9wYpJmi8DTOEfTA/nH2aoJALw="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/pt.js" integrity="sha256-eCtywrvMfbXvLM79yCZ1CaX24qPM1EbloAq/Rf3ImL4="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/livestamp/1.1.2/livestamp.min.js" integrity="sha256-8r65KJgULBDiZhwDydfWrEkx3yyV/grGsGzaekobngI="
        crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="js/main.js?cachebuster=lel2"></script>

<script>
    $(document).ready(function () {
        $("#dataTable").DataTable({
            "lengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]]
        });
    });
    moment().locale("pt");

    var ctx = document.getElementById('gasTypes').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Gasóleo', 'Gasolina', 'Ambos'],
            datasets: [{
                label: '',
                data: [<?= $stats['Gasóleo'] ?>, <?= $stats['Gasolina'] ?>, <?= $stats['Ambos'] ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });

    var bgas = document.getElementById('gasBrands').getContext('2d');
    var myChart = new Chart(bgas, {
        type: 'doughnut',
        data: {
            labels: ['Prio', 'BP', 'Galp', 'Repsol', 'Cepsa', 'Jumbo', 'Intermarché', 'Alves Bandeira', 'Pingo Doce', 'Outros'],
            datasets: [{
                label: '',
                data: [<?= $counter['prio'] ?>,
                    <?= $counter['bp'] ?>,
                    <?= $counter['galp'] ?>,
                    <?= $counter['repsol'] ?>,
                    <?= $counter['cepsa'] ?>,
                    <?= $counter['jumbo'] ?>,
                    <?= $counter['inter'] ?>,
                    <?= $counter['bandeira'] ?>,
                    <?= $counter['pingo'] ?>,
                    <?= $counter['outros'] ?>
                ],
                backgroundColor: [
                    'rgba(51, 255, 255, 0.5)',
                    'rgba(51, 255, 51, 0.5)',
                    'rgba(255, 128, 0, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(255, 51, 51, 0.5)',
                    'rgba(255, 102, 102, 0.5)',
                    'rgba(204, 0, 0, 0.5)',
                    'rgba(0, 0, 204, 0.5)',
                    'rgba(0, 153, 0, 0.5)',
                    'rgba(96, 96, 96, 0.5)',
                ],
                borderColor: [
                    'rgba(51, 255, 255, 1)',
                    'rgba(51, 255, 51, 1)',
                    'rgba(255, 128, 0, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 51, 51, 1)',
                    'rgba(255, 102, 102, 1)',
                    'rgba(204, 0, 0, 1)',
                    'rgba(0, 0, 204, 1)',
                    'rgba(0, 153, 0, 1)',
                    'rgba(96, 96, 96, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });
</script>

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script async>
    (function (b, o, i, l, e, r) {
        b.GoogleAnalyticsObject = l;
        b[l] || (b[l] =
            function () {
                (b[l].q = b[l].q || []).push(arguments)
            });
        b[l].l = +new Date;
        e = o.createElement(i);
        r = o.getElementsByTagName(i)[0];
        e.src = '//www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e, r)
    }(window, document, 'script', 'ga'));
    ga('create', 'UA-138398529-1\n', 'auto');
    ga('send', 'pageview');
</script>
</body>
</html>
