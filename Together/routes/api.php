<?php

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

//---------------------default one
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//--------------------------this route for user to sign up
Route::post('/signup','Api\UserController@signup');
//----------------------------this route for user to sign in 
Route::post('/login','Api\UserController@signin');
//-------------------------------this route to view user profile
Route::get('/show','Api\UserController@show');
//-----------------------------------this to update profile
Route::get('/update/{id}','Api\UserController@update');
//-------------------------this route to create group
Route::post('/createGroup','Api\GroupController@create');
//-------------------------this route to show group
Route::get('/show/{groupid}','Api\GroupController@show');
//-----------------------------this route to add member
Route::get('/add/groupid/id','Api\GroupController@addMember');
//----------------------------this route to remove member
Route::get('/remove/groupid/id','Api\GroupController@removeMember');
//----------------------------this route to add new task
Route::post('/add','Api\TaskController@add');
//------------------------------this route to add task to in-progress list
Route::get('/progress/id','Api\TaskController@moveToProgress');
//------------------------------this route to move function to done
Route::get('/done/id','Api\TaskController@moveToDone');


//-------------------------then all routes with be grouped to authenticate them

//----------------------sanctum generate token for user 
Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required'
    ]);
});