<?php

namespace App;
use App\User;
use App\Group;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['title','body','info','img','user_id','group_id'];
    public function user(){
        return $this->belognsTo(User::class);
    }
    public function group(){
        return $this->belognsTo(Group::class);
    }
}
