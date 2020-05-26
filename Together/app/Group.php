<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //------------ this represent many to many relationship btn groups and users
    public function users(){
        return $this->belongsToMany('App\User');
    }
    //------------- this repreent one to many realtion btn group and interest
    public function interest(){
        return $this->hasOne('App\Interest');
    }
    //----------------- this represent one to many realtion btn group and task 
    public function tasks(){
        return $this->hasMany('App\Task');
    }
}
