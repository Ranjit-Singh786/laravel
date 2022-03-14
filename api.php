<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/createuser', [AuthController::class, 'create']);
Route::post('/loginUser', [AuthController::class, 'loginUser']);



Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/userHomepage', [AuthController::class, 'UserHomeGetAllPosts']);
    Route::post('/createpost',[AuthController::class,'createPost']);
    Route::get('/getpost/{id}',[AuthController::class,'showAllPostsOnlyOneuser']);
    Route::put('/update/{id}',[AuthController::class,'updateData']);
    Route::delete('/destroy/{id}',[AuthController::class,'destroy']);
    Route::delete('/logout',[AuthController::class,'UserLogOut']);
    Route::get('/countLike/{id}',[AuthController::class,'countLikeWithNames']);
    Route::post('/createComment/{id}',[AuthController::class,'createComment']);
    Route::delete('/deleteComment/{id}',[AuthController::class,'deleteComment']);
    Route::post('/likeCount/{id}',[AuthController::class,'likeCount']);
    Route::get('/showComment/{id}',[AuthController::class,'showComment']);





    });

