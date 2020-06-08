<?php

namespace App\Http\Controllers\Api;
use App\User;
use App\Interest;
use App\Group;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\NotificationResource;
use App\Notification;

class UserController extends Controller
{
    //-------------------this function to sign up
    public function signup(Request $request){
        //----------- this to vslidate request
        $valid = $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required',
            'password' => 'required',
            'gender' => 'required',
            'BirthDate' => 'required',
        ]);
        //-----here i ckeck if this is his first account in our app
        if($valid){
        $user=User::where('email',$request->email)->first();
        if($user){
            return ['response'=>'This email is exist '];
        }
        //-------------- then if itis his first sign up
        $user=new User;
        // -------------------here if pic is attached
        // if($request->file('photo')){
        //     $image=$request->photo;
        //     $destinationPath = 'images/'; // upload path
        //     $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
        //     $image->move($destinationPath, $profileImage);
        //     $user->photo = $profileImage;
        // }
        // ------------her to attach his intrests
        $interestArr=array();
        if($request->interests){
         $listOfInterests=$request->interests;
          foreach($listOfInterests as $interest){
             array_push($interestArr,Interest::where('name',$interest)->first()->id);
             }
            }

        //--------------------here if user has other interests allah y5rb bytooo
        $user->name = $request->name;
        $user->email=$request->email;
        $user->fill(['password' => encrypt($request->password)]);
        $user->BirthDate =$request->BirthDate;
        $user->gender = $request->gender;
        $user->address=$request->address;
        $user->photo=$request->photo;
        $user->save();
        //---------here i attach el inteerests
        $user->interests()->attach($interestArr);
        //------------- here user saved

        if($user){
       // return ['response'=>'Signed up Successfully'];
          $response=$this->signin($request);
          return $response;
         }

        else {

            return ['response'=>'Please fill all required feilds'];
        }
    }
}
    //----------------------this function to login
    public function signin(Request $request){
        $valid = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
         $user=User::where('email',$request->email)->first();

         if($user){
            //return $user->email;
           //  $user=User::where('password',$request->password)->first();
           $pasword=Crypt::decrypt($user->password);
             if($request->password == $pasword){
                $token=$user->createToken($request->email)->plainTextToken;
                 return ['token' => $token,'id'=>$user->id];
             }
             else{
                return ['response'=>'Password not correct'];
             }
         }
         else{
            return ['response'=>'This mail not registered'];
         }
    }
    //---------------------------- this function to view Profile
    public function show(Request $request){
        $id=$request->input('id');
        $user=User::where('id',$id)->first();
        //----------here to return interests of user

        $interestsList=$user->interests;
        $userInterests=array();
         foreach($interestsList as $interest){
           array_push($userInterests,$interest->name);
         }
         $groups=$user->groups;
         $groupsNames=array();
         if($groups){
         foreach($groups as $group){
             array_push($groupsNames,['name'=>$group->name,'id'=>$group->id]);
         }
        }
        if($user){
        return [
        'name'=>$user->name,
        'email'=>$user->email,
        'gender'=>$user->gender,
        'photo'=>$user->photo,
        'password'=>decrypt($user->password),
        'BirthDate'=>$user->BirthDate,
        'address'=>$user->address,
        'interests'=>$userInterests,
        'groups'=>$groupsNames];
        }
        else{
            return ['response'=>'Error param'];
        }
    }
    //------------------------------ this function to edit profile
    public function update(Request $request,$id){
            $user=User::where('id',$id)->first();
            if($user){
            //$user=User::where('id',$id)->first()->update($request->all());
            $user->name = $request->name;
            $user->email=$request->email;
            $user->fill(['password' => encrypt($request->password)]);
            $user->BirthDate =$request->BirthDate;
            $user->gender = $request->gender;
            $user->photo= $request->photo;
            $user->address=$request->address;
            $user->update();
            return ['response'=>'Updated Successfully'];
    }
       else{
        return ['response'=>'This user is not exist'];
    }
    }
    //------------------- this to retrive all groups of certain user
    public function home($id){
        $groups=array();
        $user=User::find($id);
        if($user){
            $user_groups=$user->groups;
            foreach($user_groups as $group){
                $obj=['group_id'=>$group->id,
                      'address'=>$group->address,
                       'max_member_number'=>$group->max_member_number,
                       'name'=>$group->name,
                       'description'=>$group->description,
                       'current_number_of_members'=>$group->current_number_of_members,
                       'status'=>$group->status,
                       'level'=>$group->level,
                       'photo'=>$group->photo,
                       'interest_id'=>$group->interest_id,
                        'id'=>$group->admin_id];
                array_push($groups,$obj);
            }
            return $groups;
        }
        else{
            return ['response'=>'This user is not exist'];
        }
    }
    //---------------------------- this function to update interests of user
    public function updateInterests(Request $request,$id){
        $user=User::find($id);
        if($user){
            $interestArr=array();
             $listOfInterests=$request->interests;
              foreach($listOfInterests as $interest){
                 array_push($interestArr,Interest::where('name',$interest)->first()->id);
                 }
                 $user->interests()->sync($interestArr);
                 $user->save();
                 return ['response'=>'Interests changed successfully'];
        }
        return ['response'=>'This user is not exist'];
      }
        //---------------------------- this function used to logout
        public function logout($id){
            $user=User::find($id);
            if($user){
               //Auth::logout();
               $tokens=PersonalAccessToken::where('name',$user->email)->get();
               foreach($tokens as $token){
                 $token->delete();
               }
               
               return ['response'=>'logout successfully'];
            }
 
        }
        //----------------------------- this to return status of user(one of group , not in send request)
       public function getStatus ($groupId,$id){
        $group=Group::find($groupId);
        $members=$group->users;
       // return $members;
        foreach ($members as $member){
            if($member->id==$id){
                $status ='Member of this group';
                return ['response'=>$status];
            }
            else {
                $status = 'Not member';
            }
        }
        if($status){
            $case=$status;
            $status=' ';
        }
        $requests=$group->requests;
        foreach($requests as $request){
            if($request->user_id == $id){
                $status =' , This user waiting for admin of group to accept his request of join';
            }
            else {
                $status=' ';
            }
        }
        return ['response'=>$case .$status];
    }
      //this function to return user notification -- nahla
      public function notifications(){
        $userId = request()->user_id;
        $user = User::find($userId);
        $notifications = $user->notifications()->paginate(10);
        $notificationResource = NotificationResource::collection($notifications);
          return $notificationResource;
      }
      

      //this function to enable notification --nahla
      public function enable(){
        $userId = request()->user_id;
        $user = User::find($userId);
        if($user){
        $user->update(['enable'=>true]);
        return ['response'=>'notification enabled'];
    }else{
        return ["response"=>"User Does not exist !!"];
    }
    }
    //this function to disable notification --nahla
    public function disable(){
        $userId = request()->user_id;
        $user = User::find($userId);
        if($user){
        $user->update(['enable'=>false]);
        return ['response'=>'notification disabled'];
    }else{
        return ["response"=>"User Does not exist !!"];
    }
    }
    //this function to update device token for notifications --nahla
    public function updateDeviceToken(){
        $userId = request()->user_id;
        $user = User::find($userId);
        if($user){
        $user->update(['device_token'=>request()->token]);
        return ['response'=>'device token update successfully'];
    }else{
        return ["response"=>"User Does not exist !!"];
    }

    }
      //this function to return user requests -- nahla
      public function requests(){
        $userId = request()->user_id;
        //$user = User::find($userId);
          return 0;
      }


}
