<?php

namespace App\Http\Controllers;

use App\Models\Videos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{
    public function uploadVideo(Request $request)
    {
        try {
            if (!$request->hasFile('file')) { // Changed to check for 'file' key
                return response()->json(['uploaded_file_not_found'], 404);
            }

            $file = $request->file('file');
            $videoName = time() . '.' . $file->hashName();
             $video_path = public_path() . '/videos';
          
            $saveVideo = $file->move($video_path, $videoName);

            if ($saveVideo) {
                
                $save = new Videos();
                $save->video_status = "pending";
                $save->video_path = '/videos/' . $videoName; 
                $save->save();

                return response()->json(['success' => true, 'message' => 'Video is uploaded and saved in DB'], 200);
            }

            return response()->json(['Video not saved in DB'], 422);
        } catch (\Exception $e) {
            Log::error('Exception occurred' . $e->getMessage());
            return response()->json(['message' => 'something happend while saving Video in DB']);
        }
    }
    public function deleteVideo($video_id)
    {
        try {
            $video = Videos::find($video_id);
            $video->delete();

            return response()->json([
                'message' => 'Video deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Exception occurred' . $e->getMessage());
            return response()->json(['message' => 'something happend while deleting video']);
        }
    }
    public function getAllVideos(){
        try {
           
            $video = Videos::All();
            if ($video->count() > 0) {
                return $video;
            }

            return response()->json(['message' => 'no records found'], 404);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'An error occurred while fetching All videos.'], 501);
        }
    }
    
}
