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
Route::post('/signin','Api\UserController@signin');
//-------------------------------this route to view user profile
Route::get('/show','Api\UserController@show');
//-----------------------------------this to update profile
Route::get('/update/{id}','Api\UserController@update');
//-------------------------this route to create group
Route::post('/createGroup','Api\GroupController@create');
//-------------------------this route to show group
Route::get('/show/{groupid}','Api\GroupController@show');
//-----------------------------this route to add member
Route::get('/add/{groupid}/{id}','Api\GroupController@addMember');
//----------------------------this route to remove member
Route::get('/remove/{groupid}/{id}','Api\GroupController@removeMember');
//----------------------------this route to add new task
Route::post('/add','Api\TaskController@add');
//------------------------------this route to add task to in-progress list
Route::get('/progress/{id}','Api\TaskController@moveToProgress');
//------------------------------this route to move function to done
Route::get('/done/{id}','Api\TaskController@moveToDone');
//-----------------------------this route to get all groups with the same interest
Route::get('/groups/{id}','Api\InterestController@ListGroups');
//---------------------------- this route for user to leave el group 
Route::get('/leave/{groupid}/{id}','Api\GroupController@leave');
//----------------------------------- this route to update group info
Route::post('/updateGroup/{id}','Api\GroupController@updateGroup');
//-------------------------then all routes with be grouped to authenticate them
Route::get('/todo/{groupId}','Api\TaskController@listTodos');
//-------------------------- this to get in-progress tasks of group 
Route::get('/progresses/{groupId}','Api\TaskController@listProgress');
//-------------------------- this to get done function of same group
Route::get('/dones/{groupId}','Api\TaskController@listDone');
//-------------------------- this to send request
Route::post('/request/{groupId}/{id}','Api\GroupController@requestToJoin');
//-------------------------- this to show all request
Route::get('/requests/{groupId}','Api\GroupController@requests');
//------------------------- this to accept join request
Route::get('/accept/{requestId}','Api\UserRequestController@accept');
//------------------------- this to reject join request
Route::get('/reject/{requestId}','Api\UserRequestController@reject');
//------------------------------- this route to view groups of certain user
Route::get('/home/{id}','Api\UserController@home');

// //----------------------sanctum generate token for user 
// Route::post('/sanctum/token', function (Request $request) {
//     $request->validate([
//         'email' => 'required|email',
//         'password' => 'required',
//         'device_name' => 'required'
//     ]);
// });