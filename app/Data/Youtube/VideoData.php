<?php

namespace App\Data\Youtube;

readonly class VideoData
{
    public function __construct(
        public string $videoId,
        public string $publishedAt,
        public string $title,
        public int $lengthSeconds,
        public string $lengthFormatted,
        public bool $sponsored,
        public float $engagementScore,
        public ?float $ratioViewLike,
        public array $externalLinks,
        public bool $isShorts,
        public array $averageDailyStats,
        public array $descriptionWordLengthCounts,
        public array $descriptionTimeStamps,
    ) {}
}