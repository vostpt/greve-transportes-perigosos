<?php
/**
 * Created by PhpStorm.
 * User: tomahock
 * Date: 16/04/2019
 * Time: 20:00
 */

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