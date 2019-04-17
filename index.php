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
<?php include_once('modules/navbar.php'); ?>

<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
    <div class="container">
        <p>ℹ️⛽️🚘 Sabes de algum posto de combustível onde não seja possível abastecer neste momento?</p>
        <p>Preenche <a href="https://docs.google.com/forms/d/e/1FAIpQLSemmYZ-KF6mSa_aqFN0bXwEnZiBnSUC3BXghcVRK0bvwuA6gA/viewform">este formulário</a>, por
            favor.🚘⛽️ℹ️</p>

        <?php

        // create curl resource
        //        $ch = curl_init();
        //
        //        // set url
        //        curl_setopt($ch, CURLOPT_URL,
        //            "https://docs.google.com/spreadsheets/d/1WD3ojeEd-ll2T-xCXMda5UJrQhVxX6TgvEbEtkqL2J4/export?format=csv&id=1WD3ojeEd-ll2T-xCXMda5UJrQhVxX6TgvEbEtkqL2J4&gid=59515973");
        //
        //        //return the transfer as a string
        //        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //
        //        // $output contains the output string
        //        $output = curl_exec($ch);
        //
        //        var_dump($output);


        //        echo '<pre>';
        /*
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

                // close curl resource to free up system resources
                //        curl_close($ch);*/
        ?>
        <br>
        <table id="dataTable" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th class="th-sm">Data</th>
                <th class="th-sm">Nome</th>
                <th class="th-sm">Concelho</th>
                <th class="th-sm">Distrito</th>
                <th class="th-sm">Tipo de Combustível não disponível</th>
                <th class="th-sm">Tipo Gasóleo não disponível</th>
                <th class="th-sm">Tipo de Gasolina não disponível</th>
            </tr>
            </thead>
            <tbody>
            <?php
            /*foreach ($array as $a): ?>
                <tr>
                    <td><span data-livestamp="<?php echo htmlspecialchars($antiXss->xss_clean($a[0]), ENT_QUOTES, 'UTF-8'); ?>"></span></td>
                    <td><?php echo htmlspecialchars($antiXss->xss_clean($a[1]), ENT_QUOTES, 'UTF-8'); ?>
                        - <?php echo htmlspecialchars($antiXss->xss_clean($a[2]), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($antiXss->xss_clean($a[3]), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($antiXss->xss_clean($a[4]), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($antiXss->xss_clean($a[5]), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($antiXss->xss_clean($a[6]), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($antiXss->xss_clean($a[7]), ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
            <?php endforeach;*/ ?>
            </tbody>
        </table>

        <p>ℹ️⛽️🚘 Sabes de algum posto de combustível onde não seja possível abastecer neste momento?</p>
        <p>Preenche <a href="https://docs.google.com/forms/d/e/1FAIpQLSemmYZ-KF6mSa_aqFN0bXwEnZiBnSUC3BXghcVRK0bvwuA6gA/viewform">este formulário</a>, por
            favor.🚘⛽️ℹ️</p>
    </div>
</div>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

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
        moment().locale("pt");
        var table = $("#dataTable").DataTable({
            "lengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
            "ajax": '/data.json',
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese.json"
            }
        });

        table.on('xhr', function () {
            var json = table.ajax.json();
        });
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
