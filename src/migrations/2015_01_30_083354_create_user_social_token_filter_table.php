<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSocialTokenFilterTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_social_token_filter', function(Blueprint $table)
        {
            $table->increments('id');

            $table->integer('user_social_token_id')->unsigned();
            $table->foreign('user_social_token_id')->references('id')->on('user_social_token')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('filter');

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
        Schema::drop('user_social_token_filter');
    }

}
