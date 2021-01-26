<?php

$rootDir = dirname(__DIR__, 1);

require_once $rootDir . '/src/Convertor.php';

echo 'root dir: ' . $rootDir . PHP_EOL;

$convertor = new \UKAikikai\Videos\Api\Convertor($rootDir);
$videoCollection = $convertor->fromCsvToArray();
$convertor->saveAsJson($videoCollection);
