<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Events\Sendmessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', function () {
    return response([
        'status_code' => "0",
        "status_text" => "failed",
        'message' => 'UnAuthenticated'
    ], 401);
})->name('login');

// Route::get('/', function () {
//     return view('index');
// });
// Route::post('/send-message', function (Request $request) {
//     event(
//         new message(
//             $request->input('username'),
//             $request->input('message')
//         )
//     );
// });

Route::get('/log', function () {
    return view('login');
});
Route::post('/login','MessageController@login_data');

Route::group(['middleware' => ['auth:web']], function () {


    //Route::post('/dashboard','MessageController@login');

    Route::post("/show/{id}", 'MessageController@send');
    Route::get('/showdata/{id}','MessageController@showdata');
    Route::get('/get/{id}','MessageController@get_data');
    Route::get('/UserLogOut','MessageController@UserLogOut');



});





//Route::get('/get/{id}','MessageController@get_all');




