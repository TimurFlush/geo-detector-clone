<?php

require_once __DIR__ . '/../vendor/autoload.php';

define('RESOURCE_DIR', __DIR__ . '/_resource');
define('VENDOR_DIR', RESOURCE_DIR . '/vendor');
define('PATH_TO_SYPEX_BASE', RESOURCE_DIR . '/Sypex-02_2020.dat');
define('PATH_TO_MAXMIND_BASE', RESOURCE_DIR . '/MaxMind-02_2020.dat');
define('MACHINE_IP', json_decode(
    file_get_contents('https://api.ipify.org/?format=json'),
    true
)['ip']);

\TimurFlush\GeoDetector\TorDetector::serverAddress(
    MACHINE_IP
);
