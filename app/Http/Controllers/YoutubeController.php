<?php

namespace App\Http\Controllers;


use App\Services\Youtube\YoutubeApiService;
use App\Services\Youtube\VideoDataFactory;
use Illuminate\Http\Request;
use Exception;

class YoutubeController extends Controller
{
    public function __construct(protected YoutubeApiService $youtubeApiService)
    {
    }

    public function getVideo(Request  $request)
    {
        $request->validate([
            'video_id' => 'required|string',
        ]);

        try {
            $data = $this->youtubeApiService->getVideoById($request->input('video_id'));
            $video = VideoDataFactory::make($data);
            return response()->json($video);
        } catch (Exception $e) {
            $status = $e->getMessage() === 'Video not found' ? 404 : 500;
            return response()->json(['error' => $e->getMessage()], $status);
        }
    }
}