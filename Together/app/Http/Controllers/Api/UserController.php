<?php

namespace App\Http\Controllers\Api;
use App\User;
use App\Interest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //-------------------this function to sign up 
    public function signup(Request $request){
        //-----here i ckeck if this is his first account in our app
        $user=User::where('email',$request->email)->first();
        if($user){
            return ['response'=>'this email is exist '];
        }
        //-------------- then if itis his first sign up
        $user=new User; 
        // -------------------here if pic is attached
        if($request->file('photo')){
            $image=$request->photo;
            $destinationPath = 'images/'; // upload path
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $user->photo = $profileImage;   
        }
        // ------------her to attach his intrests
         $listOfInterests=$request->interests;
         $interestArr=array();
          foreach($listOfInterests as $interest){
             array_push($interestArr,Interest::where('name',$interest)->first());
             }
        
        $user->name = $request->name;
        $user->email=$request->email;
        $user->password= $request->password;
        $user->age =$request->age;
        $user->gender = $request->gender;
        $user->address=$request->address;
        //---------here i attach el inteerests
        $user->interests()->attach($interestArr);
        //------------- here user saved
        $user->save();
        if($user){
        return ['response'=>'Signed In Successfully'];}

        else {

            return ['response'=>'plz fill all required feilds'];
        }
    }
    //----------------------this function to login
    public function signin(Request $request){
         $user=User::where('email',$request->email)->first();
         
         if($user){
            //return $user->email;
             $user=User::where('password',$request->password)->first();
             if($user){
                $token=$user->createToken($request->email)->plainTextToken;
                 return ['token' => $token,'id'=>$user->id];
             }
             else{
                return ['response'=>'password not correct'];
             }
         }
         else{
            return ['response'=>'this mail not registered'];
         }
    }
    //---------------------------- this function to view Profile
    public function show(Request $request){
        $id=$request->input('id');
        $user=User::where('id',$id)->first();
        //----------here to return interests of user
        $interestsList=$user->interests();
        $ret=['name'=>$user->name,
        'email'=>$user->email,
        'gender'=>$user->gender,
        'age'=>$user->age,
        'address'=>$user->address,
        'intrests'=>$interestsList];

        if($user){
            return ['response'=>$ret];
        }
        else{
            return ['response'=>'error param'];
        }
    }
    //------------------------------ this function to edit profile
    public function update(Request $request,$id){
            $user=User::where('id',$id)->first();
            if($user){
                $valid=$request->validate([]);
                if($valid){
            $user=User::where('id',$id)->first()->update($request->all());
            return ['response'=>'updated Successfully'];
       }
       else{
           return ['response'=>'not valid'];
       }
    }
       else{
        return ['response'=>'this user is not exist'];               
    }
    }
}
