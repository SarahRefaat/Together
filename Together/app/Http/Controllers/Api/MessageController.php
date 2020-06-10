<?php

namespace App\Http\Controllers\Api;
use App\Message;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    //---------------------- this function for admin to delete certain message
    public function delete($messageId){
        $message=Message::find($messageId);
        if($message){
           if($message->group->admin_id==$request->input('current_user_id')){
              $message->delete();
              return ['response'=>'This messge deleted successfully'];
           }
           return ['response'=>'U aren\'t the admin of this group'];
        }
        return ['response'=>'Param error'];
    }
}
