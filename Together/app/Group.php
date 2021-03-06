<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //---------------------- this to listt fill feilds of  group
    protected $fillable = [
        'name', 'description' , 'max_member_number', 'duration', 'current_number_of_members', 'status','level' ,
        'interest_id'
    ];
    //------------ this represent many to many relationship btn groups and users
    public function users(){
        return $this->belongsToMany('App\User');
    }
    //------------------------- this to get members of group
    public function admin(){
        return $this->belongsToOne('App\User');
    }
    //----------------- this represent one to many realtion btn group and task
    public function tasks(){
        return $this->hasMany('App\Task');
    }
    //---------------------- this repesent relation btn request and group
    public function requests(){
        return $this->hasMany('App\UserRequest');
    }
    //------------------------- this represent relation between group and messages
    public function messages(){
        return $this->hasMany('App\Message');
    }
    //to get the interest of specific group
    public function interest(){
        return $this->belongsTo(Interest::class);
    }
}
