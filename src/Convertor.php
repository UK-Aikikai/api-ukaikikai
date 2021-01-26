<?php

declare(strict_types=1);

namespace UKAikikai\Videos\Api;

final class Convertor
{
    private string $rootDir;
    private string $filename;
    private int $videoCount = 0;

    public function __construct(string $rootDir, ?string $filename = null)
    {
        $this->rootDir = $rootDir;
        $this->setFilename($filename);
    }

    private function setFilename(?string $filename = null): void
    {
        if (null === $filename) {
            $this->filename = 'videos.csv';

            return;
        }

        if ('' === \trim($filename)) {
            throw new \RuntimeException('Filename cannot be empty');
        }

        $this->filename = $filename;
    }

    private function getFilename(): string
    {
        return $this->rootDir . '/assets/' . $this->filename;
    }

    public function fromCsvToArray(): array
    {
        $videoCollection = [];
        $csv = array_map('str_getcsv', file($this->getFilename()));
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
                'player' => $videoConversion[3],
                'player_type' => $this->getPlayerType($videoConversion[3]),
            ];

            ++$this->videoCount;
        }

        return $videoCollection;
    }

    private function getPlayerType(string $player): string
    {
        if (strpos($player, 'https://www.youtube.com') === 0) {
            return 'youtube';
        }

        if (strpos($player, 'https://player.vimeo.com') === 0) {
            return 'vimeo';
        }

        throw new \RuntimeException(\sprintf('Player type is not supported for: %s', $player));
    }

    public function saveAsCsv(array $videoCollection): void
    {
        $list = [
            ['year', 'title', 'description', 'player'],
        ];

        $fp = fopen($this->rootDir. '/assets/videos_backup.csv', 'wb');

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

    public function fromJson(): array
    {
        $videoCollection = [];
        $existingJson = file_get_contents($this->rootDir . '/videos.json');
        $existingCollection = \json_decode($existingJson, true, 512, JSON_THROW_ON_ERROR);
        foreach ( $existingCollection as $year => $videos ) {
            $videoCollection[$year] = $videos;
        }

        return $videoCollection;
    }

    public function saveAsJson(array $videoCollection): void
    {
        file_put_contents($this->rootDir . '/videos.json', \json_encode($videoCollection, JSON_THROW_ON_ERROR));
    }

    public function getTotalVideos(): int
    {
        return $this->videoCount;
    }
}
