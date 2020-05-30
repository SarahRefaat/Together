<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UserRequest;
use App\Http\Controllers\Api\GroupController;

class UserRequestController extends Controller
{
 
    //----------------- this function to accept request of certain user
    public function accept($requestId,Request $request){
        $outRequest=UserRequest::find($requestId);
        if($outRequest){
            $groupController= new GroupController;
            $response=GroupController::addMember($outRequest->group_id,$outRequest->user_id,$request);
            $outRequest->delete();   
            return $response;
        }
        else{
            return ['response'=>'Error in params'];
    }
}
//---------------------- this to reject request
    public function reject($requestId){
        $outRequest=UserRequest::find($requestId);  
        if($outRequest){
            $outRequest->delete();
            return ['response'=>'Request rejected successfully'];   
        }
        else{
            return ['response'=>'Error in params'];
        }
    }
}
