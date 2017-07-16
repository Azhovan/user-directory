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
            // there are two cases
            // search for user_id
            // search for user_id and friend_id
            // so i created 2 indexes ::
            // 1. (user_id)
            // 2. (user_id, friend_id)
            $table->integer('user_id')->unsigned();
            $table->index('user_id');

            $table->integer('friend_id');
            $table->index(['friend_id', 'user_id']);
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
