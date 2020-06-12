<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UserRequest;
use App\Http\Controllers\Api\GroupController;
use App\Helpers\Helper;
class UserRequestController extends Controller
{

    //----------------- this function to accept request of certain user
    public function accept($requestId,Request $request){
        $outRequest=UserRequest::find($requestId);
        if($outRequest){
            $groupController= new GroupController;
            $response=GroupController::addMember($outRequest->group_id,$outRequest->user_id,$request);
            $outRequest->delete();
            if(User::find($outRequest->user_id)->enable){
            Helper::save_notification_for_request(User::find($outRequest->user_id),Group::find($outRequest->group_id),true);
            }
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
            if(User::find($outRequest->user_id)->enable){
            Helper::save_notification_for_request(User::find($outRequest->user_id),Group::find($outRequest->group_id),false);
            }
            return ['response'=>'Request rejected successfully'];
        }
        else{
            return ['response'=>'Error in params'];
        }
    }
}
