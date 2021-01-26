<?php

declare(strict_types=1);

namespace UKAikikai\Test\Videos\Api;

use PHPUnit\Framework\TestCase;
use UKAikikai\Videos\Api\Convertor;

final class ConvertorTest extends TestCase
{
    private string $rootDir;

    public function setUp(): void
    {
        $this->rootDir = __DIR__;
    }

    /**
     * @test
     */
    public function can_have_multiple_videos_per_year(): void
    {
        $convertor = new Convertor($this->rootDir, 'multiple-videos-per-year.csv');
        $videos = $convertor->fromCsvToArray();

        $this->assertCount(1, $videos);
        $this->assertCount(3, $videos[1986]);
    }

    /**
     * @test
     */
    public function can_identify_different_player_types(): void
    {
        $convertor = new Convertor($this->rootDir, 'videos-with-different-player-types.csv');
        $videos = $convertor->fromCsvToArray();

        $this->assertCount(2, $videos);
        $this->assertSame('youtube', $videos[2019][0]['player_type']);
        $this->assertSame('vimeo', $videos[1986][0]['player_type']);
    }
}
