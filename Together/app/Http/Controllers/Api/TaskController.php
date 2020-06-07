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
        return ['response'=>'U aren\'t the admin'];
    }
        return ['response'=>'Error with params leeh ha leeh'];
    }
    //----------------------this function to move from to do to progress
    public function moveToProgress($id){
        $task=Task::find($id);
        if($task){
            $task->update(array('status'=>'in-progress'));
            return ['response'=>'Success'];
        }
        return ['response'=>'This task not exist'];
    }
    //----------------------this function to move from to do to progress
    public function moveTodo($id){
        $task=Task::find($id);
        if($task){
            $task->update(array('status'=>'to do'));
            return ['response'=>'Moved successfully'];
        }
        return ['response'=>'This task not exist'];
    }

    //---------------------------this function to move from in-progress to done
    public function moveToDone($id){
        $task=Task::find($id);
        if($task){
            $task->update(array('status'=>'done'));
            return ['response'=>'sucess'];
        }
        return ['response'=>'This task not exist'];
    }
    //---------------------------- this to get to do list of tasks of specific group
    public function listTodos($groupId){
        $group=Group::find($groupId);
        if($group){
            $tasks=Task::select('*')->where('group_id',$group->id)->where('status','to do')->get();
            $tasksList=array();
            $position=array();
            foreach($tasks as $task){
                $taskEle=['id'=>$task->id,'name'=>$task->name,'description'=>$task->description,'position'=>$task->position];
               array_push($tasksList,$taskEle);
               array_push($position,$task->position);
            }
            // ksort($tasksList);
            array_multisort($position, SORT_ASC, $tasksList);
            return $tasksList;
        }
        return ['response'=>'This group not exist'];
    }
    //---------------------------- this to get in progress tasks
    public function listProgress($groupId){
        $group=Group::find($groupId);
        if($group){
            $tasks=Task::select('*')->where('group_id',$group->id)->where('status','in-progress')->get();
            $tasksList=array();
            $position=array();
            foreach($tasks as $task){
                $taskEle=['id'=>$task->id,'name'=>$task->name,'description'=>$task->description,'position'=>$task->position];
               array_push($tasksList,$taskEle);
               array_push($position,$task->position);
            }
            array_multisort($position, SORT_ASC, $tasksList);
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
            $position=array();
            foreach($tasks as $task){
                $taskEle=['id'=>$task->id,'name'=>$task->name,'description'=>$task->description];
               array_push($tasksList,$taskEle);
               array_push($position,$task->position);
            }
            array_multisort($position, SORT_ASC, $tasksList);
            return $tasksList;
        }
        return ['response'=>'this group not exist'];
    }
   //--------------------------- this function to update exist task
   public function updateTask(Request $request,$id){
    $task=Task::find($id);
    if($task){
        $task->update($request->all());
        return ['response'=>'Updated successfully'];
    }
    return ['response'=>'This task not exist'];
   }
   //---------------------- this to delete certain task 
   public function deleteTask($id){
       $task=Task::find($id);
       $task->delete();
       return ['response'=>'This task deleted successfully'];
   }
   //----------------------------- this to chsnge certain to do task position
   public function changeDoPosition($taskId,$position){
      $task=Task::find($taskId);
      if($task){
          $task->position=$position;
          $task->save();
            return ['response'=>'Position changed successfully'];
      }
          return ['response'=>'This task didn\'t move correctly'];
   }
   //----------------------------- this to change certain in-progress task position
   public function changeProgressPosition($taskId,$position){
    $task=Task::find($taskId);
    if($task){
        $task->position=$position;
        $task->save();
          return ['response'=>'Position changed successfully'];
    }
        return ['response'=>'This task didnt move correctly'];   
   }
   //------------------------------- this to change done tasks position
   public function changeDonePosition($taskId,$position){
    $task=Task::find($taskId);
    if($task){
        $task->position=$position;
        $task->save();
        
          return ['response'=>'Position changed successfully'];
    }
        return ['response'=>'This task didnt move correctly'];   
   }
}
