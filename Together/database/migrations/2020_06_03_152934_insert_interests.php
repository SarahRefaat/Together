<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Interest;
class InsertInterests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $interests=[
            ["id"=>1,"name"=>"Development","img"=>"https://imgur.com/yXBVzZV.jpg"],
            ["id"=>2,"name"=>"Language","img"=>"https://imgur.com/Y92Th8i.jpg"],
            ["name"=>"Art","img"=>"https://imgur.com/imT16D2.jpg"],
            ["name"=>"Music","img"=>"https://imgur.com/PGOxhNJ.jpg"],
            ["name"=>"Sports","img"=>"https://imgur.com/Y3HzfY7.jpg"],
            ["name"=>"Dancing","img"=>"https://imgur.com/lmgCE7K.jpg"],
            ["name"=>"Handmade","img"=>"https://imgur.com/6UFLIu7.jpg"],
            ["name"=>"Religion","img"=>"https://imgur.com/adUN6il.jpg"],
            ["name"=>"Reading","img"=>"https://imgur.com/WdEkDyb.jpg"],
            ["name"=>"Other","img"=>"https://imgur.com/pTqDZMz.jpg"]
            ];
            for($i=0; $i < count($interests); $i++) {
                $name= $interests[$i]["name"];
                $img= $interests[$i]["img"];
                $interest = Interest::create(
                    [
                        'name'=>$name,
                        'img'=>$img
                    ]
                );
            }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Interest::truncate();
    }
}
