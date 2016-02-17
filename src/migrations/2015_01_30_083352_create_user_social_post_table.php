<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSocialPostTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_social_post', function(Blueprint $table)
        {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('user')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('social_post_id')->unsigned();
            $table->foreign('social_post_id')->references('id')->on('social_post')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_social_post', function (Blueprint $table) {
            $table->dropForeign('user_social_post_social_post_id_foreign');
        });

        Schema::drop('user_social_post');
    }

}
