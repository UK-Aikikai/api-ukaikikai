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

    public function can_identify_different_player_types(): void
    {

    }

    public function differentPlayerTypesDataProvider(): array
    {
        return [
            ['https://www.youtube.com/embed/68s7VOswctU', 'youtube'],
            ['https://player.vimeo.com/video/328594418', 'vimeo'],
        ];
    }
}
