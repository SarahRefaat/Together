<?php

namespace App\Http\Controllers\Api;
use App\User;
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
        $user=User::create(['name'=>$request->name,
        'email'=>$request->email,
        'password'=>$request->password,
        'age'=>$request->age,
        'gender'=>$request->gender,
        'address'=>$request->address]);
        if($user){
        return ['response'=>'Signed In uccessfully'];}
        else {
            return ['response'=>'plz fill all required feilds'];
        }
    }
    //----------------------this functio to login
    public function login(Request $request){
         $user=User::where('email',$request->email);
         if($user){
             $user=User::where('password',$request->password);
             if($user){
                 return ['response'=>'login Success'];
             }
             else{
                return ['response'=>'password not correct'];
             }
         }
         else{
            return ['response'=>'this mail not registered'];
         }
    }
}
