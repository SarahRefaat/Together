<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['title','body','info','img','user_id'];
    public function user(){
        return $this->belognsTo(User::class);
    }
}
