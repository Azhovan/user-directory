<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserFriendlist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_friends', function (Blueprint $table) {
            $table->increments('id');
            // since i will search only for id , i just need to index user_id
            // user_id is foreign key to users table id
            $table->integer('user_id')->unsigned();
            $table->index('user_id');
            $table->integer('friend_id');
            $table->timestamps();
        });

        // add foreign key
        Schema::table('user_friends', function ($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_friends');
    }
}
