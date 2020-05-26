<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Group;
use App\Interest;

class InterestController extends Controller
{
    //---------------- this function to get groups under certain interest
    public function ListGroups($id){
        $interest=Interest::find($id);
        $groups=$interest->groups;
      return ['reponse'=>$groups];
    }
    //-------------------this function to add new interes
    // if that is okay with 7ossam just copy it from ur file (interestdes);
}
