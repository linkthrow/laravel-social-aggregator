<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSocialTokenTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_social_token', function(Blueprint $table)
        {
            $table->increments('id');

            $table->string('type');
            $table->string('short_lived_token')->nullable();
            $table->text('long_lived_token')->nullable();
            $table->string('expires_at');

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
        Schema::drop('user_social_token');
    }

}
