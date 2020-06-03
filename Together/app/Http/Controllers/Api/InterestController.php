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
        return new InterestResource($interest);
    }
    //this function to display groups of single interest
    public function  groups(){
        $interestId = request()->interest;
        $interest = Interest::find($interestId);
        $groups =  $interest->groups;
        $groupResource = GroupResource::collection($groups);
        return $groupResource;
    }
}
