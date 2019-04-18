<?php

$counties = json_decode(file_get_contents('data/concelhos.json'));

$counties = $counties->rows;

if ( ! isset($_GET['district_key'])) {
    echo json_encode(["error" => "Invalid Request"]);
    die;
}

$district_key = $_GET['district_key'];

$selected = [];
foreach ($counties as $county) {
    if ($county->value->dId == $district_key) {
        array_push($selected, $county);
    }
}

echo json_encode($selected);
die;