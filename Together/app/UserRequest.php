<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    //------------------- this represent relation btn group and request
    public function group(){
        return $this->belongsTo('App\Group');
    }
    //--------------------- this represent relation btn user and request
    public function user(){
        return $this->belongsTo('App\User');
    }
}
