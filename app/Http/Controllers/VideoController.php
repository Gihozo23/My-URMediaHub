<?php

namespace App\Http\Controllers;

use App\Models\Videos;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
    public function updateVideoStatus(Request $request,$video_id)
    {
        $user = JWTAuth::parseToken()->authenticate();

      // Validate and update video
      try {
        $videos = Videos::where('id',$video_id)->first();
        if(!$videos)
       { 
        return response()->json(['message' => 'not video found!']);
        }
        $validator = Validator::make($request->all(), [
                
                'video_status' => 'required|string',
           
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        // Update Video
        $videos->update([
       
                'video_status' => $request->input('video_status'),
        ]);
        return response()->json([
         'message' => 'Video Updated successfully',
         'data' => $videos], 201);
        
    } catch (\Exception $e) {
        Log::error($e->getMessage());
        return response()->json([ 'message' => 'Something happed while Updating Videos!'], 501);
    }
    }
      public function getFeaturedVideo($video_status)
    {
        try {
            $featuredVideos = Videos::where('video_status', $video_status)->get();

            if ($featuredVideos->count()) {
                return response()->json(['data' => $featuredVideos], 201);          
                }
            
                return response()->json(['message' => 'No featured video found'], 201);

        } catch (\Exception $e) {
            Log::error('error occurred: ' . $e->getMessage());
            return response()->json(['message' => 'something happened while trying to get featured video'], 500);
        }
    }

}
