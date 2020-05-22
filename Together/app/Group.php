<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    public function users(){
        return $this->belongsToMany('App\User');
    }
    public function interest(){
        return $this->hasOne('App\Interest');
    }
}
