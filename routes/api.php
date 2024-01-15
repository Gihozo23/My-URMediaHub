<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
Route::group(['middleware' => ['api', 'auth'], 'prefix' => 'auth'], function () {
    Route::post('/register', [UserController::class, 'createUser']);
    Route::post('/login', [UserController::class, 'login']);
    // Route::get('/getAllUser', [UserController::class, 'getAllUser']);

    // VIDEO UPLOAD ROUTES
    Route::post('/video-upload', [VideoController::class, 'uploadVideo']);
    Route::get('/getAllVideos', [VideoController::class, 'getAllVideos']);
    Route::put('/updateVideoStatus/{id}', [VideoController::class, 'updateVideoStatus']);
    Route::delete('/deleteImage/{id}', [VideoController::class, 'deleteVideo']);
    
        //==============================================================================               

    /** ROUTE ONLY FOR ADMIN  */

    //================================================================================

    Route::group(['middleware' => 'admin'], function () {
        Route::get('/getAllUser', [UserController::class, 'getAllUser']);
        Route::put('/updateVideoStatus/{id}', [VideoController::class, 'updateVideoStatus']);
    });

//=====================================  ADIMIN ZONE  ENDs ================================================================


});
