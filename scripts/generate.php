<?php

$rootDir = dirname(__DIR__, 1);

require_once $rootDir . '/src/Convertor.php';

$convertor = new \UKAikikai\Videos\Api\Convertor($rootDir);
$videoCollection = $convertor->fromCsvToArray();
$convertor->saveAsJson($videoCollection);
