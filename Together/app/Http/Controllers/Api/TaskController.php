<?php

namespace App\Http\Controllers\Api;
use App\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //--------------------this functgion to add new task
    public function add(Request $request){
        $task=Task::create($request->all());
        if($task){
            return ['response'=>'Task added Successfully'];
        }
        return ['response'=>'error with params leeh ha leeh'];
    }
    //----------------------this function to move from to do to progress
    public function moveToProgree($id){
        $task=Task::find($id);
        if($task){
            $task->status='in-progress';
            return ['response'=>'sucess'];
        }
        return ['response'=>'this task not exist'];
    }
    //---------------------------this function to move from in-progress to done
    public function moveToDone($id){
        $task=Task::find($id);
        if($task){
            $task->status='done';
            return ['response'=>'sucess'];
        }
        return ['response'=>'this task not exist'];
    }
}
