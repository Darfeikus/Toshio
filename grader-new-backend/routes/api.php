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
Route::post("/submission", 'SubmissionController@store')->middleware('auth:teacher');
Route::post("/group", 'GroupController@store')->middleware('auth:teacher');
Route::post("/assignment", 'AssignmentController@store')->middleware('auth:teacher');
Route::post("/assignment/update", 'AssignmentController@storeUpdate')->middleware('auth:teacher');

Route::get("/submission", 'SubmissionController@index');
Route::get("/submission/{id}", 'SubmissionController@show'); //Shows all submissions from the student
Route::get("/submission/assignment/{id}", 'SubmissionController@showAssignment');
Route::get("/submission/assignment/csv/{id}", 'SubmissionController@csv');

Route::get("/group", 'GroupController@index');
Route::get("/group/test", 'GroupController@test');
Route::get("/group/teacherl", 'GroupController@showTeacherL')->middleware('auth:teacher');;
Route::get("/group/{id}", 'GroupController@show');
Route::get("/group/delete/{id}", 'GroupController@delete')->middleware('auth:teacher');
Route::get("/group/teacher/{id}", 'GroupController@showTeacher')->middleware('auth:teacher');
Route::get("/group/student/{id}", 'GroupController@showStudent')->middleware('auth:student');

Route::get("/assignment", 'AssignmentController@index');
Route::get("/assignment/{id}", 'AssignmentController@show');
Route::get("/assignment/delete/{id}", 'AssignmentController@delete')->middleware('auth:teacher');
Route::get("/assignment/teacher/{id}", 'AssignmentController@showTeacher')->middleware('auth:teacher');
Route::get("/assignment/student/{id}", 'AssignmentController@showStudent')->middleware('auth:student');

//language routes
Route::get("/language", 'LanguageController@index');
Route::get("/language/{id}", 'LanguageController@show');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

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