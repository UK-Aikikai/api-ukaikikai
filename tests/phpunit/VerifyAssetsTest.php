<?php

declare(strict_types=1);

namespace UKAikikai\Test\Videos\Api;

use PHPUnit\Framework\TestCase;
use UKAikikai\Videos\Api\Convertor;

final class VerifyAssetsTest extends TestCase
{
    private string $rootDir;

    protected function setUp(): void
    {
        $this->rootDir = dirname(__DIR__, 2);
    }

    /**
     * @test
     */
    public function video_csv_assets_cannot_be_empty(): void
    {
        $csv = array_map('str_getcsv', file($this->getFilename()));
        // first row will be the header. Discount from count
        $totalVideos = \count($csv) - 1;

        $this->assertGreaterThan(0, $totalVideos);
    }

    /**
     * @test
     */
    public function verify_total_rows_correspond_to_count_sum_for_each_year(): void
    {
        $csv = array_map('str_getcsv', file($this->getFilename()));
        // first row will be the header. Discount from count
        $totalVideos = \count($csv) - 1;

        $convertor = new Convertor($this->rootDir);
        $videos = $convertor->fromCsvToArray();

        $this->assertCount($totalVideos, $videos);
    }

    private function getFilename(): string
    {
        return $this->rootDir . '/assets/videos.csv';
    }
}
