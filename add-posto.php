<?php
require_once 'vendor/autoload.php';

use voku\helper\AntiXSS;

$antiXss = new AntiXSS();

$districts = json_decode(file_get_contents('data/distritos.json'));
$districts = $districts->rows;
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
    <link rel="stylesheet" href="css/main.css?cachebuster=lel5">

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
        <h2>Reportar posto de gasolina</h2>
        <div class="row">
            <div class="col-md-8">
                <form action="#" class="form">
                    <div class="form-group">
                        <label for="district">Distrito:</label>
                        <select name="district" id="district" class="form-control">
                            <option value="null">Selecionar Distrito</option>
                            <?php foreach ($districts as $district): ?>
                                <option value="<?php echo $district->key; ?>"><?php echo $district->value->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="county">Concelho:</label>
                        <select name="county" id="county" class="form-control">
                            <option value="null">Selecionar Concelho</option>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="parish">Freguesia:</label>
                        <select name="parish" id="parish" class="form-control">
                            <option value="null">Selecionar Freguesia</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="station">Posto de combustivel:</label>
                        <select name="station" id="station" class="form-control"></select>
                    </div>

                    <div class="form-group">

                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?php include_once('modules/footer.php'); ?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/pt.js"
        integrity="sha256-eCtywrvMfbXvLM79yCZ1CaX24qPM1EbloAq/Rf3ImL4="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/livestamp/1.1.2/livestamp.min.js"
        integrity="sha256-8r65KJgULBDiZhwDydfWrEkx3yyV/grGsGzaekobngI="
        crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="js/main.js?cachebuster=lel3"></script>

<script>
    $(document).ready(function () {
        $("#district").on('change', function () {
            var $county = $("#county");


            $.get('/get-counties.php', {'district_key': $(this).val()}, function (result) {
                result = JSON.parse(result);
                $county.empty();
                $county.append($("<option></option>").attr('value', null).text("Selecionar Concelho"))
                for (var i = 0; i < result.length; i++) {
                    $county.append($("<option></option>").attr('value', result[i].key).text(result[i].value.name))
                }

            });
        });
        $("#county").on('change', function () {
            var $parish = $("#parish");

            $.get('/get-parishes.php', {'county_key': $(this).val()}, function (result) {
                result = JSON.parse(result);
                $parish.empty();

                $parish.append($("<option></option>").attr('value', null).text("Selecionar Freguesia"));
                for (var i = 0; i < result.length; i++) {
                    $parish.append($("<option></option>").attr('value', result[i].key).text(result[i].value.name))
                }

            });
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
