<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notification;
use App\Http\Resources\NotificationResource;
use App\User;
use Illuminate\Http\Response;
class NotificationController extends Controller
{
    public function show(){
        $notificationId = request()->id;
        $notification = Notification::find($notificationId);
        if($notification){
            return new NotificationResource($notification);
        }
        else {
            $response = new Response(["response"=>"This notification does not exist !!"]);
            return $response->setStatusCode(404);
        }
    }
    public function destroy($id){
               $singleNotification= Notification::find($id);
               if($singleNotification){
               $singleNotification->delete();
               return  ["response"=>"Notification removed successfully !!"];
           }else{
            $response = new Response(["response"=>"This notification does not exist !!"]);
            return $response->setStatusCode(404);
           }
    }
}
