<?php

use GuzzleHttp\Client;
use IsraelPost\Reader;

require_once './vendor/autoload.php';

$reader = new Reader();
$client = new Client();
$file   = './src/data/page.html';

$response = $client->get('https://www.israelpost.co.il/npostcalc.nsf/calculator2');
file_put_contents($file, iconv('windows-1255', 'utf8', $response->getBody()));

$countries = $reader->countries($file, '#CountriesCombo');
file_put_contents('./src/data/countries-CountriesCombo.json', json_encode($countries));
