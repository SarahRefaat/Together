<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\InterestResource;
use App\Http\Resources\GroupResource;
use App\Interest;

class InterestController extends Controller
{
    //this function to show all interests
    public function index(){
        $interests= Interest::all();
        $interestResource = InterestResource::collection($interests);
        return $interestResource;
    }
    //this function to display single interest
    public function  show(){
        $interestId = request()->interest;
        $interest = Interest::find($interestId);
        if($interest){
        return new InterestResource($interest);
        }else{
            return ["response"=>"This interest does not exist !!.."];
        }
    }
    //this function to display groups of single interest
    public function  groups(){
        $interestId = request()->interest;
        $interest = Interest::find($interestId);
        if($interest){
        $groups =  $interest->groups;
        if(count($groups)>0){
        $groupResource = GroupResource::collection($groups);
        return ["response"=>$groupResource];
        }else{
            return ["response"=>"There is no avaliable groups in this interest .."];
        }
        }
        else{
            return ["response"=>"This interest does not exist !!.."];
        }
    }
      //---------------- this function to get groups under certain interest
      public function ListGroups($id){
        $interest=Interest::find($id);
        $groups=$interest->groups;
      return ['reponse'=>$groups];
    }
    //-------------------this function to add new interes
    // if that is okay with 7ossam just copy it from ur file (interestdes);
    //-------------------- here u can get all interests
    public function interests(Request $request){
      return Interest::all();
    }
}
