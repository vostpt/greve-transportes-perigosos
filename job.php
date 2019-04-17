<?php
/**
 * Created by PhpStorm.
 * User: tomahock
 * Date: 16/04/2019
 * Time: 20:00
 */

echo 'Start......';
$ch = curl_init();
/**
 * Set the URL of the page or file to download.
 */
curl_setopt($ch, CURLOPT_URL,
    'https://docs.google.com/spreadsheets/u/1/d/1WD3ojeEd-ll2T-xCXMda5UJrQhVxX6TgvEbEtkqL2J4/export?format=csv&id=1WD3ojeEd-ll2T-xCXMda5UJrQhVxX6TgvEbEtkqL2J4&gid=1149462056');

$fp = fopen('file.csv', 'w+');
/**
 * Ask cURL to write the contents to a file
 */
curl_setopt($ch, CURLOPT_FILE, $fp);

curl_exec($ch);

curl_close($ch);
fclose($fp);


$asneirasRegex = '/(merda|cona|caralho|winterfell|wall|shit|cunt|fuck)/i';

$csvData
    = file_get_contents('file.csv');
$lines = explode(PHP_EOL, $csvData);
$array = array();
foreach ($lines as $line) {
    $x = str_getcsv($line);
//    print_r($x);
//    if ($x[10] === '1') {
    $x[1] = $x[1] . ' - ' . $x[2];
    unset($x[2]);
    $x = array_values($x);

    $isAsneira = false;
    foreach($x as $xx){
        if(preg_match($asneirasRegex, $xx)){
            $isAsneira = true;
        }
    }

    if(!$isAsneira){
        $array[] = $x;
    }

//    }
}
//        print_r($array);
unset($array[0]);
$array = array_reverse($array);

//print_r($array);
//var_dump(count($array));
//$input = array_map("unserialize", array_unique(array_map("serialize", $array)));
//var_dump(count($input));
$json = array(
    'data' => $array
);

$fp = fopen('data.json', 'w');
fwrite($fp, json_encode($json, JSON_UNESCAPED_UNICODE));
fclose($fp);

echo 'Done' . PHP_EOL;