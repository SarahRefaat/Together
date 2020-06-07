<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    protected $fillable = ['name','img'];

    public function users(){
        return $this->belongsToMany('App\User');
    }
    //get the groups of single interest
    public function groups(){
        return $this->hasMany('App\Group');
    }
}
