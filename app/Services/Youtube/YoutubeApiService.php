<?php 

namespace App\Services\Youtube;
 
use Illuminate\Support\Facades\Http;
use Exception;

class YoutubeApiService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.youtube.base');
        $this->apiKey = config('services.youtube.key');
    }

    public function getVideoById(string $videoId)
    {
        $response = Http::get("{$this->baseUrl}/videos", [
            'part' => 'snippet,statistics,contentDetails',
            'id' => $videoId,
            'key' => $this->apiKey,
        ]);

        if ($response->failed()) {
            throw new Exception("YouTube API error: {$response->status()}");
        }
       return $response->json()['items'][0];
    }

}