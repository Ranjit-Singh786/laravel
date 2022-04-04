<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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


#######################user login && register###########################


    Route::prefix('login')->group(function () {

        Route::post("/createuser",'AuthController@create');
        Route::post('/loginUser', 'AuthController@loginUser');

    });
    Route::group(['middleware' => ['auth:api']], function () {

    #################get all users details #####################
    Route::get('/userHomepage','AuthController@UserHomeGetAllPosts');
    ################################################################
    ##################  user Post Routes ###########################
    ################################################################
    Route::prefix('user_post')->group(function () {
    Route::post('/createpost','PostController@createPost');
    Route::get('/getpost/{id}','PostController@showAllPostsOnlyOneuser');
    Route::put('/update/{id}','PostController@updateData');
    Route::delete('/destroy/{id}','PostController@destroy');
});

    Route::delete('/logout','AuthController@UserLogOut');
    ################################################################
    ###################post like Routes  ###########################
    ################################################################
    Route::prefix('post_like')->group(function () {
        Route::get('/countLike/{id}','LikeController@countLikeWithNames');
        Route::post('/likeCount/{id}','LikeController@likeCount');
        Route::get('/showuserLikes','LikeController@showuserLikes');
    });

    ################################################################
    ###################post comment Routes #########################
    ################################################################

    Route::prefix('post_comment')->group(function () {
    Route::post('/createComment/{id}','ComentController@createComment');
    Route::delete('/deleteComment/{id}','ComentController@deleteComment');
    Route::get('/showComment/{id}','ComentController@showComment');
    });

      ################################################################
    ###################post comment reply Routes #########################
    ################################################################

    Route::prefix('post_reply')->group(function () {
    Route::post('/commentReply/{pid}/{cid}','ReplyyController@commentReply');
    Route::get('/showPostReply/{id}','ReplyyController@showPostReply');
    Route::get('/showusersActivity','ReplyyController@showusersActivity');
    Route::delete('/destroyCommentReply/{pid}/{cid}','ReplyyController@destroyCommentReply');
    });



    Route::prefix('follow')->group(function () {
    Route::get('/followUser','FollowController@followUser');
    Route::post("/follow", 'FollowController@follow');
    Route::post("/unfollow", 'FollowController@unfollow');
    Route::get("/feed", 'FeedController@index');
    Route::get("/notifications", 'NotificationsController@index');
    Route::get("/follower_user_list/{id}", 'FollowController@follower_user_list');
    Route::get("/user_account", 'FollowController@user_account');
    Route::get("/followingpost", 'FollowController@followingpost');

});
   ###########################follow request apis #################################
    Route::prefix('request')->group(function () {

    Route::post('/followUser_request/{user_id}','PndrequestController@followUser_request');
});








    });

