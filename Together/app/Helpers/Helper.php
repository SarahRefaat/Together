<?php

namespace App\Helpers;
use App\User;
use App\Group;
use App\Notification;
use App\Task;
 class Helper{

    static function save_notification_for_request(User $user,Group $group,$state)
    {
        $notification = new Notification;
        $notification->title="$group->name Request";
        $request_value=$state?"accepted":"rejected";
        $notification->body="your request to join $group->name group hase been $request_value";
        $notification->user_id= $user->id;
        $notification->info=$state?"Accept-Request":"Reject-Request";
        $notification->group_id=$group->id;
        $notification->save();
    }
    static function save_notification_for_todo(Group $group,Task $task,$todo_state)
    {
        $user_ids = DB::table('group_user')
        ->where(['user_id','<>',$group->admin_id])
        ->value('user_id');
        foreach ($user_ids as $id)
        {
        if(User::find($id)->enable){
        Notification::create([
            'title'=>"$group->name todo update",
            'body'=>"$task->name has been moved to $todo_state",
            'user_id'=> $id,
            'info'=>"todo-update",
            'group_id'=>$group->id,
        ]);
    }
    }
    }
    static function save_notification_Task(Group $group,Task $task,$state)
    {
        $user_ids = DB::table('group_user')
        ->where(['user_id','<>',$group->admin_id])
        ->value('user_id');
        foreach ($user_ids as $id)
        {
        if(User::find($id)->enable){
        Notification::create([
            'title'=>"$group->name todo update",
            'body'=>$state?"New Task $task->name has been added to todo":"$task->name has been removed from todo",
            'user_id'=> $id,
            'info'=>"todo-update",
            'group_id'=>$group->id,
        ]);
    }
    }
    }
    static function save_notification_for_group_sub(Group $group)
    {
        $user_ids = DB::table('interest_user')
        ->where([['interest_id', $group->interest()->id],
        ['user_id','<>',$group->admin_id]])
        ->value('user_id');
        $interest_name =$group->interest()->name;
        foreach ($user_ids as $id)
        {
            if(User::find($id)->enable){
            Notification::create([
                'title'=>$interest_name,
                'body'=>"New group for $interest_name $group->name has been created recently",
                'user_id'=> $id,
                'info'=>"Group-Created",
                'group_id'=>$group->id,
            ]);
        }
        }
    }
    static function save_notification_for_user_removed(User $user,Group $group)
    {
        Notification::create([
            'title'=>"$group->name",
            'body'=>"You have been removed from $group->name",
            'user_id'=> $user->id,
            'info'=>"user-removed",
            'group_id'=>$group->id,
        ]);

    }
}



