<?php

namespace App\Services\Youtube;

use App\Data\Youtube\VideoData;
use Carbon\Carbon;
use DateInterval;
use DateTime;

class VideoDataFactory
{
    private const SHORTS_LIMIT = 180;

    private const SPONSORED_PATTERNS = [
        '/\bsponsored\b/i',
        '/\bsponsor(?:ship)?\b/i',
        '/\bpaid partnership\b/i',
        '/\bpaid promotion\b/i',
        '/#ad\b/i',
        '/#sponsored\b/i',
        '/\bthanks to .*? for (?:sponsoring|partnering)/i',
        '/\bin partnership with\b/i',
        '/\buse (?:code|promo code|discount code)\b/i',
        '/\bhead to .*? to (?:get|check out|sign up)\b/i',
        '/\bthis video is sponsored\b/i',
    ];

    public static function make(array $item): VideoData
    {
        $duration = self::parseDuration($item['contentDetails']['duration']);
        $statistics = $item['statistics'];
        $snippet = $item['snippet'];
        $publishedAt = Carbon::parse($snippet['publishedAt']);
        $daysSincePublished = Carbon::now()->diffInDays($publishedAt, true);

        return new VideoData(
            videoId: $item['id'],
            publishedAt: $publishedAt->format('M j, Y g:ia'), 
            title: $snippet['title'],
            lengthSeconds: $duration['seconds'],
            lengthFormatted: $duration['formatted'],
            sponsored: self::isLikelySponsored($snippet['title'], $snippet['description']),
            engagementScore: self::calculateEngagementScore($statistics),
            ratioViewLike: round(($statistics['viewCount'] / $statistics['likeCount']), 2),
            externalLinks: self::extractLinks($snippet['description']),
            isShorts: $duration['seconds'] < self::SHORTS_LIMIT,
            averageDailyStats: self::calculateAverageDailyStats($statistics, $daysSincePublished),
            descriptionWordLengthCounts: [],
            descriptionTimeStamps: self::extractDescriptionTimestamps($snippet['description']),
        );
    }

    private static function parseDuration(string $duration): array
    {
        $interval = new DateInterval($duration);
        $totalSeconds = (new DateTime('@0'))->add($interval)->getTimestamp();

        $formatted = $interval->h > 0
            ? sprintf('%d:%02d:%02d', $interval->h, $interval->i, $interval->s)
            : sprintf('%d:%02d', $interval->i, $interval->s);

        return ['seconds' => $totalSeconds, 'formatted' => $formatted];
    }

    private static function calculateEngagementScore(array $statistics): float
    {
        $views = (int) ($statistics['viewCount'] ?? 0);
        $likes = (int) ($statistics['likeCount'] ?? 0);
        $comments = (int) ($statistics['commentCount'] ?? 0);

        if ($views === 0) {
            return 0.0;
        }

        $rawRate = ($likes + $comments) / $views;
        $ceiling = 0.10;

        return round(min($rawRate / $ceiling, 1.0), 4);
    }

    private static function isLikelySponsored(string $title, string $description): bool
    {
        $text = $title . ' ' . $description;

        foreach (self::SPONSORED_PATTERNS as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }

        return false;
    }

    private static function extractLinks(string $description): array
    {
        preg_match_all('/https?:\/\/[^\s]+/i', $description, $links);

        return array_values(array_unique($links[0]));
    }

    private static function calculateAverageDailyStats(array $statistics, float $days): array
    {
        return [
            'views' => round($statistics['viewCount'] / $days, 2),
            'likes' => round($statistics['likeCount'] / $days, 2),
            'comments' => round($statistics['commentCount'] / $days, 2),
        ];
    }

    private static function extractDescriptionTimestamps(string $description): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $description);
        $timestamps = [];

        foreach ($lines as $line) {
            if (preg_match('/^[\s\-•]*(\d{1,2}(?::\d{2}){1,2})\s*[-–:]?\s*(.+)$/', trim($line), $matches)) {
                $time = $matches[1];

                $timestamps[] = [
                    'time' => $time,
                    'label' => trim($matches[2]),
                ];
            }
        }

        return $timestamps;
    }
}