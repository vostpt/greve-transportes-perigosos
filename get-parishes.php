<?php

$parishes = json_decode(file_get_contents('data/freguesias.json'));

$parishes = $parishes->rows;

if ( ! isset($_GET['county_key'])) {
    echo json_encode(["error" => "Invalid Request"]);
    die;
}

$county_key = $_GET['county_key'];

$selected = [];
foreach ($parishes as $parish) {
    if ($parish->value->cId == $county_key) {
        array_push($selected, $parish);
    }
}

echo json_encode($selected);
die;