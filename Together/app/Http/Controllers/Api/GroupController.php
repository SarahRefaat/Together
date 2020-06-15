<?php

namespace App\Http\Controllers\Api;
use App\Http\Resources\GroupResource;
use App\Group;
use App\User;
use App\Interest;
use App\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Message;
use Illuminate\Http\Response;
class GroupController extends Controller
{
    //------------------this function to create a new group
    public function create(Request $request){
      $valid = $request->validate([
        'name' => 'required|min:3|max:100',
        'description' => 'required',
        'status' => 'required',
        'level' => 'required',
        'duration' => 'required',
        'max_member_number'=>'required',
        'interest'=>'required',
        'id'=>'required'
    ]);
      $group=Group::where('name',$request->name)->first();
      if($group){
        return ['response'=>'This group title is exist'];
      }
      $admin=User::find($request->id);
      $group=new Group();
      $group->admin_id = $admin->id;
      $group->name = $request->name;
      $group->description = $request->description;
      $group->max_member_number = $request->max_member_number;
      $group->duration = $request->duration;
      $group->current_number_of_members += 1;
      $group->level = $request->level;
      $group->status = $request->status;
      $group->photo=$request->photo;
      $group->address=$request->address;
      $interest=Interest::where('name',$request->interest)->first();
      $group->interest_id = $interest->id ;
      $group->save();
      $group->users()->attach($admin);
      Helper::save_notification_for_group_sub($group);
      return ['response'=>'Group Created Successfully'];
      //test notifications
     /* $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
        "instanceId" => "ed3b05e0-b714-487d-a137-8daea3dfdecd",
        "secretKey" => "7F35FFC2B1FE5D28979F03D66AD0B0E8D593C88558605F515160E0D2F888EC67",
      ));

      $publishResponse = $beamsClient->publish(
        array("debug-together"),
        array("fcm" => array("notification" => array(
          "title" => "Together",
          "body" => "You have been removed from group",
        )),
      ));*/

      }
      //-------------------------this fuction to add member to group
      public static function addMember($groupid,$id,Request $request){

        $adminMember=User::find($request->input('current_user_id'));

        $group=Group::find($groupid);

        if($group){
          if($group->admin_id == $adminMember->id){

        $user=User::find($id);
        //------------ this user not in the group ??????
        $existUsers=$group->users;
        return $group->users;
        foreach($existUsers as $exist){
              if($user->id == $exist->id){
                return ['response'=>'This user already in this group'];
              }
        }

        if($group->current_number_of_members<$group->max_member_number){
        $group->current_number_of_members=$group->current_number_of_members+1;
        $group->save();
        $group->users()->attach($user);
        
        return ['response'=>'Member added successfully'];
        }
        else{
          return ['response'=>'This group is full'];
        }
      }
      else{
        return ['response'=>'U aren\'t the admin'];
      }
      return ['response'=>'This group doesnt exist'];
      }


  }
      //--------------------this function to get this group info
      public function show($groupid){

        $group=Group::find($groupid);
        $members=$group->users;
        if($group){
        return ['name'=>$group->name,
        'description'=>$group->sdescription,
        'group_id'=>$group->id,
        'id'=>$group->admin_id,
        'status'=>$group->status,
        'duration'=>$group->duration,
        'members'=>$members,
        'interest'=>$group->interest->name,
        'photo'=>$group->photo];
        }
        else{
          return ['response'=>'this group id not exist'];
        }
      }
      //---------------------this function to remove member from group
      public function removeMember($groupid,$id,Request $request){
        $adminMember=User::find($request->input('current_user_id'));
        $group=Group::find($groupid);
        if($group){
          if($group->admin_id == $adminMember->id){
        $user=User::find($id);
        $existUsers=$group->users;
        foreach($existUsers as $exist){
          if($user->id == $exist->id){
            $group->users()->detach($user);
            $current_member_count=$group->current_number_of_members;
            $group->current_number_of_members=$current_member_count-1;
            if($group->current_number_of_members <= 0){
              $group->delete();
              return ['response'=>'No more members group deleted'];
            }
            $group->save();
            if($user->enable){
            Helper::save_notification_for_user_removed($user,$group);
            }
            return ['response'=>'member removed successfully'];
            }
          }
          return ['response'=>'Not member of this group'];
         }
      else{
        return ['response'=>'u aren\'t the admin'];
      }
    }
        return ['response'=>'this group doesnt exist'];
      }
      //------------------ this function for user how wants to leave howa 7orr
      public function leave($groupid,$id){
        $group=Group::find($groupid);
        $user=User::find($id);
        if($group->admin_id == $user->id){
          $group->users()->detach($user);

          if($group->current_number_of_members == 1){
            $group->delete();
            return ['response'=>'Group deleted sucessfully'];
          }
          $group->admin_id=$group->users[0]->id;
          $current_member_count=$group->current_number_of_members;
          $group->current_number_of_members=$current_member_count-1;
          $group->save();
          return ['response'=>'member left successfully'];
        }
        $group->users()->detach($user);
        $current_member_count=$group->current_number_of_members;
        $group->current_number_of_members=$current_member_count-1;
        $group->save();
        return ['response'=>'member left successfully'];
      }
      //---------------------------- this function to update group information
      public function updateGroup(Request $request,$groupId){
        $adminMember=User::find($request->input('current_user_id'));
          $group=Group::find($groupId);
          $groupWithTheSameTitle=Group::where('name',$request->name)->first();
          if($group->admin_id == $adminMember->id){
            if($groupWithTheSameTitle&&$groupWithTheSameTitle->id!=$groupId){
              return ['response'=>'This title already exist'];
            }
          $group->update($request->only('name','description','address','photo','duration'));
          if($group){
            return ['response'=>'updated successfully'];
          }
        }
        else{
          return ['response'=>'u aren\'t the admin'];
        }
          return ['response'=>'updated failed param error'];
      }
      //-------------------- this to get all request of certain group
      public function requests($groupId){
        $group=Group::find($groupId);
        $allRequests=array();
        foreach($group->requests as $request){
            $obj=['id'=>$request->id,'content'=>$request->user->name.', '.$request->request_content,'photo'=>$request->user->photo];
            array_push($allRequests,$obj);
        }
        return $allRequests;
      }
       //--------------------- this to get user requests
       public function requestOfuser($userId){
        $user=User::find($userId);
        if($user){
        $adminOf = array();
        $groups=$user->groups;
        foreach($groups as $group){
          if($group->admin_id == $userId){
             array_push($adminOf,$group);
          }
        }
        $allRequests=array();
        $requests=array();
        foreach($adminOf as $groupAdmin){
            if( sizeof($groupAdmin->requests)>0){
          array_push($allRequests,$groupAdmin->requests);
          }
        }
        return ['response'=>$allRequests];
      }
    else{
      return ['response'=>'This user not exist'];
    }
  }
      //--------------------- this for user to send join request
      public function requestToJoin(Request $outRequest,$groupId,$id){
        $request = new UserRequest;
        $request->user_id = $id;
        $request->group_id = $groupId;
        $group=Group::find($groupId);
        if($outRequest->content){
        $request->request_content = $outRequest->content;
        }
        $request->save();
        return ['response'=>'Request sent successfully wait for admin to accept it'];
    }
    //---------------------- this to get chat of certain group pervious messags
    public function getChat($groupId){
      $group=Group::find($groupId);
      $allMessages=array();
      $messages=Message::where('group_id',$groupId)->get();
      foreach($messages as $message){
        $sender=$message->user;
        $senderName=$sender->name;
        $content=$message->content;
        $record = ['sender'=>$senderName,'content'=>$content,'sender_id'=>$sender->id,'msg_id'=>$message->id];
        array_push($allMessages,$record);
      }
      return ['response'=>$allMessages];
    }
    // this function for user to search for a group by keyword
    public function search(){
        $searchKeyword = request()->query('q');
        $groups = Group::where('name', 'like', "%{$searchKeyword}%")->get();
        if ($groups) {
            $groupResource = GroupResource::collection($groups);
            return $groupResource;
        }
        else{
            return ["response"=>"No results found !, Try Different Keywords "];
        }

    }

}
