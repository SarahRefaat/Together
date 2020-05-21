<?php

namespace App\Http\Controllers\Api;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //-------------------this function to sign up 
    public function signup(Request $request){
        $user=User::where('email',$request->email);
        if($user){
            return ['response'=>'this email is exist '];
        }
        if($request->file('photo')){
            $image=$request->photo;
            $destinationPath = 'images/'; // upload path
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);   
        }
        else{
        $user=User::create(['name'=>$request->name,
        'email'=>$request->email,
        'password'=>$request->password,
        'age'=>$request->age,
        'gender'=>$request->gender,
        'address'=>$request->address,
        'photo'=>$profileImage]);
        if($user){
        return ['response'=>'Signed In Successfully'];}
        else {
            return ['response'=>'plz fill all required feilds'];
        }
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
        $ret=['name'=>$user->name,
        'email'=>$user->email,
        'gender'=>$user->gender,
        'age'=>$user->age,
        'address'=>$user->address,];
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
