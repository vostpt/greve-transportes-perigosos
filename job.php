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
curl_setopt($ch, CURLOPT_URL,'https://docs.google.com/spreadsheets/d/1WD3ojeEd-ll2T-xCXMda5UJrQhVxX6TgvEbEtkqL2J4/export?format=csv&id=1WD3ojeEd-ll2T-xCXMda5UJrQhVxX6TgvEbEtkqL2J4&gid=59515973');

$fp = fopen('file.csv', 'w+');
/**
 * Ask cURL to write the contents to a file
 */
curl_setopt($ch, CURLOPT_FILE, $fp);

curl_exec ($ch);

curl_close ($ch);
fclose($fp);



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

$fp = fopen('data.json', 'w');
fwrite($fp, json_encode($array));
fclose($fp);

echo 'Done' . PHP_EOL;