<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ForgotPasswordController;
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


Route::post('/createData', [StudentController::class, 'create']);
Route::get('/getData', [StudentController::class, 'getData']);
Route::put('/update_data/{id}',[StudentController::class,'updateData']);
Route::delete('/delete/{id}',[StudentController::class,'destroy']);


Route::post('/login', [StudentController::class, 'loginUser']);
Route::post('/forgot-password', [StudentController::class, 'forgotPassword']);
Route::post('/verify-otp', [StudentController::class, 'verifyotp']);
Route::post('/change-password', [StudentController::class, 'changePassword']);



Route::group(['middleware' => ['auth:api']], function () {
    Route::get('home',[StudentController::class,'UserHome']);
    Route::delete('logout',[StudentController::class,'UserLogOut']);
    });








