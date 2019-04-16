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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.css" integrity="sha256-BbA16MRVnPLkcJWY/l5MsqhyOIQr7OpgUAkYkKVvYco=" crossorigin="anonymous" />

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            padding-top: 50px;
            padding-bottom: 20px;
        }
    </style>
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/main.css">

    <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
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
    </div>
</nav>

<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
    <div class="container">
        <p>ℹ️⛽️🚘 Sabes de algum posto de combustível onde não seja possível abastecer neste momento?</p>
        <p>Preenche <a href="https://docs.google.com/forms/d/e/1FAIpQLSemmYZ-KF6mSa_aqFN0bXwEnZiBnSUC3BXghcVRK0bvwuA6gA/viewform">este formulário</a>, por favor.🚘⛽️ℹ️</p>

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

        $csvData = file_get_contents('https://docs.google.com/spreadsheets/d/1WD3ojeEd-ll2T-xCXMda5UJrQhVxX6TgvEbEtkqL2J4/export?format=csv&id=1WD3ojeEd-ll2T-xCXMda5UJrQhVxX6TgvEbEtkqL2J4&gid=59515973');
        $lines = explode(PHP_EOL, $csvData);
        $array = array();
        foreach ($lines as $line) {
            $array[] = str_getcsv($line);
        }
//        print_r($array);

        unset($array[0]);

        // close curl resource to free up system resources
        //        curl_close($ch);
        ?>
        <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th class="th-sm">Data <i class="fas fa-sort"></i>
                </th>
                <th class="th-sm">Nome <i class="fas fa-sort"></i>
                </th>
                <th class="th-sm">Localização <i class="fas fa-sort"></i>
                </th>
                <th class="th-sm">Concelho <i class="fas fa-sort"></i>
                </th>
                <th class="th-sm">Distrito <i class="fas fa-sort"></i>
                </th>
                <th class="th-sm">Tipo de Combustível não disponível<i class="fas fa-sort"></i>
                </th>
                <th class="th-sm">Tipo Gasóleo não disponível<i class="fas fa-sort"></i>
                </th>
                <th class="th-sm">Tipo de Gasolina não disponível<i class="fas fa-sort"></i>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php
                foreach($array as $a){
                    $h = "<tr>
                            <td>{$a[0]}</td>
                            <td>{$a[1]}</td>
                            <td>{$a[2]}</td>
                            <td>{$a[3]}</td>
                            <td>{$a[4]}</td>
                            <td>{$a[5]}</td>
                            <td>{$a[6]}</td>
                            <td>{$a[7]}</td>
                            ";

                    echo $h;
                }


            ?>
            </tbody>
        </table>

        <p>ℹ️⛽️🚘 Sabes de algum posto de combustível onde não seja possível abastecer neste momento?</p>
        <p>Preenche <a href="https://docs.google.com/forms/d/e/1FAIpQLSemmYZ-KF6mSa_aqFN0bXwEnZiBnSUC3BXghcVRK0bvwuA6gA/viewform">este formulário</a>, por favor.🚘⛽️ℹ️</p>
    </div>
</div>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

<script src="js/vendor/bootstrap.min.js"></script>
<script src="js/jquery.tablesorter.js"></script>
<script src="js/main.js"></script>

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
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
