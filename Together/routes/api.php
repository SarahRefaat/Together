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
Route::get('/show','Api\UserController@show')->middleware('auth:sanctum');
//-----------------------------------this route to get all intersts
Route::get('/interests','Api\InterestController@interests');
//-----------------------------this route to get all groups with the same interest
Route::get('/groups/{id}','Api\InterestController@ListGroups');
//-----------------------------------this to update profile
Route::post('/update/{id}','Api\UserController@update')->middleware('auth:sanctum');
//-------------------------this route to create group
Route::post('/createGroup','Api\GroupController@create')->middleware('auth:sanctum');
//-------------------------this route to show group
Route::get('/show/{groupid}','Api\GroupController@show')->middleware('auth:sanctum');
//-----------------------------this route to add member
Route::get('/add/{groupid}/{id}','Api\GroupController@addMember')->middleware('auth:sanctum');
//----------------------------this route to remove member
Route::get('/remove/{groupid}/{id}','Api\GroupController@removeMember')->middleware('auth:sanctum');
//----------------------------this route to add new task
Route::post('/add','Api\TaskController@add')->middleware('auth:sanctum');
//------------------------------this route to add task to in-progress list
Route::get('/progress/{id}','Api\TaskController@moveToProgress')->middleware('auth:sanctum');
//------------------------------this route to move function to done
Route::get('/done/{id}','Api\TaskController@moveToDone')->middleware('auth:sanctum');
//---------------------------- this route for user to leave el group
Route::get('/leave/{groupid}/{id}','Api\GroupController@leave')->middleware('auth:sanctum');
//----------------------------------- this route to update group info
Route::post('/updateGroup/{id}','Api\GroupController@updateGroup')->middleware('auth:sanctum');
//-------------------------then all routes with be grouped to authenticate them
Route::get('/todo/{groupId}','Api\TaskController@listTodos')->middleware('auth:sanctum');
//-------------------------- this to get in-progress tasks of group
Route::get('/progresses/{groupId}','Api\TaskController@listProgress')->middleware('auth:sanctum');
//-------------------------- this to get done function of same group
Route::get('/dones/{groupId}','Api\TaskController@listDone')->middleware('auth:sanctum');
//-------------------------- this to send request
Route::post('/request/{groupId}/{id}','Api\GroupController@requestToJoin')->middleware('auth:sanctum');
//-------------------------- this to show all request
Route::get('/requests/{groupId}','Api\GroupController@requests')->middleware('auth:sanctum');
//------------------------- this to accept join request
Route::get('/accept/{requestId}','Api\UserRequestController@accept')->middleware('auth:sanctum');
//------------------------- this to reject join request
Route::get('/reject/{requestId}','Api\UserRequestController@reject')->middleware('auth:sanctum');
//------------------------------- this route to view groups of certain user
Route::get('/home/{id}','Api\UserController@home')->middleware('auth:sanctum');
//------------------------------ this route to update only interests
Route::post('/updateInterests/{id}','Api\UserController@updateInterests');
//----------------------this route to delete certain task
Route::get('/deleteTask/{id}','Api\TaskController@deleteTask');
//----------------------- this route to update certain task
Route::post('/updateTask/{id}','Api\TaskController@updateTask');
//------------------------ this route to move task to do list
Route::get('/do/{id}','Api\TaskController@moveTodo');
//-------------------this to  changetodoPosition tasks
Route::get('/changeTodoPosition/{taskId}/{position}','Api\TaskController@changeDoPosition');
//-------------------this to  changeProgressPosition tasks
Route::get('/changeProgressPosition/{taskId}/{position}','Api\TaskController@changeProgressPosition');
//-------------------this to  changeDonePosition tasks
Route::get('/changeDonePosition/{taskId}/{position}','Api\TaskController@changeDonePosition');
//------------------------ this route to logout
Route::get('/logout/{id}','Api\UserController@logout')->middleware('auth:sanctum');
//--------------------- this to know status of certain user according to certain group
Route::get('/status/{groupId}/{id}','Api\UserController@getStatus')->middleware('auth:sanctum');
//--------------------- this to dragADrop
Route::post('/dragAdrop','Api\TaskController@dragAdrop')->middleware('auth:sanctum');
//----------------------------------- this to get percentage
Route::get('/getpercentage/{groupId}','Api\TaskController@getpercentage')->middleware('auth:sanctum');
//----------------------- this to get request of admin
Route::get('/requestOfuser/{userId}','Api\GroupController@requestOfuser')->middleware('auth:sanctum');
//-------------------this to  changePosition tasks
Route::post('/changePosition','Api\TaskController@changePosition')->middleware('auth:sanctum');
//-------------------this to  changeProgressPosition tasks
Route::get('/changeProgressPosition/{taskId}/{position}','Api\TaskController@changeProgressPosition');
//-------------------this to  changeDonePosition tasks
Route::get('/changeDonePosition/{taskId}/{position}','Api\TaskController@changeDonePosition');


//...................................................... nahla - routes

//interests and groups routes
//this route display all interests
Route::get('/interests', 'API\InterestController@index')->middleware('auth:sanctum');
//this route display single interest
Route::get('/interests/{interest}', 'API\InterestController@show')->middleware('auth:sanctum');
//this route display groups of single interest
Route::get('/interests/{interest}/groups', 'API\InterestController@groups')->middleware('auth:sanctum');
//this route to search for group by search keyword
Route::get('/groups/search', 'API\GroupController@search')->middleware('auth:sanctum');

//notifications routes
//this route get all user notifications
Route::get('/users/{user_id}/notifications', 'API\UserController@notifications')->middleware('auth:sanctum');
//this route display single notification by id
Route::get('/notifications/show/{id}', 'API\NotificationController@show')->middleware('auth:sanctum');
//this route get only the recent notifications (last 10)
//Route::get('users/{user_id}/notifications/recent', 'API\UserController@recentNotifications')->middleware('auth:sanctum');
//this route to update the user to enable notifications
Route::put('/users/{user_id}/enable', 'API\UserController@enable')->middleware('auth:sanctum');
//this route to update user to disable notifications
Route::put('/users/{user_id}/disable', 'API\UserController@disable')->middleware('auth:sanctum');
//this route to delete single notification by id
Route::delete('/notifications/{id}', 'API\NotificationController@destroy')->middleware('auth:sanctum');

//this route to update the device token of the user once recieved
Route::post('/users/{user_id}/devicetoken','API\UserController@updateDeviceToken')->middleware('auth:sanctum');

// //----------------------sanctum generate token for user
// Route::post('/sanctum/token', function (Request $request) {
//     $request->validate([
//         'email' => 'required|email',
//         'password' => 'required',
//         'device_name' => 'required'
//     ]);
// });
