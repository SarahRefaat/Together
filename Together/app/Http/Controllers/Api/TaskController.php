<?php

namespace App\Http\Controllers\Api;
use App\Task;
use App\User;
use App\Group;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //--------------------this functgion to add new task
    public function add(Request $request){
        $group=Group::find($request->group_id);
        $adminMember=User::find($request->current_user_id);
        if($group->admin_id==$adminMember->id){
        $task=Task::create($request->except('current_user_id'));
        if($task){
            return ['response'=>'Task added Successfully'];
        }
    }
    else{
        return ['response'=>'u aren\'t the admin'];
    }
        return ['response'=>'error with params leeh ha leeh'];
    }
    //----------------------this function to move from to do to progress
    public function moveToProgress($id){
        $task=Task::find($id);
        if($task){
            $task->update(array('status'=>'in-progress'));
            return ['response'=>'sucess'];
        }
        return ['response'=>'this task not exist'];
    }
    //---------------------------this function to move from in-progress to done
    public function moveToDone($id){
        $task=Task::find($id);
        if($task){
            $task->update(array('status'=>'done'));
            return ['response'=>'sucess'];
        }
        return ['response'=>'this task not exist'];
    }
    //---------------------------- this to get to do list of tasks of specific group
    public function listTodos($groupId){
        $group=Group::find($groupId);
        if($group){
            $tasks=Task::select('*')->where('group_id',$group->id)->where('status','to do')->get();
            $tasksList=array();
            
            foreach($tasks as $task){
                $taskEle=['id'=>$task->id,'name'=>$task->name,'description'=>$task->description];
               array_push($tasksList,$taskEle);
            }
            return $tasksList;
        }
        return ['response'=>'this group not exist'];
    }
    //---------------------------- this to get in progress tasks
    public function listProgress($groupId){
        $group=Group::find($groupId);
        if($group){
            $tasks=Task::select('*')->where('group_id',$group->id)->where('status','in-progress')->get();
            $tasksList=array();
            
            foreach($tasks as $task){
                $taskEle=['id'=>$task->id,'name'=>$task->name,'description'=>$task->description];
               array_push($tasksList,$taskEle);
            }
            return $tasksList;
        }
        return ['response'=>'this group not exist'];
    } 
    //--------------------- this to list done functions
    public function listDone($groupId){
        $group=Group::find($groupId);
        if($group){
            $tasks=Task::select('*')->where('group_id',$group->id)->where('status','done')->get();
            $tasksList=array();
            
            foreach($tasks as $task){
                $taskEle=['id'=>$task->id,'name'=>$task->name,'description'=>$task->description];
               array_push($tasksList,$taskEle);
            }
            return $tasksList;
        }
        return ['response'=>'this group not exist'];
    }
 
}
