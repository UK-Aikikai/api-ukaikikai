<?php

$rootDir = dirname(__DIR__);

fromCsvToJson($rootDir);

function fromCsvToJson($rootDir) {
    $videoCollection = [];
    $csv = array_map('str_getcsv', file($rootDir . '/assets/videos.csv'));
    foreach ($csv as $key => $videoConversion) {
        if ($videoConversion[0] === 'year') {
            continue;
        }

        if (!isset($videoCollection[$videoConversion[0]])) {
            $videoCollection[$videoConversion[0]] = [];
        }

        $videoCollection[$videoConversion[0]][] = [
            'title' => $videoConversion[1],
            'description' => $videoConversion[2],
            'player' => $videoConversion[3]
        ];
    }

    saveAsJson($rootDir, $videoCollection);
}

function saveAsCsv($rootDir, $videoCollection) {
    $list = [
        ['year', 'title', 'description', 'player'],
    ];

    $fp = fopen($rootDir. '/assets/videos_backup.csv', 'wb');

    foreach ($videoCollection as $year => $videos) {
        foreach ($videos as $video) {
            $list[] = [
                $year,
                $video['title'],
                $video['description'],
                $video['player'],
            ];
        }
    }

    foreach ($list as $fields) {
        fputcsv($fp, $fields);
    }

    fclose($fp);
}

function fromJson($rootDir) {
    $videoCollection = [];
    $existingJson = file_get_contents($rootDir . '/videos.json');
    $existingCollection = \json_decode($existingJson, true);
    foreach ( $existingCollection as $year => $videos ) {
        $videoCollection[$year] = $videos;
    }
}

function saveAsJson($rootDir, $videoCollection) {
    file_put_contents($rootDir . '/videos.json', \json_encode($videoCollection));
}