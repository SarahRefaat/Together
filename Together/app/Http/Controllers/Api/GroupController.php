<?php

namespace App\Http\Controllers\Api;
use App\Group;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    //------------------this function to create a new group
    public function create(Request $request){
      $group=Group::create($request->all());
      $user=User::find($request->id);
      $user->admin=1;
      }
      //-------------------------this fuction to add member to group
      public function addMember($groupid,$id){
        $group=Group::find($groupid);
        if($group){
        $user=User::find($id);
        if($group->current_number_of_members<$group->max_member_number){
        $group->current_number_of_members=$group->current_number_of_members+1;
        $user->group_id=$group->id;
        return ['response'=>'member added successfully'];
        }
        else{
          return ['response'=>'this group id full'];
        }
      }
      return ['response'=>'this group doesnt exist'];
      }
      //--------------------this function to get this group info
      public function show($groupid){
        $group=Group::find($groupid);
        $members=$group->users();
        $memberNames=array();
        foreach($members as $member){
          array_push($memberNames,User::where('email',$member->email)->first());
        }
        if($group){
        $ret=['name'=>$group->name,
        'description'=>$group->sdescription,
        'status'=>$group->status,
        'duration'=>$group->duration,
        'members'=>$memberNames,
        // 'members'=>[
        // foreach ($group->users as $user){
        //   'member'=> $user->name];}
        ];
        return ['response'=>$ret];
        }
        else{
          return ['response'=>'this group id not exist'];
        }
      }
      //---------------------this function to remove member from group
      public function removeMember($groupid,$id){
        $group=Group::find($groupid);
        if($group){
        $user=User::find($id);
        $user->group_id=0;
        $group->current_number_of_memebers=$group->current_number_of_memebers-1;
        return ['response'=>'member removed successfully'];
        }
        return ['response'=>'this group doesnt exist'];
      }
}
