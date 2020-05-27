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
Route::post("/submission", 'SubmissionController@store');
Route::post("/group", 'GroupController@store');

//language routes
Route::get("/language", 'LanguageController@index');
Route::get("/language/{id}", 'LanguageController@show');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Register Routes
Route::post('/register', 'Auth\RegisterController@register');
Route::post('/login', 'Auth\LoginController@login');

Route::get('/home', 'HomeController@index');
Route::get('/unauthorized', 'Auth\LoginController@denyAccess')->name('unauthorized');

//Verify Route
Route::get('/', function (Request $request){
    $array = array(
        "Hello" => "API is working and running",
    );
    return json_encode($array);
});