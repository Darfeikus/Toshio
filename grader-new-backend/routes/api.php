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

//API ROUTES
Route::get("/submission", 'SubmissionController@store');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Verify Route
Route::get('/', function (Request $request){
    $array = array(
        "Hello" => "API is working and running",
    );
    return json_encode($array);
});