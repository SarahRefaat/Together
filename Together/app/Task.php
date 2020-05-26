<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //----------this represent relation onee to many btn group and task
    public function group(){
        return $this->belongsTo('App\Group');
    }
}
